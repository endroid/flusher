<?php

declare(strict_types=1);

namespace Endroid\Flusher\Tests;

use Doctrine\DBAL\DriverManager;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\ORMSetup;
use Endroid\Flusher\Flusher;
use PHPUnit\Framework\TestCase;

final class FlusherTest extends TestCase
{
    public function testCreateFlusher()
    {
        $config = ORMSetup::createAttributeMetadataConfiguration(['/'], true);
        $config->enableNativeLazyObjects(true);
        $connection = DriverManager::getConnection(['driver' => 'pdo_sqlite', 'path' => __DIR__.'/db.sqlite']);
        $entityManager = new EntityManager($connection, $config);

        $flusher = new Flusher($entityManager);
        $flusher->flush();
        $flusher->finish();

        $this->assertInstanceOf(Flusher::class, $flusher);
    }
}
