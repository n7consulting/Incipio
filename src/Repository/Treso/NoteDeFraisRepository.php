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

use App\Entity\Treso\NoteDeFrais;
use Doctrine\ORM\EntityRepository;

/**
 * NoteDeFraisRepository.
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class NoteDeFraisRepository extends EntityRepository
{
    /**
     * @return array
     */
    public function findAllByMandat()
    {
        $entities = $this->findBy([], ['mandat' => 'desc', 'date' => 'asc']);
        $nfsParMandat = [];
        /** @var NoteDeFrais $nf */
        foreach ($entities as $nf) {
            $mandat = $nf->getMandat();
            if (array_key_exists($mandat, $nfsParMandat)) {
                $nfsParMandat[$mandat][] = $nf;
            } else {
                $nfsParMandat[$mandat] = [$nf];
            }
        }

        return $nfsParMandat;
    }

    /**
     * Renvoie les NDF pour un mois donné
     * YEAR MONTH DAY sont défini dans DashBoardBundle/DQL (qui doit devenir FrontEndBundle).
     *
     * @param      $month
     * @param      $year
     * @param bool $trimestriel
     *
     * @return array
     */
    public function findAllByMonth($month, $year, $trimestriel = false)
    {
        $qb = $this->_em->createQueryBuilder();
        $query = $qb->select('f')
                     ->from(NoteDeFrais::class, 'f');
        if ($trimestriel) {
            $query->where("MONTH(f.date) >= $month")
                  ->andWhere("MONTH(f.date) < ($month + 2)");
        } else {
            $query->where("MONTH(f.date) = $month");
        }

        $query->andWhere("YEAR(f.date) = $year")->orderBy('f.date');

        return $query->getQuery()->getResult();
    }
}
