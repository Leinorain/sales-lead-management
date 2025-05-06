<?php

declare(strict_types=1);

use App\Application\Settings\SettingsInterface;
use App\Domain\User\UserRepository;
use App\Infrastructure\Persistence\User\DoctrineUserRepository;
use DI\ContainerBuilder;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityManager;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Monolog\Processor\UidProcessor;
use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;
use Slim\Views\Twig;
use App\Domain\Auth\SessionInterface;
use App\Infrastructure\Auth\NativeSession;
use App\Domain\Auth\LoginService;
use Doctrine\ORM\ORMSetup;
use Doctrine\DBAL\DriverManager;
use Dotenv\Dotenv;
use App\Domain\Lead\LeadRepository;
use App\Application\Service\LeadService;
use App\Application\Actions\Lead\ViewLeadsAction;
use App\Application\Actions\Lead\CreateLeadAction;
use App\Infrastructure\Persistence\Lead\DoctrineLeadRepository;
use Slim\Handlers\ErrorHandler;

$dotenv = Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->safeLoad();

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

        Twig::class => function () {
            return Twig::create(__DIR__ . '/../templates', ['cache' => false]);
        },

        SessionInterface::class => DI\autowire(NativeSession::class),

        EntityManagerInterface::class => function () {
            $config = ORMSetup::createAttributeMetadataConfiguration(
                paths: [__DIR__ . '/../src/Domain'], 
                isDevMode: true
            );

            $connection = DriverManager::getConnection([
                'driver' => 'pdo_mysql',            
                'host' => $_ENV['DB_HOST'],         
                'port' => $_ENV['DB_PORT'],         
                'dbname' => $_ENV['DB_DATABASE'],   
                'user' => $_ENV['DB_USERNAME'],     
                'password' => $_ENV['DB_PASSWORD'], 
                'charset' => 'utf8mb4',             
            ], $config);

            return new EntityManager($connection, $config);
        },

        LeadRepository::class => \DI\autowire(DoctrineLeadRepository::class),

        UserRepository::class => function (ContainerInterface $c) {
            return new DoctrineUserRepository($c->get(EntityManagerInterface::class));
        },

        LoginService::class => function (ContainerInterface $c) {
            return new LoginService($c->get(UserRepository::class));
        },

        LeadService::class => function (ContainerInterface $container) {
            return new LeadService($container->get(App\Domain\Lead\LeadRepository::class));
        },
        ViewLeadsAction::class => function (ContainerInterface $container) {
            return new ViewLeadsAction(
                $container->get(LeadService::class),
                $container->get(Twig::class)
            );
        },

        CreateLeadAction::class => function (ContainerInterface $container) {
            return new CreateLeadAction($container->get(LeadService::class));
        },
    ]);
};
