<?php
namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * Description of BandRepository
 *
 * @author Norman
 */
class BandRepository extends EntityRepository
{
    /**
     * @return Band[]
     */
    public function findAllPublished()
    {
        return $this->createQueryBuilder('band')
                ->andWhere("band.isPublished = :isPublished")
                ->setParameter('isPublished', true)     
                ->getQuery()
                ->execute()
        ;
    }
}
