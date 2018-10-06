<?php
// App/DataFixtures/Booking_691Fixtures.php
namespace App\DataFixtures;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use App\Entity\File;
use App\Entity\User;
use App\Entity\Label;
use App\Entity\Resource;
use App\Entity\Planification;
use App\Entity\Booking;
use App\Entity\BookingLabel;

class Booking_691Fixtures extends Fixture implements DependentFixtureInterface
{
	public function load(ObjectManager $manager)
	{
	foreach ($this->getData() as [$id, $userID, $fileID, $planificationID, $resourceID, $activityID, $boNote, $stNote, $beginningDatetime, $endDatetime]) {
		$user = $this->getReference('user-1');
		$file = $this->getReference('file-'.$fileID);
		$planification = $this->getReference('planification-'.$planificationID);
		$resource = $this->getReference('resource-'.$resourceID);
		$booking = new Booking($user, $file, $planification, $resource);
		if ($boNote > 0) {
			$booking->setNote($stNote);
		}
		$booking->setBeginningDate(date_create_from_format('Y-m-d H:i:s',$beginningDatetime));
		$booking->setEndDate(date_create_from_format('Y-m-d H:i:s',$endDatetime));
		$manager->persist($booking);
		$manager->flush();
		$this->addReference('bookingHeader-'.$id, $booking); // L'ID est un bookingHeaderID dans le modèle Slam-Data
		if ($activityID > 0) {
			$label = $this->getReference('activity-'.$activityID);
			$bookingLabel = new BookingLabel($user, $booking, $label);
			$bookingLabel->setOrder(1);
			$manager->persist($bookingLabel);
			$manager->flush();
		}
	}
    }

