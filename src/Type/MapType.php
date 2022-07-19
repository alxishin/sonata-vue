<?php

declare(strict_types=1);

namespace Page\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MapType extends AbstractType
{
	public function __construct(private readonly iterable $blockServices)
	{

	}

	private array $services = [];

	public function getBlockServices():array{
		if(!count($this->services)){
			foreach ($this->blockServices as $service){
				$this->services[$service::class] = $service;
			}
		}
		return $this->services;
	}

	public function configureOptions(OptionsResolver $resolver)
	{
		$resolver->setDefault('page', null);
	}

	public function getBlockPrefix()
	{
		return 'map_type';
	}

	public function buildView(FormView $view, FormInterface $form, array $options)
	{
		$view->vars['services'] = $this->blockServices;
		$view->vars['page'] = $options['page'];
	}


}
