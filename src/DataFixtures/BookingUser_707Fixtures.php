<?php
// App/DataFixtures/BookingUser_707Fixtures.php
namespace App\DataFixtures;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use App\Entity\File;
use App\Entity\User;
use App\Entity\UserFile;
use App\Entity\Booking;
use App\Entity\BookingUser;

class BookingUser_707Fixtures extends Fixture implements DependentFixtureInterface
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
[707, 14185, 1, 1638, 1],
[707, 14260, 1, 1702, 1],
[707, 14309, 1, 1635, 1],
[707, 14310, 1, 1635, 1],
[707, 14311, 1, 1635, 1],
[707, 14312, 1, 1702, 1],
[707, 14313, 1, 1635, 1],
[707, 14359, 1, 1702, 1],
[707, 14361, 1, 1702, 1],
[707, 14633, 1, 1635, 1],
[707, 14634, 1, 1720, 1],
[707, 14696, 1, 1638, 1],
[707, 14697, 1, 1638, 1],
[707, 14698, 1, 1638, 1],
[707, 14699, 1, 1638, 1],
[707, 14964, 1, 1635, 1],
[707, 15031, 1, 1635, 1],
[707, 15032, 1, 1635, 1],
[707, 15033, 1, 1635, 1],
[707, 15034, 1, 1635, 1],
[707, 15035, 1, 1635, 1],
[707, 15036, 1, 1635, 1],
[707, 15037, 1, 1635, 1],
[707, 15038, 1, 1635, 1],
[707, 15056, 1, 1717, 1],
[707, 15057, 1, 1720, 1],
[707, 15160, 1, 1635, 1],
[707, 15161, 1, 1635, 1],
[707, 15352, 1, 1635, 1],
[707, 15353, 1, 1635, 1],
[707, 17310, 1, 1720, 1],
[707, 18070, 1, 1647, 1],
[707, 18071, 1, 1647, 1],
[707, 18481, 1, 1702, 1],
[707, 18542, 1, 1735, 1],
[707, 18543, 1, 1735, 1],
[707, 18593, 1, 1635, 1],
[707, 18594, 1, 1635, 1],
[707, 18642, 1, 1635, 1],
[707, 18643, 1, 1635, 1],
[707, 18644, 1, 1635, 1],
[707, 20538, 1, 1635, 1],
[707, 20539, 1, 1720, 1],
[707, 21611, 1, 1635, 1],
[707, 22820, 1, 1706, 1],
[707, 22821, 1, 1706, 1],
[707, 22961, 1, 1635, 1],
[707, 22962, 1, 1635, 1],
[707, 22963, 1, 1635, 1],
[707, 22964, 1, 1635, 1],
[707, 22965, 1, 1635, 1],
[707, 23355, 1, 1635, 1],
[707, 23356, 1, 1635, 1],
[707, 23357, 1, 1635, 1],
[707, 24101, 1, 1635, 1],
[707, 24102, 1, 1635, 1],
[707, 24103, 1, 1720, 1],
[707, 24104, 1, 1720, 1],
[707, 24105, 1, 1635, 1],
[707, 24106, 1, 1635, 1],
[707, 24109, 1, 1635, 1],
[707, 24110, 1, 1635, 1],
[707, 24279, 1, 1635, 1],
[707, 24280, 1, 1635, 1],
[707, 24301, 1, 1720, 1],
[707, 24426, 1, 1635, 1],
[707, 24427, 1, 1648, 1],
[707, 24428, 1, 1635, 1],
[707, 25231, 1, 1635, 1],
[707, 25326, 1, 1635, 1],
	];
    }
	
	public function getDependencies()
	{
		return array(FileFixtures::class, UserFixtures::class, UserFileFixtures::class, Booking_707Fixtures::class);
    }
}