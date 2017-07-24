<?php

/*
 * (c) Jeroen van den Enden <info@endroid.nl>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Endroid\Flusher;

use Doctrine\ORM\EntityManager;

class Flusher
{
    /**
     * @var EntityManager
     */
    protected $manager;

    /**
     * @param EntityManager $manager
     */
    public function __construct(EntityManager $manager)
    {
        $this->manager = $manager;
    }

    /**
     * @return EntityManager
     */
    public function getManager()
    {
        return $this->manager;
    }

    public function flush()
    {
        $this->manager->flush();
    }

    public function finish()
    {
        $this->manager->flush();
    }
}
