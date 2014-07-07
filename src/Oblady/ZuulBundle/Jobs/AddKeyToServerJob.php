<?php


namespace Oblady\ZuulBundle\Jobs;
/**
 * Description of AddKey
 *
 * @author sebastien
 */

class AddKeyToServerJob extends BaseJob
{
    public function run($args)
    {
        $server = $args['server'];
        $key = $args['user'];
        
        $this->getKeyManager()->setServer($server)->addKey($key->getAuthorizedKey());
        
    }
}
