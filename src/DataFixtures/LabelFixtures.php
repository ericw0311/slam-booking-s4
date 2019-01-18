<?php
// App/DataFixtures/LabelFixtures.php
namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

use App\Entity\File;
use App\Entity\User;
use App\Entity\Label;

class LabelFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
	foreach ($this->getData() as [$id, $userID, $fileID, $name]) {

		$user = $this->getReference('user-1');
		$file = $this->getReference('file-'.$fileID);

		$label = new Label($user, $file);
		$label->setName($name);
		$manager->persist($label);
		$manager->flush();
		$this->addReference('activity-'.$id, $label);
	}
    }

	private function getData(): array
    {
	return [
		// $data = [$id, $userID, $fileID, $name]
[5, 1, 321, 'Essais BE'],
[6, 1, 321, 'Course sur Lorient'],
[7, 1, 321, 'CP/RTT'],
[54, 1, 440, 'Bernard DESMARET'],
[55, 1, 507, 'Réservation terrain'],
[58, 1, 507, 'Entraînements adultes'],
[59, 1, 507, 'Ecole de Tennis et/ou entraînements'],
[65, 1, 523, 'Informatique'],
[76, 1, 507, 'Matchs par équipe'],
[91, 1, 507, 'Tournoi Interne'],
[117, 1, 614, 'Cours'],
[121, 1, 614, 'Visite'],
[127, 1, 614, 'Réunion'],
[128, 1, 614, 'Séminaire'],
[129, 1, 614, 'Formation'],
[136, 1, 507, 'Champ Ain Indiv'],
[137, 1, 507, 'Matchs par équipe jeunes'],
[140, 1, 507, 'Entretien'],
[157, 1, 507, 'réservation clients extérieurs'],
[158, 1, 507, 'TOURNOI OPEN TBB'],
[167, 1, 535, 'ECOLE DE TENNIS'],
[168, 1, 535, 'GROUPE ADULTES'],
[169, 1, 535, 'COMPETITION'],
[180, 1, 507, 'Coupe des dames'],
	];
    }
	
	public function getDependencies()
	{
		return array(FileFixtures::class, UserFixtures::class);
    }
}
