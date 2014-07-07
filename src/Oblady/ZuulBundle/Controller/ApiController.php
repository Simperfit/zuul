<?php

namespace Oblady\ZuulBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\JsonResponse;
/**
 * @Route("/api")
 * @todo
 * - les methodes se ressemble bcp voir si pas moyen de factoriser en conservant 
 *  la lisibilité 
 * -remplacer les exceptions par la transmission de vrai messages d'erreurs 
 * - revoir les routes pour qu'elles soient plus logique /server/groups/{id} par /server/{id}/groups  
 * - exploser en plusieur controlleur selon l'objet traité
 * 
*/
class ApiController  extends Controller
{
    protected function  getRepository($class) {
        
        return $this->getDoctrine()->getRepository('Oblady\ZuulBundle\Entity\\'.$class);
    }
    
    protected function json($data) {
        
        $response = new JsonResponse();
        $response->setData($data);
        
        return $response;
    }
     /**
     * @Route("/cluster/{id}")
     */
    public function clusterAction($id)
    {
        
        $clusterRepo = $this->getRepository('Cluster');
        $cluster = $clusterRepo->find($id);
        if(!$cluster) {
            $data = ['error'=>['code'=>'404','msg'=>'Cluster not found']];
        } else {
            $data = ['servers'=>[],'groups'=>[],'users'=>[]];
            $serverRepo = $this->getRepository('Server');
            $servers = $serverRepo->findAll();
            foreach($servers as  $server) {
                    $data['servers'][] = ['id'=>$server->getId(),'name'=>$server->getName(),'selected'=>$cluster->hasServer($server)];
            }
            $groupRepo = $this->getRepository('Group');
            $groups = $groupRepo->findAll();
            foreach($groups as  $group) {
                    $data['groups'][] = ['id'=>$group->getId(),'name'=>$group->getName(),'selected'=>$cluster->hasGroup($group)];
            }
            $userRepo = $this->getRepository('User');
            $users = $userRepo->findAll();
            foreach($users as  $user) {
                    $data['users'][] = ['id'=>$user->getId(),'name'=>$user->getName(),'selected'=>$cluster->hasUser($user)];
            }
            $data['cluster']['serverUrl'] = $this->get('router')->generate('oblady_zuul_api_updateclusterservers', array('id' => $cluster->getId()));
            $data['cluster']['userUrl'] = $this->get('router')->generate('oblady_zuul_api_updateclusterusers', array('id' => $cluster->getId()));;
            $data['cluster']['groupUrl'] = $this->get('router')->generate('oblady_zuul_api_updateclustergroups', array('id' => $cluster->getId()));
          
        }
        
        
        return $this->json($data);
    }
    
     /**
     * @Route("/server/{id}")
     */
      public function serverAction($id)
    {
        
        $serverRepo = $this->getRepository('Server');
        $server = $serverRepo->find($id);
        if(!$server) {
            $data = ['error'=>['code'=>'404','msg'=>'Server not found']];
        } else {
            $data = ['groups'=>[],'users'=>[]];
            $groupRepo = $this->getRepository('Group');
            $groups = $groupRepo->findAll();
            foreach($groups as  $group) {
                    $data['groups'][] = ['id'=>$group->getId(),'name'=>$group->getName(),'selected'=>$server->hasGroup($group)];
            }
            $userRepo = $this->getRepository('User');
            $users = $userRepo->findAll();
            foreach($users as  $user) {
                    $data['users'][] = ['id'=>$user->getId(),'name'=>$user->getName(),'selected'=>$server->hasUser($user)];
            }
            $data['server']['groupUrl'] = $this->get('router')->generate('oblady_zuul_api_updateservergroups', array('id' => $server->getId()));
            $data['server']['userUrl'] = $this->get('router')->generate('oblady_zuul_api_updateserverusers', array('id' => $server->getId()));;
        }
        
        
        return $this->json($data);
    }
    
