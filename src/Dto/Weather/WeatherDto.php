<?php

declare(strict_types=1);

namespace App\Dto\Weather;

class WeatherDto
{
    public float $temp;
    public float $feels_like;
    public float $temp_min;
    public float $temp_max;
}