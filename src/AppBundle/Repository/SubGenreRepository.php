<?php

namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;

class SubGenreRepository extends EntityRepository
{
    public function createAlphabeticalQueryBuilder()
    {
        return $this->createQueryBuilder('subgenre')
            ->orderBy('subgenre.name', 'ASC');
    }
}
