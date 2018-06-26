<?php

namespace App\Repository;

use App\Entity\Tag;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Tag|null find($id, $lockMode = null, $lockVersion = null)
 * @method Tag|null findOneBy(array $criteria, array $orderBy = null)
 * @method Tag[]    findAll()
 * @method Tag[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TagRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Tag::class);
    }
    
    public function findTags(bool $qry = true)
    {
        $dql = "SELECT t "
                . "FROM App\Entity\Tag t "
                . "ORDER BY t.id DESC";
        $query = $this->getEntityManager()
                ->createQuery($dql);
        return $qry ? $query : $query->getResult();        
    }     
    
    public function findActiveTagById($id)
    {
        $dql = "SELECT t "
                . "FROM App\Entity\Tag t "
                . "WHERE t.active = 1 AND t.id = :id";
        $query = $this->getEntityManager()
                ->createQuery($dql)
                ->setParameter('id', $id);
        return $query->getOneOrNullResult();
    }
    
    public function findDeletedTagById($id)
    {
        $dql = "SELECT t "
                . "FROM App\Entity\Tag t "
                . "WHERE t.active = 0 AND t.id = :id";
        $query = $this->getEntityManager()
                ->createQuery($dql)
                ->setParameter('id', $id);
        return $query->getOneOrNullResult();        
    }
    
    
    public function findApiTags()
    {
        $dql = "SELECT p.id, p.title "
                . "FROM App\Entity\Post p";
        $query = $this->getEntityManager()
                ->createQuery($dql);
        return $query->getResult();
    }    
}
