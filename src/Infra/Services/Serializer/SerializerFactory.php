<?php

declare(strict_types=1);

namespace App\Infra\Services\Serializer;

use Symfony\Component\Serializer\Encoder\JsonEncode;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Normalizer\ArrayDenormalizer;

class SerializerFactory
{
    public function create(): SerializerInterface
    {
        return new Serializer([
            new ObjectNormalizer(),
            new ArrayDenormalizer()
        ], [
            new JsonEncode()
        ]);
    }
}