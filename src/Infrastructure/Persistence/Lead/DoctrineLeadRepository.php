<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Lead;

use App\Domain\Lead\Lead;
use App\Domain\Lead\LeadRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectRepository;

class DoctrineLeadRepository implements LeadRepository
{
    private ObjectRepository $repository;

    public function __construct(private EntityManagerInterface $entityManager)
    {
        $this->repository = $entityManager->getRepository(Lead::class);
    }

    public function save(Lead $lead): void
    {
        $this->entityManager->persist($lead);
        $this->entityManager->flush();
    }

    public function findAll(): array
    {
        return $this->repository->findAll();
    }

    public function findById(int $id): ?Lead
    {
        return $this->entityManager->find(Lead::class, $id);
    }

    public function updateLead(int $id, string $name, string $contact, string $email, string $interest, string $status): void
    {
        $lead = $this->findById($id);
        if ($lead) {
            $lead->setName($name);
            $lead->setContactNumber($contact);
            $lead->setEmail($email);
            $lead->setProductInterest($interest);
            $lead->setStatus($status);
            $this->entityManager->flush();
        }
    }

    public function delete(int $id): void
    {
        $lead = $this->entityManager->find(Lead::class, $id);
        if ($lead !== null) {
            $this->entityManager->remove($lead);
            $this->entityManager->flush();
        }
    }

    public function countByStatus(string $status): int
    {
        return (int) $this->entityManager
            ->createQuery('SELECT COUNT(l.id) FROM App\Domain\Lead\Lead l WHERE l.status = :status')
            ->setParameter('status', $status)
            ->getSingleScalarResult();
    }
}
