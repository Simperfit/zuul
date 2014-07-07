<?php

/**
 *
 * @author beleneglorion
 */
namespace Oblady\ZuulBundle\Services;


use Oblady\ZuulBundle\Entity\Server;
use Oblady\ZuulBundle\Entity\Group;
use Oblady\ZuulBundle\Entity\User;
use Oblady\ZuulBundle\Jobs\AddKeyToServerJob;
use Oblady\ZuulBundle\Jobs\RemoveKeyToServerJob;

//use Symfony\Component\HttpKernel\Debug;

class ServerManager extends ObjectManager
{
    protected $server;
    
    public function setServer(Server $server) {
        $this->server = $server;
    }

    public function addUser(User $user) {
        
        $resque = $this->getResque();
        
        foreach($user->getKeys() as $key ) {
        
            $job = new AddKeyToServerJob();
            $job->args = array(
                'server'    => $this->server->clean(),
                'key' => $key->clean()
            );
            $resque->enqueue($job);
        }
        
        
    }
    
    public function removeUser(User $user) {
        
        $resque = $this->getResque();
        foreach($user->getKeys() as $key ) {
            $job = new RemoveKeyToServerJob();
            $job->args = array(
                'server'    => $this->server->clean(),
                'key' => $key->clean(),
            );
        }
        $resque->enqueue($job);
        
    }
    
    public function addGroup(Group $group) {
        
        //$resque = $this->getResque();
        
        foreach($group->getUsers() as $user ) {
            $this->addUser($user);
        }
    }
    
    public function removeGroup(Group $group) {
        
       // $resque = $this->getResque();
        
        foreach($group->getUsers() as $user ) {
            $this->removeUser($user);
        }
    }

    protected function hasLinkUser(User $user) {

        return $this->server->getUsers()->contains($user);
        
    }
    
    protected function hasLinkGroup(Group $group) {

        return $this->server->getGroups()->contains($group);
        
    }
    
    

    
    public function linkUser(User $user){
             $returnValue = true;
             try {
             if(!$this->hasLinkUser($user)) {
                $this->server->addUser($user);
                $this->getEM()->persist( $this->server);
                $this->getEM()->flush();
                $this->addUser($user); 
             } 
             
             } catch(\Exception $e) {
                 $returnValue = false;
             }
            return $returnValue;
    }
     public function unlinkUser(User $user){
            $returnValue = true;
            try {
            if($this->hasLinkUser($user)) {
                $this->server->removeUser($user);
                $this->getEM()->persist( $this->server);
                $this->getEM()->flush();
                $this->removeUser($user);
            }
            
            } catch(\Exception $e) {
                 $returnValue = false;
             }
            
             return $returnValue;
        
    }
     
    public function linkGroup(Group $group){
            $returnValue = true;
             try {
             if(!$this->hasLinkGroup($group)) {
                $this->server->addGroup($group);
                $this->getEM()->persist( $this->server);
                $this->getEM()->flush();
                $this->addGroup($group); 
            }
            } catch(\Exception $e) {
                 $returnValue = false;
             }
             return $returnValue;
        
    }
     public function unlinkGroup(Group $group){
            $returnValue = true;
            try {
            if($this->hasLinkGroup($group)) {
                $this->server->removeGroup($group);
                $this->getEM()->persist( $this->server);
                $this->getEM()->flush();
                $this->removeGroup($group);
                
            }
            } catch(\Exception $e) {
                 $returnValue = false;
             }
            return $returnValue;
        
    }

}
