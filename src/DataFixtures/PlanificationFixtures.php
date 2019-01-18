<?php
// App/DataFixtures/PlanificationFixtures.php
namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

use App\Entity\File;
use App\Entity\User;
use App\Entity\Planification;

class PlanificationFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
	foreach ($this->getData() as [$id, $userID, $fileID, $type, $internal, $code, $name]) {

		$user = $this->getReference('user-1');
		$file = $this->getReference('file-'.$fileID);

		$planification = new Planification($user, $file);
		$planification->setType($type);
		$planification->setInternal($internal);
		$planification->setCode($code);
		$planification->setName($name);

		$manager->persist($planification);
		$manager->flush();
		$this->addReference('planification-'.$id, $planification);
	}
    }

	private function getData(): array
    {
	return [
		// $data = [$id, $userID, $fileID, $type, $internal, $code, $name]
[99, 1, 321, 'PLACE', 1, 'ROOM', 'SALLE DE REUNION N° 25 1ER ETAGE...'],
[100, 1, 321, 'VEHICLE', 1, 'CAR', 'SCENIC...'],
[101, 1, 321, 'VEHICLE', 1, 'TRUCK', 'FOURGON FIAT DUCATO...'],
[102, 1, 321, 'TOOL', 1, 'COMPUTER', 'ZODIAC...'],
[103, 1, 321, 'PLACE', 1, 'ROOM', 'ALVEOLE G   K3...'],
[111, 1, 321, 'TOOL', 1, 'COMPUTER', 'Didier BOUCHETON...'],
[112, 1, 321, 'TOOL', 1, 'COMPUTER', 'PORTABLE BE...'],
[177, 1, 321, 'PLACE', 1, 'ROOM', 'SALLE DE REUNION N° 85...'],
[207, 1, 321, 'USER', 1, 'CONTRACTOR', 'BOUCHETON...'],
[244, 1, 440, 'PLACE', 1, 'ROOM', 'LIBM Labo 15 CHARTREUSE'],
[245, 1, 440, 'PLACE', 1, 'ROOM', 'LIBM Labo 27 CHARTREUSE'],
[247, 1, 440, 'PLACE', 1, 'ROOM', 'Clôturé au 07/11/2018'],
[248, 1, 440, 'TOOL', 1, 'COMPUTER', 'Accéléromètres HIKOB'],
[249, 1, 440, 'TOOL', 1, 'COMPUTER', 'Analyseur lactate'],
[250, 1, 440, 'TOOL', 1, 'COMPUTER', 'Anémomètre'],
[251, 1, 440, 'TOOL', 1, 'COMPUTER', 'Appareil photo reflex SONY'],
[252, 1, 440, 'TOOL', 1, 'COMPUTER', 'Balance de précision KERN'],
[253, 1, 440, 'TOOL', 1, 'COMPUTER', 'BIOPAC MP150'],
[256, 1, 440, 'TOOL', 1, 'COMPUTER', 'Caméra BLACKEYE'],
[257, 1, 440, 'TOOL', 1, 'COMPUTER', 'Caméra HD SONY'],
[258, 1, 440, 'TOOL', 1, 'COMPUTER', 'Ordinatuer pro book 640 G2 Windows 10 Pro'],
[260, 1, 440, 'TOOL', 1, 'COMPUTER', 'Caméra Thermique'],
[261, 1, 440, 'TOOL', 1, 'COMPUTER', 'Cardio POLAR (16 dispo   preciser le nombre)'],
[262, 1, 440, 'TOOL', 1, 'COMPUTER', 'Cardio POLAR TEAM'],
[263, 1, 440, 'TOOL', 1, 'COMPUTER', 'Chronomètre TAG CP 540'],
[264, 1, 440, 'TOOL', 1, 'COMPUTER', 'EMG BIOMETRICS MWX8'],
[265, 1, 440, 'TOOL', 1, 'COMPUTER', 'Hémo Control EKF'],
[266, 1, 440, 'TOOL', 1, 'COMPUTER', 'INNOVISION'],
[268, 1, 440, 'TOOL', 1, 'COMPUTER', 'K4'],
[269, 1, 440, 'TOOL', 1, 'COMPUTER', 'Lactate SCOUT'],
[276, 1, 440, 'TOOL', 1, 'COMPUTER', 'Data logger thermo couple'],
[284, 1, 440, 'TOOL', 1, 'COMPUTER', 'PEDAR+ Ordi DELL 820 N°8'],
[288, 1, 440, 'TOOL', 1, 'COMPUTER', 'PINCH GRIP'],
[291, 1, 440, 'TOOL', 1, 'COMPUTER', 'Radar STALKER'],
[292, 1, 440, 'TOOL', 1, 'COMPUTER', 'Sonde thermo HANNA'],
[293, 1, 440, 'TOOL', 1, 'COMPUTER', 'Sono + micro BST'],
[294, 1, 440, 'TOOL', 1, 'COMPUTER', 'SPORIDENT'],
[295, 1, 440, 'TOOL', 1, 'COMPUTER', 'Tapis BOSCO'],
[296, 1, 440, 'TOOL', 1, 'COMPUTER', 'Tapis de course PRECOR'],
[297, 1, 440, 'TOOL', 1, 'COMPUTER', 'THERMOCHRONS'],
[306, 1, 440, 'TOOL', 1, 'COMPUTER', 'XSENS'],
[308, 1, 440, 'TOOL', 1, 'COMPUTER', '  Petits materiels divers (à préciser en note)'],
[309, 1, 440, 'TOOL', 1, 'COMPUTER', 'VIDEOPROJECTEUR 0 ...'],
[310, 1, 440, 'TOOL', 1, 'COMPUTER', 'BIOPAC MP36 1 ...'],
[311, 1, 440, 'TOOL', 1, 'COMPUTER', 'Pèse personne numérique TERAILLON ...'],
[312, 1, 440, 'TOOL', 1, 'COMPUTER', 'MONARK 818E ARSAC ...'],
[313, 1, 440, 'TOOL', 1, 'COMPUTER', 'MYOTEST ...'],
[314, 1, 440, 'TOOL', 1, 'COMPUTER', 'Ordinateur DELL Latitude D810 ...'],
[315, 1, 440, 'TOOL', 1, 'COMPUTER', ' ...'],
[316, 1, 440, 'TOOL', 1, 'COMPUTER', 'IPAD 1 ...'],
[317, 1, 440, 'TOOL', 1, 'COMPUTER', 'Montre GPS GARMIN 1 ...'],
[319, 1, 440, 'TOOL', 1, 'COMPUTER', 'Webcam (12 dispo'],
[338, 1, 440, 'PLACE', 1, 'ROOM', 'LIBM Labo  BIO 131 Chartreuse...'],
[339, 1, 440, 'PLACE', 1, 'ROOM', 'LIBM Labo 132 Chartreuse...'],
[340, 1, 440, 'TOOL', 1, 'COMPUTER', 'Plateforme AMTI...'],
[361, 1, 440, 'TOOL', 1, 'COMPUTER', 'Caméra GOPRO...'],
[377, 1, 507, 'SPORT', 1, 'COURT', 'Terrains Intérieurs'],
[401, 1, 523, 'PLACE', 1, 'ROOM', 'Multimédia'],
[408, 1, 523, 'PLACE', 1, 'ROOM', 'Salle Vidéo...'],
[409, 1, 507, 'SPORT', 1, 'COURT', 'Terrains Extérieurs'],
[421, 1, 535, 'SPORT', 1, 'COURT', 'RESERVATION'],
[424, 1, 538, 'VEHICLE', 1, 'PLANE', 'Avions'],
[437, 1, 555, 'VEHICLE', 1, 'PLANE', '31SA ...'],
[440, 1, 555, 'VEHICLE', 1, 'PLANE', 'F GDLY'],
[477, 1, 550, 'PLACE', 0, 'NULL', 'Réserver un Bureau'],
[478, 1, 550, 'PLACE', 1, 'ROOM', 'Réserver la Salle de Réunion'],
[480, 1, 538, 'USER', 1, 'TEACHER', 'VERA Stéphane'],
[482, 1, 612, 'SPORT', 1, 'COURT', 'Squash'],
[488, 1, 614, 'PLACE', 1, 'ROOM', 'Lévezou'],
[491, 1, 614, 'PLACE', 0, 'NULL', 'Visites'],
[492, 1, 614, 'PLACE', 1, 'ROOM', 'Larzac'],
[493, 1, 614, 'TOOL', 1, 'PROJECTOR', 'Rétro projecteur'],
[494, 1, 614, 'PLACE', 0, 'NULL', 'Réunion'],
[495, 1, 614, 'PLACE', 0, 'NULL', 'Séminaire'],
[508, 1, 440, 'TOOL', 1, 'COMPUTER', 'Compex SP 4.0 N°1 ...'],
[525, 1, 440, 'TOOL', 1, 'COMPUTER', 'Compex SP6.0 N°1 ...'],
[532, 1, 440, 'TOOL', 1, 'COMPUTER', 'Tablette HP windows 8 N°1 ...'],
[561, 1, 440, 'TOOL', 1, 'COMPUTER', 'Optojump N°1 9m de kit supplémentaire'],
[569, 1, 523, 'PLACE', 1, 'ROOM', 'C12  Chariot 16 ordinateurs portables'],
[578, 1, 440, 'PLACE', 1, 'ROOM', 'CEPART+ clé à récuperer bureau 139'],
[579, 1, 440, 'PLACE', 1, 'ROOM', 'LIBM Labo 18bis CHARTREUSE'],
[580, 1, 440, 'TOOL', 1, 'COMPUTER', 'Chaine Neuromusculaire (Ergo+Digitimer+Powerlab)'],
[583, 1, 321, 'VEHICLE', 1, 'TRUCK', 'JUMPY'],
[584, 1, 440, 'TOOL', 1, 'COMPUTER', 'Thermomètre auriculaire numérique OMRON N°1 ...'],
[585, 1, 440, 'TOOL', 1, 'COMPUTER', 'Thermomètre frontal sans contact COLSON N°1 ...'],
[590, 1, 550, 'PLACE', 0, 'NULL', 'Salle Rdc   Bureau PMR'],
[604, 1, 440, 'TOOL', 1, 'COMPUTER', 'MONARK LC6'],
[606, 1, 440, 'PLACE', 1, 'ROOM', 'LIBM ENTREMONT  labo avant ou arriere'],
[609, 1, 321, 'VEHICLE', 1, 'BOAT', 'OCQUETEAU'],
[610, 1, 321, 'VEHICLE', 1, 'CAR', 'MEGANE EY 137 GE'],
[612, 1, 523, 'PLACE', 1, 'ROOM', 'Techno'],
	];
    }
	
	public function getDependencies()
	{
		return array(FileFixtures::class, UserFixtures::class);
    }
}
