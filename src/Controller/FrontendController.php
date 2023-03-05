<?php

declare(strict_types=1);

namespace App\Controller;

use App\Handlers\WeatherHandler;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Environment;

class FrontendController
{
    /** @var Environment  */
    private Environment $twig;

    /** @var WeatherHandler  */
    private WeatherHandler $weatherHandler;

    public function __construct(
        Environment $twig,
        WeatherHandler $weatherHandler
    ) {
        $this->twig = $twig;
        $this->weatherHandler = $weatherHandler;
    }

    #[Route('/')]
    public function main(): Response
    {
        $contents = $this->twig->render('frontend/main.html.twig');

        return new Response($contents);
    }

    #[Route('/weather/{city}')]
    public function weatherByCity(string $city): Response
    {
        $info = $this->weatherHandler->handle($city);
        $content = $this->twig->render('frontend/city.html.twig', ['info' => $info]);

        return new Response($content);
    }
}