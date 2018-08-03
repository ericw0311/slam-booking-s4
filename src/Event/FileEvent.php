<?php
namespace App\Event;

use App\Entity\File;
use App\Entity\UserFile;
use App\Entity\UserParameter;

use App\Api\AdministrationApi;

class FileEvent
{
    static function postPersist($em, \App\Entity\User $user, \App\Entity\File $file, $translator)
    {
	FileEvent::createUserFile($em, $user, $file);
	AdministrationApi::setCurrentFileIfNotDefined($em, $user, $file);
	// FileEvent::createTimetables($em, $user, $file, $translator);
    }

    // Rattache l'utilisateur courant au dossier
    static function createUserFile($em, \App\Entity\User $user, \App\Entity\File $file)
    {
    $userFile = new UserFile($user, $file);
    $userFile->setAccount($user);
    $userFile->setEmail($user->getEmail());
    $userFile->setAccountType($user->getAccountType());
    $userFile->setLastName($user->getLastName());
    $userFile->setFirstName($user->getFirstName());
    $userFile->setUniqueName($user->getUniqueName());
    $userFile->setAdministrator(1); // Le createur du dossier est administrateur.
    $userFile->setUserCreated(1);
    $userFile->setUsername($user->getUsername());
    $userFile->setResourceUser(0);
    
    $em->persist($userFile);
    $em->flush();
    }
}
