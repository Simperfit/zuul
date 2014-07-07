<?php

/**
 *
 * @author beleneglorion
 */
namespace Oblady\ZuulBundle\Services;

use Oblady\ZuulBundle\Entity\Server;
use Oblady\ZuulBundle\Entity\Cluster;
use Oblady\ZuulBundle\Entity\Group;
use Oblady\ZuulBundle\Entity\User;

class ClusterManager extends ObjectManager
{
    
    protected $cluster;
    
    public function setCluster(Cluster $cluster) {
        
        $this->cluster = $cluster;
   
    }
    
    public function addUser(User $user) {
        $serverManager  = $this->get('oblady_zuul.server_manager');

        foreach($this->cluster->getServers() as $server) {
             $serverManager->setServer($server) ;
             $serverManager->addUser($user)  ;  
        }
    }
    
    public function removeUser(User $user) {
        $serverManager  = $this->get('oblady_zuul.server_manager');

        foreach($this->cluster->getServers() as $server) {
             $serverManager->setServer($server) ;
             $serverManager->removeUser($user)  ;  
        }
    }
    
     public function addGroup(Group $group) {
         
          $serverManager  = $this->get('oblady_zuul.server_manager');
          foreach($this->cluster->getServers() as $server) {
             $serverManager->setServer($server) ;
             $serverManager->addGroup($group)  ;  
          }  
    }
    
    public function removeGroup(Group $group) {
         
          $serverManager  = $this->get('oblady_zuul.server_manager');
          foreach($this->cluster->getServers() as $server) {
             $serverManager->setServer($server) ;
             $serverManager->removeGroup($group)  ;  
          }  
    }
    
    public function addServer(Server $server) {
        
        $serverManager  = $this->get('oblady_zuul.server_manager');
        $serverManager->setServer($server) ;
        foreach($this->cluster->getUsers() as $user) {
            $serverManager->addUser($user);
        }  
         foreach($this->cluster->getGroups() as $group) {
            $serverManager->addGroup($user);
        } 
    }
    
    public function removeServer(Server $server) {
        
        $serverManager  = $this->get('oblady_zuul.server_manager');
        $serverManager->setServer($server) ;
        foreach($this->cluster->getUsers() as $user) {
            $serverManager->removeUser($user);
        }  
         foreach($this->cluster->getGroups() as $group) {
            $serverManager->removeGroup($user);
        } 
    }
   
    protected function hasLinkUser(User $user) {

        return $this->cluster->getUsers()->contains($user);
        
    }
    protected function hasLinkGroup(Group $group) {

        return $this->cluster->getGroups()->contains($group);
        
    }
    
    protected function hasLinkServer(Server $server) {

        return $this->cluster->getServers()->contains($server);
        
    }
    
    public function linkUser(User $user){
        
            if(!$this->hasLinkUser($user)) {
                $this->cluster->addUser($user);
                $this->getEM()->persist($this->cluster);
                $this->getEM()->flush();
                $this->addUser($user);
            }
        
    }
    
    public function unlinkUser(User $user){
        
            if($this->hasLinkUser($user)) {
                $this->cluster->removeUser($user);
                $this->getEM()->persist($this->cluster);
                $this->getEM()->flush();
                $this->removeUser($user);
            }
        
    }
    
      
    public function linkGroup(Group $group){
        
            if(!$this->hasLinkGroup($group)) {
                $this->cluster->addGroup($group);
                $this->getEM()->persist($this->cluster);
                $this->getEM()->flush();
                $this->addGroup($group);
            }
        
    }
    
    public function unlinkGroup(Group $group){
        
            if($this->hasLinkGroup($group)) {
                $this->cluster->removeGroup($group);
                $this->getEM()->persist($this->cluster);
                $this->getEM()->flush();
                $this->removeGroup($group);
            }
        
    }
    
    public function linkServer(Server $server){
        
            if(!$this->hasLinkServer($server)) {
                $this->cluster->addServer($server);
                $this->getEM()->persist($this->cluster);
                $this->getEM()->flush();
                $this->addServer($server);
            }
        
    }
    
    public function unlinkServer(Server $server){
        
            if($this->hasLinkServer($server)) {
                $this->cluster->removeServer($server);
                $this->getEM()->persist($this->cluster);
                $this->getEM()->flush();
                $this->removeServer($server);
            }
        
    }


    
     
    
}

?>
