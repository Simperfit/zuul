<?php

namespace Oblady\ZuulBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;
use Sonata\AdminBundle\Route\RouteCollection;

class ServerAdmin extends Admin
{
    /**
     * @param DatagridMapper $datagridMapper
     */
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('id')
            ->add('name')
            ->add('hostname')
            ->add('ip')
            ->add('masterKey')
            ->add('enabled')
            ->add('reachable')
        ;
    }

    /**
     * @param ListMapper $listMapper
     */
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper

            ->add('name')
            ->add('hostname')
            ->add('user')
            ->add('port')
            ->add('masterKey')
            ->add('enabled')
            ->add('reachable')
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
            ->add('hostname')
            ->add('ip')
            ->add('user')
            ->add('port')
          ->end()
        /*   ->with('Clusters')      
            ->add('clusters', 'sonata_type_model', array(
                'expanded' => true, 
                'compound' => true,
                'property' => 'name',
                'multiple'=>true
                ))
           ->end()
          ->with('Users')      
            ->add('users', 'sonata_type_model', array(
                'expanded' => true, 
                'compound' => true,
                'property' => 'username',
                'multiple'=>true
                ))
           ->end()   
                 */
           ->with('Status')      
             ->add('enabled','sonata_type_boolean')
             ->end()         
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
            ->add('hostname')
            ->add('ip')
            ->add('user')
            ->add('port')
            ->add('masterKey')
            ->add('enabled')
            ->add('reachable')
            ->add('fingerprint')
            ->add('keys')
        ;
    }
    
        
  /*  public function getTemplate($name)
    {
        switch ($name) {
            case 'show':
                return 'ObladyZuulBundle:CRUD:server-show.html.twig';
                break;
            default:
                return parent::getTemplate($name);
                break;
        }
    }
    
    protected function configureRoutes(RouteCollection $collection)
    {
        $collection->add('removeUser', $this->getRouterIdParameter().'/remove/');
        $collection->add('addUser', $this->getRouterIdParameter().'/add/');
    }
    */
}
