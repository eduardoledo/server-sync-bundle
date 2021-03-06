<?php

namespace EduardoLedo\ServerSyncBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files.
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/configuration.html}
 */
class Configuration implements ConfigurationInterface
{

    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('eduardo_ledo_server_sync');

        // Here you should define the parameters that are allowed to
        // configure your bundle. See the documentation linked above for
        // more information on that topic.

        $rootNode
                ->children()
                    ->arrayNode("servers")
                        ->useAttributeAsKey("name")
                        ->prototype("array")
                            ->children()
                                ->scalarNode("user")->end()
                                ->scalarNode("password")->end()
                                ->scalarNode("host")->isRequired()->end()
                                ->scalarNode("destination_dir")->isRequired()->end()
                                ->arrayNode("exclude")
                                    ->prototype("scalar")->end()
                                ->end()
                                ->scalarNode("exclude_from")->end()
                            ->end()
                        ->end()
                    ->end()
                ->end();

        return $treeBuilder;
    }

}
