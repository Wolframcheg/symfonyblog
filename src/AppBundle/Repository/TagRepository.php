<?php

namespace AppBundle\Repository;

/**
 * TagRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class TagRepository extends \Doctrine\ORM\EntityRepository
{
    public function getTagsWithCount()
    {
        return $this->createQueryBuilder('t')
            ->select('t, count(p.id) as countPosts')
            ->leftJoin('t.posts', 'p')
            //->where('count(p.id) > 0')
            ->groupBy('t')
            ->getQuery()
            ->getResult();
    }

}
