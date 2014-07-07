<?php

namespace Oblady\ZuulBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Route\RouteCollection;

class MapAdmin extends Admin
{
   protected $baseRoutePattern = 'map';
   protected $baseRouteName = 'map';

    protected function configureRoutes(RouteCollection $collection)
    {
        $collection->clearExcept(array('list'));
    }
    
}
