<?php

namespace JDR\CoreBundle\Repository;
use JDR\CoreBundle\JDRCoreBundle;

/**
 * InvitationRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class InvitationRepository extends \Doctrine\ORM\EntityRepository
{

    /**
     * Renvoie le statut et le nom des joueurs invité à la session passée en paramètre
     */
    public function findNameAndStatut($idSession){

        return $this->getEntityManager()
            ->createQueryBuilder()
            ->select('i.statut, p.username')
            ->from('JDRCoreBundle:Invitation', 'i')
            ->innerJoin('i.player', 'p')
            ->innerJoin('i.session', 's')
            ->where('s.id = :session_id')
            ->setParameter('session_id', $idSession)
            ->getQuery()
            ->getResult();
    }

    /**
     * Retourne l'invitation qui possède les paramètres saisis
     */
    public function select($playerName, $idSession){
        return $this->getEntityManager()
            ->createQueryBuilder()
            ->select('i')
            ->from('JDRCoreBundle:Invitation', 'i')
            ->innerJoin('i.player', 'p')
            ->innerJoin('i.session','s')
            ->where('s.id = :session_id')
            ->andWhere('p.username = :username')
            ->setParameter('session_id', $idSession)
            ->setParameter('username', $playerName)
            ->getQuery()
            ->getSingleResult();
    }
}
