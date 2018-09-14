<?php
// App/DataFixtures/FileFixtures.php
namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

use App\Entity\File;
use App\Entity\User;

class FileFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
	foreach ($this->getData() as [$id, $accountID, $name]) {
		$accountReference = 'user-'.$accountID;
		$file = new File($this->getReference($accountReference));
		$file->setName($name);
		$manager->persist($file);
		$manager->flush();
		$reference = 'file-'.$id;
		$this->addReference($reference, $file);
	}
    }

	private function getData(): array
    {
	return [
		// $data = [id, accountID, name]
[321, 527, 'RESERVATIONS'],
[440, 738, 'Réservation'],
[492, 843, 'Tennis Club de Veurey Voroize'],
[507, 860, 'Tie Break Beynolan'],
[516, 865, 'Réservation salles'],
[523, 1022, 'Salle Sacré Coeur'],
[535, 1077, 'COURTS DE TENNIS DE MARNAY'],
[538, 1095, 'Aeroclub de  Gray'],
[550, 1139, 'Le Plan B.'],
[555, 1160, 'ACSRC reservations'],
[612, 1408, 'SQUASH'],
[614, 1418, 'Gestion'],
[690, 1602, 'SallereunionLevallois'],
[691, 1602, 'Conf Call KPC'],
[707, 1661, 'ASRV tennis'],
[757, 1868, 'Salles des travaux pratiques sciences'],
	];
    }
	
	public function getDependencies()
	{
		return array(UserFixtures::class);
    }
}
