<?php

/**
 *
 * @author beleneglorion
 */
namespace Oblady\ZuulBundle\Services;

use Oblady\ZuulBundle\Entity\User;
use Oblady\ZuulBundle\Entity\Key;
/*
use Oblady\ZuulBundle\Jobs\AddUserToServerJob;
use Oblady\ZuulBundle\Jobs\RemoveUserToServerJob;
use Oblady\ZuulBundle\Jobs\RemoveKeyToServerJob;*/

//use Symfony\Component\HttpKernel\Debug;

class UserManager extends ObjectManager
{
    protected $user;
    
    
    public function setUser(User $user) {
        
        $this->user = $user;
    }
    
    public function addKey(Key $key)
    {
        $servers = $this->user->getAllServers();
        $resque = $this->getResque();
        foreach($servers as $server) {
        
            $job = new AddKeyToServerJob();
            $job->args = array(
                'server'    => $server,
                'key' => $key
            );
            $resque->enqueue($job);
        }
    }
    
    public function removeKey(Key $key)
    {
        $servers = $this->user->getAllServers();
        $resque = $this->getResque();
        foreach($servers as $server) {
        
            $job = new removeKeyToServerJob();
            $job->args = array(
                'server'    => $server,
                'key' => $key
            );
            $resque->enqueue($job);
        }
    }
    
    
    protected function hasLinkkey(Key $key) {

        return $this->getKeys()->contains($key);
        
    }
    
    
    public function linkKey(Key $key){
        
             if(!$this->hasLinkKey($key)) {
                $this->user->addKey($key);
                $this->getEM()->persist($this->user);
                $this->getEM()->flush();
                $this->addKey($key);
            }
        
    }
    public function unlinkKey(Key $key){
        
             if($this->hasLinkKey($key)) {
                $this->user->removeKey($key);
                $this->getEM()->persist($this->user);
                $this->getEM()->flush();
                $this->removeKey($key);
            }
        
    }
 
}

