<?php

/*
 * (c) Jeroen van den Enden <info@endroid.nl>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Endroid\Flusher;

use Doctrine\ORM\EntityManager;
use Symfony\Component\Stopwatch\Stopwatch;

class Flusher
{
    /**
     * @var EntityManager
     */
    protected $manager;

    /**
     * @var int
     */
    protected $batchSize;

    /**
     * @var int
     */
    protected $batchNumber;

    /**
     * @var float[]
     */
    protected $ratios;

    /**
     * @param EntityManager $manager
     */
    public function __construct(EntityManager $manager)
    {
        $this->manager = $manager;

        $this->ratios = [];
        $this->batchSize = 1;
        $this->batchNumber = 0;
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
        $this->batchNumber++;

        // Only flush upon latest of the current batch
        if ($this->batchNumber < $this->batchSize) {
            return;
        }

        $stopwatch = new Stopwatch();
        $stopwatch->start('flush');

        $this->manager->flush();
        $this->batchNumber = 0;

        $event = $stopwatch->stop('flush');

        $this->updateBatchSize($event->getPeriods()[0]->getDuration());
    }

    public function finish()
    {
        $this->manager->flush();
    }

    /**
     * @param int $duration
     */
    protected function updateBatchSize($duration)
    {
        $ratio = $duration / $this->batchSize;

        $this->ratios[$this->batchSize] = $ratio;

        $this->batchSize = array_search(min($this->ratios), $this->ratios);

        // Best batch size is the maximum batch size: try a higher value
        if ($this->batchSize == max(array_keys($this->ratios))) {
            $this->increaseBatchSize();
        }
    }

    protected function increaseBatchSize()
    {
        $this->batchSize = ceil($this->batchSize * 1.5);
    }
}
