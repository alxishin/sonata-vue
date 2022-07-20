<?php

declare(strict_types=1);

namespace SonataVue\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class Configuration implements ConfigurationInterface
{
	/**
	 * {@inheritdoc}
	 */
	public function getConfigTreeBuilder()
	{
		$treeBuilder = new TreeBuilder('sonata_vue');

		$treeBuilder->getRootNode()
			->children()
			->scalarNode('route_prefix')->end()
			->end()
			->end()
		;

		return $treeBuilder;
	}

	/**
	 * @param array $config
	 * @param ContainerBuilder $container
	 *
	 * @return Configuration
	 */
	public function getConfiguration(array $config, ContainerBuilder $container)
	{
		return new self([]);
	}
}
