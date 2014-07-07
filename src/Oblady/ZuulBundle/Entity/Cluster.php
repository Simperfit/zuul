<?php

namespace Oblady\ZuulBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Cluster
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Oblady\ZuulBundle\Entity\ClusterRepository")
 */
class Cluster
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=40)
     */
    private $name;
  
   /**
    * @ORM\ManyToMany(targetEntity="Oblady\ZuulBundle\Entity\Server", mappedBy="clusters")
    * @ORM\OrderBy({"name" = "ASC"})
    **/
    private $servers;
    
    /**
     * 
     * @var  \Doctrine\Common\Collections\ArrayCollection
     * 
     * @ORM\ManyToMany(targetEntity="User", inversedBy="clusters")
     * @ORM\OrderBy({"username" = "ASC"})  
     * @ORM\JoinTable(name="clusters_users")
     **/ 

    private $users;  
    
     /**
     * 
     * @var  \Doctrine\Common\Collections\ArrayCollection
     * 
     * @ORM\ManyToMany(targetEntity="Group", inversedBy="clusters")
     * @ORM\JoinTable(name="clusters_groups")
     * @ORM\OrderBy({"name" = "ASC"}) 
     **/ 

    private $groups;      
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
     * Set name
     *
     * @param string $name
     * @return Cluster
     */
    public function setName($name)
    {
        $this->name = $name;
    
        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }
    
    public function __toString()
    {
        return $this->getName();
    }
    
    /**
     * Constructor
     */
    public function __construct()
    {
    
         $this->servers = new \Doctrine\Common\Collections\ArrayCollection();
    }
    
      /**
     * Add servers
     *
     * @param Server $servers
     * @return Key
     */
    public function addServer(Server $server,$mirror = true)
    {
        $this->servers[] = $server;
        if($mirror) {
          $server->addCluster($this,false);
        }
        return $this;
    }

    /**
     * Remove servers
     *
     * @param Server $servers
     */
    public function removeServer(Server $server,$mirror= true)
    {
        $this->servers->removeElement($server);
        if($mirror) {
          $server->removeCluster($this,false);
        }
    }
    
    /**
     * test if cluster has this server
     *
     * @param Server $server
     */
    public function hasServer(Server $server)
    {
        
        return $this->servers->contains($server);
    }
    
     /**
     * test if cluster has this group
     *
     * @param Group $group
     */
    public function hasGroup(Group $group)
    {
        
        return $this->groups->contains($group);
    }
    
    
     /**
     * test if cluster has this user
     *
     * @param User $user
     */
    public function hasUser(User $user)
    {
        
        return $this->users->contains($user);
    }

    /**
     * Get servers
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getServers()
    {
        return $this->servers;
    }
    
     /**
     * Add user
     *
     * @param \User $user
     * @return Server
     */
    public function addUser(User $user,$mirror = true)
    {
        $this->users[] = $user;
        if($mirror) {
          $user->removeCluster($this,false);
        }
        return $this;
    }

    /**
     * Remove users
     *
     * @param \User $user
     */
    public function removeUser(User $user,$mirror = true)
    {
        $this->users->removeElement($user);
        if($mirror) {
          $user->removeCluster($this,false);
        }
    }

    /**
     * Get users
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getUsers()
    {
        return $this->users;
    }
    
    
    
    public function getKeys() {
        
        return array();
    }
    
     /**
     * Add Group
     *
     * @param \User $user
     * @return Server
     */
    public function addGroup(Group $group,$mirror = true)
    {
        $this->groups[] = $group;
        if($mirror) {
          $group->removeCluster($this,false);
        }
        return $this;
    }

    /**
     * Remove Group
     *
     * @param \Group $group
     */
    public function removeGroup(Group $group,$mirror = true)
    {
        $this->groups->removeElement($group);
        if($mirror) {
          $group->removeCluster($this,false);
        }
    }

    /**
     * Get users
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getGroups()
    {
        return $this->groups;
    }
    
     
    public function getServersIds() {
        $returnValue = array();
        
        foreach($this->servers as $server) {
            $returnValue[] = $server->getId();
        }
        
        return $returnValue;
        
    }       
    
    public function getGroupsIds() {
        $returnValue = array();
        
        foreach($this->groups as $group) {
            $returnValue[] = $group->getId();
        }
        
        return $returnValue;
        
    }
    
    public function getUsersIds() {
        $returnValue = array();
        
        foreach($this->users as $user) {
            $returnValue[] = $user->getId();
        }
        
        return $returnValue;
        
    }
    /**
     * Remove related objects for small footprint
     * 
     * @return \Oblady\ZuulBundle\Entity\Cluster
     */
    public function clean() {
         unset($this->users);
         unset($this->groups);
         unset($this->servers);
         
         return $this;
    }
     
   
}