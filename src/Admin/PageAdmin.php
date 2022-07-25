<?php

declare(strict_types=1);

namespace SonataVue\Admin;

use SonataVue\Entity\Page;
use SonataVue\Type\MapType;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\HttpFoundation\Request;

final class PageAdmin extends AbstractAdmin
{

    protected function configureDatagridFilters(DatagridMapper $filter): void
    {
        $filter
            ->add('path')
			->add('published')
            ->add('requirements')
            ->add('defaults')
            ->add('createdAt')
            ->add('updatedAt')
            ;
    }

    protected function configureListFields(ListMapper $list): void
    {
        $list
            ->add('path')
			->add('published',null,['editable'=>true])
            ->add(ListMapper::NAME_ACTIONS, null, [
                'actions' => [
                    'show' => [],
                    'edit' => [],
                    'delete' => [],
                ],
            ]);
    }

    protected function configureFormFields(FormMapper $form): void
    {
		$form
			->with('slots',['tab'=>true])
				->add('slotMap', MapType::class,['mapped'=>false,'page'=>$this->getSubject(),'label'=>false])
			->end()
			->end()
			->with('options',['tab'=>true])
				->add('path')
//				->add('responseType', ChoiceType::class, ['choices'=>Page::RESPONSE_TYPES])
				->add('template')
				->add('site')
				->add('published')
				->add('defaults',CollectionType::class,['allow_add'=>true,'allow_delete'=>true, 'label_format' => '%name%'])
				->add('requirements',CollectionType::class,['allow_add'=>true,'allow_delete'=>true, 'label_format' => '%name%'])
			->end()
		;
    }

	protected function configureShowFields(ShowMapper $show): void
    {
        $show
            ->add('path')
			->add('published')
            ->add('requirements')
            ->add('defaults')
            ->add('createdAt')
            ->add('updatedAt')
            ;
    }
}
