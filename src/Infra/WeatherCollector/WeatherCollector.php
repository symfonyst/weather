<?php

declare(strict_types=1);

namespace App\Infra\WeatherCollector;

use App\Dto\Weather\WeatherDto;
use App\Infra\Services\Serializer\SerializerFactory;
use Exception;
use GuzzleHttp\ClientInterface;
use App\Dto\Weather\ResponseDto;
use App\Infra\Services\Validators\WeatherValidator;
use Symfony\Component\Serializer\Serializer;

class WeatherCollector
{
    private ClientInterface $client;
    private RequestFactory $requestFactory;
    private WeatherValidator $validator;
    private Serializer $serializer;

    public function __construct(
        ClientInterface $client,
        RequestFactory $requestFactory,
        WeatherValidator $validator,
        SerializerFactory $serializerFactory
    ) {
        $this->client = $client;
        $this->requestFactory = $requestFactory;
        $this->validator = $validator;
        $this->serializer = $serializerFactory->create();
    }

    /**
     * @throws Exception
     */
    public function collect(string $town): WeatherDto
    {
        $url = sprintf('weather?units=metric&lang=ru&q=%s', $town);

        $request = $this->requestFactory->create('GET', $url);
        $response = $this->client->send($request);

        $json = $response->getBody()->getContents();
        $data = json_decode($json, true);
        $this->validate($data);

        return $this->serializer->denormalize($data['main'], WeatherDto::class);
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