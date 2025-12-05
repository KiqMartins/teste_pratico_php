<?php

namespace Tests\Integration;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\DBAL\Connection;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;

class DatabaseConnectionTest extends TestCase
{
    private ContainerInterface $container;

    protected function setUp(): void
    {
        $this->container = require __DIR__ . '/../../config/bootstrap.php';
    }

    public function testDependencyInjectionContainerIsWorking(): void
    {
        $this->assertInstanceOf(ContainerInterface::class, $this->container);
    }

    public function testCanConnectToDatabaseViaDoctrine(): void
    {
        $entityManager = $this->container->get(EntityManagerInterface::class);
        $this->assertInstanceOf(EntityManagerInterface::class, $entityManager);

        $connection = $entityManager->getConnection();
        $this->assertInstanceOf(Connection::class, $connection);

        $result = $connection->executeQuery('SELECT 1')->fetchOne();

        $this->assertEquals(1, $result, 'Database connection has failed.');
    }
}