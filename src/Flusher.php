<?php

declare(strict_types=1);

/*
 * (c) Jeroen van den Enden <info@endroid.nl>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Endroid\Flusher;

use Doctrine\ORM\EntityManagerInterface;
use Endroid\Flusher\Exception\PendingFlushesException;
use Symfony\Component\Stopwatch\Stopwatch;

class Flusher
{
    private $manager;
    private $stepSize = 1.5;
    private $batchSize;
    private $ratios;
    private $isFlushing = false;
    private $hasPendingFlushes = false;

    public function __construct(EntityManagerInterface $manager)
    {
        $this->manager = $manager;

        $this->ratios = [];
        $this->batchSize = 1;
    }

    public function getManager(): EntityManagerInterface
    {
        return $this->manager;
    }

    public function setStepSize(float $stepSize): void
    {
        $this->stepSize = $stepSize;
    }

    public function isFlushing(): bool
    {
        return $this->isFlushing;
    }

    public function flush(): void
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
     * Makes sure all last items are flushed.
     * Even when the batch size is not reached yet.
     */
    public function finish()
    {
        $this->manager->flush();

        $this->hasPendingFlushes = false;
    }

    protected function updateBatchSize(int $count, int $duration): void
    {
        $ratio = $duration / $count;

        $this->ratios[$this->batchSize] = $ratio;

        $this->batchSize = array_search(min($this->ratios), $this->ratios);

        // Best batch size is the maximum batch size: try a higher value
        if ($this->batchSize == max(array_keys($this->ratios))) {
            $this->increaseBatchSize();
        }
    }

    protected function increaseBatchSize(): void
    {
        $this->batchSize = (int) ceil($this->batchSize * $this->stepSize);
    }

    public function __destruct()
    {
        if ($this->hasPendingFlushes) {
            throw new PendingFlushesException('Please call finish() to ensure all flushes are executed');
        }
    }
}
