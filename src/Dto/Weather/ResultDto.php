<?php

namespace App\Dto\Weather;

class ResultDto
{
    private string $name;

    private WeatherDto $main;

    public function __construct(string $name, WeatherDto $main)
    {
        $this->name = $name;
        $this->main = $main;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getMain(): WeatherDto
    {
        return $this->main;
    }
}