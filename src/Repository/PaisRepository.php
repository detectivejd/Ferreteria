<?php

namespace App\Repository;

use App\Entity\Pais;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
/**
 * @method Pais|null find($id, $lockMode = null, $lockVersion = null)
 * @method Pais|null findOneBy(array $criteria, array $orderBy = null)
 * @method Pais[]    findAll()
 * @method Pais[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PaisRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Pais::class);
    }
    public function findByFilter($dato){
        $em = $this->getEntityManager();
        $query = $em->createQuery("select p from App\Entity\Pais p where p.nombre like :nombre")
            ->setParameter('nombre', '%'.$dato.'%');
        return $query->getResult();
    }
    public function countDepartamentos($pais){
        $em = $this->getEntityManager();
        $query = $em->createQuery("select d from App\Entity\Departamento d where d.pais = :pais")
            ->setParameter('pais', $pais);
        return count($query->getResult()) > 0 ? true : false;
    }
}
