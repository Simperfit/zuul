<?php

namespace Oblady\ZuulBundle\Controller;

use Sonata\AdminBundle\Controller\CRUDController;

class ServerAdminController extends CRUDController
{
    
     /**
     * return the Response object associated to the view action
     *
     * @param null $id
     *
     * @throws \Symfony\Component\Security\Core\Exception\AccessDeniedException
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     *
     * @return Response
     */
        public function showAction($id = null)
        {
            $id = $this->get('request')->get($this->admin->getIdParameter());

            $object = $this->admin->getObject($id);

            if (!$object) {
                throw new NotFoundHttpException(sprintf('unable to find the object with id : %s', $id));
            }

            if (false === $this->admin->isGranted('VIEW', $object)) {
                throw new AccessDeniedException();
            }
            
            $repo =  $this->getDoctrine()->getRepository('Oblady\ZuulBundle\Entity\User');
            $users = $repo->findAll();
            $this->admin->setSubject($object);

            return $this->render($this->admin->getTemplate('show'), array(
                'action'   => 'show',
                'object'   => $object,
                'elements' => $this->admin->getShow(),
                'users' => $users,
            ));
        }
    
     public function addUserAction($id = null,$fk = null)
     {
           $answer = array();
           $answer['error'] = 0;
           $answer['msg'] = 'Server added';
          
           try {
                $id = $this->get('request')->get($this->admin->getIdParameter());
                $object = $this->admin->getObject($id);
                if (!$object) {
                     throw new NotFoundHttpException(sprintf('unable to find the object with id : %s', $id));
                }


                $fk = $this->get('request')->get('fk');
                $remote = $this->getDoctrine()->getRepository('Oblady\ZuulBundle\Entity\User')->find($fk);
                if (!$remote) {
                     throw new NotFoundHttpException(sprintf('unable to find the remote object with id : %s', $fk));
                }

                $manager = $this->container->get('oblady_zuul.server_manager');
                $manager->setServer($object);
                $manager->linkUser($remote);
           } catch (NotFoundHttpException $e) {
                $answer = array();
                $answer['error'] = 1;
                $answer['msg'] = $e->getMessage();
           }
           
           return $this->renderJSON($answer);
     }
     
      public function addGroupAction($id = null,$fk = null)
     {
           $answer = array();
           $answer['error'] = 0;
           $answer['msg'] = 'Server added';
          
           try {
                $id = $this->get('request')->get($this->admin->getIdParameter());
                $object = $this->admin->getObject($id);
                if (!$object) {
                     throw new NotFoundHttpException(sprintf('unable to find the object with id : %s', $id));
                }


                $fk = $this->get('request')->get('fk');
                $remote = $this->getDoctrine()->getRepository('Oblady\ZuulBundle\Entity\Group')->find($fk);
                if (!$remote) {
                     throw new NotFoundHttpException(sprintf('unable to find the remote object with id : %s', $fk));
                }

                $manager = $this->container->get('oblady_zuul.server_manager');
                $manager->setServer($object);
                $manager->linkGroup($remote);
           } catch (NotFoundHttpException $e) {
                $answer = array();
                $answer['error'] = 1;
                $answer['msg'] = $e->getMessage();
           }
           
           return $this->renderJSON($answer);
     }
     
     public function removeUserAction($id = null,$fk = null)
     {
           $answer = array();
           $answer['error'] = 0;
           $answer['msg'] = 'Server removed';
           try {
                $id = $this->get('request')->get($this->admin->getIdParameter());
                $object = $this->admin->getObject($id);
                if (!$object) {
                     throw new NotFoundHttpException(sprintf('unable to find the object with id : %s', $id));
                }


                $fk = $this->get('request')->get('fk');
                $remote = $this->getDoctrine()->getRepository('Oblady\ZuulBundle\Entity\User')->find($fk);
                if (!$remote) {
                     throw new NotFoundHttpException(sprintf('unable to find the remote object with id : %s', $fk));
                }

                $manager = $this->container->get('oblady_zuul.server_manager');
                $manager->setServer($object);
                $manager->unlinkUser($remote);
           } catch (NotFoundHttpException $e) {
                $answer = array();
                $answer['error'] = 1;
                $answer['msg'] = $e->getMessage();
           }
       
           return $this->renderJSON($answer);
     }
     
      public function removeGroupAction($id = null,$fk = null)
     {
           $answer = array();
           $answer['error'] = 0;
           $answer['msg'] = 'Server removed';
           try {
                $id = $this->get('request')->get($this->admin->getIdParameter());
                $object = $this->admin->getObject($id);
                if (!$object) {
                     throw new NotFoundHttpException(sprintf('unable to find the object with id : %s', $id));
                }


                $fk = $this->get('request')->get('fk');
                $remote = $this->getDoctrine()->getRepository('Oblady\ZuulBundle\Entity\Group')->find($fk);
                if (!$remote) {
                     throw new NotFoundHttpException(sprintf('unable to find the remote object with id : %s', $fk));
                }

                $manager = $this->container->get('oblady_zuul.server_manager');
                $manager->setServer($object);
                $manager->unlinkGroup($remote);
           } catch (NotFoundHttpException $e) {
                $answer = array();
                $answer['error'] = 1;
                $answer['msg'] = $e->getMessage();
           }
       
           return $this->renderJSON($answer);
     }
}
