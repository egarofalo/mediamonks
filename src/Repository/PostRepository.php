<?php

namespace App\Repository;

use App\Entity\Post;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Post|null find($id, $lockMode = null, $lockVersion = null)
 * @method Post|null findOneBy(array $criteria, array $orderBy = null)
 * @method Post[]    findAll()
 * @method Post[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PostRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Post::class);
    }

    public function findPosts(bool $qry = true)
    {
        $dql = "SELECT p, t, u "
                . "FROM App\Entity\Post p "
                . "LEFT JOIN p.tags t "
                . "JOIN p.user u "
                . "ORDER BY p.id DESC";
        $query = $this->getEntityManager()
                ->createQuery($dql);
        return $qry ? $query : $query->getResult();        
    }  
    
    public function findPostById($id)
    {
        $dql = "SELECT p, t, u "
                . "FROM App\Entity\Post p "
                . "LEFT JOIN p.tags t "
                . "JOIN p.user u "
                . "WHERE p.id = :id";
        $query = $this->getEntityManager()
                ->createQuery($dql)
                ->setParameter('id', $id);
        return $query->getOneOrNullResult();
    }
    
    public function findApiPosts()
    {
        $dql = "SELECT p.id, p.title "
                . "FROM App\Entity\Post p ";
        $query = $this->getEntityManager()
                ->createQuery($dql);
        return $query->getResult();   
    }
    
    public function findApiPostById($id)
    {
        $dql = "SELECT p, t "
                . "FROM App\Entity\Post p "
                . "LEFT JOIN p.tags t "
                . "WHERE p.id = :id";
        $query = $this->getEntityManager()
                ->createQuery($dql)
                ->setParameter('id', $id);
        return $query->getOneOrNullResult();
    }
}
