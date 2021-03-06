<?php
// App/DataFixtures/UserFileFixtures.php
namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

use App\Entity\File;
use App\Entity\User;
use App\Entity\UserFile;

class UserFileFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
	foreach ($this->getData() as [$accountID, $fileID, $administrator, $userFileID, $active, $boResource, $resourceID]) {

		$user = $this->getReference('user-1');
		$file = $this->getReference('file-'.$fileID);
		$account = $this->getReference('user-'.$accountID);
		if ($boResource > 0) {
			$resource = $this->getReference('resource-'.$resourceID);
		}
		$userFile = new UserFile($user, $file);
		$userFile->setAdministrator($administrator);
		$userFile->setActive($active);
		$userFile->setAccount($account);
		$userFile->setEmail($account->getEmail());
		$userFile->setAccountType($account->getAccountType());
		$userFile->setLastName($account->getLastName());
		$userFile->setFirstName($account->getFirstName());
		$userFile->setUniqueName($account->getUniqueName());
		$userFile->setUserCreated(true);
		$userFile->setUsername($account->getUsername());
		if ($boResource > 0) {
			$userFile->setResourceUser(true);
			$userFile->setResource($resource);
		} else {
			$userFile->setResourceUser(false);
		}
		$manager->persist($userFile);
		$manager->flush();

		$this->addReference('userFile-'.$userFileID, $userFile);
	}
    }

	private function getData(): array
    {
	return [
		// $data = [?]
[527, 321, 1, 250, 1, 0, 0],
[532, 321, 0, 259, 1, 0, 0],
[544, 321, 0, 271, 1, 0, 0],
[585, 321, 0, 306, 1, 0, 0],
[549, 321, 0, 276, 1, 0, 0],
[547, 321, 0, 274, 1, 0, 0],
[538, 321, 0, 265, 1, 0, 0],
[534, 321, 0, 261, 1, 0, 0],
[551, 321, 0, 278, 1, 0, 0],
[525, 321, 0, 251, 1, 0, 0],
[541, 321, 0, 268, 1, 0, 0],
[550, 321, 0, 277, 1, 0, 0],
[548, 321, 0, 275, 1, 0, 0],
[531, 321, 0, 256, 1, 0, 0],
[533, 321, 0, 260, 1, 0, 0],
[754, 440, 0, 565, 1, 0, 0],
[741, 440, 0, 551, 1, 0, 0],
[752, 440, 0, 563, 1, 0, 0],
[753, 440, 0, 564, 1, 0, 0],
[759, 440, 0, 570, 1, 0, 0],
[767, 440, 0, 578, 1, 0, 0],
[747, 440, 0, 557, 1, 0, 0],
[768, 440, 0, 579, 1, 0, 0],
[749, 440, 0, 560, 1, 0, 0],
[761, 440, 0, 572, 1, 0, 0],
[750, 440, 0, 561, 1, 0, 0],
[762, 440, 0, 573, 1, 0, 0],
[760, 440, 0, 571, 1, 0, 0],
[743, 440, 0, 553, 1, 0, 0],
[751, 440, 0, 562, 1, 0, 0],
[748, 440, 0, 559, 1, 0, 0],
[757, 440, 0, 581, 1, 0, 0],
[755, 440, 0, 566, 1, 0, 0],
[746, 440, 0, 556, 1, 0, 0],
[744, 440, 0, 554, 1, 0, 0],
[756, 440, 0, 567, 1, 0, 0],
[763, 440, 0, 574, 1, 0, 0],
[765, 440, 0, 576, 1, 0, 0],
[766, 440, 0, 577, 1, 0, 0],
[758, 440, 0, 569, 1, 0, 0],
[813, 321, 0, 634, 1, 0, 0],
[738, 440, 1, 542, 1, 0, 0],
[860, 507, 1, 707, 1, 0, 0],
[861, 507, 0, 711, 1, 0, 0],
[862, 507, 0, 712, 1, 0, 0],
[868, 507, 0, 719, 1, 0, 0],
[871, 507, 0, 722, 1, 0, 0],
[873, 507, 0, 724, 1, 0, 0],
[876, 507, 0, 727, 1, 0, 0],
[877, 507, 0, 728, 1, 0, 0],
[880, 507, 0, 731, 1, 0, 0],
[881, 507, 0, 732, 1, 0, 0],
[882, 507, 0, 733, 1, 0, 0],
[886, 507, 0, 737, 1, 0, 0],
[891, 507, 0, 742, 1, 0, 0],
[895, 507, 0, 746, 1, 0, 0],
[896, 507, 1, 747, 1, 0, 0],
[897, 507, 0, 748, 1, 0, 0],
[900, 507, 1, 751, 1, 0, 0],
[902, 507, 0, 753, 1, 0, 0],
[907, 507, 0, 758, 1, 0, 0],
[910, 507, 0, 761, 1, 0, 0],
[911, 507, 0, 762, 1, 0, 0],
[912, 507, 0, 763, 1, 0, 0],
[915, 507, 0, 766, 1, 0, 0],
[917, 507, 0, 768, 1, 0, 0],
[918, 507, 0, 769, 1, 0, 0],
[919, 507, 0, 770, 1, 0, 0],
[920, 507, 0, 771, 1, 0, 0],
[921, 507, 0, 772, 1, 0, 0],
[922, 507, 0, 773, 1, 0, 0],
[923, 507, 0, 774, 1, 0, 0],
[924, 507, 0, 775, 1, 0, 0],
[926, 507, 0, 777, 1, 0, 0],
[931, 507, 0, 782, 1, 0, 0],
[934, 507, 0, 785, 1, 0, 0],
[936, 507, 0, 787, 1, 0, 0],
[930, 507, 0, 792, 1, 0, 0],
[1022, 523, 1, 884, 1, 0, 0],
[1024, 523, 0, 886, 1, 0, 0],
[1025, 523, 0, 887, 1, 0, 0],
[1026, 523, 0, 888, 1, 0, 0],
[1027, 523, 0, 889, 1, 0, 0],
[1029, 523, 0, 891, 1, 0, 0],
[1030, 523, 0, 892, 1, 0, 0],
[1037, 523, 0, 899, 1, 0, 0],
[1039, 523, 0, 901, 1, 0, 0],
[1040, 523, 0, 902, 1, 0, 0],
[1041, 523, 0, 903, 1, 0, 0],
[1042, 523, 0, 904, 1, 0, 0],
[1044, 523, 0, 906, 1, 0, 0],
[1045, 523, 0, 907, 1, 0, 0],
[1046, 523, 0, 908, 1, 0, 0],
[1047, 523, 0, 909, 1, 0, 0],
[1048, 523, 0, 910, 1, 0, 0],
[1049, 523, 0, 911, 1, 0, 0],
[1050, 523, 0, 912, 1, 0, 0],
[1051, 523, 0, 913, 1, 0, 0],
[1052, 523, 0, 914, 1, 0, 0],
[1056, 507, 0, 917, 1, 0, 0],
[1070, 321, 0, 930, 1, 0, 0],
[1077, 535, 1, 935, 1, 0, 0],
[1081, 440, 0, 615, 1, 0, 0],
[1084, 535, 0, 943, 1, 0, 0],
[1085, 535, 0, 944, 1, 0, 0],
[1086, 535, 0, 945, 1, 0, 0],
[1088, 535, 0, 947, 1, 0, 0],
[1089, 535, 0, 948, 1, 0, 0],
[1091, 321, 0, 950, 1, 0, 0],
[1092, 535, 0, 951, 1, 0, 0],
[1094, 535, 0, 952, 1, 0, 0],
[1095, 538, 1, 953, 1, 0, 0],
[1096, 538, 0, 954, 1, 0, 0],
[1097, 538, 0, 955, 1, 0, 0],
[1103, 538, 0, 962, 1, 0, 0],
[1104, 538, 0, 963, 1, 0, 0],
[1105, 538, 0, 964, 1, 0, 0],
[1106, 538, 0, 965, 1, 0, 0],
[1107, 538, 0, 966, 1, 0, 0],
[1108, 538, 0, 967, 1, 0, 0],
[1109, 538, 0, 968, 1, 0, 0],
[1110, 538, 0, 969, 1, 0, 0],
[1111, 538, 0, 970, 1, 0, 0],
[1112, 538, 0, 971, 1, 0, 0],
[1113, 538, 0, 972, 1, 0, 0],
[1115, 538, 0, 974, 1, 0, 0],
[1116, 538, 0, 975, 1, 0, 0],
[1117, 538, 0, 976, 1, 0, 0],
[1118, 538, 0, 977, 1, 0, 0],
[1119, 538, 0, 978, 1, 0, 0],
[1120, 538, 0, 979, 1, 0, 0],
[1121, 538, 0, 980, 1, 0, 0],
[1122, 538, 0, 981, 1, 0, 0],
[1123, 538, 0, 982, 1, 0, 0],
[1124, 538, 0, 983, 1, 0, 0],
[1125, 538, 0, 984, 1, 0, 0],
[1126, 538, 0, 985, 1, 0, 0],
[1127, 538, 0, 986, 1, 0, 0],
[1129, 538, 0, 988, 1, 0, 0],
[1130, 538, 0, 989, 1, 0, 0],
[1131, 538, 0, 990, 1, 0, 0],
[1132, 535, 0, 991, 1, 0, 0],
[1082, 535, 1, 941, 1, 0, 0],
[1139, 550, 1, 1001, 1, 0, 0],
[1140, 550, 0, 1003, 1, 0, 0],
[1141, 550, 0, 1004, 1, 0, 0],
[1142, 535, 0, 1005, 1, 0, 0],
[1143, 535, 0, 1006, 1, 0, 0],
[1144, 535, 0, 1007, 1, 0, 0],
[1147, 535, 0, 1009, 1, 0, 0],
[1151, 535, 0, 1013, 1, 0, 0],
[1153, 550, 0, 1014, 1, 0, 0],
[1154, 550, 0, 1015, 1, 0, 0],
[1155, 550, 0, 1016, 1, 0, 0],
[1159, 535, 0, 1020, 1, 0, 0],
[1160, 555, 1, 1021, 1, 0, 0],
[1161, 555, 0, 1022, 1, 0, 0],
[1171, 535, 0, 1036, 1, 0, 0],
[1172, 555, 0, 1037, 1, 0, 0],
[1173, 535, 0, 1038, 1, 0, 0],
[1178, 555, 0, 1043, 1, 0, 0],
[1179, 555, 0, 1044, 1, 0, 0],
[1180, 555, 0, 1045, 1, 0, 0],
[1182, 555, 0, 1047, 1, 0, 0],
[1183, 555, 0, 1048, 1, 0, 0],
[1184, 555, 0, 1049, 1, 0, 0],
[1185, 555, 0, 1050, 1, 0, 0],
[1186, 555, 0, 1051, 1, 0, 0],
[1187, 555, 0, 1052, 1, 0, 0],
[1188, 555, 0, 1053, 1, 0, 0],
[1189, 555, 0, 1054, 1, 0, 0],
[1190, 555, 0, 1055, 1, 0, 0],
[1191, 555, 0, 1056, 1, 0, 0],
[1192, 555, 0, 1057, 1, 0, 0],
[1193, 555, 0, 1058, 1, 0, 0],
[1194, 555, 0, 1059, 1, 0, 0],
[1196, 555, 0, 1061, 1, 0, 0],
[1197, 555, 0, 1062, 1, 0, 0],
[1198, 555, 0, 1063, 1, 0, 0],
[1201, 555, 0, 1066, 1, 0, 0],
[1202, 555, 0, 1067, 1, 0, 0],
[1203, 555, 0, 1068, 1, 0, 0],
[1212, 555, 0, 1077, 1, 0, 0],
[1213, 555, 0, 1078, 1, 0, 0],
[1214, 535, 0, 1079, 1, 0, 0],
[1216, 555, 0, 1082, 1, 0, 0],
[1217, 555, 0, 1086, 1, 0, 0],
[1241, 321, 0, 1117, 1, 0, 0],
[1242, 555, 0, 1118, 1, 0, 0],
[1246, 555, 0, 1122, 1, 0, 0],
[1253, 555, 0, 1130, 1, 0, 0],
[1254, 535, 0, 1131, 1, 0, 0],
[1237, 535, 0, 1154, 1, 0, 0],
[1286, 507, 0, 1165, 1, 0, 0],
[1282, 523, 0, 1161, 1, 0, 0],
[1305, 535, 0, 1182, 1, 0, 0],
[1308, 555, 0, 1185, 1, 0, 0],
[1309, 507, 0, 1186, 1, 0, 0],
[1310, 507, 0, 1187, 1, 0, 0],
[1312, 535, 0, 1189, 1, 0, 0],
[1313, 535, 0, 1190, 1, 0, 0],
[1314, 507, 0, 1191, 1, 0, 0],
[1315, 507, 0, 1192, 1, 0, 0],
[1316, 507, 0, 1193, 1, 0, 0],
[1317, 440, 0, 603, 1, 0, 0],
[1368, 507, 0, 1251, 1, 0, 0],
[1369, 507, 0, 1252, 1, 0, 0],
[1374, 535, 0, 1259, 1, 0, 0],
[1375, 555, 0, 1260, 1, 0, 0],
[1376, 555, 0, 1261, 1, 0, 0],
[1378, 555, 0, 1263, 1, 0, 0],
[1114, 538, 0, 1264, 1, 0, 0],
[1383, 507, 0, 1271, 1, 0, 0],
[1386, 550, 0, 1274, 1, 0, 0],
[1387, 550, 0, 1266, 1, 0, 0],
[1388, 550, 0, 1267, 1, 0, 0],
[1389, 550, 0, 1269, 1, 0, 0],
[1391, 550, 0, 1275, 1, 0, 0],
[1393, 550, 0, 1277, 1, 0, 0],
[1394, 550, 0, 1278, 1, 0, 0],
[1395, 535, 0, 1279, 1, 0, 0],
[1396, 535, 0, 1280, 1, 0, 0],
[1400, 535, 0, 1284, 1, 0, 0],
[1401, 535, 0, 1285, 1, 0, 0],
[1402, 507, 0, 1286, 1, 0, 0],
[1403, 507, 0, 1287, 1, 0, 0],
[1405, 321, 0, 1290, 1, 0, 0],
[1406, 321, 0, 1291, 1, 0, 0],
[1408, 612, 1, 1293, 1, 0, 0],
[1409, 612, 0, 1295, 1, 0, 0],
[1410, 612, 0, 1296, 1, 0, 0],
[1411, 612, 0, 1297, 1, 0, 0],
[1412, 612, 0, 1298, 1, 0, 0],
[1413, 612, 0, 1299, 1, 0, 0],
[1414, 612, 0, 1300, 1, 0, 0],
[1420, 614, 0, 1305, 1, 0, 0],
[1429, 614, 0, 1314, 1, 0, 0],
[1430, 614, 0, 1315, 1, 0, 0],
[1431, 614, 0, 1316, 1, 0, 0],
[1433, 614, 0, 1318, 1, 0, 0],
[1434, 614, 0, 1319, 1, 0, 0],
[1435, 614, 0, 1320, 1, 0, 0],
[1436, 614, 0, 1321, 1, 0, 0],
[1437, 614, 0, 1322, 1, 0, 0],
[1438, 614, 0, 1323, 1, 0, 0],
[1441, 614, 0, 1326, 1, 0, 0],
[1442, 612, 0, 1327, 1, 0, 0],
[1443, 612, 0, 1328, 1, 0, 0],
[1416, 612, 0, 1329, 1, 0, 0],
[1444, 612, 0, 1330, 1, 0, 0],
[1445, 612, 0, 1331, 1, 0, 0],
[1446, 612, 0, 1333, 1, 0, 0],
[1447, 612, 0, 1334, 1, 0, 0],
[1448, 612, 0, 1335, 1, 0, 0],
[1449, 612, 0, 1336, 1, 0, 0],
[1450, 612, 0, 1337, 1, 0, 0],
[1451, 612, 0, 1338, 1, 0, 0],
[1452, 612, 0, 1339, 1, 0, 0],
[1453, 612, 0, 1340, 1, 0, 0],
[1454, 612, 0, 1341, 1, 0, 0],
[1457, 612, 0, 1344, 1, 0, 0],
[1459, 612, 0, 1346, 1, 0, 0],
[1460, 612, 0, 1347, 1, 0, 0],
[1462, 507, 0, 1349, 1, 0, 0],
[1464, 612, 0, 1352, 1, 0, 0],
[1468, 612, 0, 1356, 1, 0, 0],
[1471, 612, 0, 1360, 1, 0, 0],
[1474, 612, 0, 1364, 1, 0, 0],
[1477, 612, 0, 1367, 1, 0, 0],
[1478, 612, 0, 1368, 1, 0, 0],
[1475, 612, 0, 1366, 1, 0, 0],
[1479, 612, 0, 1373, 1, 0, 0],
[1483, 612, 0, 1375, 1, 0, 0],
[1485, 612, 0, 1380, 1, 0, 0],
[1486, 612, 0, 1382, 1, 0, 0],
[1487, 612, 0, 1384, 1, 0, 0],
[1488, 612, 0, 1385, 1, 0, 0],
[1489, 612, 0, 1386, 1, 0, 0],
[1490, 612, 0, 1388, 1, 0, 0],
[1496, 612, 0, 1394, 1, 0, 0],
[1497, 612, 0, 1395, 1, 0, 0],
[1499, 612, 0, 1396, 1, 0, 0],
[1500, 612, 0, 1397, 1, 0, 0],
[1502, 612, 0, 1398, 1, 0, 0],
[1503, 612, 0, 1401, 1, 0, 0],
[1506, 612, 0, 1404, 1, 0, 0],
[1505, 612, 0, 1405, 1, 0, 0],
[1507, 612, 0, 1406, 1, 0, 0],
[1509, 612, 0, 1410, 1, 0, 0],
[1511, 555, 0, 1411, 1, 0, 0],
[1512, 555, 0, 1412, 1, 0, 0],
[1513, 612, 0, 1413, 1, 0, 0],
[1515, 612, 0, 1415, 1, 0, 0],
[1516, 612, 0, 1416, 1, 0, 0],
[1517, 612, 0, 1417, 1, 0, 0],
[1523, 612, 0, 1427, 1, 0, 0],
[1524, 612, 0, 1428, 1, 0, 0],
[1525, 612, 0, 1430, 1, 0, 0],
[1529, 612, 0, 1444, 1, 0, 0],
[1530, 612, 0, 1445, 1, 0, 0],
[1531, 612, 0, 1452, 1, 0, 0],
[1532, 612, 0, 1453, 1, 0, 0],
[1534, 550, 0, 1455, 1, 0, 0],
[1535, 612, 0, 1456, 1, 0, 0],
[1536, 612, 0, 1457, 1, 0, 0],
[1537, 612, 0, 1458, 1, 0, 0],
[1545, 612, 0, 1464, 1, 0, 0],
[1553, 555, 0, 1470, 1, 0, 0],
[1558, 612, 0, 1481, 1, 0, 0],
[1559, 612, 0, 1483, 1, 0, 0],
[1560, 612, 0, 1487, 1, 0, 0],
[1563, 612, 0, 1491, 1, 0, 0],
[1564, 507, 0, 1493, 1, 0, 0],
[1570, 612, 0, 1500, 1, 0, 0],
[1572, 612, 0, 1505, 1, 0, 0],
[1573, 535, 0, 1513, 1, 0, 0],
[1574, 612, 0, 1515, 1, 0, 0],
[1575, 612, 0, 1516, 1, 0, 0],
[1578, 612, 0, 1519, 1, 0, 0],
[1579, 612, 0, 1521, 1, 0, 0],
[1580, 612, 0, 1524, 1, 0, 0],
[1584, 612, 0, 1529, 1, 0, 0],
[1583, 612, 0, 1531, 1, 0, 0],
[1588, 507, 0, 1542, 1, 0, 0],
[1593, 550, 0, 1551, 1, 0, 0],
[1597, 612, 0, 1558, 1, 0, 0],
[1581, 612, 0, 1563, 1, 0, 0],
[1600, 612, 0, 1565, 1, 0, 0],
[1601, 612, 0, 1566, 1, 0, 0],
[1611, 612, 0, 1578, 1, 0, 0],
[1613, 535, 0, 1581, 1, 0, 0],
[1618, 612, 0, 1586, 1, 0, 0],
[1620, 321, 0, 1589, 1, 0, 0],
[1624, 535, 0, 1593, 1, 0, 0],
[1625, 535, 0, 1594, 1, 0, 0],
[1626, 612, 0, 1595, 1, 0, 0],
[1632, 535, 0, 1603, 1, 0, 0],
[1633, 612, 0, 1604, 1, 0, 0],
[1638, 535, 0, 1608, 1, 0, 0],
[1639, 612, 0, 1609, 1, 0, 0],
[1640, 612, 0, 1610, 1, 0, 0],
[1641, 550, 0, 1611, 1, 0, 0],
[1642, 550, 0, 1612, 1, 0, 0],
[1646, 612, 0, 1618, 1, 0, 0],
[1650, 538, 0, 1621, 1, 0, 0],
[1651, 535, 0, 1622, 1, 0, 0],
[1652, 535, 0, 1623, 1, 0, 0],
[1653, 535, 0, 1624, 1, 0, 0],
[1546, 612, 0, 1627, 1, 0, 0],
[1655, 535, 0, 1629, 1, 0, 0],
[1658, 612, 0, 1632, 1, 0, 0],
[1663, 523, 0, 1640, 1, 0, 0],
[1664, 523, 0, 1641, 1, 0, 0],
[1674, 612, 0, 1651, 1, 0, 0],
[1675, 612, 0, 1654, 1, 0, 0],
[1678, 612, 0, 1656, 1, 0, 0],
[1682, 507, 0, 1659, 1, 0, 0],
[1683, 535, 0, 1661, 1, 0, 0],
[1684, 535, 0, 1662, 1, 0, 0],
[1685, 550, 0, 1663, 1, 0, 0],
[1686, 555, 0, 1664, 1, 0, 0],
[1687, 555, 0, 1665, 1, 0, 0],
[1688, 555, 0, 1666, 1, 0, 0],
[1689, 555, 0, 1667, 1, 0, 0],
[1690, 555, 0, 1668, 1, 0, 0],
[1692, 555, 0, 1670, 1, 0, 0],
[1693, 555, 0, 1671, 1, 0, 0],
[1694, 535, 0, 1672, 1, 0, 0],
[1696, 612, 0, 1673, 1, 0, 0],
[1697, 612, 0, 1674, 1, 0, 0],
[1698, 535, 0, 1675, 1, 0, 0],
[1699, 535, 0, 1676, 1, 0, 0],
[1700, 612, 0, 1677, 1, 0, 0],
[1703, 535, 0, 1682, 1, 0, 0],
[1704, 612, 0, 1683, 1, 0, 0],
[1705, 555, 0, 1684, 1, 0, 0],
[1707, 612, 0, 1686, 1, 0, 0],
[905, 507, 0, 1687, 1, 0, 0],
[1708, 555, 0, 1688, 1, 0, 0],
[1709, 612, 0, 1694, 1, 0, 0],
[1741, 612, 0, 1721, 1, 0, 0],
[1742, 507, 0, 1722, 1, 0, 0],
[1743, 507, 0, 1723, 1, 0, 0],
[1744, 507, 0, 1724, 1, 0, 0],
[1745, 507, 0, 1725, 1, 0, 0],
[1747, 507, 0, 1727, 1, 0, 0],
[1748, 612, 0, 1728, 1, 0, 0],
[1749, 612, 0, 1729, 1, 0, 0],
[1752, 612, 0, 1732, 1, 0, 0],
[1276, 440, 0, 619, 1, 0, 0],
[745, 440, 0, 555, 1, 0, 0],
[1754, 440, 0, 1734, 1, 0, 0],
[1757, 612, 0, 1737, 1, 0, 0],
[1758, 507, 0, 1738, 1, 0, 0],
[1759, 507, 0, 1739, 1, 0, 0],
[1764, 507, 0, 1744, 1, 0, 0],
[1767, 507, 0, 1747, 1, 0, 0],
[1768, 535, 0, 1749, 1, 0, 0],
[1773, 612, 0, 1753, 1, 0, 0],
[1769, 612, 0, 1755, 1, 0, 0],
[1778, 535, 0, 1757, 1, 0, 0],
[1777, 612, 0, 1758, 1, 0, 0],
[1779, 535, 0, 1759, 1, 0, 0],
[1780, 612, 0, 1761, 1, 0, 0],
[1781, 612, 0, 1762, 1, 0, 0],
[1782, 612, 0, 1766, 1, 0, 0],
[1783, 555, 0, 1767, 1, 0, 0],
[1786, 321, 0, 1769, 1, 0, 0],
[1787, 321, 0, 1770, 1, 0, 0],
[1788, 321, 0, 1772, 1, 0, 0],
[1791, 550, 0, 1777, 1, 0, 0],
[1795, 555, 0, 1781, 1, 0, 0],
[1801, 614, 0, 1786, 1, 0, 0],
[1800, 612, 0, 1787, 1, 0, 0],
[1804, 321, 0, 1791, 1, 0, 0],
[1806, 507, 0, 1796, 1, 0, 0],
[1807, 507, 0, 1797, 1, 0, 0],
[1808, 555, 0, 1798, 1, 0, 0],
[1809, 555, 0, 1799, 1, 0, 0],
[1812, 538, 0, 1803, 1, 0, 0],
[1158, 440, 0, 1019, 1, 0, 0],
[1816, 550, 0, 1807, 1, 0, 0],
[1817, 612, 0, 1808, 1, 0, 0],
[1818, 440, 0, 604, 1, 0, 0],
[1819, 535, 0, 1809, 1, 0, 0],
[1820, 538, 0, 1810, 1, 0, 0],
[1824, 535, 0, 1814, 1, 0, 0],
[1827, 550, 0, 1817, 1, 0, 0],
[1828, 550, 0, 1818, 1, 0, 0],
[1829, 612, 0, 1820, 1, 0, 0],
[1830, 321, 0, 1824, 1, 0, 0],
[1832, 321, 0, 1826, 1, 0, 0],
[1833, 612, 0, 1827, 1, 0, 0],
[1836, 550, 0, 1830, 1, 0, 0],
[1841, 550, 0, 1835, 1, 0, 0],
[1842, 550, 0, 1836, 1, 0, 0],
[1843, 555, 0, 1837, 1, 0, 0],
[1844, 555, 0, 1838, 1, 0, 0],
[1774, 612, 0, 1841, 1, 0, 0],
[1847, 555, 0, 1842, 1, 0, 0],
[1775, 612, 0, 1843, 1, 0, 0],
[1848, 550, 0, 1844, 1, 0, 0],
[1852, 555, 0, 1846, 1, 0, 0],
[1853, 555, 0, 1847, 1, 0, 0],
[1631, 612, 0, 1848, 1, 0, 0],
[1854, 535, 0, 1849, 1, 0, 0],
[1857, 321, 0, 1851, 1, 0, 0],
[1858, 523, 0, 1852, 1, 0, 0],
[1859, 523, 0, 1853, 1, 0, 0],
[1860, 555, 0, 1854, 1, 0, 0],
[1861, 550, 0, 1855, 1, 0, 0],
[1864, 612, 0, 1862, 1, 0, 0],
[1866, 612, 0, 1865, 1, 0, 0],
[1867, 550, 0, 1868, 1, 0, 0],
[1878, 614, 0, 1882, 1, 0, 0],
[1879, 507, 0, 1883, 1, 0, 0],
[1880, 555, 0, 1884, 1, 0, 0],
[1882, 507, 0, 1886, 1, 0, 0],
[1889, 614, 0, 1894, 1, 0, 0],
[1890, 507, 0, 1895, 1, 0, 0],
[1891, 507, 0, 1896, 1, 0, 0],
[1892, 555, 0, 1897, 1, 0, 0],
[1893, 555, 0, 1898, 1, 0, 0],
[1894, 555, 0, 1899, 1, 0, 0],
[1895, 555, 0, 1900, 1, 0, 0],
[1896, 555, 0, 1901, 1, 0, 0],
[1897, 555, 0, 1902, 1, 0, 0],
[1899, 555, 0, 1903, 1, 0, 0],
[1901, 535, 0, 1908, 1, 0, 0],
[1902, 535, 0, 1909, 1, 0, 0],
[1903, 535, 0, 1910, 1, 0, 0],
[1904, 535, 0, 1911, 1, 0, 0],
[1905, 535, 0, 1912, 1, 0, 0],
[1377, 555, 0, 1913, 1, 0, 0],
[1907, 612, 0, 1914, 1, 0, 0],
[1908, 612, 0, 1915, 1, 0, 0],
[1909, 612, 0, 1916, 1, 0, 0],
[1645, 612, 0, 1919, 1, 0, 0],
[1906, 535, 0, 1920, 1, 0, 0],
[1910, 535, 0, 1921, 1, 0, 0],
[1912, 612, 0, 1923, 1, 0, 0],
[1913, 507, 0, 1924, 1, 0, 0],
[1916, 612, 0, 1926, 1, 0, 0],
[1917, 612, 0, 1927, 1, 0, 0],
[1918, 321, 0, 1928, 1, 0, 0],
[1919, 550, 0, 1930, 1, 0, 0],
[1924, 612, 0, 1935, 1, 0, 0],
[1925, 535, 0, 1939, 1, 0, 0],
[1926, 523, 0, 1941, 1, 0, 0],
[1928, 555, 0, 1943, 1, 0, 0],
[1927, 555, 0, 1945, 1, 0, 0],
[1929, 612, 0, 1946, 1, 0, 0],
[1930, 612, 0, 1947, 1, 0, 0],
[1935, 550, 0, 1953, 1, 0, 0],
[1936, 321, 0, 1929, 1, 0, 0],
[1937, 612, 0, 1954, 1, 0, 0],
[1939, 555, 0, 1955, 1, 0, 0],
[1940, 321, 0, 1956, 1, 0, 0],
[1934, 538, 0, 1960, 1, 0, 0],
[1962, 507, 0, 1989, 1, 0, 0],
[1963, 507, 0, 1990, 1, 0, 0],
[1967, 612, 0, 1995, 1, 0, 0],
[1968, 612, 0, 1996, 1, 0, 0],
[1969, 612, 0, 1997, 1, 0, 0],
[1970, 550, 0, 1998, 1, 0, 0],
[1971, 612, 0, 1999, 1, 0, 0],
[1974, 535, 0, 2001, 1, 0, 0],
[1975, 535, 0, 2002, 1, 0, 0],
[1976, 612, 0, 2003, 1, 0, 0],
[1979, 321, 0, 2005, 1, 0, 0],
[1981, 321, 0, 2007, 1, 0, 0],
[1983, 555, 0, 2008, 1, 0, 0],
[1984, 535, 0, 2009, 1, 0, 0],
[1985, 535, 0, 2010, 1, 0, 0],
[1986, 535, 0, 2011, 1, 0, 0],
[1987, 555, 0, 2012, 1, 0, 0],
[1988, 555, 0, 2013, 1, 0, 0],
[1989, 555, 0, 2014, 1, 0, 0],
[1990, 555, 0, 2015, 1, 0, 0],
[1991, 535, 0, 2016, 1, 0, 0],
[1992, 612, 0, 2017, 1, 0, 0],
[1993, 523, 0, 2018, 1, 0, 0],
[1994, 523, 0, 2019, 1, 0, 0],
[1995, 550, 0, 2021, 1, 0, 0],
[1998, 612, 0, 2022, 1, 0, 0],
[1630, 612, 0, 2023, 1, 0, 0],
[1999, 550, 0, 2024, 1, 0, 0],
[2000, 550, 0, 2025, 1, 0, 0],
[2001, 550, 0, 2026, 1, 0, 0],
[2002, 550, 0, 2027, 1, 0, 0],
[2003, 550, 0, 2028, 1, 0, 0],
[2004, 550, 0, 2029, 1, 0, 0],
[2005, 523, 0, 2030, 1, 0, 0],
[2006, 550, 0, 2031, 1, 0, 0],
[2008, 614, 0, 2036, 1, 0, 0],
[2009, 507, 0, 2037, 1, 0, 0],
[1586, 507, 0, 1538, 1, 0, 0],
[2010, 550, 0, 2038, 1, 0, 0],
[2011, 550, 0, 2039, 1, 0, 0],
[2012, 550, 0, 2040, 1, 0, 0],
[2013, 507, 0, 2041, 1, 0, 0],
[887, 507, 0, 738, 1, 0, 0],
[2014, 550, 0, 2042, 1, 0, 0],
[2015, 507, 0, 2043, 1, 0, 0],
[2017, 550, 0, 2045, 1, 0, 0],
[2019, 507, 0, 2046, 1, 0, 0],
[2021, 550, 0, 2049, 1, 0, 0],
[2022, 507, 0, 2050, 1, 0, 0],
[2023, 550, 0, 2052, 1, 0, 0],
[2020, 507, 0, 2054, 1, 0, 0],
[2025, 507, 0, 2055, 1, 0, 0],
[2026, 550, 0, 2057, 1, 0, 0],
[2027, 550, 0, 2058, 1, 0, 0],
[2028, 555, 0, 2059, 1, 0, 0],
[2030, 321, 0, 2060, 1, 0, 0],
[2031, 507, 0, 2061, 1, 0, 0],
[2032, 507, 0, 2062, 1, 0, 0],
[2033, 550, 0, 2063, 1, 0, 0],
[2034, 321, 0, 2051, 1, 0, 0],
[2035, 550, 0, 2065, 1, 0, 0],
[2036, 550, 0, 2066, 1, 0, 0],
[1418, 614, 1, 1302, 1, 0, 0],
[552, 321, 0, 279, 1, 1, 654],
[1128, 538, 0, 987, 1, 1, 1567],
	];
    }

	public function getDependencies()
	{
		return array(FileFixtures::class, UserFixtures::class, ResourceFixtures::class);
    }
}
