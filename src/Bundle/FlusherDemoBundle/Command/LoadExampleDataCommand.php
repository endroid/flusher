<?php

/*
 * (c) Jeroen van den Enden <info@endroid.nl>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Endroid\Flusher\Bundle\FlusherDemoBundle\Command;

use Endroid\Flusher\Bundle\FlusherDemoBundle\Entity\Task;
use Endroid\Flusher\Flusher;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class LoadExampleDataCommand extends ContainerAwareCommand
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('endroid:flusher:flush-example-data')
            ->setDescription('Flushes example data')
        ;
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $flusher = $this->getFlusher();

        for ($n = 1; $n <= 50000; $n++) {
            $task = new Task();
            $task->setName('Task '.$n);
            $flusher->getManager()->persist($task);
            $flusher->flush();
        }

        $flusher->finish();
    }

    /**
     * @return Flusher
     */
    protected function getFlusher()
    {
        return $this->getContainer()->get('endroid_flusher.flusher');
    }
}
