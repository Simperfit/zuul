<?php

namespace Oblady\ZuulBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Oblady\ZuulBundle\Tools\AuthorizedKey;
//use User as User;
//use Oblady\ZuulBundle\Entity\AuthorizedKey as AuthorizedKey;

/**
 * Oblady\ZuulBundle\Entity\Key
 *
 * @ORM\Table(name="ssh_key")
 * @ORM\Entity(repositoryClass="Oblady\ZuulBundle\Entity\KeyRepository")
 */
class Key
{
    /**
     * @var integer $id
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;



    /**
     * @var string $name
     *
     * @ORM\Column(name="name", type="string", length=150)
     */
    private $name;
    /**
     * @var string fingerprint
     *
     * @ORM\Column(name="fingerprint", type="string", length=150)
     */
    private $fingerprint;

    /**
    * @var string $content
    *
    * @ORM\Column(name="content",  type="text")doc 
   */
    private $content;


    /**
    * @ORM\ManyToOne(targetEntity="User", inversedBy="keys", cascade={"remove"})
    * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
    */
    private $user;
    
   
    /**
    * @ORM\ManyToMany(targetEntity="Server", mappedBy="keys")
    **/
    private $servers;

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
     * @return Key
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
     * Set content
     *
     * @param string $content
     * @return Key
     */
    public function setContent($content)
    {
        $this->content = $content;
    
        return $this;
    }

    /**
     * Get content
     *
     * @return string 
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Set user
     *
     * @param \User $user
     * @return Key
     */
    public function setUser(User $user = null)
    {
        $this->user = $user;
    
        return $this;
    }

    /**
     * Get user
     *
     * @return \User 
     */
    public function getUser()
    {
        return $this->user;
    }
    
    
    public function generateFingerprint() {
         
         $this->fingerprint =  $this->getAuthorizedKey()->getFingerprint();
    }
      
    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->servers = new \Doctrine\Common\Collections\ArrayCollection();
    }
    
    /**
     * Set fingerprint
     *
     * @param string $fingerprint
     * @return Key
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
     * Add servers
     *
     * @param Server $servers
     * @return Key
     */
    public function addServer(Server $servers)
    {
        $this->servers[] = $servers;
    
        return $this;
    }

    /**
     * Remove servers
     *
     * @param Server $servers
     */
    public function removeServer(Server $servers)
    {
        $this->servers->removeElement($servers);
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
    
    public function __toString()
    {
        return $this->getName();
    }
    
    
    public function getAuthorizedKey() {
        
           return  new AuthorizedKey($this->getContent(),$this);
    }
    
     /**
     * Remove related objects for small footprint
     * 
     * @return \Oblady\ZuulBundle\Entity\Key
     */
    public function clean() {
 
         unset($this->servers);
         
         return $this;
    }
     
}