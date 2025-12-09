<?php

use Doctrine\DBAL\DriverManager;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\ORMSetup;
use Psr\Container\ContainerInterface;
use Doctrine\DBAL\Connection;

use App\Domain\Repository\UserRepositoryInterface;
use App\Infrastructure\Persistence\Doctrine\Repository\DoctrineUserRepository;
use App\Domain\Repository\ContactRepositoryInterface;
use App\Infrastructure\Persistence\Doctrine\Repository\DoctrineContactRepository;

return [
    EntityManagerInterface::class => function (ContainerInterface $c) {
        $mappingPaths = [__DIR__ . '/../src/Infrastructure/Persistence/Doctrine/Mapping'];
        $isDevMode = getenv('APP_ENV') === 'local';

        $proxyDir = __DIR__ . '/../var/proxies';

        $config = ORMSetup::createXMLMetadataConfiguration(
            $mappingPaths, 
            $isDevMode, 
            $proxyDir 
        );

        if ($isDevMode) {
            $config->setAutoGenerateProxyClasses(true);
        } else {
            $config->setAutoGenerateProxyClasses(false);
        }

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

    UserRepositoryInterface::class => DI\autowire(DoctrineUserRepository::class),
    ContactRepositoryInterface::class => \DI\autowire(DoctrineContactRepository::class),
];