<?php
namespace App\EventSubscriber;

use Doctrine\Common\EventSubscriber;
use Doctrine\Common\Persistence\Event\LifecycleEventArgs;
use App\Entity\File;

class DoctrineSubscriber implements EventSubscriber
{
	private $security;
	private $translator;

	public function __construct($security, $translator)
	{
	$this->security = $security;
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

	public function getTranslator()
	{
	return $this->translator;
	}

	public function getSubscribedEvents()
    {
		return array('postPersist');
	}

    public function postPersist(LifecycleEventArgs $args)
    {
		$entity = $args->getEntity();

        if ($entity instanceof File) {
            $entityManager = $args->getEntityManager();

			FileEvent::postPersist($entityManager, $this->getUser(), $entity, $this->getTranslator());
		}
    }
}
