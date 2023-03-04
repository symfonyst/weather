<?php

declare(strict_types=1);

namespace App\Handlers;

use Throwable;
use App\Dto\Weather\WeatherDto;
use App\Infra\WeatherCollector\WeatherCollector;

class WeatherHandler
{
    private WeatherCollector $weatherCollector;

    public function __construct(WeatherCollector $weatherCollector)
    {
        $this->weatherCollector = $weatherCollector;
    }

    public function handle(): ?WeatherDto
    {
        try {
            return $this->weatherCollector->collect('moscow');
        } catch (Throwable $exception) {
            echo $exception->getMessage();

            return null;
        }

    }
}