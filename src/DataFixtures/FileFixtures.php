<?php
// App/DataFixtures/FileFixtures.php
namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

use App\Entity\File;
use App\Entity\User;
use App\Entity\Timetable;
use App\Entity\TimetableLine;

class FileFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
	foreach ($this->getData() as [$id, $accountID, $name]) {

		$user = $this->getReference('user-1');
		$account = $this->getReference('user-'.$accountID);

		$file = new File($account);
		$file->setName($name);
		$manager->persist($file);
		$manager->flush();
		$this->addReference('file-'.$id, $file);

		$timetable = new Timetable($user, $file);
		$timetable->setName('Journée');
		$timetable->setType('D');
		$manager->persist($timetable);
		$manager->flush();
		$this->addReference('timetable-D-'.$id, $timetable);

		$timetableLine = new TimetableLine($user, $timetable);
		$timetableLine->setBeginningTime(date_create_from_format('H:i:s','00:00:00'));
		$timetableLine->setEndTime(date_create_from_format('H:i:s','23:59:00'));
		$timetableLine->setType('D');
		$manager->persist($timetableLine);
		$manager->flush();
		$this->addReference('timetableLine-D-'.$id, $timetableLine);

		$timetable = new Timetable($user, $file);
		$timetable->setName('Demi-journée');
		$timetable->setType('HD');
		$manager->persist($timetable);
		$manager->flush();
		$this->addReference('timetable-HD-'.$id, $timetable);

		$timetableLine = new TimetableLine($user, $timetable);
		$timetableLine->setBeginningTime(date_create_from_format('H:i:s','00:00:00'));
		$timetableLine->setEndTime(date_create_from_format('H:i:s','12:00:00'));
		$timetableLine->setType('AM');
		$manager->persist($timetableLine);
		$manager->flush();
		$this->addReference('timetableLine-AM-'.$id, $timetableLine);

		$timetableLine = new TimetableLine($user, $timetable);
		$timetableLine->setBeginningTime(date_create_from_format('H:i:s','12:00:00'));
		$timetableLine->setEndTime(date_create_from_format('H:i:s','23:59:00'));
		$timetableLine->setType('PM');
		$manager->persist($timetableLine);
		$manager->flush();
		$this->addReference('timetableLine-PM-'.$id, $timetableLine);
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
