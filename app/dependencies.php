<?php

declare(strict_types=1);

use App\Application\Settings\SettingsInterface;
use App\Domain\User\UserRepository;
use App\Infrastructure\Persistence\User\InMemoryUserRepository;
use DI\ContainerBuilder;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Monolog\Processor\UidProcessor;
use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;
use Slim\Views\Twig;
use App\Domain\Auth\SessionInterface;
use App\Infrastructure\Auth\NativeSession;
use Psr\Http\Message\ResponseFactoryInterface;
use Slim\App;

return function (ContainerBuilder $containerBuilder) {
    $containerBuilder->addDefinitions([
        LoggerInterface::class => function (ContainerInterface $c) {
            $settings = $c->get(SettingsInterface::class);
            $loggerSettings = $settings->get('logger');

            $logger = new Logger($loggerSettings['name']);
            $logger->pushProcessor(new UidProcessor());
            $logger->pushHandler(new StreamHandler($loggerSettings['path'], $loggerSettings['level']));

            return $logger;
        },

        UserRepository::class => function () {
            return new InMemoryUserRepository();
        },

        Twig::class => function () {
            return Twig::create(__DIR__ . '/../templates', ['cache' => false]);
        },

        SessionInterface::class => DI\autowire(NativeSession::class),
    ]);
};
