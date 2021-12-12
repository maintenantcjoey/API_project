<?php

namespace App\Controller;

use App\AR;
use App\Entity\Client;
use App\Entity\User;
use App\Pagination\Pagination;
use App\Repository\UserRepository;
use App\Service\SerialisationService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Entity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
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
     * @Route("/{id}/users", name="app_users_list")
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
     * @Route("/{client_id}/users/{user_id}", name="app_users_details")
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