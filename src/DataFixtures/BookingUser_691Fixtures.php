<?php
// App/DataFixtures/BookingUser_691Fixtures.php
namespace App\DataFixtures;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use App\Entity\File;
use App\Entity\User;
use App\Entity\UserFile;
use App\Entity\Booking;
use App\Entity\BookingUser;

class BookingUser_691Fixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
	foreach ($this->getData() as [$fileID, $bookingHeaderID, $userID, $userFileID, $oorder]) {
	
		$user = $this->getReference('user-1');
		$file = $this->getReference('file-'.$fileID);
		$booking = $this->getReference('bookingHeader-'.$bookingHeaderID);
		$userFile = $this->getReference('userFile-'.$userFileID);
		$bookingUser = new bookingUser($user, $booking, $userFile);
		$bookingUser->setOrder($oorder);
		$manager->persist($bookingUser);
		$manager->flush();
	}
    }
	private function getData(): array
    {
	return [
// $data = [$fileID, $bookingHeaderID, $userID, $userFileID, $oorder]
[691, 12011, 1, 1573, 1],
[691, 12017, 1, 1575, 1],
[691, 12048, 1, 1575, 1],
[691, 12091, 1, 1575, 1],
[691, 12232, 1, 1575, 1],
[691, 12233, 1, 1575, 1],
[691, 12407, 1, 1575, 1],
[691, 12408, 1, 1575, 1],
[691, 12446, 1, 1573, 1],
[691, 12933, 1, 1575, 1],
[691, 13097, 1, 1573, 1],
[691, 13098, 1, 1573, 1],
[691, 13285, 1, 1573, 1],
[691, 13287, 1, 1573, 1],
[691, 13288, 1, 1573, 1],
[691, 13712, 1, 1573, 1],
[691, 13713, 1, 1573, 1],
[691, 13714, 1, 1573, 1],
[691, 13716, 1, 1681, 1],
[691, 14816, 1, 1575, 1],
[691, 14817, 1, 1575, 1],
[691, 14822, 1, 1575, 1],
[691, 14823, 1, 1575, 1],
[691, 15010, 1, 1575, 1],
[691, 15011, 1, 1575, 1],
[691, 15012, 1, 1575, 1],
[691, 15013, 1, 1575, 1],
[691, 15014, 1, 1575, 1],
[691, 15015, 1, 1575, 1],
[691, 15016, 1, 1575, 1],
[691, 15017, 1, 1575, 1],
[691, 15018, 1, 1575, 1],
[691, 16081, 1, 1573, 1],
[691, 16244, 1, 1573, 1],
[691, 16387, 1, 1573, 1],
[691, 16389, 1, 1575, 1],
[691, 16784, 1, 1573, 1],
[691, 17013, 1, 1575, 1],
[691, 17223, 1, 1575, 1],
[691, 17311, 1, 1573, 1],
[691, 17821, 1, 1575, 1],
[691, 18341, 1, 1573, 1],
[691, 20803, 1, 1681, 1],
[691, 20846, 1, 1573, 1],
[691, 22296, 1, 1575, 1],
	];
    }
	
	public function getDependencies()
	{
		return array(FileFixtures::class, UserFixtures::class, UserFileFixtures::class, Booking_691Fixtures::class);
    }
}