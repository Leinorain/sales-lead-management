<?php

declare(strict_types=1);

namespace App\Domain\Lead;

interface LeadRepository
{
    public function save(Lead $lead): void;

    public function findAll(): array;
    
    public function updateLead(int $id, string $name, string $contactNumber, string $email, string $productInterest): void;

    public function findById(int $id): ?Lead;
}
