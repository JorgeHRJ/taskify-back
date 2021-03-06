<?php

namespace ApiBundle\Repository;

use Doctrine\ORM\Query\ResultSetMappingBuilder;

/**
 * TaskRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class TaskRepository extends \Doctrine\ORM\EntityRepository
{
    
    public function findPaginatedTasksByUser(\ApiBundle\Entity\User $user, $page)
    {
        $dql = "SELECT t 
                    FROM {$this->_entityName} t
                    WHERE t.user = :user
                    AND t.deletedAt IS NULL";

        $query = $this->getEntityManager()->createQuery($dql);
        $query->setParameter('user', $user);

        return $query;
    }

    public function findAndSearchTasks(\ApiBundle\Entity\User $user, $filter, $order, $search) {
        $dql = "SELECT t
                    FROM {$this->_entityName} t
                    JOIN t.status tS
                    WHERE t.user = :user 
                    AND t.deletedAt IS NULL ";
        
        if( !is_null($search) ) {
            $dql .= "AND (t.title LIKE :search OR t.description LIKE :search) ";
        }

        if( !is_null($filter) ){
            $dql .= "AND tS.codec = :filter ";
        }

        $dql .= "ORDER BY t.id {$order} ";

        $query = $this->getEntityManager()->createQuery($dql);
        $query->setParameter('user', $user);
        
        if( !is_null($search) ) {
            $query->setParameter('search', "%{$search}%");
        }

        if( !is_null($filter) ){
            $query->setParameter('filter', $filter);
        }
        
        return $query->getResult();
    }
    
}
