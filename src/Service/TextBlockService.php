<?php

declare(strict_types=1);

namespace SonataVue\Service;

use Symfony\Component\Form\Extension\Core\Type\TextType;

class TextBlockService extends AbstractBlockService
{
	protected function getFieldsForConfigForm()
	{
		return ['text' => [TextType::class, []]];
	}

	public function buildData(array $options, ?array $routeParams)
	{
		return $options['text'];
	}

	public function getLabel(): string
	{
		return 'Text block';
	}

}
