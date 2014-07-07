<?php

namespace Oblady\ZuulBundle\Entity;

use Sonata\UserBundle\Entity\BaseGroup as BaseGroup;
use Doctrine\ORM\Mapping as ORM;

/**
 * Group
 *
 * @ORM\Table(name="fos_user_group")
 * @ORM\Entity
 * @ORM\Entity(repositoryClass="Oblady\ZuulBundle\Entity\GroupRepository")
 */
class Group extends BaseGroup
{

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\ManyToMany(targetEntity="Cluster", mappedBy="groups")
     * @ORM\OrderBy({"name" = "ASC"}) 
     * */
    private $clusters;

    /**
     * @ORM\ManyToMany(targetEntity="Server", mappedBy="groups")
     * @ORM\OrderBy({"name" = "ASC"})
     * */
    private $servers;
    
    /**
     * @ORM\ManyToMany(targetEntity="User", mappedBy="groups")
     * @ORM\OrderBy({"username" = "ASC"})
     * */
    private $users;    
    
    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }
    
      /**
     * Add Cluster
     *
     * @param Cluster $cluster
     * @return User
     */
    public function addCluster(Cluster $cluster,$mirror= true)
    {
        $this->clusters[] = $cluster;
        if($mirror) {
          $cluster->removeUser($this,false);
        }
    
        return $this;
    }

    /**
     * Remove Cluster
     *
     * @param Cluster $cluster
     */
    public function removeCluster(Cluster $cluster,$mirror= true)
    {
        $this->clusters->removeElement($cluster);
         if($mirror) {
          $cluster->removeUser($this,false);
        }
    }

    /**
     * Get servers
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getClusters()
    {
        return $this->clusters;
    }
    
    /**
     * Add server
     *
     * @param Server $server
     * @return Group
     */
    public function addServer(Server $server, $mirror = true) {
        
        $this->servers[] = $server;
        if ($mirror) {
            $server->addGroup($this, false);
        }
        
        return $this;
    }

    /**
     * Remove server
     *
     * @param Server $server
     * @return Group
     */
    public function removeServer(Server $server, $mirror = true) {
        $this->servers->removeElement($server);
        if ($mirror) {
            $server->removeGroup($this, false);
        }
        
        return $this;
    }

    /**
     * Get servers
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getServers() {
        
        return $this->servers;
    }
    
     /**
     * Add User
     *
     * @param User $user
     * @return Group
     */
    public function addUser(User $user, $mirror = true) {
        
        $this->users[] = $user;
        if ($mirror) {
            $user->addGroup($this, false);
        }
        
        return $this;
    }

    /**
     * Remove user
     *
     * @param User $user
     */
    public function removeUser(User $user, $mirror = true) {
        $this->users->removeElement($user);
        if ($mirror) {
            $user->removeGroup($this, false);
        }
        
        return $this;
    }

    /**
     * Get users
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getUsers() {
        
        return $this->users;
    }
    
    /**
     *  test if user belong to server 
     * 
     * @param \Oblady\ZuulBundle\Entity\User $user
     * @return boolean
     */
    public function hasUser(User $user) {   
        return $this->users->contains($user);
        
    }
    /**
     * Get all servers, merge from cluster, servers
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getAllServers() {
        
        
        $servers = $this->getServers();

        foreach ($this->getClusters() as $cluster) {
            $servers = new \Doctrine\Common\Collections\Collection(
                    array_merge($servers->toArray(), $cluster->getServers()->toArray())
            );
        }

        return $servers;
        
    }
}