<?php

/*
 * (c) Jeroen van den Enden <info@endroid.nl>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Endroid\Flusher;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Endroid\Flusher\Exception\PendingFlushesException;
use Symfony\Component\Stopwatch\Stopwatch;

class Flusher
{
    /**
     * @var EntityManager
     */
    protected $manager;

    /**
     * @var float
     */
    protected $stepSize = 1.5;

    /**
     * @var int
     */
    protected $batchSize;

    /**
     * @var float[]
     */
    protected $ratios;

    /**
     * @var bool
     */
    protected $isFlushing = false;

    /**
     * @var bool
     */
    protected $hasPendingFlushes = false;

    /**
     * @param EntityManagerInterface $manager
     */
    public function __construct(EntityManagerInterface $manager)
    {
        $this->manager = $manager;

        $this->ratios = [];
        $this->batchSize = 1;
    }

    /**
     * @return EntityManager
     */
    public function getManager()
    {
        return $this->manager;
    }

    /**
     * @param float $stepSize
     *
     * @return $this
     */
    public function setStepSize($stepSize)
    {
        $this->stepSize = $stepSize;

        return $this;
    }

    /**
     * @return bool
     */
    public function isFlushing()
    {
        return $this->isFlushing;
    }

    /**
     * Only executes when the current batch size is met.
     */
    public function flush()
    {
        $this->isFlushing = true;

        $count = count($this->manager->getUnitOfWork()->getScheduledEntityInsertions()) + $this->manager->getUnitOfWork()->size();

        // Only flush upon latest of the current batch
        if ($count < $this->batchSize) {
            $this->hasPendingFlushes = true;

            return;
        }

        $stopwatch = new Stopwatch();
        $stopwatch->start('flush');

        $this->manager->flush();
        $this->manager->clear();

        $event = $stopwatch->stop('flush');

        $this->isFlushing = false;
        $this->hasPendingFlushes = false;

        $this->updateBatchSize($count, $event->getPeriods()[0]->getDuration());
    }

    /**
     * Makes sure all last items are flushed even if the batch
     * size was not reached yet.
     */
    public function finish()
    {
        $this->manager->flush();

        $this->hasPendingFlushes = false;
    }

    /**
     * @param int $count
     * @param int $duration
     */
    protected function updateBatchSize($count, $duration)
    {
        $ratio = $duration / $count;

        $this->ratios[$this->batchSize] = $ratio;

        $this->batchSize = array_search(min($this->ratios), $this->ratios);

        // Best batch size is the maximum batch size: try a higher value
        if ($this->batchSize == max(array_keys($this->ratios))) {
            $this->increaseBatchSize();
        }
    }

    protected function increaseBatchSize()
    {
        $this->batchSize = (int) ceil($this->batchSize * $this->stepSize);
    }

    /**
     * Checks if there exist pending flushes that are not executed.
     */
    public function __destruct()
    {
        if ($this->hasPendingFlushes) {
            throw new PendingFlushesException('Please call finish() to ensure all flushes are executed');
        }
    }
}
