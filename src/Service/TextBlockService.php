<?php

declare(strict_types=1);

namespace Page\Service;

use Symfony\Component\Form\Extension\Core\Type\TextType;

class TextBlockService extends AbstractBlockService
{
	protected function configureForm()
	{
		return ['text' => [TextType::class, []]];
	}

	public function buildData(array $options)
	{
		return $options['text'];
	}

}
