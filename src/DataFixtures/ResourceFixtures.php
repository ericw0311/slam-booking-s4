<?php
// App/DataFixtures/ResourceFixtures.php
namespace App\DataFixtures;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use App\Entity\File;
use App\Entity\User;
use App\Entity\ResourceClassification;
use App\Entity\Resource;

class ResourceFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
	foreach ($this->getData() as [$id, $userID, $fileID, $type, $internal, $code, $name, $classificationID]) {
		$user = $this->getReference('user-1');
		$file = $this->getReference('file-'.$fileID);
		$resource = new Resource($user, $file);
		$resource->setType($type);
		$resource->setName($name);
		if ($internal > 0) {
			$resource->setInternal(true);
			$resource->setCode($code);
		} else {
			$resource->setInternal(false);
			$resourceClassification = $this->getReference('resourceClassification-'.$classificationID);
			$resource->setClassification($resourceClassification);
		}
		$manager->persist($resource);
		$manager->flush();
		$this->addReference('resource-'.$id, $resource);
	}
    }
	private function getData(): array
    {
	return [
		// $data = [$id, $userID, $fileID, $type, $internal, $code, $name, $classificationID]
[250, 1, 321, 'PLACE', 1, 'ROOM', 'SALLE DE REUNION N° 25 1ER ETAGE', 2473],
[251, 1, 321, 'VEHICLE', 1, 'CAR', 'SCENIC', 2478],
[252, 1, 321, 'VEHICLE', 1, 'TRUCK', 'FIAT DUCATO', 2479],
[253, 1, 321, 'TOOL', 1, 'COMPUTER', 'ZODIAC', 2486],
[254, 1, 321, 'PLACE', 1, 'ROOM', 'ALVEOLE G - K3', 2473],
[269, 1, 321, 'TOOL', 1, 'COMPUTER', 'Didier BOUCHETON', 2486],
[270, 1, 321, 'TOOL', 1, 'COMPUTER', 'PORTABLE BE', 2486],
[442, 1, 321, 'PLACE', 1, 'ROOM', 'SALLE DE REUNION N° 85', 2473],
[654, 1, 321, 'USER', 1, 'CONTRACTOR', 'BOUCHETON', 2494],
[954, 1, 440, 'TOOL', 1, 'COMPUTER', 'BIOPAC MP36-1 * B', 4598],
[955, 1, 440, 'TOOL', 1, 'COMPUTER', 'BIOPAC MP36-2 * B', 4598],
[956, 1, 440, 'TOOL', 1, 'COMPUTER', 'Caméra Thermique', 4598],
[957, 1, 440, 'TOOL', 1, 'COMPUTER', 'Accéléromètres HIKOB', 4598],
[958, 1, 440, 'TOOL', 1, 'COMPUTER', 'Analyseur lactate', 4598],
[959, 1, 440, 'TOOL', 1, 'COMPUTER', 'BIOPAC MP150', 4598],
[960, 1, 440, 'TOOL', 1, 'COMPUTER', 'EMG BIOMETRICS MWX8', 4598],
[961, 1, 440, 'TOOL', 1, 'COMPUTER', 'Hémo Control EKF', 4598],
[962, 1, 440, 'TOOL', 1, 'COMPUTER', 'K4 * K', 4598],
[963, 1, 440, 'TOOL', 1, 'COMPUTER', 'Lactate SCOUT 1', 4598],
[964, 1, 440, 'TOOL', 1, 'COMPUTER', 'MYOTEST', 4598],
[965, 1, 440, 'TOOL', 1, 'COMPUTER', 'MYOTEST PRO', 4598],
[966, 1, 440, 'TOOL', 1, 'COMPUTER', 'PEDAR * P', 4598],
[967, 1, 440, 'TOOL', 1, 'COMPUTER', 'Data logger thermo couple', 4598],
[968, 1, 440, 'TOOL', 1, 'COMPUTER', 'PINCH GRIP', 4598],
[969, 1, 440, 'TOOL', 1, 'COMPUTER', 'THERMOCHRONS * T', 4598],
[970, 1, 440, 'TOOL', 1, 'COMPUTER', 'XSENS * X', 4598],
[972, 1, 440, 'TOOL', 1, 'COMPUTER', 'Caméra HD SONY', 4598],
[973, 1, 440, 'PLACE', 1, 'ROOM', '1', 4585],
[974, 1, 440, 'PLACE', 1, 'ROOM', 'LIBM Labo 15 CHARTREUSE', 4585],
[975, 1, 440, 'PLACE', 1, 'ROOM', 'LIBM Labo 27 CHARTREUSE', 4585],
[976, 1, 440, 'PLACE', 1, 'ROOM', 'LIBM Labo BIO 131 Chartreuse', 4585],
[977, 1, 440, 'TOOL', 1, 'COMPUTER', 'MONARK 818E ARSAC * A', 4598],
[978, 1, 440, 'TOOL', 1, 'COMPUTER', 'MONARK 874E', 4598],
[979, 1, 440, 'TOOL', 1, 'COMPUTER', 'MONARK 915E', 4598],
[980, 1, 440, 'TOOL', 1, 'COMPUTER', 'Plateforme EQUI+ * PE', 4598],
[981, 1, 440, 'TOOL', 1, 'COMPUTER', 'Plateforme SATEL * PS', 4598],
[982, 1, 440, 'TOOL', 1, 'COMPUTER', 'Tapis de course PRECOR', 4598],
[983, 1, 440, 'TOOL', 1, 'COMPUTER', 'Balance  précision SOEHNLE', 4598],
[984, 1, 440, 'TOOL', 1, 'COMPUTER', 'Cardio POLAR TEAM', 4598],
[985, 1, 440, 'TOOL', 1, 'COMPUTER', 'Radar STALKER * R', 4598],
[986, 1, 440, 'TOOL', 1, 'COMPUTER', 'Sonde thermo HANNA', 4598],
[987, 1, 440, 'TOOL', 1, 'COMPUTER', 'Anémomètre', 4598],
[988, 1, 440, 'TOOL', 1, 'COMPUTER', 'INNOVISION', 4598],
[989, 1, 440, 'TOOL', 1, 'COMPUTER', 'Tapis BOSCO', 4598],
[990, 1, 440, 'TOOL', 1, 'COMPUTER', 'Chronomètre TAG CP-540  * C', 4598],
[991, 1, 440, 'TOOL', 1, 'COMPUTER', 'SPORIDENT * S', 4598],
[992, 1, 440, 'TOOL', 1, 'COMPUTER', 'Webcam (12 dispo - preciser le nombre)', 4598],
[993, 1, 440, 'TOOL', 1, 'COMPUTER', 'Cardio POLAR (11 dispo - preciser le nombre)', 4598],
[994, 1, 440, 'TOOL', 1, 'COMPUTER', 'Ordinatuer pro book 640 G2 Windows 10 Pro	', 4598],
[995, 1, 440, 'TOOL', 1, 'COMPUTER', 'IPAD-1', 4598],
[996, 1, 440, 'TOOL', 1, 'COMPUTER', 'Montre GPS GARMIN-1', 4598],
[997, 1, 440, 'TOOL', 1, 'COMPUTER', 'Camera blackeye', 4598],
[998, 1, 440, 'TOOL', 1, 'COMPUTER', 'Appareil photo reflex SONY', 4598],
[999, 1, 440, 'TOOL', 1, 'COMPUTER', 'Sono + micro BST', 4598],
[1000, 1, 440, 'TOOL', 1, 'COMPUTER', 'VIDEOPROJECTEUR-1', 4598],
[1001, 1, 440, 'TOOL', 1, 'COMPUTER', 'VIDEOPROJECTEUR-2', 4598],
[1002, 1, 440, 'TOOL', 1, 'COMPUTER', 'VIDEOPROJECTEUR-3', 4598],
[1003, 1, 440, 'TOOL', 1, 'COMPUTER', 'VIDEOPROJECTEUR-4', 4598],
[1004, 1, 440, 'TOOL', 1, 'COMPUTER', 'VIDEOPROJECTEUR-5', 4598],
[1005, 1, 440, 'TOOL', 1, 'COMPUTER', 'VIDEOPROJECTEUR-6', 4598],
[1006, 1, 440, 'TOOL', 1, 'COMPUTER', 'VIDEOPROJECTEUR-0', 4598],
[1007, 1, 440, 'TOOL', 1, 'COMPUTER', 'Ordi DELL E6420 ATG N°2    A-B-C-R-K-O-S', 4598],
[1008, 1, 440, 'TOOL', 1, 'COMPUTER', 'Ordi DELL  D830 N°5    B-K-MA-ME-PE-P-X-SA-SE', 4598],
[1009, 1, 440, 'TOOL', 1, 'COMPUTER', 'Ordi DELL D810-N°6  k4*    B-G-K-T', 4598],
[1010, 1, 440, 'TOOL', 1, 'COMPUTER', 'Ordi DELL D830 -N°4     B-K-P-PE-R-X', 4598],
[1011, 1, 440, 'TOOL', 1, 'COMPUTER', 'Ordi DELL E6400 ATG  N°3    B-C-O-P-R-K-L-S', 4598],
[1012, 1, 440, 'TOOL', 1, 'COMPUTER', 'Ordi HP PROBOOK 6570B N°7     B-K-OC-OP-R', 4598],
[1013, 1, 440, 'TOOL', 1, 'COMPUTER', 'Ordi HP 4330S N°1    B-K-O-PE-PS-S-T', 4598],
[1014, 1, 440, 'TOOL', 1, 'COMPUTER', 'Pèse personne analogique', 4598],
[1015, 1, 440, 'TOOL', 1, 'COMPUTER', 'Pèse personne numérique carrefour home', 4598],
[1016, 1, 440, 'TOOL', 1, 'COMPUTER', 'Pèse personne numérique TERAILLON', 4598],
[1029, 1, 440, 'TOOL', 1, 'COMPUTER', '  Petits materiels divers (à préciser en note)', 4598],
[1057, 1, 440, 'TOOL', 1, 'COMPUTER', 'IPAD-2', 4598],
[1058, 1, 440, 'TOOL', 1, 'COMPUTER', 'IPAD-3', 4598],
[1059, 1, 440, 'TOOL', 1, 'COMPUTER', 'IPAD-4', 4598],
[1060, 1, 440, 'TOOL', 1, 'COMPUTER', 'IPAD-5', 4598],
[1061, 1, 440, 'TOOL', 1, 'COMPUTER', 'IPAD-6', 4598],
[1062, 1, 440, 'TOOL', 1, 'COMPUTER', 'Montre GPS GARMIN-2', 4598],
[1063, 1, 440, 'TOOL', 1, 'COMPUTER', 'Montre GPS GARMIN-3', 4598],
[1064, 1, 440, 'TOOL', 1, 'COMPUTER', 'Montre GPS GARMIN-4', 4598],
[1065, 1, 440, 'TOOL', 1, 'COMPUTER', 'Montre GPS GARMIN-5', 4598],
[1066, 1, 440, 'TOOL', 1, 'COMPUTER', 'Montre GPS GARMIN-6', 4598],
[1156, 1, 440, 'PLACE', 1, 'ROOM', 'LIBM Labo 132 Chartreuse', 4585],
[1157, 1, 440, 'TOOL', 1, 'COMPUTER', 'Plateforme AMTI', 4598],
[1219, 1, 440, 'TOOL', 1, 'COMPUTER', 'Caméra GOPRO', 4598],
[1221, 1, 440, 'TOOL', 1, 'COMPUTER', 'BIOPAC MP36 3 * B', 4598],
[1222, 1, 440, 'TOOL', 1, 'COMPUTER', 'LACTATE SCOUT 2', 4598],
[1225, 1, 492, 'SPORT', 1, 'COURT', 'Court n°1', 5585],
[1226, 1, 492, 'SPORT', 1, 'COURT', 'Court n°2 ancien', 5585],
[1261, 1, 440, 'TOOL', 1, 'COMPUTER', 'IPAD-7', 4598],
[1262, 1, 440, 'TOOL', 1, 'COMPUTER', 'IPAD-8', 4598],
[1272, 1, 507, 'SPORT', 1, 'COURT', 'Terrain intérieur N°1', 5825],
[1273, 1, 507, 'SPORT', 1, 'COURT', 'Terrain intérieur N°2', 5825],
[1276, 1, 507, 'SPORT', 1, 'COURT', 'Entraînements adultes', 5825],
[1277, 1, 507, 'SPORT', 1, 'COURT', 'Ecole de Tennis', 5825],
[1306, 1, 516, 'PLACE', 1, 'ROOM', 'Salle informatique 1', 5929],
[1307, 1, 516, 'PLACE', 1, 'ROOM', 'Salle informatique 19', 5929],
[1308, 1, 516, 'PLACE', 1, 'ROOM', 'Salle de conférences', 5929],
[1412, 1, 523, 'PLACE', 1, 'ROOM', 'Multimédia - 22 ordinateurs de bureau', 6049],
[1431, 1, 523, 'PLACE', 1, 'ROOM', 'Salle de Théâtre', 6049],
[1453, 1, 507, 'SPORT', 1, 'COURT', 'Terrain Extérieur N°3', 5825],
[1454, 1, 507, 'SPORT', 1, 'COURT', 'Terrain Extérieur N°4', 5825],
[1505, 1, 535, 'SPORT', 1, 'COURT', 'Béton', 6329],
[1506, 1, 535, 'SPORT', 1, 'COURT', 'Gazon', 6329],
[1524, 1, 538, 'VEHICLE', 1, 'PLANE', 'Indisp', 6372],
[1525, 1, 538, 'VEHICLE', 1, 'PLANE', 'DV20 F-GNJD', 6372],
[1529, 1, 492, 'SPORT', 1, 'COURT', 'Court n°2', 5585],
[1567, 1, 538, 'USER', 1, 'TEACHER', 'VERA Stéphane', 6381],
[1586, 1, 550, 'PLACE', 0, '', 'Moscou', 11770],
[1587, 1, 550, 'PLACE', 0, '', 'Venise', 11770],
[1588, 1, 550, 'PLACE', 0, '', 'New York', 11770],
[1589, 1, 550, 'PLACE', 0, '', 'Tokyo', 11770],
[1613, 1, 555, 'VEHICLE', 1, 'PLANE', '90 DW', 6708],
[1615, 1, 555, 'VEHICLE', 1, 'PLANE', '31SA', 6708],
[1683, 1, 555, 'VEHICLE', 1, 'PLANE', 'F-GDLY', 6708],
[1947, 1, 550, 'PLACE', 1, 'ROOM', 'Réunion/Ateliers', 6625],
[1968, 1, 612, 'SPORT', 1, 'COURT', 'Squash', 7843],
[1983, 1, 614, 'PLACE', 1, 'ROOM', 'Larzac', 7875],
[1984, 1, 614, 'PLACE', 1, 'ROOM', 'Lévezou', 7875],
[1993, 1, 614, 'PLACE', 0, '', 'Visites', 7947],
[1994, 1, 614, 'TOOL', 1, 'PROJECTOR', 'Rétro projecteur', 7890],
[1995, 1, 614, 'PLACE', 0, '', 'Séminaire', 7947],
[1996, 1, 614, 'PLACE', 0, '', 'Réunion', 7947],
[2113, 1, 440, 'TOOL', 1, 'COMPUTER', 'Compex SP 4.0 N°1', 4598],
[2117, 1, 440, 'TOOL', 1, 'COMPUTER', 'Compex SP 4.0 N°2', 4598],
[2118, 1, 440, 'TOOL', 1, 'COMPUTER', 'Compex SP 4.0 N°3', 4598],
[2119, 1, 440, 'TOOL', 1, 'COMPUTER', 'Compex SP 4.0 N°4', 4598],
[2223, 1, 440, 'TOOL', 1, 'COMPUTER', 'Compex SP6.0 N°1', 4598],
[2224, 1, 440, 'TOOL', 1, 'COMPUTER', 'Compex SP6.0 N°2', 4598],
[2225, 1, 440, 'TOOL', 1, 'COMPUTER', 'Compex SP6.0 N°3', 4598],
[2226, 1, 440, 'TOOL', 1, 'COMPUTER', 'Compex SP6.0 N°4', 4598],
[2227, 1, 440, 'TOOL', 1, 'COMPUTER', 'Compex SP6.0 N°5', 4598],
[2263, 1, 440, 'TOOL', 1, 'COMPUTER', 'Tablette HP windows 8 N°1', 4598],
[2264, 1, 440, 'TOOL', 1, 'COMPUTER', 'Tablette HP Windows 8 N°2', 4598],
[2265, 1, 440, 'TOOL', 1, 'COMPUTER', 'Tablette HP hybride Windows 8 avec clavier  N°3', 4598],
[2382, 1, 690, 'PLACE', 1, 'ROOM', 'Grande Salle réunion', 9717],
[2383, 1, 690, 'PLACE', 1, 'ROOM', 'Cafet - Box droit', 9717],
[2384, 1, 690, 'PLACE', 1, 'ROOM', 'Cafet - Box gauche', 9717],
[2385, 1, 690, 'TOOL', 1, 'COMPUTER', 'Conférence téléphone', 9730],
[2393, 1, 691, 'PLACE', 1, 'ROOM', 'Conf Call KPC', 9741],
[2464, 1, 707, 'SPORT', 1, 'COURT', '1', 10069],
[2465, 1, 707, 'SPORT', 1, 'COURT', 'Court de tennis Intérieur', 10069],
[2485, 1, 707, 'SPORT', 1, 'COURT', 'Court de tennis Extérieur 1', 10069],
[2649, 1, 440, 'TOOL', 1, 'COMPUTER', 'Optojump N°1', 4598],
[2650, 1, 440, 'TOOL', 1, 'COMPUTER', 'Optojump N°2', 4598],
[2686, 1, 550, 'PLACE', 0, '', 'Lima', 11770],
[2705, 1, 550, 'PLACE', 0, '', 'Berlin', 11770],
[2706, 1, 550, 'PLACE', 0, '', 'Istanbul', 11770],
[2707, 1, 550, 'PLACE', 0, '', 'San Francisco', 11770],
[2708, 1, 550, 'PLACE', 0, '', 'Paris', 11770],
[2722, 1, 523, 'PLACE', 1, 'ROOM', 'C12- Chariot 16 ordinateurs portables', 6049],
[2776, 1, 757, 'PLACE', 1, 'ROOM', 'F203', 11202],
[2777, 1, 757, 'PLACE', 1, 'ROOM', 'F210', 11202],
[2778, 1, 757, 'PLACE', 1, 'ROOM', 'F207', 11202],
[2780, 1, 757, 'PLACE', 1, 'ROOM', 'F205', 11202],
[2784, 1, 757, 'PLACE', 1, 'ROOM', 'Salle de cours 1', 11202],
[2785, 1, 757, 'PLACE', 1, 'ROOM', 'Salle de cours 2', 11202],
[2786, 1, 757, 'PLACE', 1, 'ROOM', 'Salle de cours 3', 11202],
[2825, 1, 440, 'PLACE', 1, 'ROOM', 'CEPART', 4585],
[2826, 1, 440, 'PLACE', 1, 'ROOM', 'LIBM Labo 18bis CHARTREUSE', 4585],
[2827, 1, 440, 'TOOL', 1, 'COMPUTER', 'Chaine Neuromusculaire (Ergo+Digitimer+Powerlab)', 4598],
[2844, 1, 321, 'VEHICLE', 1, 'TRUCK', 'JUMPY', 2479],
[2846, 1, 440, 'TOOL', 1, 'COMPUTER', 'Thermomètre auriculaire numérique OMRON N°1', 4598],
[2847, 1, 440, 'TOOL', 1, 'COMPUTER', 'Thermomètre auriculaire numérique OMRON N°2', 4598],
[2848, 1, 440, 'TOOL', 1, 'COMPUTER', 'Thermomètre auriculaire numérique OMRON N°3', 4598],
[2849, 1, 440, 'TOOL', 1, 'COMPUTER', 'Thermomètre frontal sans contact COLSON N°1', 4598],
[2850, 1, 440, 'TOOL', 1, 'COMPUTER', 'Thermomètre frontal sans contact COLSON N°2', 4598],
[2851, 1, 440, 'TOOL', 1, 'COMPUTER', 'Thermomètre frontal sans contact COLSON N°3', 4598],
[2882, 1, 550, 'PLACE', 0, '', 'Bureau PMR-Salle Rdc', 11770],
[2963, 1, 440, 'TOOL', 1, 'COMPUTER', 'MONARK LC6 (en entremont)', 4598],
[2964, 1, 440, 'TOOL', 1, 'COMPUTER', 'MONARK LC6  (CEPART ET ENTREMONT)', 4598],
[2966, 1, 440, 'PLACE', 1, 'ROOM', 'LIBM ENTREMONT  Labo arriere', 4585],
[2967, 1, 440, 'PLACE', 1, 'ROOM', 'LIBM ENTREMONT  Labo avant', 4585],
[2986, 1, 321, 'VEHICLE', 1, 'BOAT', 'OCQUETEAU', 2483],
[2998, 1, 321, 'VEHICLE', 1, 'CAR', 'MEGANE EY-137-GE', 2478],
[2999, 1, 550, 'PLACE', 0, '', 'Vancouver', 11770],
	];
    }
	
	public function getDependencies()
	{
		return array(FileFixtures::class, UserFixtures::class, ResourceClassification_iFixtures::class, ResourceClassification_eFixtures::class);
    }
}
