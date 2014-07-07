<?php

namespace Oblady\ZuulBundle\Controller;


use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sonata\AdminBundle\Controller\CRUDController as Controller;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
/**
* @Route("/map")
* @todo : les users et les clefs ne sont plus utilisés supprimer leur chargement
*/
class MapController  extends Controller
{
    
     /**
     * @Route("/")
     */
      public function listAction()
        {
     
            if (false === $this->admin->isGranted('LIST')) {
                throw new AccessDeniedException();
            }
            
            $clusterRepository =  $this->getDoctrine()->getRepository('Oblady\ZuulBundle\Entity\Cluster');
            $clusters = $clusterRepository->findAll();  
            
            $serverRepository =  $this->getDoctrine()->getRepository('Oblady\ZuulBundle\Entity\Server');
            $servers = $serverRepository->findAll();  
            
            $groupRepository =  $this->getDoctrine()->getRepository('Oblady\ZuulBundle\Entity\Group');
            $groups = $groupRepository->findAll();
            
            $userRepository =  $this->getDoctrine()->getRepository('Oblady\ZuulBundle\Entity\User');
            $users = $userRepository->findAll();

            $keyRepository =  $this->getDoctrine()->getRepository('Oblady\ZuulBundle\Entity\Key');
            $keys = $keyRepository->findAll();

            return $this->render('ObladyZuulBundle:Default:map.html.twig', array(
                'action'   => 'map',
                'clusters'   => $clusters,
                'servers'   => $servers,
                'groups' => $groups,
                'users' => $users,
                'keys' => $keys
                    
            ));
        }
        

    
}
