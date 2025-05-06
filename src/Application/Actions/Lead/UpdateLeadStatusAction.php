<?php

declare(strict_types=1);

namespace App\Application\Actions\Lead;

use App\Application\Service\LeadService;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class UpdateLeadStatusAction
{
    public function __construct(private LeadService $leadService) {}

    public function __invoke(Request $request, Response $response, array $args): Response
    {
        $leadId = (int) $args['id'];
        $parsedBody = json_decode((string) $request->getBody(), true);

        $newStatus = $parsedBody['status'] ?? null;

        if (!in_array($newStatus, ['New', 'Contacted', 'Closed'])) {
            $response->getBody()->write(json_encode(['error' => 'Invalid status']));
            return $response
                ->withStatus(400)
                ->withHeader('Content-Type', 'application/json');
        }

        try {
            $this->leadService->updateLeadStatus($leadId, $newStatus);
            $response->getBody()->write(json_encode(['success' => true]));
            return $response->withHeader('Content-Type', 'application/json');
        } catch (\Throwable $e) {
            $response->getBody()->write(json_encode(['error' => 'Failed to update lead']));
            return $response
                ->withStatus(500)
                ->withHeader('Content-Type', 'application/json');
        }
    }
}
