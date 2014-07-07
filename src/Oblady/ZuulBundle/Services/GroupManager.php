<?php

/**
 *
 * @author beleneglorion
 */
namespace Oblady\ZuulBundle\Services;

use Oblady\ZuulBundle\Entity\Group;
use Oblady\ZuulBundle\Entity\User;


class GroupManager extends ObjectManager
{
    protected $group;
    
    
    public function setGroup(Group $group) {
        
        $this->group = $group;
    }
    
    public function addUser(User $user)
    {

        $serverManager  = $this->get('oblady_zuul.server_manager');
        foreach($this->group->getAllServers() as $server) {
            $serverManager->setServer($server) ;
            $serverManager->addUser($user)  ;     
        }
    }
    
    public function removeUser(User $user)
    {

        $serverManager  = $this->get('oblady_zuul.server_manager');
        foreach($this->group->getAllServers() as $server) {
            $serverManager->setServer($server) ;
            $serverManager->removeUser($user)  ;     
        }
    }
    
    
    
    
    protected function hasLinkUser(User $user) {

        return $this->group->getUsers()->contains($user);
        
    }
    
  
    
    public function linkUser(User $user){
        
             if(!$this->hasLinkUser($user)) {
                $this->group->addUser($user);
                $this->getEM()->persist($this->group);
                $this->getEM()->flush();
                $this->addUser($user);

            }
        
    }
    
     public function unlinkUser(User $user){
        
            if($this->hasLinkUser($user)) {
                $this->group->removeUser($user);
                $this->getEM()->persist($this->group);
                $this->getEM()->flush();
                $this->removeUser($user);
            }
        
    }
    
    
     
    
}
