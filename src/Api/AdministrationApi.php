<?php
// src/Api/AdministrationApi.php
namespace App\Api;
use App\Entity\File;
use App\Entity\UserParameter;
class AdministrationApi
{
	// Retourne le dossier en cours d'un utilisateur
	static function getCurrentFile($em, \App\Entity\User $user)
	{
	$upRepository = $em->getRepository(UserParameter::class);
	return $upRepository->findOneBy(array('user' => $user, 'parameterGroup' => 'booking', 'parameter' => 'current.file'));
	}

	// Retourne l'ID du dossier en cours d'un utilisateur
	static function getCurrentFileID($em, \App\Entity\User $user)
	{
	$upRepository = $em->getRepository(UserParameter::class);
	$userParameter = $upRepository->findOneBy(array('user' => $user, 'parameterGroup' => 'booking', 'parameter' => 'current.file'));
	if ($userParameter === null) {
		return 0;
	} else {
		return $userParameter->getIntegerValue();
	}
	}
    
	// Positionne le dossier comme dossier en cours
	static function setCurrentFile($em, \App\Entity\User $user, \App\Entity\File $file)
	{
	// Recherche du dossier en cours
	$userParameter = AdministrationApi::getCurrentFile($em, $user);
	if ($userParameter === null) {
		$userParameter = new UserParameter($user, 'booking', 'current.file');
		$userParameter->setSDIntegerValue($file->getId());
		$em->persist($userParameter);
	} else {
		$userParameter->setSDIntegerValue($file->getId());
	}
	$em->flush();
	}

	// Positionne le dossier comme dossier en cours (idem setCurrentFile mais directement à partir de l'ID du dossier)
	static function setCurrentFileID($em, \App\Entity\User $user, $fileID)
	{
	// Recherche du dossier en cours
	$userParameter = AdministrationApi::getCurrentFile($em, $user);
	if ($userParameter === null) {
		$userParameter = new UserParameter($user, 'booking', 'current.file');
		$userParameter->setSDIntegerValue($fileID);
		$em->persist($userParameter);
	} else {
		$userParameter->setSDIntegerValue($fileID);
	}
	$em->flush();
	}

	// Positionne le dossier comme dossier en cours si l'utilisateur n'a pas de dossier en cours
	static function setCurrentFileIfNotDefined($em, \App\Entity\User $user, \App\Entity\File $file)
	{
	// Recherche du dossier en cours
	$userParameter = AdministrationApi::getCurrentFile($em, $user);
	if ($userParameter === null) {
		$userParameter = new UserParameter($user, 'booking', 'current.file');
		$userParameter->setSDIntegerValue($file->getId());
		$em->persist($userParameter);
		$em->flush();
	}
	}

	// Positionne le premier dossier comme dossier en cours
	static function setFirstFileAsCurrent($em, \App\Entity\User $user)
	{
	// Recherche du dossier en cours
	$userParameter = AdministrationApi::getCurrentFile($em, $user);
	// Recherche du premier dossier de l'utilisateur
	$fRepository = $em->getRepository(File::class);
	$firstFile = $fRepository->getUserFirstFile($user);

	$doFlush = false;
	if ($firstFile != null) { // Le premier dossier est trouve
		if ($userParameter === null) { // Mise a jour du parametre "dossier en cours"
			$userParameter = new UserParameter($user, 'booking', 'current.file');
			$userParameter->setSDIntegerValue($firstFile->getId());
			$em->persist($userParameter);
		} else { // Creation "dossier en cours"
			$userParameter->setSDIntegerValue($firstFile->getId());
		}
		$doFlush = true;
	} else { // Plus de dossier: suppression du parametre
		if ($userParameter != null) {
			$em->remove($userParameter);
			$doFlush = true;
		}
	}
	if ($doFlush) {
		$em->flush();
	}
	}
}
