<?php

declare(strict_types=1);

namespace App\Application\Actions\Lead;

use App\Application\Service\LeadService;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Routing\RouteContext;

class CreateLeadAction
{
    public function __construct(private LeadService $leadService) {}

    public function __invoke(Request $request, Response $response): Response
    {
        $data = $request->getParsedBody();

        $name = trim($data['name'] ?? '');
        $contact = trim($data['contactNumber'] ?? '');
        $email = trim($data['email'] ?? '');
        $product = ($data['productInterest'] ?? '');

        if ($name && $contact && $email && $product) {
            $this->leadService->createLead($name, $contact, $email, $product);
        }

        $routeParser = RouteContext::fromRequest($request)->getRouteParser();
        return $response
            ->withHeader('Location', $routeParser->urlFor('dashboard'))
            ->withStatus(302);
    }
}
