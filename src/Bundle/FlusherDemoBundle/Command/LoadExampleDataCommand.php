<?php

/*
 * (c) Jeroen van den Enden <info@endroid.nl>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Endroid\Flusher\Bundle\FlusherDemoBundle\Command;

use Doctrine\ORM\EntityManager;
use Endroid\Bundle\DataSanitizeBundle\Entity\Project;
use Endroid\Bundle\DataSanitizeBundle\Entity\Tag;
use Endroid\Bundle\DataSanitizeBundle\Entity\Task;
use Endroid\Bundle\DataSanitizeBundle\Entity\User;
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
        $manager = $this->getEntityManager();

        for ($n = 1; $n <= 100; $n++) {
            $task = new Task();
            $task->setName('Task '.$n);
            $manager->persist($task);
            $manager->flush();
        }
    }

    /**
     * @return EntityManager
     */
    protected function getEntityManager()
    {
        return $this->getContainer()->get('doctrine.orm.entity_manager');
    }
}
