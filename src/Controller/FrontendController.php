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
        $info = $this->weatherHandler->handle();
        print_r($info);die;
        $contents = $this->twig->render('frontend/main.html.twig', []);

        return new Response($contents);
    }
}