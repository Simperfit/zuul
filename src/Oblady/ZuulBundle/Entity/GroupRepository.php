<?php

namespace Oblady\ZuulBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * GroupRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class GroupRepository extends EntityRepository
{

    public function findAll()
    {
        return $this->findBy(array(), array('name'=>'asc'));  
    }
}