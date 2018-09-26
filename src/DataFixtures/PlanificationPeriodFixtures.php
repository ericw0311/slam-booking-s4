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
	foreach ($this->getData_NoDate() as [$id, $userID, $planificationID]) {

		$user = $this->getReference('user-1');
		$planification = $this->getReference('planification-'.$planificationID);

		$planificationPeriod = new PlanificationPeriod($user, $planification);

		$manager->persist($planificationPeriod);
		$manager->flush();
		$this->addReference('planificationHeader-'.$id, $planificationPeriod);
	}

	foreach ($this->getData_BeginningDate() as [$id, $userID, $planificationID, $beginningDate]) {

		$user = $this->getReference('user-1');
		$planification = $this->getReference('planification-'.$planificationID);

		$planificationPeriod = new PlanificationPeriod($user, $planification);
		$planificationPeriod->setBeginningDate(date_create_from_format('d/m/Y',$beginningDate));

		$manager->persist($planificationPeriod);
		$manager->flush();
		$this->addReference('planificationHeader-'.$id, $planificationPeriod);
	}

	foreach ($this->getData_EndDate() as [$id, $userID, $planificationID, $endDate]) {

		$user = $this->getReference('user-1');
		$planification = $this->getReference('planification-'.$planificationID);

		$planificationPeriod = new PlanificationPeriod($user, $planification);
		$planificationPeriod->setEndDate(date_create_from_format('d/m/Y',$endDate));

		$manager->persist($planificationPeriod);
		$manager->flush();
		$this->addReference('planificationHeader-'.$id, $planificationPeriod);
	}

	foreach ($this->getData_BeginningDate_EndDate() as [$id, $userID, $planificationID, $beginningDate, $endDate]) {

		$user = $this->getReference('user-1');
		$planification = $this->getReference('planification-'.$planificationID);

		$planificationPeriod = new PlanificationPeriod($user, $planification);
		$planificationPeriod->setBeginningDate(date_create_from_format('d/m/Y',$beginningDate));
		$planificationPeriod->setEndDate(date_create_from_format('d/m/Y',$endDate));

		$manager->persist($planificationPeriod);
		$manager->flush();
		$this->addReference('planificationHeader-'.$id, $planificationPeriod);
	}
    }

	private function getData_NoDate(): array
    {
	return [
		// $data = [$id, $userID, $planificationID]
[110, 1, 99],
[111, 1, 100],
[112, 1, 101],
[113, 1, 102],
[114, 1, 103],
[123, 1, 112],
[194, 1, 177],
[225, 1, 207],
[265, 1, 245],
[268, 1, 248],
[269, 1, 249],
[270, 1, 250],
[271, 1, 251],
[277, 1, 257],
[278, 1, 258],
[280, 1, 260],
[281, 1, 261],
[282, 1, 262],
[284, 1, 264],
[285, 1, 265],
[288, 1, 268],
[289, 1, 269],
[304, 1, 284],
[311, 1, 291],
[312, 1, 292],
[314, 1, 294],
[315, 1, 295],
[316, 1, 296],
[317, 1, 297],
[326, 1, 306],
[328, 1, 308],
[329, 1, 309],
[332, 1, 312],
[333, 1, 313],
[335, 1, 315],
[337, 1, 317],
[339, 1, 319],
[360, 1, 338],
[361, 1, 339],
[362, 1, 340],
[384, 1, 361],
[416, 1, 390],
[417, 1, 391],
[418, 1, 392],
[457, 1, 424],
[473, 1, 437],
[478, 1, 440],
[526, 1, 480],
[536, 1, 488],
[540, 1, 491],
[541, 1, 492],
[542, 1, 493],
[543, 1, 494],
[544, 1, 495],
[558, 1, 508],
[578, 1, 525],
[591, 1, 532],
[611, 1, 545],
[619, 1, 551],
[647, 1, 561],
[657, 1, 569],
[672, 1, 578],
[673, 1, 579],
[674, 1, 580],
[678, 1, 583],
[680, 1, 584],
[681, 1, 585],
[689, 1, 590],
[704, 1, 604],
[708, 1, 606],
[726, 1, 609],
[727, 1, 610],
	];
    }
	
	private function getData_BeginningDate(): array
    {
	return [
		// $data = [$id, $userID, $planificationID, $beginningDate]
[385, 1, 310, '28/11/2014'],
[452, 1, 396, '28/06/2015'],
[581, 1, 252, '12/12/2015'],
[605, 1, 482, '23/05/2016'],
[618, 1, 550, '07/09/2016'],
[620, 1, 377, '05/10/2016'],
[623, 1, 316, '01/03/2016'],
[624, 1, 263, '04/11/2016'],
[625, 1, 288, '27/02/2015'],
[631, 1, 401, '28/01/2017'],
[643, 1, 276, '24/03/2017'],
[644, 1, 266, '01/05/2015'],
[646, 1, 314, '15/04/2017'],
[648, 1, 244, '01/04/2017'],
[668, 1, 576, '23/09/2017'],
[669, 1, 478, '11/10/2017'],
[676, 1, 421, '23/11/2017'],
[679, 1, 256, '11/11/2016'],
[686, 1, 409, '25/10/2017'],
[717, 1, 293, '23/09/2014'],
[718, 1, 253, '13/01/2018'],
[725, 1, 311, '24/03/2017'],
[728, 1, 477, '10/11/2018'],
	];
    }
	
	private function getData_EndDate(): array
    {
	return [
		// $data = [$id, $userID, $planificationID, $endDate]
[122, 1, 111, '14/01/2014'],
[264, 1, 244, '31/03/2017'],
[267, 1, 247, '07/11/2018'],
[272, 1, 252, '11/12/2015'],
[273, 1, 253, '12/01/2018'],
[276, 1, 256, '10/11/2016'],
[283, 1, 263, '03/11/2016'],
[286, 1, 266, '30/04/2015'],
[296, 1, 276, '23/03/2017'],
[308, 1, 288, '26/02/2015'],
[313, 1, 293, '22/09/2014'],
[330, 1, 310, '27/11/2014'],
[331, 1, 311, '23/03/2017'],
[334, 1, 314, '14/04/2017'],
[336, 1, 316, '16/01/2015'],
[403, 1, 377, '21/06/2015'],
[423, 1, 396, '24/02/2015'],
[426, 1, 398, '23/04/2015'],
[429, 1, 401, '27/01/2017'],
[436, 1, 408, '04/10/2017'],
[437, 1, 409, '25/06/2015'],
[451, 1, 421, '03/07/2015'],
[523, 1, 477, '06/07/2017'],
[524, 1, 478, '10/10/2017'],
[528, 1, 482, '19/11/2015'],
[609, 1, 543, '16/03/2018'],
[617, 1, 550, '06/09/2016'],
[667, 1, 576, '22/09/2017'],
	];
    }
	
	private function getData_BeginningDate_EndDate(): array
    {
	return [
		// $data = [$id, $userID, $planificationID, $beginningDate, $endDate]
[399, 1, 316, '17/01/2015', '29/02/2016'],
[424, 1, 396, '25/02/2015', '27/06/2015'],
[463, 1, 398, '24/04/2015', '23/06/2015'],
[475, 1, 409, '26/06/2015', '23/09/2016'],
[476, 1, 377, '22/06/2015', '30/12/2015'],
[492, 1, 421, '04/07/2015', '22/11/2017'],
[529, 1, 482, '20/11/2015', '08/03/2016'],
[552, 1, 377, '31/12/2015', '04/10/2016'],
[592, 1, 482, '09/03/2016', '07/04/2016'],
[597, 1, 482, '08/04/2016', '22/05/2016'],
[621, 1, 409, '24/09/2016', '14/10/2017'],
[655, 1, 477, '07/07/2017', '09/11/2018'],
[670, 1, 409, '15/10/2017', '24/10/2017'],
	];
    }
	
	public function getDependencies()
	{
		return array(UserFixtures::class, PlanificationFixtures::class);
    }
}
