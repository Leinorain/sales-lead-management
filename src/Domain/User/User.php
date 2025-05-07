<?php

    namespace App\Domain\User;

    use DateTimeImmutable;
    use Doctrine\ORM\Mapping\Column;
    use Doctrine\ORM\Mapping\Entity;
    use Doctrine\ORM\Mapping\GeneratedValue;
    use Doctrine\ORM\Mapping\Id;
    use Doctrine\ORM\Mapping\Table;

    #[Entity, Table(name: 'users')]
    class User
    {
        #[Id, Column(type: 'integer'), GeneratedValue(strategy: 'AUTO')]
        private int $id;

        #[Column(type: 'string', unique: true, nullable: false)]
        private string $username;

        #[Column(type: 'string', unique: true, nullable: false)]
        private string $password;

        #[Column(name: 'registered_at', type: 'datetimetz_immutable', nullable: false)]
        private DateTimeImmutable $registeredAt;

        public function __construct(string $username)
        {
            $this->username = $username;
            $this->registeredAt = new DateTimeImmutable('now');
            // $this->registeredAt = new DateTimeImmutable('now');
        }

        public function getId(): int
        {
            return $this->id;
        }

        public function findByUsername(): string
        {
            return $this->username;
        }

        public function password(): string
        {
            return $this->password;
        }

        public function getRegisteredAt(): DateTimeImmutable
        {
            return $this->registeredAt;
        }
    }