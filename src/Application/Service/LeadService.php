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

    public function updateLead(int $id, string $name, string $contact, string $email, string $interest): void
    {
        $this->leadRepository->updateLead($id, $name, $contact, $email, $interest);
    }
}
