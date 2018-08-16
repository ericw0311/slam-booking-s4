<?php

namespace App\Repository;

use App\Entity\Resource;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Resource|null find($id, $lockMode = null, $lockVersion = null)
 * @method Resource|null findOneBy(array $criteria, array $orderBy = null)
 * @method Resource[]    findAll()
 * @method Resource[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ResourceRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Resource::class);
    }

    public function getResourcesCount($file)
    {
    $qb = $this->createQueryBuilder('r');
    $qb->select($qb->expr()->count('r'));
    $qb->where('r.file = :file')->setParameter('file', $file);
	$qb->andWhere($qb->expr()->not($qb->expr()->eq('r.type', '?1')));
	$qb->setParameter(1, 'USER'); 
    $query = $qb->getQuery();
    $singleScalar = $query->getSingleScalarResult();
    return $singleScalar;
    }

	public function getResources($file)
    {
    $qb = $this->createQueryBuilder('r');
    $qb->where('r.file = :file')->setParameter('file', $file);
	$qb->andWhere($qb->expr()->not($qb->expr()->eq('r.type', '?1')));
    $qb->orderBy('r.type', 'ASC');
    $qb->addOrderBy('r.internal', 'DESC');
    $qb->addOrderBy('r.code', 'ASC');
    $qb->addOrderBy('r.name', 'ASC');
	$qb->setParameter(1, 'USER'); 
   
    $query = $qb->getQuery();
    $results = $query->getResult();
    return $results;
    }

	public function getDisplayedResources($file, $firstRecordIndex, $maxRecord)
    {
    $qb = $this->createQueryBuilder('r');
    $qb->where('r.file = :file')->setParameter('file', $file);
	$qb->andWhere($qb->expr()->not($qb->expr()->eq('r.type', '?1')));
    $qb->orderBy('r.type', 'ASC');
    $qb->addOrderBy('r.internal', 'DESC');
    $qb->addOrderBy('r.code', 'ASC');
    $qb->addOrderBy('r.name', 'ASC');
    $qb->setFirstResult($firstRecordIndex);
    $qb->setMaxResults($maxRecord);
	$qb->setParameter(1, 'USER'); 
 
    $query = $qb->getQuery();
    $results = $query->getResult();
    return $results;
    }

	public function getResourcesToPlanify($file, $type, $resourcePlanifiedQB)
    {
    $qb = $this->createQueryBuilder('r');
    $qb->where('r.file = :file')->setParameter('file', $file);
    $qb->andWhere('r.type = :type')->setParameter('type', $type);
    
	$qb->andWhere($qb->expr()->not($qb->expr()->exists($resourcePlanifiedQB->getDQL())));
     
    $qb->orderBy('r.name', 'ASC');
    $query = $qb->getQuery();
    $results = $query->getResult();
    return $results;
    }

	public function getResourceTypesToPlanify($file, $resourcePlanifiedQB)
    {
    $qb = $this->createQueryBuilder('r');
    $qb->select('r.type');
    $qb->addSelect($qb->expr()->count('r'));
    $qb->where('r.file = :file')->setParameter('file', $file);
    
	$qb->andWhere($qb->expr()->not($qb->expr()->exists($resourcePlanifiedQB->getDQL())));
     
    $qb->groupBy('r.type');
    $qb->orderBy('r.type', 'ASC');
    $query = $qb->getQuery();
    $results = $query->getResult();
    return $results;
    }

    // Retourne le nombre de ressources d'une classification interne
    public function getResourcesCount_IRC($file, $resourceType, $resourceClassificationCode)
    {
    $qb = $this->createQueryBuilder('r');
    $qb->select($qb->expr()->count('r'));
    $qb->where('r.file = :file')->setParameter('file', $file);
    $qb->andWhere('r.type = :type')->setParameter('type', $resourceType);
    $qb->andWhere('r.internal = :internal')->setParameter('internal', 1);
    $qb->andWhere('r.code = :code')->setParameter('code', $resourceClassificationCode);
    $query = $qb->getQuery();
    $singleScalar = $query->getSingleScalarResult();
    return $singleScalar;
    }

    // Retourne les ressources d'une classification interne
    public function getResources_IRC($file, $resourceType, $resourceClassificationCode)
    {
	$qb = $this->createQueryBuilder('r');
    $qb->where('r.file = :file')->setParameter('file', $file);
    $qb->andWhere('r.type = :type')->setParameter('type', $resourceType);
    $qb->andWhere('r.internal = :internal')->setParameter('internal', 1);
    $qb->andWhere('r.code = :code')->setParameter('code', $resourceClassificationCode);
	$qb->orderBy('r.name', 'ASC');
	$query = $qb->getQuery();
	$results = $query->getResult();
	return $results;
    }

    // Retourne le nombre de ressources d'une classification externe
    public function getResourcesCount_ERC($file, $resourceType, $resourceClassification)
    {
    $qb = $this->createQueryBuilder('r');
    $qb->select($qb->expr()->count('r'));
    $qb->where('r.file = :file')->setParameter('file', $file);
    $qb->andWhere('r.type = :type')->setParameter('type', $resourceType);
    $qb->andWhere('r.internal = :internal')->setParameter('internal', 0);
    $qb->andWhere('r.classification = :classification')->setParameter('classification', $resourceClassification);
    $query = $qb->getQuery();
    $singleScalar = $query->getSingleScalarResult();
    return $singleScalar;
    }

    // Retourne les ressources d'une classification
    public function getResources_ERC($file, $resourceType, $resourceClassification)
    {
	$qb = $this->createQueryBuilder('r');
    $qb->where('r.file = :file')->setParameter('file', $file);
    $qb->andWhere('r.type = :type')->setParameter('type', $resourceType);
    $qb->andWhere('r.internal = :internal')->setParameter('internal', 0);
    $qb->andWhere('r.classification = :classification')->setParameter('classification', $resourceClassification);
	$qb->orderBy('r.name', 'ASC');
	$query = $qb->getQuery();
	$results = $query->getResult();
	return $results;
    }

	// Affichage des réservations dans le calendrier
	public function getCalendarBookings($file)
    {
    $qb = $this->createQueryBuilder('r');
    $qb->where('r.file = :file')->setParameter('file', $file);
   
    $query = $qb->getQuery();
    $results = $query->getResult();
    return $results;
    }
}
