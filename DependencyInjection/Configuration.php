<?php

namespace Kornushkin\Bundle\ImageProcessorBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html#cookbook-bundles-extension-config-class}
 */
class Configuration implements ConfigurationInterface
{
    //    /**
    //     * {@inheritDoc}
    //     */
    //    public function getConfigTreeBuilder()
    //    {
    //        $treeBuilder = new TreeBuilder();
    //        $treeBuilder->root('liuggio_excel');
    //        return $treeBuilder;
    //    }


    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        //        $treeBuilder = new TreeBuilder();
        //        $treeBuilder->root('kornushkin_image_processor');
        //
        //        // Here you should define the parameters that are allowed to
        //        // configure your bundle. See the documentation linked above for
        //        // more information on that topic.
        //
        //        return $treeBuilder;


        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('kornushkin_image_processor');


        $rootNode
            ->children()
                ->arrayNode('image_types')
                    ->isRequired()
                    ->requiresAtLeastOneElement()
                    ->useAttributeAsKey('name')
                    ->prototype('scalar')
            ->end();


        return $treeBuilder;
    }
}
