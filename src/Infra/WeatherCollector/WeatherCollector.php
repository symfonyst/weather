<?php

declare(strict_types=1);

namespace App\Infra\WeatherCollector;

use Exception;
use Throwable;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\BadResponseException;

class WeatherCollector
{
    private ClientInterface $client;
    private RequestFactory $requestFactory;

    public function __construct(
        ClientInterface $client,
        RequestFactory $requestFactory
    ) {
        $this->client = $client;
        $this->requestFactory = $requestFactory;
    }

    /**
     * @throws Throwable
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function collect(string $town): array
    {
        $url = sprintf('weather?units=metric&lang=ru&q=%s', $town);

        $request = $this->requestFactory->create('GET', $url);
        $response = $this->client->send($request);

        $json = $response->getBody()->getContents();
        $data = json_decode($json, true);
        $this->validate($data);

        return $data;
    }

    /**
     * @throws Exception
     */
    private function validate($data): void
    {
        if (!is_array($data)) {
            throw new Exception('Неверный формат ответа');
        }
    }
}