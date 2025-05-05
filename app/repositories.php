<?php

declare(strict_types=1);

use App\Domain\User\UserRepository;
use App\Infrastructure\Persistence\User\DoctrineUserRepository;
use DI\ContainerBuilder;

return function (ContainerBuilder $containerBuilder) {
    // Map UserRepository interface to Doctrine implementation
    $containerBuilder->addDefinitions([
        UserRepository::class => \DI\autowire(DoctrineUserRepository::class),
    ]);
};
