<?php

namespace App\Infra\Services\Config;

class ConfigFactory
{
    public function create(string $apiKey, string $baseUrl): ConfigInterface
    {
        return new BaseConfig($apiKey, $baseUrl);
    }
}