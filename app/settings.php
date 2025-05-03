<?php

declare(strict_types=1);

use App\Application\Settings\Settings;
use App\Application\Settings\SettingsInterface;
use DI\ContainerBuilder;
use Monolog\Logger;

define('APP_ROOT', dirname(__DIR__));

return function (ContainerBuilder $containerBuilder): void {
    $containerBuilder->addDefinitions([
        SettingsInterface::class => function () {
            return new Settings([
                'displayErrorDetails' => true,
                'logError'            => true,
                'logErrorDetails'     => true,
                'doctrine' => [
                    'dev_mode' => true,
                    'cache_dir' => APP_ROOT . '/var/doctrine',
                    'metadata_dirs' => [APP_ROOT . '/src/Domain'],
                    'connection' => [
                        'driver' => 'pdo_mysql',
                        'host' => 'localhost',
                        'port' => 3306,
                        'dbname' => 'mydb',
                        'user' => 'user',
                        'password' => 'secret',
                        'charset' => 'utf8'
                    ]
                ],
                'logger' => [
                    'name' => 'slim-app',
                    'path' => isset($_ENV['docker']) ? 'php://stdout' : __DIR__ . '/../logs/app.log',
                    'level' => Logger::DEBUG,
                ],
            ]);
        }
    ]);
};
