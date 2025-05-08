<?php

declare(strict_types=1);

namespace App\Application\Service;

use App\Domain\Lead\Lead;
use App\Domain\Lead\LeadRepository;

class LeadService
{
    public function __construct(private LeadRepository $leadRepository) {}

    public function createLead(string $name, string $contactNumber, string $email, string $productInterest): void
    {
        $lead = new Lead($name, $contactNumber, $email, $productInterest);
        $this->leadRepository->save($lead);
    }

    public function getAllLeads(): array
    {
        return $this->leadRepository->findAll();
    }

    public function getLeadById(int $id): ?Lead
    {
        return $this->leadRepository->findById($id);
    }

    public function updateLead(int $id, string $name, string $contact, string $email, string $interest, string $status): void
    {
        $this->leadRepository->updateLead($id, $name, $contact, $email, $interest, $status);
    }

    public function deleteLead(int $id): void
    {
        $this->leadRepository->delete($id);
    }

    public function countLeadsByStatus(string $status): int
    {
        return $this->leadRepository->countByStatus($status);
    }

    public function getPipelineLeads(): array
    {
        $grouped = ['New' => [], 'Contacted' => [], 'Closed' => []];
        foreach ($this->leadRepository->findAll() as $lead) {
            $grouped[$lead->getStatus()][] = $lead;
        }
        return $grouped;
    }

    public function updateLeadStatus(int $id, string $status): void
    {
        $lead = $this->leadRepository->findById($id);
        if (!$lead) {
            throw new \RuntimeException("Lead not found");
        }

        $lead->setStatus($status);
        $this->leadRepository->save($lead);
    }

    public function getLeadsForLastThreeMonths(): array
    {
        $allLeads = $this->leadRepository->findAll();
        $counts = [];

        $now = new \DateTimeImmutable('first day of this month');

        for ($i = 0; $i < 3; $i++) {
            $month = $now->modify("-{$i} months")->format('F Y');
            $counts[$month] = 0;
        }

        foreach ($allLeads as $lead) {
            $createdAt = $lead->getCreatedAt();
            if (!$createdAt instanceof \DateTimeInterface) continue;

            $leadMonth = \DateTimeImmutable::createFromFormat('Y-m-d', $createdAt->format('Y-m-01'));
            $monthKey = $leadMonth->format('F Y');

            if (array_key_exists($monthKey, $counts)) {
                $counts[$monthKey]++;
            }
        }

        uksort($counts, function ($a, $b) {
            return strtotime($b) <=> strtotime($a);
        });

        return $counts;
    }

    public function getLatestLeads(int $limit = 3): array
    {
        $allLeads = $this->leadRepository->findAll();
        usort($allLeads, function ($a, $b) {
            return $b->getCreatedAt() <=> $a->getCreatedAt();
        });

        return 
        
        array_slice($allLeads, 0, $limit);
    }
    
}
