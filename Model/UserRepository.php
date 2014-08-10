<?php
namespace Magice\Bundle\UserBundle\Model;

use Doctrine\ORM\EntityRepository;

class UserRepository extends EntityRepository
{
    public function findByRole($role)
    {
        if (!preg_match('/^ROLE_/', $role)) {
            $role = 'ROLE_' . $role;
        }

        $qb = $this->_em->createQueryBuilder();
        $qb->select('u')
            ->from($this->_entityName, 'u')
            ->where('u.roles LIKE :roles')
            ->setParameter('roles', '%"' . $role . '"%');

        return $qb->getQuery()->getResult();
    }
}