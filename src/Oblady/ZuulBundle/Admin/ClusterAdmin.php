<?php

namespace Oblady\ZuulBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;
use Sonata\AdminBundle\Route\RouteCollection;

class ClusterAdmin extends Admin
{
    /**
     * @param DatagridMapper $datagridMapper
     */
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('name')
        ;
    }

    /**
     * @param ListMapper $listMapper
     */
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->add('name')
            ->add('_action', 'actions', array(
                'actions' => array(
                    'show' => array(),
                    'edit' => array(),
                    'delete' => array(),
                )
            ))
        ;
    }

    /**
     * @param FormMapper $formMapper
     */
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
          ->with('General')
            ->add('name')
           ->end()
      /*    ->with('Servers')      
            ->add('servers', 'sonata_type_model', array(
                'expanded' => true, 
                'compound' => true,
                'property' => 'name',
                'multiple'=>true,
                'by_reference' => false
                ))
           ->end()      */
        ;
    }

    /**
     * @param ShowMapper $showMapper
     */
    protected function configureShowFields(ShowMapper $showMapper)
    {
        $showMapper
            ->add('id')
            ->add('name')
        ;
    }
    
    
   /* public function getTemplate($name)
    {
        switch ($name) {
            case 'show':
                return 'ObladyZuulBundle:CRUD:cluster-show.html.twig';
                break;
            case 'map':
                return 'ObladyZuulBundle:Default:map.html.twig';
                break;
            default:
                return parent::getTemplate($name);
                break;
        }
    }*/
    
   /*protected function configureRoutes(RouteCollection $collection)
    {
      //  $collection->add('removeServer', $this->getRouterIdParameter().'/remove/');
        //$collection->add('addServer', $this->getRouterIdParameter().'/add/');
        //$collection->add('map');
    }
*/
    
    
}
