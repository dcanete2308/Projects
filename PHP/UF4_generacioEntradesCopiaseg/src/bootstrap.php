<?php
// bootstrap.php

use Doctrine\DBAL\DriverManager;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\ORMSetup;

require_once __DIR__.'/../vendor/autoload.php' ;

$config = ORMSetup::createAttributeMetadataConfiguration(
    paths: [__DIR__ . '/Entity'],
    isDevMode: true,
);

$connection = DriverManager::getConnection([
    'driver' =>'pdo_mysql',
    'host' =>'127.0.0.1',
    'dbname' =>'doctrine_CaneteDidac',
    'user' =>'didacAdmin',
    'password' =>'didac',
], $config);

$entityManager = new EntityManager($connection, $config);