	private function getData(): array
    {
	return [
// $data = [$id, $userID, $fileID, $planificationID, $resourceID, $activityID, $boNote, $stNote, $beginningDatetime, $endDatetime]
[12011, 1, 691, 545, 2393, 0, 1, 'Call ADLP', '2016-06-07 14:30:00', '2016-06-07 15:30:00'],
[12017, 1, 691, 545, 2393, 0, 0, '', '2016-06-09 10:00:00', '2016-06-09 10:30:00'],
[12048, 1, 691, 545, 2393, 0, 1, 'SACD', '2016-06-09 15:00:00', '2016-06-09 15:30:00'],
[12091, 1, 691, 545, 2393, 0, 1, 'Call SOMFY', '2016-06-13 10:00:00', '2016-06-13 11:00:00'],
[12232, 1, 691, 545, 2393, 0, 1, 'Mickael Gomes pour SACD', '2016-06-21 16:30:00', '2016-06-21 17:00:00'],
[12233, 1, 691, 545, 2393, 0, 1, 'Mickael pour la SACD', '2016-06-21 17:00:00', '2016-06-21 17:30:00'],
[12407, 1, 691, 545, 2393, 0, 0, '', '2016-07-08 16:00:00', '2016-07-08 16:30:00'],
[12408, 1, 691, 545, 2393, 0, 0, '', '2016-07-08 16:30:00', '2016-07-08 17:00:00'],
[12446, 1, 691, 545, 2393, 0, 1, 'CCI Nord de France  Laurent - Corinne', '2016-07-19 11:00:00', '2016-07-19 12:00:00'],
[12933, 1, 691, 545, 2393, 0, 1, 'Mickael Gomes KPC_FORTUNEO', '2016-08-30 17:30:00', '2016-08-30 18:00:00'],
[13097, 1, 691, 545, 2393, 0, 1, 'ONP', '2016-09-07 11:30:00', '2016-09-07 12:30:00'],
[13098, 1, 691, 545, 2393, 0, 0, '', '2016-09-07 14:00:00', '2016-09-07 15:00:00'],
[13285, 1, 691, 545, 2393, 0, 1, 'Comite projet N2 ONP', '2016-09-16 10:30:00', '2016-09-16 11:30:00'],
[13287, 1, 691, 545, 2393, 0, 1, 'Comite projet ONP', '2016-09-23 10:30:00', '2016-09-23 11:30:00'],
[13288, 1, 691, 545, 2393, 0, 1, 'ONP - Coproj', '2016-09-30 10:30:00', '2016-09-30 11:30:00'],
[13712, 1, 691, 545, 2393, 0, 1, 'ONP', '2016-10-10 16:00:00', '2016-10-10 17:30:00'],
[13713, 1, 691, 545, 2393, 0, 1, 'ONP', '2016-10-17 16:00:00', '2016-10-17 19:00:00'],
[13714, 1, 691, 545, 2393, 0, 1, 'ONP', '2016-10-28 10:30:00', '2016-10-28 11:30:00'],
[13716, 1, 691, 545, 2393, 0, 1, 'RESA ASSU2000', '2016-10-14 10:30:00', '2016-10-14 11:30:00'],
[14816, 1, 691, 545, 2393, 0, 0, '', '2016-12-05 09:30:00', '2016-12-05 10:00:00'],
[14817, 1, 691, 545, 2393, 0, 0, '', '2016-12-05 10:00:00', '2016-12-05 10:30:00'],
[14822, 1, 691, 545, 2393, 0, 1, 'Gilles + emmanuel + TCL', '2016-12-06 10:00:00', '2016-12-06 11:00:00'],
[14823, 1, 691, 545, 2393, 0, 1, 'GILLES', '2016-12-05 15:30:00', '2016-12-05 17:00:00'],
[15010, 1, 691, 545, 2393, 0, 1, 'LE POINT', '2016-12-16 14:00:00', '2016-12-16 14:30:00'],
[15011, 1, 691, 545, 2393, 0, 1, 'LE POINT', '2016-12-19 12:00:00', '2016-12-19 12:30:00'],
[15012, 1, 691, 545, 2393, 0, 1, 'LE POINT', '2016-12-19 17:00:00', '2016-12-19 17:30:00'],
[15013, 1, 691, 545, 2393, 0, 1, 'LE POINT', '2016-12-20 12:00:00', '2016-12-20 12:30:00'],
[15014, 1, 691, 545, 2393, 0, 1, 'LE POINT', '2016-12-20 17:00:00', '2016-12-20 17:30:00'],
[15015, 1, 691, 545, 2393, 0, 1, 'LE POINT', '2016-12-21 12:00:00', '2016-12-21 12:30:00'],
[15016, 1, 691, 545, 2393, 0, 1, 'LE POINT', '2016-12-21 17:00:00', '2016-12-21 17:30:00'],
[15017, 1, 691, 545, 2393, 0, 1, 'LE POINT', '2016-12-22 17:00:00', '2016-12-22 17:30:00'],
[15018, 1, 691, 545, 2393, 0, 1, 'LE POINT', '2016-12-23 17:00:00', '2016-12-23 17:30:00'],
[16081, 1, 691, 545, 2393, 0, 0, '', '2017-02-06 14:00:00', '2017-02-06 15:30:00'],
[16244, 1, 691, 545, 2393, 0, 1, 'TCL Corinne', '2017-02-16 11:30:00', '2017-02-16 12:00:00'],
[16387, 1, 691, 545, 2393, 0, 1, 'iman', '2017-02-21 14:00:00', '2017-02-21 16:00:00'],
[16389, 1, 691, 545, 2393, 0, 1, 'Point Fortuneo', '2017-02-28 17:30:00', '2017-02-28 18:00:00'],
[16784, 1, 691, 545, 2393, 0, 1, 'Laurent MRS', '2017-03-16 16:00:00', '2017-03-16 20:00:00'],
[17013, 1, 691, 545, 2393, 0, 1, 'Mickael Gomes pour SACD', '2017-03-29 10:00:00', '2017-03-29 10:30:00'],
[17223, 1, 691, 545, 2393, 0, 1, 'Paula', '2017-04-11 17:00:00', '2017-04-11 18:00:00'],
[17311, 1, 691, 545, 2393, 0, 1, 'KPC Marseille - Laurent', '2017-04-12 17:00:00', '2017-04-12 19:00:00'],
[17821, 1, 691, 545, 2393, 0, 1, 'Resa Emmanuel pour MTP', '2017-05-19 15:30:00', '2017-05-19 16:00:00'],
[18341, 1, 691, 545, 2393, 0, 1, 'Laurent SIROT KPC AIX', '2017-06-20 16:00:00', '2017-06-20 19:00:00'],
[20803, 1, 691, 545, 2393, 0, 0, '', '2017-11-14 11:00:00', '2017-11-14 12:30:00'],
[20846, 1, 691, 545, 2393, 0, 1, 'Laurent', '2017-11-15 14:00:00', '2017-11-15 14:30:00'],
[22296, 1, 691, 545, 2393, 0, 0, '', '2018-02-14 14:00:00', '2018-02-14 16:00:00'],
	];
    }

	public function getDependencies()
	{
		return array(FileFixtures::class, UserFixtures::class, ResourceFixtures::class, PlanificationFixtures::class, LabelFixtures::class);
    }
}
