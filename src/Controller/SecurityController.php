<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use OpenApi\Annotations as OA;

class SecurityController extends AbstractController
{
    /**
     * @OA\Parameter(
     *     name="username",
     *     in="path",
     *     description="Client Email",
     *     @OA\Schema(type="string")
     * )
     * @OA\Parameter(
     *     name="password",
     *     in="path",
     *     description="Client Password",
     *     @OA\Schema(type="string")
     * )
     * @OA\Tag(name="Login")
     * @Route("/login", name="security", methods={"POST"})
     */
    public function index(): Response
    {
        if (!$user = $this->getUser()) {
            return $this->json('Not found', Response::HTTP_NOT_FOUND);
        }
       return $this->json([
           'username' => $user->getEmail()
       ]);
    }
}
