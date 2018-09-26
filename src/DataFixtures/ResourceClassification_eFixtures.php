<?php
// App/DataFixtures/ResourceClassification_eFixtures.php
namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use App\Entity\File;
use App\Entity\User;
use App\Entity\ResourceClassification;

class ResourceClassification_eFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
	foreach ($this->getData() as [$id, $userID, $fileID, $type, $name, $active]) {
		$user = $this->getReference('user-1');
		$file = $this->getReference('file-'.$fileID);
		$resourceClassification = new ResourceClassification($user, $file);
		$resourceClassification->setType($type);
		$resourceClassification->setInternal(false);
		$resourceClassification->setName($name);
		$resourceClassification->setActive($active);
		$manager->persist($resourceClassification);
		$manager->flush();
		$this->addReference('resourceClassification-'.$id, $resourceClassification);
	}
    }
	private function getData(): array
    {
	return [
		// $data = [$id, $userID, $fileID, $type, $name, $active]
[11770, 1, 550, 'PLACE', 'Bureau', 1],
[7947, 1, 614, 'PLACE', 'Exterieur', 1],
	];
    }
	
	public function getDependencies()
	{
		return array(FileFixtures::class, UserFixtures::class);
    }
}
