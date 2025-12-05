<?php

use Doctrine\DBAL\DriverManager;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\ORMSetup;
use Psr\Container\ContainerInterface;
use Doctrine\DBAL\Connection;

return [
    EntityManagerInterface::class => function (ContainerInterface $c) {
        $paths = [__DIR__ . '/../src/Domain/Entity'];
        $isDevMode = true; 

        $config = ORMSetup::createAttributeMetadataConfiguration($paths, $isDevMode);

        $connectionParams = [
            'dbname'   => getenv('MYSQL_DATABASE') ?: 'teste_pratico_db',
            'user'     => getenv('MYSQL_USER') ?: 'user',
            'password' => getenv('MYSQL_PASSWORD') ?: 'password',
            'host'     => 'db',
            'driver'   => 'pdo_mysql',
        ];

        $connection = DriverManager::getConnection($connectionParams, $config);
        return new EntityManager($connection, $config);
    },

    Connection::class => function (ContainerInterface $c) {
        return $c->get(EntityManagerInterface::class)->getConnection();
    },
];