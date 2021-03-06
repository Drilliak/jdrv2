<?php

namespace JDR\UserBundle\Repository;

/**
 * UserRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class UserRepository extends \Doctrine\ORM\EntityRepository
{

    public function selectSimilarName($term)
    {
        return $this->_em->createQueryBuilder()
            ->select('u.username')
            ->from($this->_entityName, 'u')
            ->where('u.username LIKE :term')
            ->setParameter('term', '%' . $term . '%')
            ->getQuery()
            ->getArrayResult();
    }
}
