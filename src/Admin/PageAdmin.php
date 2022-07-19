<?php

declare(strict_types=1);

namespace Page\Admin;

use Page\Type\MapType;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;
use Symfony\Component\HttpFoundation\Request;

final class PageAdmin extends AbstractAdmin
{

    protected function configureDatagridFilters(DatagridMapper $filter): void
    {
        $filter
            ->add('path')
            ->add('publishedAt')
            ->add('publishedUntil')
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
			->with('map',['tab'=>true])
				->add('slotMap', MapType::class,['mapped'=>false,'page'=>$this->getSubject(),'label'=>false])
			->end()
			->end()
			->with('options',['tab'=>true])
				->add('path')
				->add('template')
				->add('site')
				->add('site')
				->add('requirements')
				->add('defaults')
			->end()
		;
    }

	protected function configureShowFields(ShowMapper $show): void
    {
        $show
            ->add('path')
            ->add('publishedAt')
            ->add('publishedUntil')
            ->add('requirements')
            ->add('defaults')
            ->add('createdAt')
            ->add('updatedAt')
            ;
    }
}
