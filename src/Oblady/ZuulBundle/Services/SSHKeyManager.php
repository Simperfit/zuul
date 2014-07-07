<?php

/**
 * Description of SSH2
 *
 * @author beleneglorion
 */

namespace Oblady\ZuulBundle\Services;

use Oblady\ZuulBundle\Entity\Server;
use Oblady\ZuulBundle\Entity\Key;
use Oblady\ZuulBundle\Tools\AuthorizedKey;

class SSHKeyManager extends ObjectManager {

    protected $server;
    protected $connection;
    protected $auth = false;
    protected $masterauth = false;

    public function setServer(Server $server)
    {

        $this->disconnect();
       // echo 'changing server'.$server->getHost()."\n";
        $this->server = $server;  
        return $this;
    }
    
   /* public function loadServer($serverId)
    {

        return $this->setServer($this->getServer($serverId));
    }*/
     
    /**
     * 
     * @return type
     * @throws \Exception
     */
    private function getConnection() {
     //   echo 'humm';
        if (!isset($this->connection)) {
            $this->connection = ssh2_connect($this->server->getHost(), $this->server->getPort());

            if ($this->server->hasFingerprint()) {

                $fingerprint = ssh2_fingerprint($this->connection, SSH2_FINGERPRINT_MD5 | SSH2_FINGERPRINT_HEX);

                if ($fingerprint != $this->server->getFingerprint()) {
                    throw new \Exception("Fingerprint Mismatch - Attaque Man-In-The-Middle possible ?");
                }
            }
        }
        if($this->server->hasMasterKey()){
            $this->masterAuth();
        }
        
        return $this->connection;
    }
    
    private function disconnect()
    {
        
       
        if(is_resource( $this->connection) && $this->auth) {
             $this->execCmd('echo "EXITING" && exit;', SSH2_STREAM_STDIO);
        }
        $this->connection = null;
        $this->auth = false;
        $this->masterauth = false;
        
    }

   public  function getRemoteKeys() {
        $strCommand = 'cat ~/.ssh/authorized_keys';
        $lines = explode("\n", $this->execCmd($strCommand, SSH2_STREAM_STDIO));
        $list = $this->parseKeys($lines);

        return $list;
    }

    private function parseKeys(array $keys_list) {
        /*
         * 
          ssh-rsa AAAAB3Nza...LiPk== user@example.net
          from="*.sales.example.net,!pc.sales.example.net" ssh-rsa AAAAB2...19Q== john@example.net
          command="dump /home",no-pty,no-port-forwarding ssh-dss AAAAC3...51R== example.net
          permitopen="192.0.2.1:80",permitopen="192.0.2.2:25" ssh-dss AAAAB5...21S==
          tunnel="0",command="sh /etc/netstart tun0" ssh-rsa AAAA...== jane@example.net

         *  
         */
        $list = array();
        if (!empty($keys_list)) {
            foreach ($keys_list as $keyString) {
                $keyString = trim($keyString);
                // skipping empty line
                if (empty($keyString)) {
                    continue;
                }

                //skipping comment line
                if (substr($keyString, 0, 1) == '#') {
                    continue;
                }
                $key = $this->keyFactory($keyString);
                $list[] = $key;
            }
        }

        return $list;
    }

   

    public function removeKey(AuthorizedKey $key) {
        $ok = false;

        if ($this->hasKey($key)) {

            $strCommand = 'cp -f ~/.ssh/authorized_keys ~/.ssh/authorized_keys.save ';
            $err = $this->execCmd($strCommand, SSH2_STREAM_STDERR);
            if ($err) {
                throw new \ErrorException('can\'t backup authorized keys');
            }

            $escapedKey = escapeshellcmd($key->getKey());
            $strCommand = 'cat ~/.ssh/authorized_keys.save|grep -v "' . ($escapedKey) . '">~/.ssh/authorized_keys';
            $this->execCmd($strCommand, SSH2_STREAM_STDIO);
            $ok = !$this->hasKey($this->server, $key);
        }
        return $ok;
    }

