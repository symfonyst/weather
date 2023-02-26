<?php

namespace App\Infra\Services\Config;

interface ConfigInterface
{
    public function baseUrl(): string;
    public function apiKey(): string;
}