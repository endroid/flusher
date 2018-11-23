<?php

declare(strict_types=1);

/*
 * (c) Jeroen van den Enden <info@endroid.nl>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Endroid\Flusher\Tests;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\Setup;
use Endroid\Flusher\Flusher;
use PHPUnit\Framework\TestCase;

class FlusherTest extends TestCase
{
    public function testCreateFlusher()
    {
        $config = Setup::createAnnotationMetadataConfiguration(['/'], true);
        $entityManager = EntityManager::create([
            'driver' => 'pdo_mysql',
            'user' => 'root',
            'password' => 'root',
            'dbname' => 'flusher',
        ], $config);

        $flusher = new Flusher($entityManager);
        $flusher->flush();
        $flusher->finish();

        $this->assertInstanceOf(Flusher::class, $flusher);
    }
}
