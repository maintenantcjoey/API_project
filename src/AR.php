<?php

namespace App;
use Symfony\Component\HttpFoundation\Response;

class AR
{
    public static function ok($data): Response
    {
        return new Response($data, Response::HTTP_OK, [
            'Content-Type' => 'application/json'
        ]);
    }

}