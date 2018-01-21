<?php

namespace A2C\PlatformBundle\Repository;

/**
 * UserRepository
 *
 */
class UserRepository extends \Doctrine\ORM\EntityRepository
{

    /**
     * Fetch all email adresses that are not banned
     * @return array all email addresses
     */
    public function findAllAdresses()
    {
        $query = $this->createQueryBuilder('u')
                ->select('u.emailAddress')
                ->where('u.isBanned = :isBanned')
                ->setParameter('isBanned', false);
        
        return $query->getQuery()->getArrayResult();
    }

}
