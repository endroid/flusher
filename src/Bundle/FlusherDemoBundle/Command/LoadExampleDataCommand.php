<?php

/*
 * (c) Jeroen van den Enden <info@endroid.nl>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Endroid\Flusher\Bundle\FlusherDemoBundle\Command;

use Doctrine\ORM\EntityManagerInterface;
use Endroid\Flusher\Bundle\FlusherDemoBundle\Entity\Task;
use Endroid\Flusher\Flusher;
use Endroid\Flusher\FlusherEntityManager;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
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
            ->addOption('count', null, InputArgument::OPTIONAL, 'Number of entities to flush', 50000)
        ;
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $manager = $this->getEntityManager();

        for ($n = 1; $n <= (int) $input->getOption('count'); ++$n) {
            $task = new Task();
            $task->setName('Task '.$n);
            $manager->persist($task);
            $manager->flush();
        }

        if ($manager instanceof FlusherEntityManager) {
            $manager->finish();
        }
    }

    /**
     * @return EntityManagerInterface
     */
    protected function getEntityManager()
    {
        return $this->getContainer()->get('doctrine.orm.default_entity_manager');
    }
}
