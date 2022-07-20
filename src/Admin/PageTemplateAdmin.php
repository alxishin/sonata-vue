<?php

declare(strict_types=1);

namespace SonataVue\Admin;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

final class PageTemplateAdmin extends AbstractAdmin
{

    protected function configureDatagridFilters(DatagridMapper $filter): void
    {
        $filter
            ->add('title')
            ->add('slots')
            ;
    }

    protected function configureListFields(ListMapper $list): void
    {
        $list
            ->add('title')
            ->add('slots')
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
            ->add('slots', CollectionType::class,['allow_add'=>true,'allow_delete'=>true])
            ;
    }

    protected function configureShowFields(ShowMapper $show): void
    {
        $show
            ->add('title')
            ->add('slots')
            ;
    }
}
