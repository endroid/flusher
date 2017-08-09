<?php

/*
 * (c) Jeroen van den Enden <info@endroid.nl>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Endroid\Flusher;

use Doctrine\ORM\Decorator\EntityManagerDecorator;
use Doctrine\ORM\EntityManagerInterface;

class FlusherEntityManager extends EntityManagerDecorator implements EntityManagerInterface
{
    /**
     * @var Flusher
     */
    protected $flusher;

    /**
     * @var EntityManagerInterface
     */
    protected $manager;

    /**
     * @param EntityManagerInterface $wrapped
     * @param Flusher $flusher
     */
    public function __construct(EntityManagerInterface $wrapped, Flusher $flusher = null)
    {
        parent::__construct($wrapped);

        $this->flusher = $flusher;
    }

    /**
     * {@inheritdoc}
     */
    public function flush($entity = null)
    {
        $this->wrapped->flush();
    }

    /**
     * Makes sure all pending flushes are executed.
     */
    public function finish()
    {
        $this->flusher->finish();
    }
}