    /**
     * @Route("/server/groups/{id}")
     * @Method({"POST"})
     */
    public function updateServerGroupsAction($id) {
        
        $repo = $this->getRepository('Server');
        $obj = $repo->find($id);
        $data=[];
        if(!$obj) {
           throw new Exception('Missing Server');
        } else {
            $manager = $this->get('oblady_zuul.server_manager');
            $manager->setServer($obj);
            $ids = $this->getRequest()->get('groups');
            $data = $manager->updateRelationsByIds($obj,$ids,'Group');
        }
        
        return $this->json($data);
    }
    
    /**
     * @Route("/server/users/{id}")
     * @Method({"POST"})
     */
    public function updateServerUsersAction($id) {
        $repo = $this->getRepository('Server');
        $obj = $repo->find($id);
        $data=[];
        if(!$obj) {
           throw new Exception('Missing Server');
        } else {
            $manager = $this->get('oblady_zuul.server_manager');
            $manager->setServer($obj);
            $ids = $this->getRequest()->get('users');
            $data = $manager->updateRelationsByIds($obj,$ids,'User');
        }
        
        return $this->json($data);
        
    }
    
     /**
     * @Route("/clusters/users/{id}")
     * @Method({"POST"})
     */
    public function updateClusterUsersAction($id) {
        $repo = $this->getRepository('Cluster');
        $obj = $repo->find($id);
        $data=[];
        if(!$obj) {
           throw new Exception('Missing Cluster');
        } else {
            $manager = $this->get('oblady_zuul.cluster_manager');
            $manager->setCluster($obj);
            $ids = $this->getRequest()->get('users');
            $data = $manager->updateRelationsByIds($obj,$ids,'User');
        }
        
        return $this->json($data);
        
    }
     
     /**
     * @Route("/clusters/groups/{id}")
     * @Method({"POST"})
     */
    public function updateClusterGroupsAction($id) {
        $repo = $this->getRepository('Cluster');
        $obj = $repo->find($id);
        $data=[];
        if(!$obj) {
           throw new Exception('Missing Cluster');
        } else {
            $manager = $this->get('oblady_zuul.cluster_manager');
            $manager->setCluster($obj);
            $ids = $this->getRequest()->get('groups');
            $data = $manager->updateRelationsByIds($obj,$ids,'Group');
        }
        
        return $this->json($data);
        
    }
    
     /**
     * @Route("/clusters/servers/{id}")
     * @Method({"POST"})
     */
    public function updateClusterServersAction($id) {
        $repo = $this->getRepository('Cluster');
        $obj = $repo->find($id);
        $data=[];
        if(!$obj) {
           throw new Exception('Missing Cluster');
        } else {
            $manager = $this->get('oblady_zuul.cluster_manager');
            $manager->setCluster($obj);
            $ids = $this->getRequest()->get('servers');
            $data = $manager->updateRelationsByIds($obj,$ids,'Server');
        }
        
        return $this->json($data);
        
    }
    
     /**
     * @Route("/group/{id}")
     */
      public function groupAction($id)
    {
        
        $groupRepo = $this->getRepository('Group');
        $group = $groupRepo->find($id);
        if(!$group) {
            $data = ['error'=>['code'=>'404','msg'=>'Group not found']];
        } else {
           
            $userRepo = $this->getRepository('User');
            $users = $userRepo->findAll();
            foreach($users as  $user) {
                    $data['users'][] = ['id'=>$user->getId(),'name'=>$user->getName(),'selected'=>$group->hasUser($user)];
            }
        }
        
        
        return $this->json($data);
    }
    
     /**
     * @Route("/groups/users/{id}")
     * @Method({"POST"})
     */
    public function updateGroupUsersAction($id) {
        $repo = $this->getRepository('Group');
        $obj = $repo->find($id);
        $data=[];
        if(!$obj) {
           throw new Exception('Missing Group');
        } else {
            $manager = $this->get('oblady_zuul.group_manager');
            $manager->setGroup($obj);
            $ids = $this->getRequest()->get('users');
            $data = $manager->updateRelationsByIds($obj,$ids,'Group');
        }
        
        return $this->json($data);
        
    }
}
