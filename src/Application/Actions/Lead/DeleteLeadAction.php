<?php

declare(strict_types=1);

namespace App\Application\Actions\Lead;

use App\Application\Service\LeadService;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Routing\RouteContext;

class DeleteLeadAction
{
    public function __construct(private LeadService $leadService) {}

    public function __invoke(Request $request, Response $response, array $args): Response
    {
        $leadId = (int) $args['id'];
        $this->leadService->deleteLead($leadId);

        $routeParser = RouteContext::fromRequest($request)->getRouteParser();
        return $response
            ->withHeader('Location', $routeParser->urlFor('leads'))
            ->withStatus(302);
    }
}
