<?php

declare(strict_types=1);

namespace SonataVue\Admin;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

final class SiteAdmin extends AbstractAdmin
{

    protected function configureDatagridFilters(DatagridMapper $filter): void
    {

    }

    protected function configureListFields(ListMapper $list): void
    {
        $list
            ->add('title')
			->add('published')
            ->add('robots')
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
            ->add('title')
            ->add('robots')
            ->add('hostNames', CollectionType::class,['allow_add'=>true,'allow_delete'=>true])
			->add('published')
            ;
    }

    protected function configureShowFields(ShowMapper $show): void
    {
        $show
            ->add('title')
            ->add('published')
            ->add('robots')
            ;
    }
}
