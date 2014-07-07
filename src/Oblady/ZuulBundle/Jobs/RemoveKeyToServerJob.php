<?php


namespace Oblady\ZuulBundle\Jobs;
/**
 * Remove a key from a server
 *
 * @author sebastien
 */

class RemoveKeyToServerJob extends BaseJob
{
    public function run($args)
    {
        $server = $args['server'];
        $key = $args['key'];
        $this->getKeyManager()
             ->setServer($server)
             ->removeKey($key->getAuthorizedKey());
        
    }
}
