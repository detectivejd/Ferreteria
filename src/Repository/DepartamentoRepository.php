<?php

namespace App\Repository;

use App\Entity\Departamento;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Departamento|null find($id, $lockMode = null, $lockVersion = null)
 * @method Departamento|null findOneBy(array $criteria, array $orderBy = null)
 * @method Departamento[]    findAll()
 * @method Departamento[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DepartamentoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Departamento::class);
    }
    public function findByFilter($dato){
        $em = $this->getEntityManager();
        $query = $em->createQuery("select d from App\Entity\Departamento d where d.nombre like :nombre")
            ->setParameter('nombre', '%'.$dato.'%');
        return $query->getResult();
    }
}
