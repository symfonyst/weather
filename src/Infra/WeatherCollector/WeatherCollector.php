<?php

declare(strict_types=1);

namespace App\Infra\WeatherCollector;

use App\Infra\Services\Validators\WeatherValidator;
use Exception;
use Throwable;
use GuzzleHttp\ClientInterface;

class WeatherCollector
{
    private ClientInterface $client;
    private RequestFactory $requestFactory;
    private WeatherValidator $validator;

    public function __construct(
        ClientInterface $client,
        RequestFactory $requestFactory,
        WeatherValidator $validator
    ) {
        $this->client = $client;
        $this->requestFactory = $requestFactory;
        $this->validator = $validator;
    }

    /**
     * @throws Exception
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
        $errors = $this->validator->validate($data);

        if (!empty($errors)) {
            throw new Exception(sprintf(
                'Неверный формат ответа. Ошибка: %s', implode(', ', $errors)
            ));
        }
    }
}