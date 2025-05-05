<?php

use Doctrine\ORM\ORMSetup;
use Doctrine\DBAL\DriverManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityManager;
use Doctrine\Migrations\DependencyFactory;
use Doctrine\Migrations\Configuration\EntityManager\ExistingEntityManager;
use Doctrine\Migrations\Configuration\Migration\PhpFile;

require_once __DIR__ . '/../vendor/autoload.php';

$paths = [__DIR__ . '/../src/Domain/Entity'];
$isDevMode = true;

$config = ORMSetup::createAttributeMetadataConfiguration($paths, $isDevMode);

// Use the same connection you've already used for schema commands
$connectionParams = [
    'driver' => 'pdo_mysql',               // Use MySQL
    'host' => $_ENV['DB_HOST'],            // MySQL host (e.g., localhost)
    'port' => $_ENV['DB_PORT'],            // MySQL port (usually 3306)
    'dbname' => $_ENV['DB_DATABASE'],      // Database name
    'user' => $_ENV['DB_USERNAME'],        // Username for the database
    'password' => $_ENV['DB_PASSWORD'],    // Password for the database
    'charset' => 'utf8mb4',                // Character set
];

$connection = DriverManager::getConnection($connectionParams, $config);

return [
    'conn' => $connection,
    'config' => $config,
];
