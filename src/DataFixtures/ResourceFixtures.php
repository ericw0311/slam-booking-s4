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
	foreach ($this->getData() as [$id, $userID, $fileID, $type, $code, $name, $resourceUserID]) {

		$user = $this->getReference('user-1');
		$file = $this->getReference('file-'.$fileID);

		$resource = new Resource($user, $file);
		$resource->setType($type);
		$resource->setCode($code);
		$resource->setName($name);
		$resource->setInternal(true);

		$manager->persist($resource);
		$manager->flush();
		$this->addReference('resource-'.$id, $resource);
	}
    }

	private function getData(): array
    {
	return [
		// $data = [$id, $userID, $fileID, $type, $internal, $code, $active]
[250, 1, 321, 'PLACE', 'ROOM', 'SALLE DE REUNION N° 25 1ER ETAGE', 0],
[251, 1, 321, 'VEHICLE', 'CAR', 'SCENIC', 0],
[252, 1, 321, 'VEHICLE', 'TRUCK', 'FIAT DUCATO', 0],
[253, 1, 321, 'TOOL', 'COMPUTER', 'ZODIAC', 0],
[254, 1, 321, 'PLACE', 'ROOM', 'ALVEOLE G - K3', 0],
[269, 1, 321, 'TOOL', 'COMPUTER', 'Didier BOUCHETON', 0],
[270, 1, 321, 'TOOL', 'COMPUTER', 'PORTABLE BE', 0],
[442, 1, 321, 'PLACE', 'ROOM', 'SALLE DE REUNION N° 85', 0],
[654, 1, 321, 'USER', 'CONTRACTOR', 'BOUCHETON', 279],
[954, 1, 440, 'TOOL', 'COMPUTER', 'BIOPAC MP36-1 * B', 0],
[955, 1, 440, 'TOOL', 'COMPUTER', 'BIOPAC MP36-2 * B', 0],
[956, 1, 440, 'TOOL', 'COMPUTER', 'Caméra Thermique', 0],
[957, 1, 440, 'TOOL', 'COMPUTER', 'Accéléromètres HIKOB', 0],
[958, 1, 440, 'TOOL', 'COMPUTER', 'Analyseur lactate', 0],
[959, 1, 440, 'TOOL', 'COMPUTER', 'BIOPAC MP150', 0],
[960, 1, 440, 'TOOL', 'COMPUTER', 'EMG BIOMETRICS MWX8', 0],
[961, 1, 440, 'TOOL', 'COMPUTER', 'Hémo Control EKF', 0],
[962, 1, 440, 'TOOL', 'COMPUTER', 'K4 * K', 0],
[963, 1, 440, 'TOOL', 'COMPUTER', 'Lactate SCOUT 1', 0],
[964, 1, 440, 'TOOL', 'COMPUTER', 'MYOTEST', 0],
[965, 1, 440, 'TOOL', 'COMPUTER', 'MYOTEST PRO', 0],
[966, 1, 440, 'TOOL', 'COMPUTER', 'PEDAR * P', 0],
[967, 1, 440, 'TOOL', 'COMPUTER', 'Data logger thermo couple', 0],
[968, 1, 440, 'TOOL', 'COMPUTER', 'PINCH GRIP', 0],
[969, 1, 440, 'TOOL', 'COMPUTER', 'THERMOCHRONS * T', 0],
[970, 1, 440, 'TOOL', 'COMPUTER', 'XSENS * X', 0],
[972, 1, 440, 'TOOL', 'COMPUTER', 'Caméra HD SONY', 0],
[973, 1, 440, 'PLACE', 'ROOM', '1', 0],
[974, 1, 440, 'PLACE', 'ROOM', 'LIBM Labo 15 CHARTREUSE', 0],
[975, 1, 440, 'PLACE', 'ROOM', 'LIBM Labo 27 CHARTREUSE', 0],
[976, 1, 440, 'PLACE', 'ROOM', 'LIBM Labo BIO 131 Chartreuse', 0],
[977, 1, 440, 'TOOL', 'COMPUTER', 'MONARK 818E ARSAC * A', 0],
[978, 1, 440, 'TOOL', 'COMPUTER', 'MONARK 874E', 0],
[979, 1, 440, 'TOOL', 'COMPUTER', 'MONARK 915E', 0],
[980, 1, 440, 'TOOL', 'COMPUTER', 'Plateforme EQUI+ * PE', 0],
[981, 1, 440, 'TOOL', 'COMPUTER', 'Plateforme SATEL * PS', 0],
[982, 1, 440, 'TOOL', 'COMPUTER', 'Tapis de course PRECOR', 0],
[983, 1, 440, 'TOOL', 'COMPUTER', 'Balance  précision SOEHNLE', 0],
[984, 1, 440, 'TOOL', 'COMPUTER', 'Cardio POLAR TEAM', 0],
[985, 1, 440, 'TOOL', 'COMPUTER', 'Radar STALKER * R', 0],
[986, 1, 440, 'TOOL', 'COMPUTER', 'Sonde thermo HANNA', 0],
[987, 1, 440, 'TOOL', 'COMPUTER', 'Anémomètre', 0],
[988, 1, 440, 'TOOL', 'COMPUTER', 'INNOVISION', 0],
[989, 1, 440, 'TOOL', 'COMPUTER', 'Tapis BOSCO', 0],
[990, 1, 440, 'TOOL', 'COMPUTER', 'Chronomètre TAG CP-540  * C', 0],
[991, 1, 440, 'TOOL', 'COMPUTER', 'SPORIDENT * S', 0],
[992, 1, 440, 'TOOL', 'COMPUTER', 'Webcam (12 dispo - preciser le nombre)', 0],
[993, 1, 440, 'TOOL', 'COMPUTER', 'Cardio POLAR (11 dispo - preciser le nombre)', 0],
[994, 1, 440, 'TOOL', 'COMPUTER', 'Ordinatuer pro book 640 G2 Windows 10 Pro	', 0],
[995, 1, 440, 'TOOL', 'COMPUTER', 'IPAD-1', 0],
[996, 1, 440, 'TOOL', 'COMPUTER', 'Montre GPS GARMIN-1', 0],
[997, 1, 440, 'TOOL', 'COMPUTER', 'Camera blackeye', 0],
[998, 1, 440, 'TOOL', 'COMPUTER', 'Appareil photo reflex SONY', 0],
[999, 1, 440, 'TOOL', 'COMPUTER', 'Sono + micro BST', 0],
[1000, 1, 440, 'TOOL', 'COMPUTER', 'VIDEOPROJECTEUR-1', 0],
[1001, 1, 440, 'TOOL', 'COMPUTER', 'VIDEOPROJECTEUR-2', 0],
[1002, 1, 440, 'TOOL', 'COMPUTER', 'VIDEOPROJECTEUR-3', 0],
[1003, 1, 440, 'TOOL', 'COMPUTER', 'VIDEOPROJECTEUR-4', 0],
[1004, 1, 440, 'TOOL', 'COMPUTER', 'VIDEOPROJECTEUR-5', 0],
[1005, 1, 440, 'TOOL', 'COMPUTER', 'VIDEOPROJECTEUR-6', 0],
[1006, 1, 440, 'TOOL', 'COMPUTER', 'VIDEOPROJECTEUR-0', 0],
[1007, 1, 440, 'TOOL', 'COMPUTER', 'Ordi DELL E6420 ATG N°2    A-B-C-R-K-O-S', 0],
[1008, 1, 440, 'TOOL', 'COMPUTER', 'Ordi DELL  D830 N°5    B-K-MA-ME-PE-P-X-SA-SE', 0],
[1009, 1, 440, 'TOOL', 'COMPUTER', 'Ordi DELL D810-N°6  k4*    B-G-K-T', 0],
[1010, 1, 440, 'TOOL', 'COMPUTER', 'Ordi DELL D830 -N°4     B-K-P-PE-R-X', 0],
[1011, 1, 440, 'TOOL', 'COMPUTER', 'Ordi DELL E6400 ATG  N°3    B-C-O-P-R-K-L-S', 0],
[1012, 1, 440, 'TOOL', 'COMPUTER', 'Ordi HP PROBOOK 6570B N°7     B-K-OC-OP-R', 0],
[1013, 1, 440, 'TOOL', 'COMPUTER', 'Ordi HP 4330S N°1    B-K-O-PE-PS-S-T', 0],
[1014, 1, 440, 'TOOL', 'COMPUTER', 'Pèse personne analogique', 0],
[1015, 1, 440, 'TOOL', 'COMPUTER', 'Pèse personne numérique carrefour home', 0],
[1016, 1, 440, 'TOOL', 'COMPUTER', 'Pèse personne numérique TERAILLON', 0],
[1029, 1, 440, 'TOOL', 'COMPUTER', '440 resource à identifier', 0],
[1057, 1, 440, 'TOOL', 'COMPUTER', 'IPAD-2', 0],
[1058, 1, 440, 'TOOL', 'COMPUTER', 'IPAD-3', 0],
[1059, 1, 440, 'TOOL', 'COMPUTER', 'IPAD-4', 0],
[1060, 1, 440, 'TOOL', 'COMPUTER', 'IPAD-5', 0],
[1061, 1, 440, 'TOOL', 'COMPUTER', 'IPAD-6', 0],
[1062, 1, 440, 'TOOL', 'COMPUTER', 'Montre GPS GARMIN-2', 0],
[1063, 1, 440, 'TOOL', 'COMPUTER', 'Montre GPS GARMIN-3', 0],
[1064, 1, 440, 'TOOL', 'COMPUTER', 'Montre GPS GARMIN-4', 0],
[1065, 1, 440, 'TOOL', 'COMPUTER', 'Montre GPS GARMIN-5', 0],
[1066, 1, 440, 'TOOL', 'COMPUTER', 'Montre GPS GARMIN-6', 0],
[1156, 1, 440, 'PLACE', 'ROOM', 'LIBM Labo 132 Chartreuse', 0],
[1157, 1, 440, 'TOOL', 'COMPUTER', 'Plateforme AMTI', 0],
[1219, 1, 440, 'TOOL', 'COMPUTER', 'Caméra GOPRO', 0],
[1221, 1, 440, 'TOOL', 'COMPUTER', 'BIOPAC MP36 3 * B', 0],
[1222, 1, 440, 'TOOL', 'COMPUTER', 'LACTATE SCOUT 2', 0],
[1225, 1, 492, 'SPORT', 'COURT', 'Court n°1', 0],
[1226, 1, 492, 'SPORT', 'COURT', 'Court n°2 ancien', 0],
[1261, 1, 440, 'TOOL', 'COMPUTER', 'IPAD-7', 0],
[1262, 1, 440, 'TOOL', 'COMPUTER', 'IPAD-8', 0],
[1272, 1, 507, 'SPORT', 'COURT', 'Terrain intérieur N°1', 0],
[1273, 1, 507, 'SPORT', 'COURT', 'Terrain intérieur N°2', 0],
[1276, 1, 507, 'SPORT', 'COURT', 'Entraînements adultes', 0],
[1277, 1, 507, 'SPORT', 'COURT', 'Ecole de Tennis', 0],
[1306, 1, 516, 'PLACE', 'ROOM', 'Salle informatique 1', 0],
[1307, 1, 516, 'PLACE', 'ROOM', 'Salle informatique 19', 0],
[1308, 1, 516, 'PLACE', 'ROOM', 'Salle de conférences', 0],
[1412, 1, 523, 'PLACE', 'ROOM', 'Multimédia - 22 ordinateurs de bureau', 0],
[1431, 1, 523, 'PLACE', 'ROOM', 'Salle de Théâtre', 0],
[1453, 1, 507, 'SPORT', 'COURT', 'Terrain Extérieur N°3', 0],
[1454, 1, 507, 'SPORT', 'COURT', 'Terrain Extérieur N°4', 0],
[1505, 1, 535, 'SPORT', 'COURT', 'Béton', 0],
[1506, 1, 535, 'SPORT', 'COURT', 'Gazon', 0],
[1524, 1, 538, 'VEHICLE', 'PLANE', 'Indisp', 0],
[1525, 1, 538, 'VEHICLE', 'PLANE', 'DV20 F-GNJD', 0],
[1529, 1, 492, 'SPORT', 'COURT', 'Court n°2', 0],
[1567, 1, 538, 'USER', 'TEACHER', 'VERA Stéphane', 987],
[1613, 1, 555, 'VEHICLE', 'PLANE', '90 DW', 0],
[1615, 1, 555, 'VEHICLE', 'PLANE', '31SA', 0],
[1683, 1, 555, 'VEHICLE', 'PLANE', 'F-GDLY', 0],
[1947, 1, 550, 'PLACE', 'ROOM', 'Réunion/Ateliers', 0],
[1968, 1, 612, 'SPORT', 'COURT', 'Squash', 0],
[1983, 1, 614, 'PLACE', 'ROOM', 'Larzac', 0],
[1984, 1, 614, 'PLACE', 'ROOM', 'Lévezou', 0],
[1994, 1, 614, 'TOOL', 'PROJECTOR', 'Rétro projecteur', 0],
[2113, 1, 440, 'TOOL', 'COMPUTER', 'Compex SP 4.0 N°1', 0],
[2117, 1, 440, 'TOOL', 'COMPUTER', 'Compex SP 4.0 N°2', 0],
[2118, 1, 440, 'TOOL', 'COMPUTER', 'Compex SP 4.0 N°3', 0],
[2119, 1, 440, 'TOOL', 'COMPUTER', 'Compex SP 4.0 N°4', 0],
[2223, 1, 440, 'TOOL', 'COMPUTER', 'Compex SP6.0 N°1', 0],
[2224, 1, 440, 'TOOL', 'COMPUTER', 'Compex SP6.0 N°2', 0],
[2225, 1, 440, 'TOOL', 'COMPUTER', 'Compex SP6.0 N°3', 0],
[2226, 1, 440, 'TOOL', 'COMPUTER', 'Compex SP6.0 N°4', 0],
[2227, 1, 440, 'TOOL', 'COMPUTER', 'Compex SP6.0 N°5', 0],
[2263, 1, 440, 'TOOL', 'COMPUTER', 'Tablette HP windows 8 N°1', 0],
[2264, 1, 440, 'TOOL', 'COMPUTER', 'Tablette HP Windows 8 N°2', 0],
[2265, 1, 440, 'TOOL', 'COMPUTER', 'Tablette HP hybride Windows 8 avec clavier  N°3', 0],
[2382, 1, 690, 'PLACE', 'ROOM', 'Grande Salle réunion', 0],
[2383, 1, 690, 'PLACE', 'ROOM', 'Cafet - Box droit', 0],
[2384, 1, 690, 'PLACE', 'ROOM', 'Cafet - Box gauche', 0],
[2385, 1, 690, 'TOOL', 'COMPUTER', 'Conférence téléphone', 0],
[2393, 1, 691, 'PLACE', 'ROOM', 'Conf Call KPC', 0],
[2464, 1, 707, 'SPORT', 'COURT', '1', 0],
[2465, 1, 707, 'SPORT', 'COURT', 'Court de tennis Intérieur', 0],
[2485, 1, 707, 'SPORT', 'COURT', 'Court de tennis Extérieur 1', 0],
[2649, 1, 440, 'TOOL', 'COMPUTER', 'Optojump N°1', 0],
[2650, 1, 440, 'TOOL', 'COMPUTER', 'Optojump N°2', 0],
[2722, 1, 523, 'PLACE', 'ROOM', 'C12- Chariot 16 ordinateurs portables', 0],
[2776, 1, 757, 'PLACE', 'ROOM', 'F203', 0],
[2777, 1, 757, 'PLACE', 'ROOM', 'F210', 0],
[2778, 1, 757, 'PLACE', 'ROOM', 'F207', 0],
[2780, 1, 757, 'PLACE', 'ROOM', 'F205', 0],
[2784, 1, 757, 'PLACE', 'ROOM', 'Salle de cours 1', 0],
[2785, 1, 757, 'PLACE', 'ROOM', 'Salle de cours 2', 0],
[2786, 1, 757, 'PLACE', 'ROOM', 'Salle de cours 3', 0],
[2825, 1, 440, 'PLACE', 'ROOM', 'CEPART', 0],
[2826, 1, 440, 'PLACE', 'ROOM', 'LIBM Labo 18bis CHARTREUSE', 0],
[2827, 1, 440, 'TOOL', 'COMPUTER', 'Chaine Neuromusculaire (Ergo+Digitimer+Powerlab)', 0],
[2844, 1, 321, 'VEHICLE', 'TRUCK', 'JUMPY', 0],
[2846, 1, 440, 'TOOL', 'COMPUTER', 'Thermomètre auriculaire numérique OMRON N°1', 0],
[2847, 1, 440, 'TOOL', 'COMPUTER', 'Thermomètre auriculaire numérique OMRON N°2', 0],
[2848, 1, 440, 'TOOL', 'COMPUTER', 'Thermomètre auriculaire numérique OMRON N°3', 0],
[2849, 1, 440, 'TOOL', 'COMPUTER', 'Thermomètre frontal sans contact COLSON N°1', 0],
[2850, 1, 440, 'TOOL', 'COMPUTER', 'Thermomètre frontal sans contact COLSON N°2', 0],
[2851, 1, 440, 'TOOL', 'COMPUTER', 'Thermomètre frontal sans contact COLSON N°3', 0],
[2963, 1, 440, 'TOOL', 'COMPUTER', 'MONARK LC6 (en entremont)', 0],
[2964, 1, 440, 'TOOL', 'COMPUTER', 'MONARK LC6  (CEPART ET ENTREMONT)', 0],
[2966, 1, 440, 'PLACE', 'ROOM', 'LIBM ENTREMONT  Labo arriere', 0],
[2967, 1, 440, 'PLACE', 'ROOM', 'LIBM ENTREMONT  Labo avant', 0],
[2986, 1, 321, 'VEHICLE', 'BOAT', 'OCQUETEAU', 0],
[2998, 1, 321, 'VEHICLE', 'CAR', 'MEGANE EY-137-GE', 0],
	];
    }
	
	public function getDependencies()
	{
		return array(FileFixtures::class, UserFixtures::class, ResourceClassificationFixtures::class);
    }
}
