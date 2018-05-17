<?php

namespace Clear\ContentBundle\Repository;

use Clear\UserBundle\Entity\User;
use Clear\ContentBundle\Util\Util;

/**
 * TagRepository
 */
class TagRepository extends \Doctrine\ORM\EntityRepository
{
    public function getTagsByRoleUser(User $user) 
    {
        $util = new Util;
        $query = $this->createQueryBuilder('tag');

        if (in_array('Admin', $util->getRoles($user))) {
            $query->getQuery();
        } else if(in_array('User', $util->getRoles($user))) {
            $query->where('tag.isActive = :isActive')
                ->setParameter('isActive', 1)
                ->getQuery();
        } else {
            $query->getQuery();
        }

        return $query;
    }
}
