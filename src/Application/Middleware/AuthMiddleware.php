<?php

declare(strict_types=1);

namespace App\Application\Middleware;

use App\Domain\Auth\SessionInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Slim\Psr7\Response;
use Slim\Routing\RouteContext;

class AuthMiddleware implements MiddlewareInterface
{
    public function __construct(private SessionInterface $session) {}

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        if (!$this->session->has('user')) {
            $routeParser = RouteContext::fromRequest($request)->getRouteParser();
            $response = new Response();
            return $response->withHeader('Location', $routeParser->urlFor('login'))->withStatus(302);
        }

        return $handler->handle($request);
    }
}
