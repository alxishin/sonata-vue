<?php

declare(strict_types=1);

namespace SonataVue\Admin;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;

final class SiteAdmin extends AbstractAdmin
{

    protected function configureDatagridFilters(DatagridMapper $filter): void
    {

    }

    protected function configureListFields(ListMapper $list): void
    {
        $list
            ->add('title')
            ->add('publishedAt')
            ->add('publishedUntil')
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
            ;
    }

    protected function configureShowFields(ShowMapper $show): void
    {
        $show
            ->add('title')
            ->add('publishedAt')
            ->add('publishedUntil')
            ->add('robots')
            ;
    }
}
