<?php

declare(strict_types=1);

namespace App\Application\Actions\Lead;

use App\Application\Service\LeadService;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Views\Twig;

class ViewLeadsAction
{
    public function __construct(
        private LeadService $leadService,
        private Twig $twig
    ) {}

    public function __invoke(Request $request, Response $response): Response
    {
        $leads = $this->leadService->getAllLeads();
        return $this->twig->render($response, 'admin/dashboard.twig', [
            'leads' => $leads,
        ]);
    }
}
