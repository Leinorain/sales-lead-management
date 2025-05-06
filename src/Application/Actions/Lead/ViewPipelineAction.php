<?php

namespace App\Application\Actions\Lead;

use App\Application\Service\LeadService;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Views\Twig;

class ViewPipelineAction
{
    public function __construct(
        private LeadService $leadService,
        private Twig $twig
    ) {}

    public function __invoke(Request $request, Response $response): Response
    {
        $allLeads = $this->leadService->getAllLeads();

        $groupedLeads = [
            'New' => [],
            'Contacted' => [],
            'Closed' => [],
        ];

        foreach ($allLeads as $lead) {
            $status = $lead->getStatus();
            $groupedLeads[$status][] = $lead;
        }

        return $this->twig->render($response, 'admin/pipeline.twig', [
            'leads' => $groupedLeads,
        ]);

    }
}
