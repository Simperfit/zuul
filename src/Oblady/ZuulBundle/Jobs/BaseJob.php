<?php

namespace Oblady\ZuulBundle\Jobs;
/**
/**
 * Description of BaseJob
 *
 * @author sebastien
 */

use BCC\ResqueBundle\ContainerAwareJob;

abstract class BaseJob extends ContainerAwareJob 
{
   
    
    public function getDoctrine()
    {
        
          return $this->getContainer()->getDoctrine(); 
    }
    
    public function getEM()
    {
        
          return $this->getDoctrine()->getManager(); 
    }
      
    public function getKeyManager()
    {
        
          return $this->getContainer()->get('oblady_zuul.key_manager'); 
    }
    
}

