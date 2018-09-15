<?php
// App/DataFixtures/TimetableFixtures.php
namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

use App\Entity\File;
use App\Entity\User;
use App\Entity\Timetable;

class TimetableFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
	foreach ($this->getData() as [$id, $userID, $fileID, $name]) {

		$user = $this->getReference('user-1');
		$file = $this->getReference('file-'.$fileID);

		$timetable = new Timetable($user, $file);
		$timetable->setName($name);
		$timetable->setType('T');
		$manager->persist($timetable);
		$manager->flush();
		$this->addReference('timetable-'.$id, $timetable);
	}
    }

	private function getData(): array
    {
	return [
		// $data = [$id, $userID, $fileID, $name]
[80, 1, 321, 'Créneau Horaire'],
[81, 1, 321, 'Grille horaire 2'],
[159, 1, 440, 'Grille horaire'],
[196, 1, 492, 'Grille horaire'],
[208, 1, 507, 'Grille horaire 1'],
[212, 1, 507, 'Ecole et Entraînements mercredi'],
[213, 1, 507, 'Ecole et Entraînements  jeudi'],
[218, 1, 516, 'salle informatique 1'],
[219, 1, 516, 'Salle informatique 19'],
[220, 1, 516, 'Salle de conférences'],
[225, 1, 523, 'Grille horaire 1'],
[226, 1, 523, 'Grille horaire 2'],
[242, 1, 535, 'Grille horaire 1'],
[244, 1, 535, 'Grille VENDREDI'],
[245, 1, 535, 'Grille SAMEDI'],
[246, 1, 538, 'Grille horaire 1'],
[247, 1, 538, 'Grille horaire 2'],
[258, 1, 555, 'Grille horaire 1'],
[298, 1, 550, 'Grille horaire Salle de Réunion'],
[303, 1, 612, 'Novembre'],
[304, 1, 612, 'Dimanche'],
[305, 1, 614, 'h'],
[308, 1, 614, 'Visite'],
[309, 1, 614, 'Projecteur'],
[349, 1, 612, 'Lun - Sam'],
[355, 1, 612, 'mardi'],
[364, 1, 612, 'DIMANCHE 2'],
[365, 1, 690, 'Grille horaire KPC'],
[366, 1, 691, 'Grille horaire Conf Call'],
[375, 1, 707, 'Grille horaire 1'],
[376, 1, 707, 'Grille horaire 2'],
[377, 1, 707, 'Grille horaire 3'],
[380, 1, 707, 'Grille horaire 4'],
[381, 1, 707, 'Grille horaire 5'],
[382, 1, 707, 'Grille horaire 6'],
[394, 1, 523, 'Grille horaire 3'],
[420, 1, 757, 'Grille horaire'],
[421, 1, 757, 'Grille horaire mercredi'],
[422, 1, 550, 'Grille horaire Réunion/Atelier'],
[428, 1, 507, 'Ecole et Entraînements Samedi'],
[429, 1, 507, 'Ecole et Entraînements vendredi'],
	];
    }
	
	public function getDependencies()
	{
		return array(FileFixtures::class, UserFixtures::class);
    }
}
