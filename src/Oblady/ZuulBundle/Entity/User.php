<?php

namespace Oblady\ZuulBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Sonata\UserBundle\Entity\BaseUser as BaseUser;

/**
 * User
 *
 * @ORM\Table(name="fos_user_user")
 * @ORM\Entity
 * @ORM\Entity(repositoryClass="Oblady\ZuulBundle\Entity\UserRepository")* 
 */
class User extends BaseUser {

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * 
     * @ORM\OneToMany(targetEntity="Key", mappedBy="user", cascade={"remove"})
     */
    protected $keys;

    /**
     * @ORM\ManyToMany(targetEntity="Server", mappedBy="users")
     * @ORM\OrderBy({"name" = "ASC"})
     * */
    private $servers;

    /**
     * @ORM\ManyToMany(targetEntity="Cluster", mappedBy="users")
     * @ORM\OrderBy({"name" = "ASC"})
     * */
    private $clusters;
    /**
     * 
     * @var  \Doctrine\Common\Collections\ArrayCollection
     * 
     * @ORM\ManyToMany(targetEntity="Group", inversedBy="users")
     * @ORM\JoinTable(name="fos_user_user_group")
     * @ORM\OrderBy({"name" = "ASC"}) 
     **/ 
    protected $groups;

    /**
     * Constructor
     */
    public function __construct() {
        
        parent::__construct();
        $this->servers = new \Doctrine\Common\Collections\ArrayCollection();
        $this->clusters = new \Doctrine\Common\Collections\ArrayCollection();
        $this->keys = new \Doctrine\Common\Collections\ArrayCollection();
        $this->groups = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId() {
        
        return $this->id;
    }

    /**
     * Add keys
     *
     * @param Key $keys
     * @return User
     */
    public function addKey(Key $keys) {
        $this->keys[] = $keys;

        return $this;
    }

    /**
     * Remove keys
     *
     * @param Key $keys
     */
    public function removeKey(Key $keys) {
        
        $this->keys->removeElement($keys);
        
        return $this;
    }

    /**
     * Get keys
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getKeys() {
        
        return $this->keys;
    }

    /**
     * Add servers
     *
     * @param Server $servers
     * @return User
     */
    public function addServer(Server $server, $mirror = true) {
        
        $this->server[] = $server;
        if ($mirror) {
            $server->addUser($this, false);
        }
        
        return $this;
    }

    /**
     * Remove servers
     *
     * @param Server $servers
     */
    public function removeServer(Server $server, $mirror = true) {
        $this->servers->removeElement($server);
        if ($mirror) {
            $server->removeUser($this, false);
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
     * Add Cluster
     *
     * @param Cluster $cluster
     * @return User
     */
    public function addCluster(Cluster $cluster, $mirror = true) {
        $this->clusters[] = $cluster;
        if ($mirror) {
            $cluster->removeUser($this, false);
        }

        return $this;
    }

    /**
     * Remove Cluster
     *
     * @param Cluster $cluster
     */
    public function removeCluster(Cluster $cluster, $mirror = true) {
        $this->clusters->removeElement($cluster);
        if ($mirror) {
            $cluster->removeUser($this, false);
        }
        
        return $this;
    }

    /**
     * Get servers
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getClusters() {
        
        return $this->clusters;
    }
    
    /**
     * 
     * @return stringReturn the name of pseudonyme of the user 
     * 
     * @return string
     */
    public function getName() {

        $returnValue =  $this->getUsername();
        
        if(!empty($this->getFirstname()) && !empty($this->getLastname())) {
            $returnValue = $this->getFirstname().' '.$this->getLastname();
        }
        
        return $returnValue;
    }

    /**
     * Return all the servers from servers,groups,cluster relation
     * 
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getAllServers() {

        $servers = $this->getServers();

        foreach ($this->getGroups() as $group) {

            $servers = new \Doctrine\Common\Collections\Collection(
                    array_merge($servers->toArray(), $group->getServers()->toArray())
            );
        }
        foreach ($this->getClusters() as $cluster) {
            $servers = new \Doctrine\Common\Collections\Collection(
                    array_merge($servers->toArray(), $cluster->getServers()->toArray())
            );
        }

        return $servers;
    }
    
    public function getAvatar(){
        return '';
    }

}
