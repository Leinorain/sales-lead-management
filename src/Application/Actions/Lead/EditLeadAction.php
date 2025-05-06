<?php

namespace App\Application\Actions\Lead;

use App\Application\Service\LeadService;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Views\Twig;

class EditLeadAction
{
    public function __construct(
        private LeadService $leadService,
        private Twig $twig
    ) {}

    public function __invoke(Request $request, Response $response, array $args): Response
    {
        $leadId = (int) $args['id'];
        
        $lead = $this->leadService->getLeadById($leadId);
        return $this->twig->render($response, 'admin/edit-leads.twig', [
            'lead' => $lead
        ]);
    }
}