    public function hasMasterKey() {
        $public_key_path = $this->container->getParameter('zuul.pubkey');
        $private_key_path = $this->container->getParameter('zuul.privkey');
        try {

            $connection = $this->getConnection();
            if(!$this->masterauth) {
              $returnValue = ssh2_auth_pubkey_file($connection, $this->server->getUser(), $public_key_path, $private_key_path);
              $this->masterauth = true;
              $this->auth = true;
            } else {
                $returnValue = true;
            }
        } catch (\ErrorException $e) {

            $returnValue = false;
        }

        return $returnValue;
    }

    private function masterAuth() {
        $public_key_path = $this->container->getParameter('zuul.pubkey');
        $private_key_path = $this->container->getParameter('zuul.privkey');

        if(!$this->auth) {
            try {
                ssh2_auth_pubkey_file($this->connection, $this->server->getUser(), $public_key_path, $private_key_path);
                $this->auth = true;
                $this->masterauth = true;
            } catch (\Exception $e) {
                throw new \ErrorException('Master Auth Fail : ' . $this->server->getDsnWithKey() . '(' . $e->getMessage() . ')');
            }
        }

    }

    public function installMasterKey($password) {
        if (!$this->hasMasterKey()) {
            $connection = $this->getConnection();
            $pub_key_path = $this->container->getParameter('zuul.pubkey');
            $pubkey = trim(file_get_contents($pub_key_path));
            $key = $this->keyFactory($pubkey);
            $key->setComment($key->getComment() . ' (Zuul Master Key)');
            if (ssh2_auth_password($connection, $this->server->getUser(), $password)) {
                $this->auth = true;
                $this->initAuthorizedKeys();
                $this->addKey($key);

            } else {
                throw new \ErrorException("Auth Fail : Invalid password ?");
            }
            
        }
    }

    private function initAuthorizedKeys() {
        $strCommand1 = ' [ ! -d ~/.ssh ] && mkdir ~/.ssh ';
        $this->execCmd($strCommand1, SSH2_STREAM_STDERR);
        $strCommand2 = ' touch  ~/.ssh/authorized_keys';
        $this->execCmd($strCommand2, SSH2_STREAM_STDERR);
    }

    public function addKey(AuthorizedKey $key) {


        if (!$this->hasKey($key)) {
            
            $strCommand = 'cp -f ~/.ssh/authorized_keys ~/.ssh/authorized_keys.save ';
            $err = $this->execCmd($strCommand, SSH2_STREAM_STDERR);
            if ($err) {
                throw new \ErrorException('can\'t backup authorized keys');
            }
            $strCommand = 'echo  "' . $key->getType() . ' ' .  escapeshellcmd($key->getkey()) . ' ' . escapeshellcmd($key->getComment()) . '" >>  ~/.ssh/authorized_keys';
            $err = $this->execCmd($strCommand, SSH2_STREAM_STDERR);
            if ($err) {
                throw new \ErrorException('can\'t write key (' . $err . ')');
            }
            if (!$this->hasKey($key)) {
                throw new \ErrorException('no error but key not found in authorized_keys ');
            }
        }

        return true;
    }

    public function hasKey(AuthorizedKey $key) {

       // $this->masterAuth();
        $connection = $this->getConnection();
        $strCommand = ' cat ~/.ssh/authorized_keys |grep -v "^/s*#"|grep "' . escapeshellcmd($key->getKey()) . '" |wc -l ';
        $output = $this->execCmd($strCommand, SSH2_STREAM_STDIO);

        return (0 == $output) ? false : true;
    }

    private function execCmd($cmd, $canal) {
        $connection = $this->getConnection();
        $stdout_stream = ssh2_exec($connection, $cmd);
        stream_set_blocking($stdout_stream, 1);
        $stream = ssh2_fetch_stream($stdout_stream, $canal);
        $result = stream_get_contents($stream);
        return $result;
    }

}

