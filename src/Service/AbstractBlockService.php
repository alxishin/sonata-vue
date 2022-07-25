<?php

declare(strict_types=1);

namespace SonataVue\Service;

use Symfony\Component\Form\Extension\Core\Type\ButtonType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Form;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

abstract class AbstractBlockService implements BlockInterface
{
	protected array $options = [];

	public function __toString(): string
	{
		return static::class;
	}

	protected function getFieldsForConfigForm(){
		return [];
	}

	abstract public function buildData(array $options, ?array $routeParams);

	public function getConfigForm(FormBuilderInterface $builder, string $pssn, string $slot, mixed $num):FormInterface{
		$this->addFieldsToConfigForm($builder);
		$this->addSaveButtonToConfigForm($builder, $pssn, $slot, $num);
		return $builder->getForm();
	}

	protected function addFieldsToConfigForm(FormBuilderInterface $builder){
		foreach ($this->getFieldsForConfigForm() as $name=>$field){
			$builder->add($name, $field[0], $field[1]);
		}
	}

	protected function addSaveButtonToConfigForm(FormBuilderInterface $builder, string $pssn, string $slot, mixed $num){
		$builder->add('save',ButtonType::class,['attr'=>[
			'class'=>'btn btn-success',
			'data-pssn'=>$pssn,
			'data-toggle'=>'save-config-form',
			'data-target'=>'#'.$slot.'-config-container-'.$num]]);
	}
}
