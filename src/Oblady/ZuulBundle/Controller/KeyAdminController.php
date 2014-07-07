<?php

namespace Oblady\ZuulBundle\Controller;

use Sonata\AdminBundle\Controller\CRUDController;

class KeyAdminController extends CRUDController
{

     /* public function banAction($id = null)
      {
           $id = $this->get('request')->get($this->admin->getIdParameter());
           $object = $this->admin->getObject($id);
           
            if (!$object) {
                throw new NotFoundHttpException(sprintf('unable to find the object with id : %s', $id));
            }

            if (false === $this->admin->isGranted('VIEW', $object)) {
                throw new AccessDeniedException();
            }
            
            $manager = $this->container->get('oblady_zuul.key_manager');
            $manager->bankey($object);
            
            return new RedirectResponse($this->admin->generateUrl('list', array('filter' => $this->admin->getFilterParameters())));
            
           
      }*/
    
    
}
