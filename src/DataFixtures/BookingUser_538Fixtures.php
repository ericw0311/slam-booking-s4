<?php
// App/DataFixtures/BookingUser_538Fixtures.php
namespace App\DataFixtures;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use App\Entity\File;
use App\Entity\User;
use App\Entity\UserFile;
use App\Entity\Booking;
use App\Entity\BookingUser;

class BookingUser_538Fixtures extends Fixture implements DependentFixtureInterface
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
[538, 4609, 1, 987, 1],
[538, 4616, 1, 955, 1],
[538, 4618, 1, 953, 1],
[538, 4654, 1, 987, 1],
[538, 4657, 1, 987, 1],
[538, 4658, 1, 987, 1],
[538, 4662, 1, 987, 1],
[538, 4682, 1, 955, 1],
[538, 4700, 1, 955, 1],
[538, 4704, 1, 978, 1],
[538, 4705, 1, 978, 1],
[538, 4713, 1, 955, 1],
[538, 4725, 1, 955, 1],
[538, 4734, 1, 955, 1],
[538, 4736, 1, 978, 1],
[538, 4737, 1, 978, 1],
[538, 4746, 1, 955, 1],
[538, 4779, 1, 984, 1],
[538, 4831, 1, 955, 1],
[538, 4888, 1, 979, 1],
[538, 4909, 1, 987, 1],
[538, 4923, 1, 978, 1],
[538, 4924, 1, 978, 1],
[538, 4925, 1, 978, 1],
[538, 4941, 1, 978, 1],
[538, 4959, 1, 968, 1],
[538, 4970, 1, 979, 1],
[538, 5012, 1, 987, 1],
[538, 5014, 1, 987, 1],
[538, 5016, 1, 987, 1],
[538, 5020, 1, 978, 1],
[538, 5037, 1, 987, 1],
[538, 5141, 1, 978, 1],
[538, 5158, 1, 987, 1],
[538, 5272, 1, 987, 1],
[538, 5278, 1, 965, 1],
[538, 5304, 1, 954, 1],
[538, 5305, 1, 954, 1],
[538, 5320, 1, 978, 1],
[538, 5329, 1, 987, 1],
[538, 5392, 1, 987, 1],
[538, 5396, 1, 978, 1],
[538, 5397, 1, 978, 1],
[538, 5398, 1, 978, 1],
[538, 5407, 1, 987, 1],
[538, 5454, 1, 987, 1],
[538, 5457, 1, 987, 1],
[538, 5458, 1, 987, 1],
[538, 5459, 1, 987, 1],
[538, 5460, 1, 987, 1],
[538, 5472, 1, 987, 1],
[538, 5473, 1, 987, 1],
[538, 5474, 1, 987, 1],
[538, 5478, 1, 984, 1],
[538, 5482, 1, 962, 1],
[538, 5492, 1, 984, 1],
[538, 5539, 1, 965, 1],
[538, 5540, 1, 965, 1],
[538, 5541, 1, 965, 1],
[538, 5542, 1, 965, 1],
[538, 5565, 1, 987, 1],
[538, 5566, 1, 987, 1],
[538, 5567, 1, 987, 1],
[538, 5584, 1, 987, 1],
[538, 5600, 1, 987, 1],
[538, 5642, 1, 987, 1],
[538, 5643, 1, 987, 1],
[538, 5644, 1, 987, 1],
[538, 5656, 1, 978, 1],
[538, 5691, 1, 978, 1],
[538, 5692, 1, 990, 1],
[538, 5693, 1, 963, 1],
[538, 5694, 1, 963, 1],
[538, 5695, 1, 963, 1],
[538, 5706, 1, 968, 1],
[538, 5708, 1, 979, 1],
[538, 5739, 1, 979, 1],
[538, 5774, 1, 979, 1],
[538, 5782, 1, 990, 1],
[538, 5783, 1, 987, 1],
[538, 5801, 1, 979, 1],
[538, 5803, 1, 987, 1],
[538, 5810, 1, 990, 1],
[538, 5822, 1, 990, 1],
[538, 5844, 1, 987, 1],
[538, 5845, 1, 987, 1],
[538, 5846, 1, 987, 1],
[538, 5869, 1, 979, 1],
[538, 5872, 1, 987, 1],
[538, 5927, 1, 987, 1],
[538, 6029, 1, 979, 1],
[538, 6099, 1, 979, 1],
[538, 6220, 1, 987, 1],
[538, 6221, 1, 987, 1],
[538, 6315, 1, 968, 1],
[538, 6339, 1, 987, 1],
[538, 6373, 1, 978, 1],
[538, 6389, 1, 978, 1],
[538, 6419, 1, 972, 1],
[538, 6431, 1, 978, 1],
[538, 6439, 1, 955, 1],
[538, 6440, 1, 955, 1],
[538, 6441, 1, 955, 1],
[538, 6479, 1, 972, 1],
[538, 6481, 1, 955, 1],
[538, 6542, 1, 987, 1],
[538, 6543, 1, 987, 1],
[538, 6544, 1, 987, 1],
[538, 6545, 1, 987, 1],
[538, 6546, 1, 987, 1],
[538, 6570, 1, 984, 1],
[538, 6571, 1, 978, 1],
[538, 6643, 1, 978, 1],
[538, 6648, 1, 987, 1],
[538, 6649, 1, 987, 1],
[538, 6650, 1, 987, 1],
[538, 6670, 1, 955, 1],
[538, 6757, 1, 955, 1],
[538, 6792, 1, 987, 1],
[538, 6793, 1, 987, 1],
[538, 6842, 1, 984, 1],
[538, 6856, 1, 955, 1],
[538, 6874, 1, 987, 1],
[538, 6884, 1, 1264, 1],
[538, 6892, 1, 962, 1],
[538, 6914, 1, 978, 1],
[538, 6916, 1, 954, 1],
[538, 6933, 1, 1264, 1],
[538, 6934, 1, 987, 1],
[538, 6935, 1, 987, 1],
[538, 6940, 1, 954, 1],
[538, 6941, 1, 954, 1],
[538, 7099, 1, 954, 1],
[538, 7189, 1, 954, 1],
[538, 7190, 1, 987, 1],
[538, 7191, 1, 987, 1],
[538, 7192, 1, 987, 1],
[538, 7193, 1, 984, 1],
[538, 7194, 1, 987, 1],
[538, 7195, 1, 979, 1],
[538, 7196, 1, 987, 1],
[538, 7197, 1, 987, 1],
[538, 7198, 1, 987, 1],
[538, 7199, 1, 987, 1],
[538, 7200, 1, 987, 1],
[538, 7201, 1, 984, 1],
[538, 7202, 1, 985, 1],
[538, 7203, 1, 979, 1],
[538, 7204, 1, 984, 1],
[538, 7205, 1, 985, 1],
[538, 7206, 1, 984, 1],
[538, 7207, 1, 985, 1],
[538, 7208, 1, 965, 1],
[538, 7209, 1, 984, 1],
[538, 7210, 1, 984, 1],
[538, 7211, 1, 984, 1],
[538, 7212, 1, 970, 1],
[538, 7213, 1, 984, 1],
[538, 7214, 1, 987, 1],
[538, 7215, 1, 987, 1],
[538, 7216, 1, 954, 1],
[538, 7217, 1, 954, 1],
[538, 7218, 1, 954, 1],
[538, 7229, 1, 1264, 1],
[538, 7277, 1, 955, 1],
[538, 7353, 1, 984, 1],
[538, 7354, 1, 987, 1],
[538, 7357, 1, 968, 1],
[538, 7415, 1, 978, 1],
[538, 7449, 1, 984, 1],
[538, 7450, 1, 984, 1],
[538, 7475, 1, 987, 1],
[538, 7476, 1, 987, 1],
[538, 7477, 1, 987, 1],
[538, 7478, 1, 987, 1],
[538, 7588, 1, 984, 1],
[538, 7589, 1, 984, 1],
[538, 7609, 1, 955, 1],
[538, 7634, 1, 978, 1],
[538, 7637, 1, 987, 1],
[538, 7680, 1, 962, 1],
[538, 7708, 1, 987, 1],
[538, 7835, 1, 984, 1],
[538, 7836, 1, 984, 1],
[538, 7837, 1, 984, 1],
[538, 7838, 1, 984, 1],
[538, 7839, 1, 984, 1],
[538, 7840, 1, 984, 1],
[538, 7841, 1, 984, 1],
[538, 7842, 1, 984, 1],
[538, 7843, 1, 984, 1],
[538, 7844, 1, 984, 1],
[538, 7845, 1, 984, 1],
[538, 7846, 1, 984, 1],
[538, 8013, 1, 954, 1],
[538, 8212, 1, 987, 1],
[538, 8225, 1, 978, 1],
[538, 8403, 1, 978, 1],
[538, 8615, 1, 984, 1],
[538, 8616, 1, 984, 1],
[538, 8617, 1, 984, 1],
[538, 8618, 1, 984, 1],
[538, 8653, 1, 955, 1],
[538, 8666, 1, 987, 1],
[538, 8667, 1, 987, 1],
[538, 8707, 1, 987, 1],
[538, 8815, 1, 962, 1],
[538, 8910, 1, 987, 1],
[538, 9121, 1, 962, 1],
[538, 9235, 1, 987, 1],
[538, 9333, 1, 962, 1],
[538, 9388, 1, 987, 1],
[538, 9389, 1, 987, 1],
[538, 9390, 1, 987, 1],
[538, 9391, 1, 987, 1],
[538, 9596, 1, 987, 1],
[538, 9644, 1, 984, 1],
[538, 9645, 1, 984, 1],
[538, 9690, 1, 987, 1],
[538, 9744, 1, 984, 1],
[538, 9745, 1, 984, 1],
[538, 9829, 1, 984, 1],
[538, 9830, 1, 984, 1],
[538, 9831, 1, 987, 1],
[538, 9832, 1, 987, 1],
[538, 9833, 1, 984, 1],
[538, 9834, 1, 984, 1],
[538, 9835, 1, 978, 1],
[538, 9862, 1, 987, 1],
[538, 9863, 1, 984, 1],
[538, 9864, 1, 984, 1],
[538, 9865, 1, 984, 1],
[538, 9867, 1, 962, 1],
[538, 9868, 1, 987, 1],
[538, 9869, 1, 987, 1],
[538, 9870, 1, 987, 1],
[538, 9886, 1, 984, 1],
[538, 9887, 1, 954, 1],
[538, 9888, 1, 984, 1],
[538, 9889, 1, 984, 1],
[538, 9901, 1, 985, 1],
[538, 9902, 1, 985, 1],
[538, 9911, 1, 984, 1],
[538, 9939, 1, 987, 1],
[538, 9940, 1, 987, 1],
[538, 10024, 1, 955, 1],
[538, 10236, 1, 978, 1],
[538, 10237, 1, 987, 1],
[538, 10238, 1, 987, 1],
[538, 10283, 1, 987, 1],
[538, 10284, 1, 987, 1],
[538, 10326, 1, 987, 1],
[538, 10327, 1, 987, 1],
[538, 10454, 1, 987, 1],
[538, 10455, 1, 987, 1],
[538, 10456, 1, 987, 1],
[538, 10492, 1, 978, 1],
[538, 10540, 1, 987, 1],
[538, 10588, 1, 987, 1],
[538, 10589, 1, 987, 1],
[538, 10704, 1, 987, 1],
[538, 10746, 1, 962, 1],
[538, 10751, 1, 987, 1],
[538, 10785, 1, 984, 1],
[538, 10786, 1, 984, 1],
[538, 10787, 1, 984, 1],
[538, 10788, 1, 984, 1],
[538, 10844, 1, 978, 1],
[538, 10885, 1, 978, 1],
[538, 10886, 1, 978, 1],
[538, 10890, 1, 987, 1],
[538, 10917, 1, 987, 1],
[538, 10921, 1, 978, 1],
[538, 10922, 1, 978, 1],
[538, 11129, 1, 987, 1],
[538, 11133, 1, 972, 1],
[538, 11147, 1, 987, 1],
[538, 11157, 1, 984, 1],
[538, 11158, 1, 962, 1],
[538, 11234, 1, 962, 1],
[538, 11274, 1, 984, 1],
[538, 11275, 1, 984, 1],
[538, 11318, 1, 964, 1],
[538, 11319, 1, 964, 1],
[538, 11329, 1, 962, 1],
[538, 11336, 1, 972, 1],
[538, 11337, 1, 972, 1],
[538, 11360, 1, 987, 1],
[538, 11361, 1, 987, 1],
[538, 11442, 1, 962, 1],
[538, 11520, 1, 984, 1],
[538, 11521, 1, 984, 1],
[538, 11553, 1, 1264, 1],
[538, 11571, 1, 987, 1],
[538, 11572, 1, 987, 1],
[538, 11587, 1, 1264, 1],
[538, 11593, 1, 979, 1],
[538, 11594, 1, 979, 1],
[538, 11601, 1, 987, 1],
[538, 11613, 1, 987, 1],
[538, 11614, 1, 987, 1],
[538, 11616, 1, 987, 1],
[538, 11617, 1, 987, 1],
[538, 11618, 1, 987, 1],
[538, 11752, 1, 962, 1],
[538, 11769, 1, 979, 1],
[538, 11785, 1, 979, 1],
[538, 11792, 1, 987, 1],
[538, 11793, 1, 987, 1],
[538, 11794, 1, 987, 1],
[538, 11848, 1, 984, 1],
[538, 11849, 1, 984, 1],
[538, 11874, 1, 964, 1],
[538, 11923, 1, 964, 1],
[538, 11924, 1, 964, 1],
[538, 11945, 1, 987, 1],
[538, 11984, 1, 987, 1],
[538, 12071, 1, 964, 1],
[538, 12203, 1, 987, 1],
[538, 12204, 1, 987, 1],
[538, 12240, 1, 964, 1],
[538, 12241, 1, 964, 1],
[538, 12242, 1, 962, 1],
[538, 12245, 1, 964, 1],
[538, 12250, 1, 964, 1],
[538, 12251, 1, 964, 1],
[538, 12259, 1, 987, 1],
[538, 12299, 1, 964, 1],
[538, 12300, 1, 964, 1],
[538, 12301, 1, 964, 1],
[538, 12328, 1, 972, 1],
[538, 12329, 1, 972, 1],
[538, 12330, 1, 972, 1],
[538, 12331, 1, 972, 1],
[538, 12344, 1, 987, 1],
[538, 12359, 1, 1264, 1],
[538, 12360, 1, 1264, 1],
[538, 12367, 1, 987, 1],
[538, 12368, 1, 987, 1],
[538, 12383, 1, 987, 1],
[538, 12401, 1, 984, 1],
[538, 12444, 1, 984, 1],
[538, 12476, 1, 987, 1],
[538, 12493, 1, 987, 1],
[538, 12505, 1, 978, 1],
[538, 12506, 1, 987, 1],
[538, 12508, 1, 964, 1],
[538, 12509, 1, 964, 1],
[538, 12534, 1, 987, 1],
[538, 12535, 1, 987, 1],
[538, 12536, 1, 987, 1],
[538, 12537, 1, 978, 1],
[538, 12539, 1, 972, 1],
[538, 12540, 1, 972, 1],
[538, 12561, 1, 972, 1],
[538, 12562, 1, 972, 1],
[538, 12582, 1, 972, 1],
[538, 12583, 1, 972, 1],
[538, 12590, 1, 987, 1],
[538, 12591, 1, 987, 1],
[538, 12611, 1, 987, 1],
[538, 12612, 1, 987, 1],
[538, 12614, 1, 987, 1],
[538, 12626, 1, 972, 1],
[538, 12627, 1, 972, 1],
[538, 12656, 1, 962, 1],
[538, 12670, 1, 962, 1],
[538, 12683, 1, 987, 1],
[538, 12686, 1, 979, 1],
[538, 12687, 1, 990, 1],
[538, 12689, 1, 987, 1],
[538, 12690, 1, 987, 1],
[538, 12704, 1, 985, 1],
[538, 12705, 1, 985, 1],
[538, 12723, 1, 1264, 1],
[538, 12727, 1, 987, 1],
[538, 12729, 1, 987, 1],
[538, 12730, 1, 987, 1],
[538, 12731, 1, 987, 1],
[538, 12732, 1, 987, 1],
[538, 12750, 1, 1264, 1],
[538, 12755, 1, 987, 1],
[538, 12766, 1, 1264, 1],
[538, 12781, 1, 963, 1],
[538, 12848, 1, 955, 1],
[538, 12851, 1, 955, 1],
[538, 12862, 1, 954, 1],
[538, 12863, 1, 987, 1],
[538, 12864, 1, 987, 1],
[538, 12869, 1, 987, 1],
[538, 12870, 1, 987, 1],
[538, 12871, 1, 987, 1],
[538, 12874, 1, 979, 1],
[538, 12898, 1, 987, 1],
[538, 12913, 1, 987, 1],
[538, 12923, 1, 955, 1],
[538, 12934, 1, 972, 1],
[538, 12935, 1, 972, 1],
[538, 12951, 1, 962, 1],
[538, 12956, 1, 979, 1],
[538, 13013, 1, 987, 1],
[538, 13014, 1, 987, 1],
[538, 13015, 1, 987, 1],
[538, 13034, 1, 955, 1],
[538, 13035, 1, 962, 1],
[538, 13109, 1, 987, 1],
[538, 13214, 1, 987, 1],
[538, 13215, 1, 987, 1],
[538, 13216, 1, 987, 1],
[538, 13217, 1, 987, 1],
[538, 13286, 1, 987, 1],
[538, 13329, 1, 962, 1],
[538, 13354, 1, 987, 1],
[538, 13355, 1, 987, 1],
[538, 13397, 1, 962, 1],
[538, 13400, 1, 978, 1],
[538, 13401, 1, 978, 1],
[538, 13435, 1, 985, 1],
[538, 13448, 1, 955, 1],
[538, 13451, 1, 987, 1],
[538, 13452, 1, 987, 1],
[538, 13541, 1, 987, 1],
[538, 13545, 1, 955, 1],
[538, 13577, 1, 955, 1],
[538, 13652, 1, 978, 1],
[538, 13653, 1, 978, 1],
[538, 13685, 1, 987, 1],
[538, 13686, 1, 987, 1],
[538, 13723, 1, 987, 1],
[538, 13724, 1, 987, 1],
[538, 13814, 1, 955, 1],
[538, 13818, 1, 972, 1],
[538, 13819, 1, 972, 1],
[538, 13863, 1, 987, 1],
[538, 13879, 1, 1621, 1],
[538, 13991, 1, 987, 1],
[538, 13992, 1, 978, 1],
[538, 13994, 1, 978, 1],
[538, 13995, 1, 1621, 1],
[538, 13996, 1, 1621, 1],
[538, 14055, 1, 984, 1],
[538, 14056, 1, 984, 1],
[538, 14081, 1, 978, 1],
[538, 14097, 1, 987, 1],
[538, 14104, 1, 987, 1],
[538, 14148, 1, 1264, 1],
[538, 14159, 1, 954, 1],
[538, 14160, 1, 954, 1],
[538, 14194, 1, 987, 1],
[538, 14195, 1, 987, 1],
[538, 14354, 1, 987, 1],
[538, 14355, 1, 987, 1],
[538, 14357, 1, 987, 1],
[538, 14635, 1, 987, 1],
[538, 14700, 1, 962, 1],
[538, 14787, 1, 987, 1],
[538, 14808, 1, 954, 1],
[538, 14809, 1, 978, 1],
[538, 14845, 1, 987, 1],
[538, 15008, 1, 987, 1],
[538, 15009, 1, 987, 1],
[538, 15026, 1, 954, 1],
[538, 15027, 1, 978, 1],
[538, 15079, 1, 987, 1],
[538, 15080, 1, 987, 1],
[538, 15152, 1, 987, 1],
[538, 15153, 1, 987, 1],
[538, 15154, 1, 987, 1],
[538, 15165, 1, 954, 1],
[538, 15205, 1, 984, 1],
[538, 15206, 1, 987, 1],
[538, 15350, 1, 955, 1],
[538, 15724, 1, 987, 1],
[538, 15880, 1, 987, 1],
[538, 15881, 1, 987, 1],
[538, 15898, 1, 954, 1],
[538, 15900, 1, 955, 1],
[538, 16151, 1, 987, 1],
[538, 16152, 1, 987, 1],
[538, 16183, 1, 987, 1],
[538, 16280, 1, 987, 1],
[538, 16413, 1, 1621, 1],
[538, 16414, 1, 1621, 1],
[538, 16462, 1, 978, 1],
[538, 16677, 1, 987, 1],
[538, 16683, 1, 955, 1],
[538, 16693, 1, 972, 1],
[538, 16696, 1, 972, 1],
[538, 16718, 1, 987, 1],
[538, 16734, 1, 987, 1],
[538, 16743, 1, 987, 1],
[538, 16746, 1, 1264, 1],
[538, 16849, 1, 987, 1],
[538, 16850, 1, 987, 1],
[538, 16944, 1, 987, 1],
[538, 16947, 1, 987, 1],
[538, 16948, 1, 987, 1],
[538, 16949, 1, 987, 1],
[538, 16952, 1, 987, 1],
[538, 16957, 1, 1621, 1],
[538, 16959, 1, 976, 1],
[538, 16960, 1, 1264, 1],
[538, 16961, 1, 954, 1],
[538, 17042, 1, 1621, 1],
[538, 17043, 1, 1621, 1],
[538, 17047, 1, 1621, 1],
[538, 17048, 1, 1621, 1],
[538, 17049, 1, 1621, 1],
[538, 17050, 1, 1621, 1],
[538, 17053, 1, 1621, 1],
[538, 17070, 1, 972, 1],
[538, 17071, 1, 972, 1],
[538, 17114, 1, 987, 1],
[538, 17115, 1, 987, 1],
[538, 17143, 1, 987, 1],
[538, 17199, 1, 987, 1],
[538, 17200, 1, 987, 1],
[538, 17201, 1, 987, 1],
[538, 17202, 1, 987, 1],
[538, 17214, 1, 976, 1],
[538, 17286, 1, 972, 1],
[538, 17287, 1, 972, 1],
[538, 17306, 1, 954, 1],
[538, 17325, 1, 987, 1],
[538, 17334, 1, 987, 1],
[538, 17344, 1, 955, 1],
[538, 17347, 1, 987, 1],
[538, 17424, 1, 987, 1],
[538, 17437, 1, 954, 1],
[538, 17438, 1, 955, 1],
[538, 17444, 1, 1621, 1],
[538, 17445, 1, 1621, 1],
[538, 17500, 1, 1264, 1],
[538, 17511, 1, 987, 1],
[538, 17514, 1, 987, 1],
[538, 17527, 1, 1264, 1],
[538, 17698, 1, 987, 1],
[538, 17701, 1, 976, 1],
[538, 17710, 1, 955, 1],
[538, 17746, 1, 1264, 1],
[538, 17765, 1, 955, 1],
[538, 17772, 1, 976, 1],
[538, 17775, 1, 987, 1],
[538, 17776, 1, 987, 1],
[538, 17777, 1, 987, 1],
[538, 17806, 1, 978, 1],
[538, 17836, 1, 978, 1],
[538, 17974, 1, 976, 1],
[538, 17980, 1, 955, 1],
[538, 17990, 1, 985, 1],
[538, 18001, 1, 985, 1],
[538, 18002, 1, 987, 1],
[538, 18003, 1, 987, 1],
[538, 18004, 1, 987, 1],
[538, 18023, 1, 987, 1],
[538, 18057, 1, 987, 1],
[538, 18095, 1, 979, 1],
[538, 18097, 1, 1264, 1],
[538, 18111, 1, 1621, 1],
[538, 18112, 1, 1621, 1],
[538, 18145, 1, 976, 1],
[538, 18146, 1, 979, 1],
[538, 18168, 1, 1264, 1],
[538, 18186, 1, 987, 1],
[538, 18189, 1, 987, 1],
[538, 18193, 1, 954, 1],
[538, 18225, 1, 974, 1],
[538, 18226, 1, 974, 1],
[538, 18259, 1, 954, 1],
[538, 18264, 1, 987, 1],
[538, 18265, 1, 987, 1],
[538, 18266, 1, 987, 1],
[538, 18289, 1, 1621, 1],
[538, 18355, 1, 987, 1],
[538, 18472, 1, 1810, 1],
[538, 18523, 1, 972, 1],
[538, 18524, 1, 972, 1],
[538, 18547, 1, 987, 1],
[538, 18549, 1, 1810, 1],
[538, 18590, 1, 987, 1],
[538, 18602, 1, 962, 1],
[538, 18636, 1, 978, 1],
[538, 18706, 1, 987, 1],
[538, 18725, 1, 987, 1],
[538, 18726, 1, 987, 1],
[538, 18735, 1, 987, 1],
[538, 18736, 1, 987, 1],
[538, 18755, 1, 987, 1],
[538, 18756, 1, 987, 1],
[538, 18765, 1, 954, 1],
[538, 18827, 1, 1264, 1],
[538, 18837, 1, 954, 1],
[538, 18853, 1, 972, 1],
[538, 18854, 1, 972, 1],
[538, 18871, 1, 979, 1],
[538, 18873, 1, 972, 1],
[538, 18874, 1, 972, 1],
[538, 18890, 1, 987, 1],
[538, 18906, 1, 1810, 1],
[538, 18930, 1, 1264, 1],
[538, 18931, 1, 1264, 1],
[538, 18932, 1, 1264, 1],
[538, 18933, 1, 1264, 1],
[538, 18934, 1, 1264, 1],
[538, 18935, 1, 1264, 1],
[538, 18936, 1, 1264, 1],
[538, 18937, 1, 1264, 1],
[538, 18989, 1, 1264, 1],
[538, 19002, 1, 1810, 1],
[538, 19003, 1, 1264, 1],
[538, 19004, 1, 1264, 1],
[538, 19014, 1, 987, 1],
[538, 19035, 1, 979, 1],
[538, 19058, 1, 987, 1],
[538, 19072, 1, 1264, 1],
[538, 19078, 1, 972, 1],
[538, 19079, 1, 972, 1],
[538, 19104, 1, 1810, 1],
[538, 19126, 1, 979, 1],
[538, 19241, 1, 978, 1],
[538, 19290, 1, 976, 1],
[538, 19292, 1, 974, 1],
[538, 19293, 1, 974, 1],
[538, 19295, 1, 974, 1],
[538, 19324, 1, 987, 1],
[538, 19403, 1, 1810, 1],
[538, 19466, 1, 974, 1],
[538, 19467, 1, 974, 1],
[538, 19518, 1, 1264, 1],
[538, 19534, 1, 987, 1],
[538, 19548, 1, 962, 1],
[538, 19565, 1, 987, 1],
[538, 19585, 1, 969, 1],
[538, 19595, 1, 1810, 1],
[538, 19619, 1, 972, 1],
[538, 19641, 1, 987, 1],
[538, 19643, 1, 987, 1],
[538, 19644, 1, 987, 1],
[538, 19645, 1, 987, 1],
[538, 19649, 1, 987, 1],
[538, 19668, 1, 987, 1],
[538, 19746, 1, 976, 1],
[538, 19750, 1, 987, 1],
[538, 19799, 1, 1810, 1],
[538, 19820, 1, 972, 1],
[538, 19839, 1, 987, 1],
[538, 19854, 1, 978, 1],
[538, 19902, 1, 1810, 1],
[538, 19903, 1, 1810, 1],
[538, 20005, 1, 987, 1],
[538, 20007, 1, 987, 1],
[538, 20013, 1, 955, 1],
[538, 20039, 1, 987, 1],
[538, 20117, 1, 978, 1],
[538, 20174, 1, 987, 1],
[538, 20191, 1, 955, 1],
[538, 20199, 1, 1810, 1],
[538, 20200, 1, 1810, 1],
[538, 20250, 1, 976, 1],
[538, 20263, 1, 987, 1],
[538, 20267, 1, 955, 1],
[538, 20268, 1, 987, 1],
[538, 20286, 1, 978, 1],
[538, 20295, 1, 955, 1],
[538, 20396, 1, 1810, 1],
[538, 20412, 1, 987, 1],
[538, 20489, 1, 954, 1],
[538, 20515, 1, 976, 1],
[538, 20520, 1, 1810, 1],
[538, 20572, 1, 987, 1],
[538, 20608, 1, 987, 1],
[538, 20838, 1, 1810, 1],
[538, 20839, 1, 1810, 1],
[538, 20878, 1, 987, 1],
[538, 20879, 1, 987, 1],
[538, 20904, 1, 1264, 1],
[538, 20974, 1, 1810, 1],
[538, 20977, 1, 1264, 1],
[538, 20982, 1, 987, 1],
[538, 20999, 1, 987, 1],
[538, 21314, 1, 987, 1],
[538, 21350, 1, 1810, 1],
[538, 21399, 1, 1810, 1],
[538, 21498, 1, 1810, 1],
[538, 21542, 1, 1264, 1],
[538, 21545, 1, 955, 1],
[538, 21626, 1, 987, 1],
[538, 21627, 1, 987, 1],
[538, 21780, 1, 976, 1],
[538, 21868, 1, 987, 1],
[538, 21923, 1, 987, 1],
[538, 21925, 1, 987, 1],
[538, 21964, 1, 955, 1],
[538, 21968, 1, 1810, 1],
[538, 21976, 1, 976, 1],
[538, 22094, 1, 987, 1],
[538, 22095, 1, 987, 1],
[538, 22430, 1, 972, 1],
[538, 22431, 1, 972, 1],
[538, 22436, 1, 984, 1],
[538, 22437, 1, 987, 1],
[538, 22461, 1, 987, 1],
[538, 22462, 1, 987, 1],
[538, 22463, 1, 987, 1],
[538, 22464, 1, 987, 1],
[538, 22503, 1, 1264, 1],
[538, 22527, 1, 1264, 1],
[538, 22565, 1, 955, 1],
[538, 22569, 1, 978, 1],
[538, 22578, 1, 987, 1],
[538, 22585, 1, 1264, 1],
[538, 22630, 1, 1264, 1],
[538, 22646, 1, 987, 1],
[538, 22666, 1, 987, 1],
[538, 22673, 1, 1264, 1],
[538, 22723, 1, 955, 1],
[538, 22724, 1, 955, 1],
[538, 22726, 1, 976, 1],
[538, 22773, 1, 987, 1],
[538, 22812, 1, 1810, 1],
[538, 22841, 1, 987, 1],
[538, 22842, 1, 987, 1],
[538, 22843, 1, 987, 1],
[538, 23124, 1, 955, 1],
[538, 23182, 1, 987, 1],
[538, 23210, 1, 978, 1],
[538, 23224, 1, 955, 1],
[538, 23226, 1, 987, 1],
[538, 23346, 1, 987, 1],
[538, 23347, 1, 987, 1],
[538, 23351, 1, 976, 1],
[538, 23359, 1, 1810, 1],
[538, 23420, 1, 978, 1],
[538, 23441, 1, 1264, 1],
[538, 23447, 1, 987, 1],
[538, 23453, 1, 976, 1],
[538, 23476, 1, 987, 1],
[538, 23477, 1, 987, 1],
[538, 23516, 1, 987, 1],
[538, 23555, 1, 955, 1],
[538, 23586, 1, 955, 1],
[538, 23609, 1, 976, 1],
[538, 23611, 1, 987, 1],
[538, 23651, 1, 978, 1],
[538, 23666, 1, 987, 1],
[538, 23667, 1, 987, 1],
[538, 23669, 1, 978, 1],
[538, 23707, 1, 987, 1],
[538, 23737, 1, 954, 1],
[538, 23748, 1, 972, 1],
[538, 23749, 1, 972, 1],
[538, 23827, 1, 1264, 1],
[538, 23828, 1, 1264, 1],
[538, 23860, 1, 987, 1],
[538, 23861, 1, 987, 1],
[538, 23873, 1, 987, 1],
[538, 23911, 1, 954, 1],
[538, 23938, 1, 987, 1],
[538, 23941, 1, 1810, 1],
[538, 23979, 1, 1264, 1],
[538, 23983, 1, 987, 1],
[538, 24011, 1, 987, 1],
[538, 24016, 1, 987, 1],
[538, 24042, 1, 1810, 1],
[538, 24047, 1, 987, 1],
[538, 24048, 1, 987, 1],
[538, 24061, 1, 987, 1],
[538, 24099, 1, 987, 1],
[538, 24100, 1, 1264, 1],
[538, 24233, 1, 955, 1],
[538, 24238, 1, 955, 1],
[538, 24286, 1, 1810, 1],
[538, 24296, 1, 987, 1],
[538, 24297, 1, 987, 1],
[538, 24360, 1, 987, 1],
[538, 24361, 1, 987, 1],
[538, 24362, 1, 987, 1],
[538, 24369, 1, 1810, 1],
[538, 24396, 1, 1264, 1],
[538, 24530, 1, 987, 1],
[538, 24534, 1, 987, 1],
[538, 24535, 1, 987, 1],
[538, 24552, 1, 987, 1],
[538, 24746, 1, 1264, 1],
[538, 24747, 1, 1264, 1],
[538, 24749, 1, 1264, 1],
[538, 24755, 1, 987, 1],
[538, 24796, 1, 972, 1],
[538, 24797, 1, 972, 1],
[538, 24826, 1, 985, 1],
[538, 24891, 1, 987, 1],
[538, 24892, 1, 978, 1],
[538, 24897, 1, 976, 1],
[538, 24913, 1, 978, 1],
[538, 25021, 1, 1264, 1],
[538, 25036, 1, 979, 1],
[538, 25037, 1, 979, 1],
[538, 25049, 1, 976, 1],
[538, 25057, 1, 1810, 1],
[538, 25074, 1, 987, 1],
[538, 25075, 1, 987, 1],
[538, 25171, 1, 987, 1],
[538, 25172, 1, 987, 1],
[538, 25174, 1, 987, 1],
[538, 25200, 1, 987, 1],
[538, 25279, 1, 987, 1],
[538, 25280, 1, 987, 1],
[538, 25405, 1, 1810, 1],
[538, 25420, 1, 987, 1],
[538, 25421, 1, 987, 1],
[538, 25475, 1, 955, 1],
[538, 25510, 1, 1810, 1],
[538, 25534, 1, 955, 1],
[538, 25537, 1, 976, 1],
[538, 25546, 1, 987, 1],
[538, 25579, 1, 1810, 1],
[538, 25605, 1, 1810, 1],
[538, 25633, 1, 976, 1],
[538, 25656, 1, 987, 1],
[538, 25667, 1, 976, 1],
[538, 25696, 1, 987, 1],
[538, 25744, 1, 1810, 1],
[538, 25829, 1, 955, 1],
[538, 25853, 1, 962, 1],
[538, 25871, 1, 987, 1],
[538, 25872, 1, 987, 1],
[538, 25873, 1, 972, 1],
[538, 25874, 1, 972, 1],
[538, 25880, 1, 976, 1],
[538, 25886, 1, 987, 1],
[538, 25899, 1, 1264, 1],
[538, 25922, 1, 987, 1],
[538, 25963, 1, 954, 1],
[538, 26002, 1, 987, 1],
[538, 26003, 1, 987, 1],
[538, 26026, 1, 987, 1],
[538, 26183, 1, 987, 1],
[538, 26184, 1, 987, 1],
[538, 26195, 1, 1810, 1],
[538, 26196, 1, 1810, 1],
[538, 26263, 1, 987, 1],
[538, 26382, 1, 1264, 1],
[538, 26387, 1, 987, 1],
[538, 26736, 1, 987, 1],
[538, 26737, 1, 987, 1],
[538, 26738, 1, 987, 1],
[538, 26739, 1, 987, 1],
	];
    }
	
	public function getDependencies()
	{
		return array(FileFixtures::class, UserFixtures::class, UserFileFixtures::class, Booking_538Fixtures::class);
    }
}