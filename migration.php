<?php

require __DIR__ . '/vendor/autoload.php';

use App\Domain\User\User;
use Doctrine\ORM\EntityManagerInterface;
use App\Domain\Lead\Lead;

// Bootstrapping Doctrine
$container = require __DIR__ . '/src/bootstrap.php';
$em = $container->get(EntityManagerInterface::class);

// Create and persist the user
$user = new User('admin');
$reflection = new ReflectionClass($user);
$passwordProperty = $reflection->getProperty('password');
$passwordProperty->setAccessible(true);
$passwordProperty->setValue($user, password_hash('secret123', PASSWORD_DEFAULT));

$em->persist($user);
$em->flush();

echo "User created.\n";

$lead = new Lead('Jane Doe', '911', 'jane@example.com', 'roof', 'new');
$em->persist($lead);
$em->flush();

echo "Lead created.\n";