<?php

declare(strict_types=1);

namespace App\Application\Actions\Lead;

use App\Application\Service\LeadService;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Routing\RouteContext;

class EditLeadAction
{
    public function __construct(private LeadService $leadService) {}

    public function __invoke(Request $request, Response $response): Response
    {
        $data = $request->getParsedBody();

        $name = $data['name'] ?? '';
        $contactNumber = $data['contactNumber'] ?? '';
        $email = $data['email'] ?? '';
        $productInterest = $data['productInterest'] ?? '';

        if ($name && $contactNumber && $email && $productInterest) {
            $this->leadService->createLead($name, $contactNumber, $email, $productInterest);
        }

        $this->leadService->createLead($name, $contactNumber, $email, $productInterest);

        $routeParser = RouteContext::fromRequest($request)->getRouteParser();
        return $response
            ->withHeader('Location', $routeParser->urlFor('dashboard'))
            ->withStatus(302);
    }
}
