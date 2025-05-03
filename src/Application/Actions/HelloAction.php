<?php

declare(strict_types=1);

namespace App\Application\Actions;
use Psr\Http\Message\ResponseInterface as Response;
use Slim\Views\Twig;


class HelloAction
{
    private Twig $twig;

    public function __construct(Twig $twig)
    {
        $this->twig = $twig;
    }

    public function __invoke( Response $response): Response
    {
        return $this->twig->render($response, 'hello.twig', [
            'name' => 'Slim + Twig'
        ]);
    }
}
