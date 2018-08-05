<?php
// src/DataFixtures/UserFixtures.php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

use App\Entity\User;

class UserFixtures extends Fixture
{
	private $passwordEncoder;

	public function __construct(UserPasswordEncoderInterface $passwordEncoder)
	{
	$this->passwordEncoder = $passwordEncoder;
	}


    public function load(ObjectManager $manager)
    {
	foreach ($this->getData() as [$accountType, $username, $password, $email, $lastName, $firstName, $id, $roles]) {
		$user = new User();
		$user->setAccountType($accountType);
		$user->setUserName($username);
		$user->setPassword($this->passwordEncoder->encodePassword($user, $password));
		$user->setEmail($email);
		$user->setLastName($lastName);
		$user->setFirstName($firstName);
		$user->setRoles($roles);

		$manager->persist($user);
		$reference = 'user-'.$id;
		$this->addReference($reference, $user);
	}
	$manager->flush();
    }

	private function getData(): array
    {
	return [
		// $userData = [accountType, username, password, email, lastName, firstName, id, roles];
		['INDIVIDUAL', 'eric', 'eric', 'eric.willard@slam-data.net', 'WILLARD', 'Eric', '1', ['ROLE_USER']],
		['INDIVIDUAL', 'muriel.guillou@neotek-web.com', 'slam525', 'muriel.guillou@neotek-web.com', 'BOURDON', 'Muriel', '525', ['ROLE_USER']],
		['INDIVIDUAL', 'rbourdon@rtsys.fr', 'awt423', 'rbourdon@rtsys.fr', 'GUILLOU', 'Raphaël', '531', ['ROLE_USER']],
		['INDIVIDUAL', 'cbricaud@rtsys.fr', 'slam532', 'cbricaud@rtsys.fr', 'BRICAUD', 'C', '532', ['ROLE_USER']]
	];
    }
}
