<?php

namespace Clear\ContentBundle\Repository;

use Clear\ContentBundle\Filter\FilterOrm;
use Clear\ContentBundle\Model\FilterDate;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpFoundation\Request;
use Clear\UserBundle\Entity\User;

class ContentRepository extends EntityRepository
{
    /**
     * @param $id
     * @return array
     */
    public function toArray($id)
    {
        return
            $query = $this->createQueryBuilder('c')
                ->select('c, categories, tags')
                ->leftJoin('c.categories', 'categories')
                ->leftJoin('c.tags', 'tags')
                ->where('c.id = :id')
                ->setParameter('id', $id)
                ->getQuery()
                ->getArrayResult();
    }

    /**
     * @return \Doctrine\ORM\QueryBuilder
     */
    public function createUserContentBuilder()
    {
        return
            $this->getEntityManager()->createQueryBuilder()->select('c')
                ->from('ClearContentBundle:Content', 'c');
    }

    /**
     * @param User $user
     * @return \Doctrine\ORM\QueryBuilder
     */
    public function filterContentByRole(User $user, $id=null)
    {
        $query = $this->createQueryBuilder('c');

        if (in_array('Admin', $this->getRoles($user))) {
            $query->getQuery();
        } elseif (in_array('User', $this->getRoles($user))) {

            $query->leftJoin('c.roles', 'cr')
                ->leftJoin('c.companies', 'cc')
                ->leftJoin('cr.users', 'users')
                ->leftJoin('users.company', 'uc')
                ->where('c.status IN (:status)')
                ->setParameter('status', [1]);
            if($rolesUser=$user->getRoleUsers()) {
                $roles = [];
                foreach ($rolesUser as $value){
                    $roles[] = $value->getId();
                }
                $query->andWhere('cr.id is NULL or cr.id in (:role)')
                    ->setParameter('role', $roles);

            }else{
                $query->andWhere('cr.id is NULL');
            }

            if($user->getCompany()) {
                $query ->andWhere('cc.id is NULL OR cc.id =:company')
                    ->setParameter('company', $user->getCompany());
            }else{
                $query ->andWhere('cc.id is NULL');
            }
            $query->groupBy('c.id')
                ->getQuery();
        } else {
            $query->getQuery();
        }

        if(isset($id)) {
            $query->andWhere('c.id = :id')
                ->setParameter('id', $id);
        }
        return $query;
    }

    /**
     * @param User $user
     * @return bool
     */

    public function isAdmin(User $user)
    {
        $roles = $user->getRoleUsers();
        foreach ($roles as $role) {
            if ($role->getName() == 'Admin') {

                return true;
            }
        }

        return false;
    }

    public function getRoles(User $user) 
    {
        $roles = $user->getRoleUsers();

        $rolesArray = [];
        foreach ($roles as $role) {
            $rolesArray[] = $role->getName();
        }

        return $rolesArray;
    }

    /**
     * @param ParameterBag $request
     * @param User $user
     * @return \Doctrine\ORM\QueryBuilder
     */

    public function getSearchResult(ParameterBag $request, User $user)
    {
        $query = $this->filterContentByRole($user);
        $filter = new FilterOrm($query, $request);


        if (in_array('Admin', $this->getRoles($user))) {
            $query = $filter->search();
        } elseif (in_array('User', $this->getRoles($user))) {
            $query = $filter->search();
        } else {
            $query = $filter->search();
        }

            $query->getQuery();

        return $query;
    }
}


