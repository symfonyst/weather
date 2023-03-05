<?php

declare(strict_types=1);

namespace App\Infra\WeatherCollector;

use Exception;
use GuzzleHttp\ClientInterface;
use Symfony\Component\Serializer\Serializer;
use App\Dto\Weather\ResultDto;
use App\Dto\Weather\WeatherDto;
use App\Dto\Weather\ResponseDto;
use App\Infra\Services\Serializer\SerializerFactory;
use App\Infra\Services\Validators\WeatherValidator;

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
    public function collect(string $town): ResultDto
    {
        $url = sprintf('weather?units=metric&lang=ru&q=%s', $town);

        $request = $this->requestFactory->create('GET', $url);
        $response = $this->client->send($request);

        $json = $response->getBody()->getContents();
        $data = json_decode($json, true);
        $this->validate($data);

        $res = new ResultDto(
            $data['name'],
            $this->serializer->denormalize($data['main'], WeatherDto::class)
        );

        return $res;
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