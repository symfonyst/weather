<?php

declare(strict_types=1);

namespace App\Handlers;

use Throwable;
use App\Dto\Weather\ResultDto;
use App\Infra\WeatherCollector\WeatherCollector;

class WeatherHandler
{
    private WeatherCollector $weatherCollector;

    public function __construct(WeatherCollector $weatherCollector)
    {
        $this->weatherCollector = $weatherCollector;
    }

    public function handle(string $city): ?ResultDto
    {
        try {
            $city = $this->sanitize($city);

            if ($city === '') {
                return null;
            }

            return $this->weatherCollector->collect($city);
        } catch (Throwable $exception) {
            echo $exception->getMessage();

            return null;
        }

    }

    private function sanitize(string $city): string
    {
        $res = trim($city);

        return preg_replace('/[\W\d_]/', '', $res);
    }
}