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
     * @var bool
     */
    protected $flusherEnabled = true;

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
     * @param bool $flusherEnabled
     * @return $this
     */
    public function setFlusherEnabled($flusherEnabled)
    {
        $this->flusherEnabled = $flusherEnabled;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function flush($entity = null)
    {
        // If the flusher is flushing: do not call it again
        if ($this->flusherEnabled && !$this->flusher->isFlushing() && is_null($entity)) {
            $this->flusher->flush();
        } else {
            $this->wrapped->flush($entity);
        }
    }

    /**
     * Makes sure all pending flushes are executed.
     */
    public function finish()
    {
        $this->flusher->finish();
    }
}
