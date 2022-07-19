<?php

declare(strict_types=1);

namespace Page\Service;

use Symfony\Component\Form\Extension\Core\Type\ButtonType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

abstract class AbstractBlockService implements BlockInterface
{
	protected array $options = [];
	protected OptionsResolver $resolver;

	public function __construct(private readonly FormFactoryInterface $builder)
	{
		$this->resolver = new OptionsResolver();
	}

	public function __toString(): string
	{
		return static::class;
	}

	protected function configureForm(){
		return [];
	}

	abstract public function buildData(array $options);

	public function getConfigForm(FormBuilderInterface $builder, array $data, string $srv, string $slot):FormInterface{
		$builder = $this->builder->createNamedBuilder($builder->getName(), FormType::class, $data,['csrf_protection'=>false]);
		foreach ($this->configureForm() as $name=>$field){
			$builder->add($name, $field[0], $field[1]);
		}
		$builder->add('save',ButtonType::class,['attr'=>[
			'class'=>'btn btn-success',
			'data-srv'=>$srv,
			'data-toggle'=>'save-config-form',
			'data-target'=>'#'.$slot.'-config-container']]);
		return $builder->getForm();
	}
}
