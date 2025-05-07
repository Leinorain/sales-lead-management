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
        $queryParams = $request->getQueryParams();

        $page = isset($queryParams['page']) ? max(1, (int)$queryParams['page']) : 1;
        $limit = 10;
        $offset = ($page - 1) * $limit;

        $sortField = $queryParams['sort_field'] ?? 'name';
        $sortOrder = $queryParams['sort_order'] ?? 'desc';

        $allLeads = $this->leadService->getAllLeads();

        usort($allLeads, function ($a, $b) use ($sortField, $sortOrder) {
            $getter = 'get' . ucfirst($sortField);
        
            $valueA = method_exists($a, $getter) ? $a->$getter() : null;
            $valueB = method_exists($b, $getter) ? $b->$getter() : null;
        
            if ($valueA == $valueB) return 0;
            return ($sortOrder === 'asc') ? $valueA <=> $valueB : $valueB <=> $valueA;
        });

        $totalLeads = count($allLeads);
        $leads = array_slice($allLeads, $offset, $limit);

        

        return $this->twig->render($response, 'admin/leads.twig', [
            'leads' => $leads,
            'currentPage' => $page,
            'totalPages' => ceil($totalLeads / $limit),
            'offset' => $offset,
            'sort_field' => $sortField,
            'sort_order' => $sortOrder,
        ]);
    }
}