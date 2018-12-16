<?php

/*
 * This file is part of the Incipio package.
 *
 * (c) Florian Lefevre
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Repository\Personne;

use App\Entity\Personne\Mandat;
use Doctrine\ORM\EntityRepository;

/**
 * MandatRepository.
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class MandatRepository extends EntityRepository
{
    public function getCotisantMandats()
    {
        $qb = $this->_em->createQueryBuilder();
        $query = $qb->select('ma')->from(Mandat::class, 'ma')
          ->innerJoin('ma.poste', 'p')
          ->orderBy('ma.debutMandat')
          ->where("p.intitule LIKE 'Membre'");

        return $query->getQuery()->getResult();
    }
}
