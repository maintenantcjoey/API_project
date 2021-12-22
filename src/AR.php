<?php

namespace App;
use Symfony\Component\HttpFoundation\Response;

class AR
{
    public static function ok($data, $status = Response::HTTP_OK): Response
    {
        return new Response($data, $status, [
            'Content-Type' => 'application/json'
        ]);
    }

}