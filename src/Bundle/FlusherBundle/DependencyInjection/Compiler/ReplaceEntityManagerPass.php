<?php

/*
 * (c) Jeroen van den Enden <info@endroid.nl>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Endroid\Flusher\Bundle\FlusherBundle\DependencyInjection\Compiler;

use Endroid\Flusher\FlusherEntityManager;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class ReplaceEntityManagerPass implements CompilerPassInterface
{
    /**
     * {@inheritdoc}
     */
    public function process(ContainerBuilder $container)
    {
        //        if (!$container->hasDefinition('doctrine.orm.default_entity_manager')) {
//            return;
//        }
//
//        $flusherDefinition = $container->getDefinition('endroid_flusher.flusher');
//
//        $managerDefinition = $container->getDefinition('doctrine.orm.default_entity_manager');
//        $managerDefinition
//            ->setClass(FlusherEntityManager::class)
//            ->addMethodCall('setFlusher', [$flusherDefinition])
//        ;
    }
}
