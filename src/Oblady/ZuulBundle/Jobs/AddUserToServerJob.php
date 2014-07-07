<?php


namespace Oblady\ZuulBundle\Jobs;
/**
 * Description of AddKey
 *
 * @author sebastien
 */

class AddUserToServerJob extends BaseJob
{
    public function run($args)
    {
        $serverId = $args['server_id'];
        $userId = $args['user_id'];
        
        $user=  $this->getKeyManager()->getUser($userId);
        $this->getKeyManager()->loadServer($serverId)->addUser($user);
        
    }
}
