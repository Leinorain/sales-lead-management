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

$connectionParams = [
    'driver' => 'pdo_mysql',               
    'host' => $_ENV['DB_HOST'],            
    'port' => $_ENV['DB_PORT'],            
    'dbname' => $_ENV['DB_DATABASE'],     
    'user' => $_ENV['DB_USERNAME'],       
    'password' => $_ENV['DB_PASSWORD'],   
    'charset' => 'utf8mb4',               
];

$connection = DriverManager::getConnection($connectionParams, $config);

return [
    'conn' => $connection,
    'config' => $config,
];
