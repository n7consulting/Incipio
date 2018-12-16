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

use App\Entity\Treso\CotisationURSSAF;
use Doctrine\ORM\EntityRepository;

/**
 * CotisationURSSAFRepository.
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class CotisationURSSAFRepository extends EntityRepository
{
    /**
     * Renvoie les cotisations pour une date donnée
     * YEAR MONTH DAY sont défini dans DashBoardBundle/DQL (qui doit devenir FrontEndBundle).
     *
     * @param \DateTime $date
     *
     * @return array
     */
    public function findAllByDate(\DateTime $date)
    {
        $qb = $this->_em->createQueryBuilder();

        $date = $date->format('Y-m-d');

        $query = $qb->select('c')
                     ->from(CotisationURSSAF::class, 'c')
                     ->where("'$date' >= c.dateDebut")
                     ->andWhere("'$date' <= c.dateFin");

        return $query->getQuery()->getResult();
    }
}
