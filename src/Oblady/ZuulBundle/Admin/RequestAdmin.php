<?php

namespace Oblady\ZuulBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Route\RouteCollection;

class RequestAdmin extends Admin
{
   protected $baseRoutePattern = 'request';
   protected $baseRouteName = 'request';

    protected function configureRoutes(RouteCollection $collection)
    {
        $collection->clearExcept(array('list'));
    }
    
}
