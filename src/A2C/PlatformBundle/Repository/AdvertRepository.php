<?php

// src/A2C/PlatformBundle/Repository/AdvertRepository.php

namespace A2C\PlatformBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\Tools\Pagination\Paginator;

/**
 * Provides some custom methods to retrieve Adverts objects in the database
 *
 */
class AdvertRepository extends \Doctrine\ORM\EntityRepository
{

    /**
     * Fetch all adverts with the given page number and number of adverts per page.
     * @param int $page The page number to display, > 0
     * @param int $nbPerPage The number of adverts per page, as defined in parameters.yml
     * @return Paginator The Adverts found, corresponding to the given page number.
     */
    public function getAdverts($page, $nbPerPage)
    {
        $query = $this->createQueryBuilder('a')
                ->leftJoin('a.user', 'u')
                ->addSelect('u')
                ->orderBy('a.id', 'DESC')
                ->getQuery()
        ;

        $query
                // Set the advert that starts the results list
                ->setFirstResult(($page - 1) * $nbPerPage)
                // Set the advert's number to display
                ->setMaxResults($nbPerPage)
        ;

        return new Paginator($query, true);
    }

}
