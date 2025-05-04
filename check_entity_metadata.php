<?php
require_once __DIR__ . '/vendor/autoload.php';

use Doctrine\ORM\Tools\SchemaTool;
use Doctrine\ORM\EntityManager;
use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

/** @var \Psr\Container\ContainerInterface $container */
$container = require __DIR__ . '/src/bootstrap.php';

/** @var EntityManager $em */
$em = $container->get(EntityManager::class);

$meta = $em->getMetadataFactory()->getAllMetadata();

if (empty($meta)) {
    echo "❌ No metadata found.\n";
    exit(1);
}

echo "✅ Metadata loaded for entities:\n";
foreach ($meta as $m) {
    echo "- " . $m->getName() . "\n";
}
echo "Connected to DB: " . $em->getConnection()->getDatabase() . "\n";

$tool = new SchemaTool($em);
$sql = $tool->getCreateSchemaSql($meta);
echo "\nSQL to be executed:\n";
echo implode(";\n", $sql) . ";\n";
