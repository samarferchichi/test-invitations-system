<?php

namespace App\Repository;

use App\Entity\Notification;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use function Doctrine\ORM\QueryBuilder;

/**
 * @method Notification|null find($id, $lockMode = null, $lockVersion = null)
 * @method Notification|null findOneBy(array $criteria, array $orderBy = null)
 * @method Notification[]    findAll()
 * @method Notification[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class NotificationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Notification::class);
    }

    /**
     * updateInvitation.
     *
     * @param int $id
     * @param int $status
     */
    public function updateInvitation(int $id, int $status)
    {
        $qb = $this->createQueryBuilder('n');
        $qb
            ->update()
            ->set('n.status', $status)
            ->where('n.id = ?1')
            ->setParameter(1, $id)
            ->getQuery()
            ->execute()
        ;
    }

    /**
     * getInvitationsSent.
     *
     * @param int $id
     *
     * @return mixed
     */
    public function getInvitationsSent(int $id)
    {
        $qb = $this->createQueryBuilder('n');
        $qb
            ->select('n, s, r')
            ->join('n.sender', 's')
            ->join('n.receiver', 'r')
            ->where(
                $qb->expr()->andX(
                    $qb->expr()->eq('s.id', $id)
                )
            )
        ;

        return $qb->getQuery()->getArrayResult();
    }

    /**
     * getInvitationsReceived.
     *
     * @param int $id
     *
     * @return mixed
     */
    public function getInvitationsReceived(int $id)
    {
        $qb = $this->createQueryBuilder('n');
        $qb
            ->select('n, s, r')
            ->join('n.sender', 's')
            ->join('n.receiver', 'r')
            ->where(
                $qb->expr()->andX(
                    $qb->expr()->eq('r.id', $id)
                )
            )
        ;

        return $qb->getQuery()->getArrayResult();
    }

    /**
     * getInvitationsReceivedIds.
     *
     * @param int $id
     *
     * @return mixed
     */
    public function getInvitationsReceivedIds(int $id)
    {
        $qb = $this->createQueryBuilder('n');
        $qb
            ->select('s.id')
            ->join('n.sender', 's')
            ->join('n.receiver', 'r')
            ->where(
                $qb->expr()->andX(
                    $qb->expr()->eq('r.id', $id)
                )
            )
        ;

        return $qb->getQuery()->getArrayResult();
    }

    // /**
    //  * @return Notification[] Returns an array of Notification objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('n')
            ->andWhere('n.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('n.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Notification
    {
        return $this->createQueryBuilder('n')
            ->andWhere('n.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
