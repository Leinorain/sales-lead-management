<?php

require __DIR__ . '/vendor/autoload.php';

use Doctrine\ORM\EntityManagerInterface;
use App\Domain\Lead\Lead;

// Bootstrapping Doctrine
$container = require __DIR__ . '/src/bootstrap.php';
$em = $container->get(EntityManagerInterface::class);

$leads = [
    new Lead('Jane Doe', '911', 'jane@example.com', 'roof', 'New', new \DateTime('2025-04-04 08:00:00')),
    new Lead('John Smith', '922', 'john@example.com', 'pipe', 'Contacted', new \DateTime('2025-04-04 08:00:00')),
    new Lead('Mary Jane', '933', 'mary@example.com', 'steel bar', 'Closed', new \DateTime('2025-03-03 08:00:00')),
];

foreach ($leads as $lead) {
    $em->persist($lead);
}

$em->flush();

echo "Lead created.\n";