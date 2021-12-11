<?php

namespace App\Service;

use JMS\Serializer\SerializationContext;
use JMS\Serializer\SerializerInterface;

class SerialisationService
{
    /**
     * @var \JMS\Serializer\SerializerInterface
     */
    private $serializer;

    public function __construct(SerializerInterface $serializer)
    {
        $this->serializer = $serializer;
    }

    public function serialize($data, array $groups = []): string
    {
        return $this->serializer->serialize($data, 'json', (new SerializationContext())->setGroups($groups));
    }

}