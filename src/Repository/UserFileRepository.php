<?php

namespace App\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

use App\Entity\UserFile;
use App\Entity\File;

/**
 * @method UserFile|null find($id, $lockMode = null, $lockVersion = null)
 * @method UserFile|null findOneBy(array $criteria, array $orderBy = null)
 * @method UserFile[]    findAll()
 * @method UserFile[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserFileRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, UserFile::class);
    }


	public function getUserFilesExceptFileCreatorCount(\App\Entity\File $file)
	{
	$qb = $this->createQueryBuilder('uf');
	$qb->select($qb->expr()->count('uf'));
	$qb->where('uf.file = :file')->setParameter('file', $file);
	$qb->andWhere($qb->expr()->not($qb->expr()->eq('uf.account', '?1')));
	$qb->setParameter(1, $file->getUser()); 
	$query = $qb->getQuery();
	$singleScalar = $query->getSingleScalarResult();
	return $singleScalar;
	}
}
