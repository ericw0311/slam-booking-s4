<?php
namespace App\Event;

use Doctrine\Common\EventSubscriber;
use Doctrine\Common\Persistence\Event\LifecycleEventArgs;
use App\Entity\File;
use App\Entity\User;

class DoctrineSubscriber implements EventSubscriber
{
	private $security;
	private $logger;
	private $translator;

	public function __construct($security, $logger, $translator)
	{
	$this->security = $security;
	$this->logger = $logger;
	$this->translator = $translator;
	}

	public function getSecurity()
	{
	return $this->security;
	}

	public function getUser()
	{
	return $this->getSecurity()->getToken()->getUser();
	}

	public function getLogger()
	{
	return $this->logger;
	}

	public function getTranslator()
	{
	return $this->translator;
	}

	public function getSubscribedEvents()
    {
		return array('postPersist', 'postUpdate');
	}

    public function postPersist(LifecycleEventArgs $args)
    {
		$this->getLogger()->info('DoctrineSubscriber postPersist 1');
		$entity = $args->getEntity();

        if ($entity instanceof User) {
			$this->getLogger()->info('DoctrineSubscriber postPersist 2 User');
            $entityManager = $args->getEntityManager();

			UserEvent::postPersist($entityManager, $entity);

		} else if ($entity instanceof File) {
			$this->getLogger()->info('DoctrineSubscriber postPersist 3 File');
            $entityManager = $args->getEntityManager();

			FileEvent::postPersist($entityManager, $this->getUser(), $entity, $this->getTranslator());
		}
    }

    public function postUpdate(LifecycleEventArgs $args)
    {
		$this->getLogger()->info('DoctrineSubscriber postUpdate 1');
		$entity = $args->getEntity();

        if ($entity instanceof User) {
			$this->getLogger()->info('DoctrineSubscriber postUpdate 2 User');
            $entityManager = $args->getEntityManager();

			UserEvent::postUpdate($entityManager, $entity);
		}
    }
}