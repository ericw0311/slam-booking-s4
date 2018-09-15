<?php
// App/DataFixtures/ResourceClassificationFixtures.php
namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

use App\Entity\File;
use App\Entity\User;
use App\Entity\ResourceClassification;

class ResourceClassificationFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
	foreach ($this->getData() as [$id, $userID, $fileID, $type, $internal, $code, $active]) {

		$user = $this->getReference('user-1');
		$file = $this->getReference('file-'.$fileID);

		$resourceClassification = new ResourceClassification($user, $file);
		$resourceClassification->setType($type);
		$resourceClassification->setInternal($internal);
		$resourceClassification->setCode($code);
		$resourceClassification->setName($code);
		$resourceClassification->setActive($active);

		$manager->persist($resourceClassification);
		$manager->flush();
		$this->addReference('resourceClassification-'.$id, $resourceClassification);
	}
    }

	private function getData(): array
    {
	return [
		// $data = [$id, $userID, $fileID, $type, $internal, $code, $active]
[7827, 1, 612, 'PLACE', 1, 'ROOM', 0],
[7829, 1, 612, 'PLACE', 1, 'HOUSE', 0],
[7832, 1, 612, 'VEHICLE', 1, 'CAR', 0],
[7840, 1, 612, 'TOOL', 1, 'COMPUTER', 0],
[7845, 1, 612, 'SPORT', 1, 'GYMNASIUM', 0],
[7847, 1, 612, 'USER', 1, 'TEACHER', 0],
[7877, 1, 614, 'PLACE', 1, 'HOUSE', 0],
[7880, 1, 614, 'VEHICLE', 1, 'CAR', 0],
[7891, 1, 614, 'SPORT', 1, 'COURT', 0],
[7893, 1, 614, 'SPORT', 1, 'GYMNASIUM', 0],
[10053, 1, 707, 'PLACE', 1, 'ROOM', 0],
[10055, 1, 707, 'PLACE', 1, 'HOUSE', 0],
[10058, 1, 707, 'VEHICLE', 1, 'CAR', 0],
[10066, 1, 707, 'TOOL', 1, 'COMPUTER', 0],
[10071, 1, 707, 'SPORT', 1, 'GYMNASIUM', 0],
[10073, 1, 707, 'USER', 1, 'TEACHER', 0],
[11204, 1, 757, 'PLACE', 1, 'HOUSE', 0],
[11207, 1, 757, 'VEHICLE', 1, 'CAR', 0],
[11215, 1, 757, 'TOOL', 1, 'COMPUTER', 0],
[11218, 1, 757, 'SPORT', 1, 'COURT', 0],
[11220, 1, 757, 'SPORT', 1, 'GYMNASIUM', 0],
[11222, 1, 757, 'USER', 1, 'TEACHER', 0],
[2479, 1, 321, 'VEHICLE', 1, 'TRUCK', 1],
[2483, 1, 321, 'VEHICLE', 1, 'BOAT', 1],
[2494, 1, 321, 'USER', 1, 'CONTRACTOR', 1],
[6372, 1, 538, 'VEHICLE', 1, 'PLANE', 1],
[6708, 1, 555, 'VEHICLE', 1, 'PLANE', 1],
[7890, 1, 614, 'TOOL', 1, 'PROJECTOR', 1],
	];
    }
	
	public function getDependencies()
	{
		return array(FileFixtures::class, UserFixtures::class);
    }
}
