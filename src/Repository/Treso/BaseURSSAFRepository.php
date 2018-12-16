<?php

/*
 * This file is part of the Incipio package.
 *
 * (c) Florian Lefevre
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Repository\Treso;

use App\Entity\Treso\BaseURSSAF;
use Doctrine\ORM\EntityRepository;

/**
 * BaseURSSAFRepository.
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class BaseURSSAFRepository extends EntityRepository
{
    /**
     * Renvoie les cotisations pour une date donnée
     * YEAR MONTH DAY sont défini dans DashBoardBundle/DQL (qui doit devenir FrontEndBundle).
     *
     * @return array
     */
    public function findByDate(\DateTime $date)
    {
        $qb = $this->_em->createQueryBuilder();

        $date = $date->format('Y-m-d');

        $query = $qb->select('b')
                     ->from(BaseURSSAF::class, 'b')
                     ->where("'$date' >= b.dateDebut")
                     ->andWhere("'$date' <= b.dateFin");

        return $query->getQuery()->getOneOrNullResult();
    }
}
