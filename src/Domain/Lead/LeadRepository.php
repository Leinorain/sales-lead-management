<?php

declare(strict_types=1);

namespace App\Domain\Lead;

interface LeadRepository
{
    public function save(Lead $lead): void;

    public function findAll(): array;
    
    public function updateLead(int $id, string $name, string $contactNumber, string $email, string $productInterest, string $status): void;

    public function findById(int $id): ?Lead;

    public function delete(int $id): void;

    public function countByStatus(string $status): int;
}
