<?php

use Doctrine\DBAL\Exception;
use Doctrine\ORM\EntityManager;

require_once __DIR__ . '/vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

$container = require __DIR__ . '/src/bootstrap.php';
$entityManager = $container->get(EntityManager::class);

$connection = $entityManager->getConnection();
$params = $connection->getParams();

$dbName = $_ENV['DB_DATABASE'];
unset($params['dbname']);

try {
    $tmpConnection = \Doctrine\DBAL\DriverManager::getConnection($params);
    $schemaManager = $tmpConnection->createSchemaManager();

    echo "Database '$dbName' created successfully.\n";

} catch (Exception $e) {
    echo "Failed to create database: " . $e->getMessage() . "\n";
}
