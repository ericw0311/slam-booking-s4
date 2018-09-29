<?php
// App/DataFixtures/PlanificationPeriodFixtures.php

namespace App\DataFixtures;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use App\Entity\User;
use App\Entity\Planification;
use App\Entity\PlanificationPeriod;

class PlanificationPeriodFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
	foreach ($this->getData() as [$id, $userID, $planificationID, $beginningDate, $endDate, $type]) {
		$user = $this->getReference('user-1');
		$planification = $this->getReference('planification-'.$planificationID);
		$planificationPeriod = new PlanificationPeriod($user, $planification);
		if ($type == 3 or $type == 4) {
			$planificationPeriod->setBeginningDate(date_create_from_format('d/m/Y',$beginningDate));
		}
		if ($type == 2 or $type == 4) {
			$planificationPeriod->setEndDate(date_create_from_format('d/m/Y',$endDate));
		}
		$manager->persist($planificationPeriod);
		$manager->flush();
		$this->addReference('planificationHeader-'.$id, $planificationPeriod);
	}
    }

	
	private function getData(): array
    {
	return [
		// $data = [$id, $userID, $planificationID, $beginningDate, $endDate, $type]
[110, 1, 99, 'NULL', 'NULL', 1],
[111, 1, 100, 'NULL', 'NULL', 1],
[112, 1, 101, 'NULL', 'NULL', 1],
[113, 1, 102, 'NULL', 'NULL', 1],
[114, 1, 103, 'NULL', 'NULL', 1],
[122, 1, 111, 'NULL', '14/01/2014', 2],
[123, 1, 112, 'NULL', 'NULL', 1],
[194, 1, 177, 'NULL', 'NULL', 1],
[225, 1, 207, 'NULL', 'NULL', 1],
[264, 1, 244, 'NULL', '31/03/2017', 2],
[265, 1, 245, 'NULL', 'NULL', 1],
[267, 1, 247, 'NULL', '07/11/2018', 2],
[268, 1, 248, 'NULL', 'NULL', 1],
[269, 1, 249, 'NULL', 'NULL', 1],
[270, 1, 250, 'NULL', 'NULL', 1],
[271, 1, 251, 'NULL', 'NULL', 1],
[272, 1, 252, 'NULL', '11/12/2015', 2],
[273, 1, 253, 'NULL', '12/01/2018', 2],
[276, 1, 256, 'NULL', '10/11/2016', 2],
[277, 1, 257, 'NULL', 'NULL', 1],
[278, 1, 258, 'NULL', 'NULL', 1],
[280, 1, 260, 'NULL', 'NULL', 1],
[281, 1, 261, 'NULL', 'NULL', 1],
[282, 1, 262, 'NULL', 'NULL', 1],
[283, 1, 263, 'NULL', '03/11/2016', 2],
[284, 1, 264, 'NULL', 'NULL', 1],
[285, 1, 265, 'NULL', 'NULL', 1],
[286, 1, 266, 'NULL', '30/04/2015', 2],
[288, 1, 268, 'NULL', 'NULL', 1],
[289, 1, 269, 'NULL', 'NULL', 1],
[296, 1, 276, 'NULL', '23/03/2017', 2],
[304, 1, 284, 'NULL', 'NULL', 1],
[308, 1, 288, 'NULL', '26/02/2015', 2],
[311, 1, 291, 'NULL', 'NULL', 1],
[312, 1, 292, 'NULL', 'NULL', 1],
[313, 1, 293, 'NULL', '22/09/2014', 2],
[314, 1, 294, 'NULL', 'NULL', 1],
[315, 1, 295, 'NULL', 'NULL', 1],
[316, 1, 296, 'NULL', 'NULL', 1],
[317, 1, 297, 'NULL', 'NULL', 1],
[326, 1, 306, 'NULL', 'NULL', 1],
[328, 1, 308, 'NULL', 'NULL', 1],
[329, 1, 309, 'NULL', 'NULL', 1],
[330, 1, 310, 'NULL', '27/11/2014', 2],
[331, 1, 311, 'NULL', '23/03/2017', 2],
[332, 1, 312, 'NULL', 'NULL', 1],
[333, 1, 313, 'NULL', 'NULL', 1],
[334, 1, 314, 'NULL', '14/04/2017', 2],
[335, 1, 315, 'NULL', 'NULL', 1],
[336, 1, 316, 'NULL', '16/01/2015', 2],
[337, 1, 317, 'NULL', 'NULL', 1],
[339, 1, 319, 'NULL', 'NULL', 1],
[360, 1, 338, 'NULL', 'NULL', 1],
[361, 1, 339, 'NULL', 'NULL', 1],
[362, 1, 340, 'NULL', 'NULL', 1],
[384, 1, 361, 'NULL', 'NULL', 1],
[385, 1, 310, '28/11/2014', 'NULL', 3],
[399, 1, 316, '17/01/2015', '29/02/2016', 4],
[403, 1, 377, 'NULL', '21/06/2015', 2],
[416, 1, 390, 'NULL', 'NULL', 1],
[417, 1, 391, 'NULL', 'NULL', 1],
[418, 1, 392, 'NULL', 'NULL', 1],
[423, 1, 396, 'NULL', '24/02/2015', 2],
[424, 1, 396, '25/02/2015', '27/06/2015', 4],
[426, 1, 398, 'NULL', '23/04/2015', 2],
[429, 1, 401, 'NULL', '27/01/2017', 2],
[436, 1, 408, 'NULL', '04/10/2017', 2],
[437, 1, 409, 'NULL', '25/06/2015', 2],
[451, 1, 421, 'NULL', '03/07/2015', 2],
[452, 1, 396, '28/06/2015', 'NULL', 3],
[457, 1, 424, 'NULL', 'NULL', 1],
[463, 1, 398, '24/04/2015', '23/06/2015', 4],
[473, 1, 437, 'NULL', 'NULL', 1],
[475, 1, 409, '26/06/2015', '23/09/2016', 4],
[476, 1, 377, '22/06/2015', '30/12/2015', 4],
[478, 1, 440, 'NULL', 'NULL', 1],
[492, 1, 421, '04/07/2015', '22/11/2017', 4],
[523, 1, 477, 'NULL', '06/07/2017', 2],
[524, 1, 478, 'NULL', '10/10/2017', 2],
[526, 1, 480, 'NULL', 'NULL', 1],
[528, 1, 482, 'NULL', '19/11/2015', 2],
[529, 1, 482, '20/11/2015', '08/03/2016', 4],
[536, 1, 488, 'NULL', 'NULL', 1],
[540, 1, 491, 'NULL', 'NULL', 1],
[541, 1, 492, 'NULL', 'NULL', 1],
[542, 1, 493, 'NULL', 'NULL', 1],
[543, 1, 494, 'NULL', 'NULL', 1],
[544, 1, 495, 'NULL', 'NULL', 1],
[552, 1, 377, '31/12/2015', '04/10/2016', 4],
[558, 1, 508, 'NULL', 'NULL', 1],
[578, 1, 525, 'NULL', 'NULL', 1],
[581, 1, 252, '12/12/2015', 'NULL', 3],
[591, 1, 532, 'NULL', 'NULL', 1],
[592, 1, 482, '09/03/2016', '07/04/2016', 4],
[597, 1, 482, '08/04/2016', '22/05/2016', 4],
[605, 1, 482, '23/05/2016', 'NULL', 3],
[609, 1, 543, 'NULL', '16/03/2018', 2],
[611, 1, 545, 'NULL', 'NULL', 1],
[617, 1, 550, 'NULL', '06/09/2016', 2],
[618, 1, 550, '07/09/2016', 'NULL', 3],
[619, 1, 551, 'NULL', 'NULL', 1],
[620, 1, 377, '05/10/2016', 'NULL', 3],
[621, 1, 409, '24/09/2016', '14/10/2017', 4],
[623, 1, 316, '01/03/2016', 'NULL', 3],
[624, 1, 263, '04/11/2016', 'NULL', 3],
[625, 1, 288, '27/02/2015', 'NULL', 3],
[631, 1, 401, '28/01/2017', 'NULL', 3],
[643, 1, 276, '24/03/2017', 'NULL', 3],
[644, 1, 266, '01/05/2015', 'NULL', 3],
[646, 1, 314, '15/04/2017', 'NULL', 3],
[647, 1, 561, 'NULL', 'NULL', 1],
[648, 1, 244, '01/04/2017', 'NULL', 3],
[655, 1, 477, '07/07/2017', '09/11/2018', 4],
[657, 1, 569, 'NULL', 'NULL', 1],
[667, 1, 576, 'NULL', '22/09/2017', 2],
[668, 1, 576, '23/09/2017', 'NULL', 3],
[669, 1, 478, '11/10/2017', 'NULL', 3],
[670, 1, 409, '15/10/2017', '24/10/2017', 4],
[672, 1, 578, 'NULL', 'NULL', 1],
[673, 1, 579, 'NULL', 'NULL', 1],
[674, 1, 580, 'NULL', 'NULL', 1],
[676, 1, 421, '23/11/2017', 'NULL', 3],
[678, 1, 583, 'NULL', 'NULL', 1],
[679, 1, 256, '11/11/2016', 'NULL', 3],
[680, 1, 584, 'NULL', 'NULL', 1],
[681, 1, 585, 'NULL', 'NULL', 1],
[686, 1, 409, '25/10/2017', 'NULL', 3],
[689, 1, 590, 'NULL', 'NULL', 1],
[704, 1, 604, 'NULL', 'NULL', 1],
[708, 1, 606, 'NULL', 'NULL', 1],
[717, 1, 293, '23/09/2014', 'NULL', 3],
[718, 1, 253, '13/01/2018', 'NULL', 3],
[725, 1, 311, '24/03/2017', 'NULL', 3],
[726, 1, 609, 'NULL', 'NULL', 1],
[727, 1, 610, 'NULL', 'NULL', 1],
[728, 1, 477, '10/11/2018', 'NULL', 3],
	];
    }
	
	public function getDependencies()
	{
		return array(UserFixtures::class, PlanificationFixtures::class);
    }
}
