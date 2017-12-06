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
    private $flusher;
    private $flusherEnabled = true;

    public function __construct(EntityManagerInterface $wrapped, Flusher $flusher = null)
    {
        parent::__construct($wrapped);

        $this->flusher = $flusher;
    }

    public function setFlusherEnabled(bool $flusherEnabled): void
    {
        $this->flusherEnabled = $flusherEnabled;
    }

    public function flush($entity = null): void
    {
        // If the flusher is flushing: do not call it again
        if ($this->flusherEnabled && !$this->flusher->isFlushing() && is_null($entity)) {
            $this->flusher->flush();
        } else {
            $this->wrapped->flush($entity);
        }
    }

    public function finish(): void
    {
        $this->flusher->finish();
    }
}
