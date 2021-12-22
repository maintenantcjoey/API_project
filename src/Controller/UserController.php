<?php

namespace App\Controller;

use App\AR;
use App\Entity\Client;
use App\Entity\User;
use App\Pagination\Pagination;
use App\Repository\UserRepository;
use App\Service\SerialisationService;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Entity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Webmozart\Assert\Assert;
use OpenApi\Annotations as OA;

/**
 * @OA\Tag(name="Clients")
 *
 * @Route("/clients")
 */
class UserController extends AbstractController
{
    /**
     * @var \App\Service\SerialisationService
     */
    private $serialisationService;
    /**
     * @var \App\Pagination\Pagination
     */
    private $pagination;
    /**
     * @var \App\Repository\UserRepository
     */
    private $userRepository;

    public function __construct(SerialisationService $serialisationService, Pagination $pagination, UserRepository $userRepository)
    {
        $this->serialisationService = $serialisationService;
        $this->pagination = $pagination;
        $this->userRepository = $userRepository;
    }

    /**
     * @Route("/{id}/users", name="app_users_list", methods={"GET"})
     */
    public function users(Client $client): Response
    {
        $query = $this->userRepository->createQueryBuilder('u')
            ->where('u.client = :client')
            ->setParameter('client', $client)
            ->getQuery();
        $data = $this->pagination->create($query);
        $users = $this->serialisationService->serialize($data, ['show']);
        return AR::ok($users);
    }

    /**
     * @Route("/{id}/users", name="app_users_create", methods={"POST"})
     */
    public function create(Client $client, Request $request, EntityManagerInterface $manager): Response
    {
        Assert::notNull($lastname = $request->get('lastname'), 'lastname is required');
        Assert::notNull($firstname = $request->get('firstname'), 'firstname is required');
        $user = User::create($lastname, $firstname);
        $client->addUser($user);
        $manager->persist($client);
        $manager->flush();
        $user = $this->serialisationService->serialize($user, ['show']);
        return AR::ok($user, Response::HTTP_CREATED);
    }

    /**
     * @Route("/{client_id}/users/{user_id}", name="app_users_create", methods={"DELETE"})
     * @Entity("user", expr="repository.find(user_id)")
     * @Entity("client", expr="repository.find(client_id)")
     */
    public function delete(Client $client, EntityManagerInterface $manager, User $user): Response
    {
        if (!$client->getUsers()->contains($user)) {
            throw $this->createAccessDeniedException();
        }
        $manager->remove($user);
        $manager->flush();
        return AR::ok([]);
    }

    /**
     * @Route("/{client_id}/users/{user_id}", name="app_users_details", methods={"GET"})
     * @Entity("client", expr="repository.find(client_id)")
     * @Entity("user", expr="repository.find(user_id)")
     */
    public function details(Client $client, User $user): Response
    {
        if (!$client->getUsers()->contains($user)) {
            throw $this->createAccessDeniedException();
        }
        $user = $this->serialisationService->serialize($user, ['show']);
        return AR::ok($user);
    }

}