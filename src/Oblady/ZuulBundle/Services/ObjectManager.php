<?php

/**
 *
 * @author beleneglorion
 */
namespace Oblady\ZuulBundle\Services;

use Symfony\Component\DependencyInjection\ContainerAware;
use Oblady\ZuulBundle\Tools\AuthorizedKey;
/**
 * Description of ObjectManager
 *
 * @author sebastien
 */
class ObjectManager extends ContainerAware {
    //put your code here
    
     
    private $prefix = 'Oblady\ZuulBundle\Entity\\';
    

    public function getDoctrine() {
        
      return   $this->container->get('doctrine');
        
    }
    public function getEM() {
        
      return   $this->getDoctrine()->getManager();
        
    }
    
    public function getResque() {
        
      return   $this->container->get('bcc_resque.resque');
        
    }
     public function get($service) {
        
      return   $this->container->get($service);
        
    }
    
    public function getRepository($class) {
        
        return $this->getDoctrine()->getRepository($this->prefix.$class);
    }
    
    public function getServer($serverId){
        
       return $this->getRepository('Server')->find((int)$serverId);
    }
    
    public function getAllAvailableServer(){ 
        
       return $this->getRepository('Server')->findBy(array('enabled'=>true,'reachable'=>true));
    }
    
    public function getUser($UserId){
        
       return $this->getDoctrine()->getRepository('Oblady\ZuulBundle\User')->find((int) $UserId);
    }
    
    public function getAuthKey($keyId){
        
       $keyObject = $this->getRepository('Key')->find((int)$keyId);
       
       return $this->keyFactory($keyObject->getContent());
    }
    
    public function keyFactory($key) {
        $key = new AuthorizedKey($key);

        return $key;
    }
    
    
    public function updateRelationsByIds($object,$ids,$class) {
            $data = [];
            $repo  = $this->getRepository($class);
            $currentIds = $object->{'get'.ucfirst($class).'sIds'}();
            $newIds = array_diff($ids, $currentIds);
            $removeIds = array_diff($currentIds,$ids );
            $data['ids'] = array_values(array_intersect($currentIds, $ids));
            $data['current'] = $currentIds;
            $data['new'] = $newIds;
            $data['remove'] = $removeIds;
            if(!empty($removeIds)) {
                foreach($object->{'get'.ucfirst($class).'s'}() as $related) {
                    if(in_array($related->getId(),$removeIds)) {
                        if(!$this->{'unlink'.ucfirst($class)}($related)) {
                           $data['ids'][] = $related->getId();  
                        } 
                    }
                }
            }
            if(!empty($newIds)) {
                $newRelated = $repo->findById($newIds);
                foreach($newRelated as $related) {
                    if($this->{'link'.ucfirst($class)}($related)) {
                        $data['ids'][] = $related->getId();
                    }
                }
            }
        
            return $data;
    }
    
}

