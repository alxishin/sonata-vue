<?php

declare(strict_types=1);

namespace Page\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class Configuration implements ConfigurationInterface
{
	/**
	 * @var array
	 */
	protected $defaultContainerTemplates;

	/**
	 * @param array $defaultContainerTemplates
	 */
	public function __construct(array $defaultContainerTemplates)
	{
		$this->defaultContainerTemplates = $defaultContainerTemplates;
	}

	/**
	 * {@inheritdoc}
	 */
	public function getConfigTreeBuilder()
	{
		$treeBuilder = new TreeBuilder('ppr_page');
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