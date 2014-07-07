<?php

namespace Oblady\ZuulBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Server
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Oblady\ZuulBundle\Entity\ServerRepository")
 */
class Server
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
     * @ORM\Column(name="name", type="string", length=30)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="hostname", type="string", length=255)
     */
    private $hostname;

    /**
     * @var string
     *
     * @ORM\Column(name="ip", type="string", length=20)
     */
    private $ip;

    /**
     * @var string
     *
     * @ORM\Column(name="user", type="string", length=50)
     */
    private $user;
    /**
     * @var integer
     *
     * @ORM\Column(name="port", type="integer")
     */
    private $port;

    /**
     * @var boolean
     *
     * @ORM\Column(name="masterKey", type="boolean",options={"default" = false})
     */
    private $masterKey;


    /**
     * @var boolean
     *
     * @ORM\Column(name="enabled", type="boolean",options={"default" = true})
     */
    private $enabled;


    /**
     * @var boolean
     *
     * @ORM\Column(name="reachable", type="boolean",options={"default" = true})
     */
    private $reachable;


    /**
     * @var string
     *
     * @ORM\Column(name="fingerprint", type="string", length=40)
     */
    private $fingerprint;
    
    
    /**
     * 
     * @var  \Doctrine\Common\Collections\ArrayCollection
     * 
     * @ORM\ManyToMany(targetEntity="Oblady\ZuulBundle\Entity\Cluster", inversedBy="servers")
     * @ORM\JoinTable(name="servers_clusters")
     * @ORM\OrderBy({"name" = "ASC"})     
     */
    
    private $clusters;    
    
     /**
     * 
     * @var  \Doctrine\Common\Collections\ArrayCollection
     * 
     * @ORM\ManyToMany(targetEntity="User", inversedBy="servers")
     * @ORM\JoinTable(name="servers_users")
     * @ORM\OrderBy({"username" = "ASC"})
     **/ 

    private $users;  
    
     /**
     * 
     * @var  \Doctrine\Common\Collections\ArrayCollection
     * 
     * @ORM\ManyToMany(targetEntity="Group", inversedBy="servers")
     * @ORM\JoinTable(name="servers_groups")
     * @ORM\OrderBy({"name" = "ASC"}) 
     **/ 

    private $groups;      
    
    /**
     * 
     * @var  \Doctrine\Common\Collections\ArrayCollection
     * 
     * @ORM\ManyToMany(targetEntity="Oblady\ZuulBundle\Entity\Key", inversedBy="servers")
     * @ORM\JoinTable(name="servers_keys")
     **/ 

    private $keys; 
    
    
       
    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
        $this->clusters = new \Doctrine\Common\Collections\ArrayCollection();
        $this->users = new \Doctrine\Common\Collections\ArrayCollection();
    } 
    
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
     * @return Server
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

    /**
     * Set hostname
     *
     * @param string $hostname
     * @return Server
     */
    public function setHostname($hostname)
    {
        $this->hostname = $hostname;
    
        return $this;
    }

    /**
     * Get hostname
     *
     * @return string 
     */
    public function getHostname()
    {
        return $this->hostname;
    }
    
    /**
     * Set user
     *
     * @param string $user
     * @return Server
     */
    public function setUser($user)
    {
        $this->user = $user;
    
        return $this;
    }

    /**
     * Get user
     *
     * @return string 
     */
    public function getUser()
    {
        return $this->user;
    }


    /**
     * Set ip
     *
     * @param string $ip
     * @return Server
     */
    public function setIp($ip)
    {
        $this->ip = $ip;
    
        return $this;
    }

    /**
     * Get ip
     *
     * @return string 
     */
    public function getIp()
    {
        return $this->ip;
    }

    /**
     * Set port
     *
     * @param integer $port
     * @return Server
     */
    public function setPort($port)
    {
        $this->port = $port;
    
        return $this;
    }

    /**
     * Get port
     *
     * @return integer 
     */
    public function getPort()
    {
        return $this->port;
    }

    /**
     * Set masterKey
     *
     * @param boolean $masterKey
     * @return Server
     */
    public function setMasterKey($masterKey)
    {
        $this->masterKey = $masterKey;
    
        return $this;
    }

    /**
     * Get masterKey
     *
     * @return boolean 
     */
    public function getMasterKey()
    {
        return $this->masterKey;
    }

    /**
     * Set fingerprint
     *
     * @param string $fingerprint
     * @return Server
     */
    public function setFingerprint($fingerprint)
    {
        $this->fingerprint = $fingerprint;
    
        return $this;
    }

    /**
     * Get fingerprint
     *
     * @return string 
     */
    public function getFingerprint()
    {
        return $this->fingerprint;
    }
    
    
   
    
    /**
     * Add clusters
     *
     * @param Cluster $clusters
     * @return Server
     */
    public function addCluster(Cluster $clusters, $mirror = true)
    {
        $this->clusters[] = $clusters;
        if($mirror) {
          $clusters->addServer($this,false);
        }
        return $this;
    }

    /**
     * Remove clusters
     *
     * @param Cluster $clusters
     */
    public function removeCluster(Cluster $clusters, $mirror = true)
    {
        $this->clusters->removeElement($clusters);
        if($mirror) {
         $clusters->removeServer($this,false);
        }
    }

    /**
     * Get clusters
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getClusters()
    {
        return $this->clusters;
    }
    
    public function __toString()
    {
        return $this->getName();
    }
    

    /**
     * Add users
     *
     * @param User $user
     * @return Server
     */
    public function addUser(User $user,$mirror = true)
    {
        $this->users[] = $user;
        if($mirror) {
          $user->removeServer($this,false);
        }
        return $this;
    }

    /**
     * Remove user
     *
     * @param User $user
     */
    public function removeUser(User $user,$mirror = true)
    {
        $this->users->removeElement($user);
        if($mirror) {
          $user->removeServer($this,false);
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
    
     /**
     * Add groups
     *
     * @param Group $group
     * @return Server
     */
    public function addGroup(Group $group,$mirror = true)
    {
        $this->groups[] = $group;
        if($mirror) {
          $group->removeServer($this,false);
        }
        return $this;
    }

    /**
     * Remove users
     *
     * @param \User $users
     */
    public function removeGroup(Group $group,$mirror = true)
    {
        $this->groups->removeElement($group);
        if($mirror) {
          $group->removeServer($this,false);
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
    
    
    /**
     * test if group belong to server 
     * 
     * @param \Oblady\ZuulBundle\Entity\Group $group
     * @return boolean
     */
    public function hasGroup(Group $group) {
        
        return $this->groups->contains($group);
        
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
    
    public function getHost()
    {
        $returnvalue = $this->getHostname();
        
        if(empty($returnvalue)) {
            $returnvalue = $this->getIp();
        }
        
       return $returnvalue;
        
    }
    public function hasFingerprint()
    {
      
        return ('' !== trim($this->getFingerprint()));
    }     
    
     /**
    * Return the dsn string when using key auth
    *
    * @return string
    */

    public function getDsnWithKey($method = 'sftp')
    {

    return 'ssh2.'.$method.'://'.$this->getUser().'@'.$this->getHost().':'.$this->getPort();

    }



    /**
    * Return the dsn string when using password auth
    *
    * @return string
    */

    public function getDsnWithPassword($password, $method = 'sftp')
    {

    return 'ssh2.'.$method.'://'.$this->getUser().':'.$password.'@'.$this->getHost().':'.$this->getPort();



    }
    
    
     public function hasMasterKey()
    {
        return $this->getMasterKey();
        
    }
         
    

    /**
     * Set enabled
     *
     * @param boolean $enabled
     * @return Server
     */
    public function setEnabled($enabled)
    {
        $this->enabled = $enabled;
    
        return $this;
    }

    /**
     * Get enabled
     *
     * @return boolean 
     */
    public function getEnabled()
    {
        return $this->enabled;
    }

    /**
     * Set reachable
     *
     * @param boolean $reachable
     * @return Server
     */
    public function setReachable($reachable)
    {
        $this->reachable = $reachable;
    
        return $this;
    }

    /**
     * Get reachable
     *
     * @return boolean 
     */
    public function getReachable()
    {
        return $this->reachable;
    }



   public function isAlive() {
      $returnValue = true;
      $oldtime = ini_get("default_socket_timeout");
      try {
      
       ini_set("default_socket_timeout",3);
       $con = @fsockopen($this->getHost(), $this->getPort(), $eroare, $eroare_str);
       if(is_resource($con)) {
         fclose($con);
       } else {
         $returnValue = false;  
       }
      
      } catch (\Exception $e )
      {
	   $returnValue = false;
       
       if(is_resource($con)) {
         fclose($con);
       }
      }
      ini_set("default_socket_timeout",$oldtime);
	return $returnValue;

   }

    /**
     * Add key
     *
     * @param Key $key
     * @return Server
     */
    public function addKey(Key $key)
    {
        $this->keys[] = $key;
    
        return $this;
    }

    /**
     * Remove keys
     *
     * @param Key $keys
     */
    public function removeKey(Key $key)
    {
        $this->keys->removeElement($key);
    }

    /**
     * Get keys
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getKeys()
    {
        return $this->keys;
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
     * @return \Oblady\ZuulBundle\Entity\Server
     */
    public function clean() {
         $clone = clone $this;
         $clone->users = null;
         $clone->groups = null;

         return $clone;
    }
     
}