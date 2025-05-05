<?php

declare(strict_types=1);

namespace App\Domain\Lead;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: "leads")]
class Lead
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: "integer")]
    private int $id;

    #[ORM\Column(type: "string", length: 100)]
    private string $name;

    #[ORM\Column(type: "string", length: 20)]
    private string $contactNumber;

    #[ORM\Column(type: "string", length: 100)]
    private string $email;

    #[ORM\Column(type: "string", length: 100)]
    private string $productInterest;

    #[ORM\Column(type: "string", length: 20)]
    private string $status = 'New';

    #[ORM\Column(type: "datetime")]
    private \DateTime $createdAt;

    public function __construct(
        string $name,
        string $contactNumber,
        string $email,
        string $productInterest,
        string $status = 'New'
    ) {
        $this->name = $name;
        $this->contactNumber = $contactNumber;
        $this->email = $email;
        $this->productInterest = $productInterest;
        $this->status = $status;
        $this->createdAt = new \DateTime();
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }


    public function getContactNumber(): string
    {
        return $this->contactNumber;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    public function getProductInterest(): string
    {
        return $this->productInterest;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function getCreatedAt(): \DateTime
    {
        return $this->createdAt;
    }

    public function setStatus(string $status): void
    {
        $this->status = $status;
    }
}
