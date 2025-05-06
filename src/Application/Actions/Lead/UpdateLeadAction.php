<?php

namespace App\Application\Actions\Lead;

use App\Application\Service\LeadService;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Routing\RouteContext;

class UpdateLeadAction
{
    public function __construct(private LeadService $leadService) {}

    public function __invoke(Request $request, Response $response, array $args): Response
    {
        $leadId = (int) $args['id'];
        $data = $request->getParsedBody();

        $this->leadService->updateLead(
            $leadId,
            $data['name'] ?? '',
            $data['contactNumber'] ?? '',
            $data['email'] ?? '',
            $data['productInterest'] ?? ''
        );

        $routeParser = RouteContext::fromRequest($request)->getRouteParser();
        return $response->withHeader('Location', $routeParser->urlFor('dashboard'))->withStatus(302);
    }
}
