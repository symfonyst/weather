<?php

declare(strict_types=1);

namespace App\Infra\WeatherCollector;

use App\Infra\Services\Config\ConfigInterface;
use GuzzleHttp\Psr7\Request;

class RequestFactory
{
    private ConfigInterface $config;

    public function __construct(ConfigInterface $config)
    {
        $this->config = $config;
    }

    public function create(string $method, string $url): Request
    {
        $url = sprintf('%s/%s&appid=%s',
            rtrim($this->config->baseUrl(), '/'),
            ltrim($url, '/'),
            $this->config->apiKey()
        );

        return new Request(
            $method,
            $url,
            [],
        );
    }
}