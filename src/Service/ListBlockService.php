<?php

declare(strict_types=1);

namespace Page\Service;

use Sonata\AdminBundle\Form\Type\CollectionType;

class ListBlockService extends AbstractBlockService
{
	protected function getFieldsForConfigForm()
	{
		return ['list' => [CollectionType::class, ['allow_add'=>'true','allow_delete'=>true,'prototype_name'=>0]]];
	}

	public function buildData(array $options)
	{
		return $options['list'];
	}

}
