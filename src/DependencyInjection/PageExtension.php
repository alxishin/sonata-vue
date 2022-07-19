<?php

declare(strict_types=1);

namespace Page\DependencyInjection;

use Doctrine\Bundle\DoctrineBundle\DependencyInjection\Configuration;
use Page\Service\BlockInterface;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

class PageExtension extends Extension
{
	/**
	 * {@inheritdoc}
	 */
	final public function getConfiguration(array $config, ContainerBuilder $container): Configuration
	{
		return new Configuration((bool) $container->getParameter('kernel.debug'));
	}

	/**
	 * {@inheritdoc}
	 */
	public function load(array $configs, ContainerBuilder $container)
	{
		$loader = new YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
		$loader->load('service.yml');
		$container->registerForAutoconfiguration(BlockInterface::class)
			->addTag('page.block_service')
		;


	}
}