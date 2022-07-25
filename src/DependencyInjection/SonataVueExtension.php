<?php

declare(strict_types=1);

namespace SonataVue\DependencyInjection;


use SonataVue\Service\BlockInterface;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

class SonataVueExtension extends Extension
{
	/**
	 * {@inheritdoc}
	 */
//	final public function getConfiguration(array $config, ContainerBuilder $container): Configuration
//	{
//		return new Configuration((bool) $container->getParameter('kernel.debug'));
//	}

	/**
	 * {@inheritdoc}
	 */
	public function load(array $configs, ContainerBuilder $container)
	{
		$configuration = new Configuration();

		$config = $this->processConfiguration($configuration, $configs);
		$container->setParameter('sonata_vue.route_prefix', $config['route_prefix']);
		$container->setParameter('sonata_vue.role_for_ajax_request', $config['role_for_ajax_request']);

		$loader = new YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
		$loader->load('service.yml');
		$container->registerForAutoconfiguration(BlockInterface::class)
			->addTag('sonata_vue.block_service')
		;
	}
}