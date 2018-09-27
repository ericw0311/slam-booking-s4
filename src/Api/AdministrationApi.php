<?php
// src/Api/AdministrationApi.php
namespace App\Api;
use App\Entity\File;
use App\Entity\UserParameter;
use App\Entity\Constants;

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
		$userParameter->setSBIntegerValue($file->getId());
		$em->persist($userParameter);
	} else {
		$userParameter->setSBIntegerValue($file->getId());
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
		$userParameter->setSBIntegerValue($fileID);
		$em->persist($userParameter);
	} else {
		$userParameter->setSBIntegerValue($fileID);
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
		$userParameter->setSBIntegerValue($file->getId());
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
			$userParameter->setSBIntegerValue($firstFile->getId());
			$em->persist($userParameter);
		} else { // Creation "dossier en cours"
			$userParameter->setSBIntegerValue($firstFile->getId());
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

	// Retourne le nombre de lignes affichées dans les listes pour une entité donnée
	static function getNumberLines($em, \App\Entity\User $user, $entityCode)
	{
	$upRepository = $em->getRepository(UserParameter::Class);

	$userParameter = $upRepository->findOneBy(array('user' => $user, 'parameterGroup' => ($entityCode.'.number.lines.columns'), 'parameter' => ($entityCode.'.number.lines')));
	if ($userParameter != null) { $numberLines = $userParameter->getIntegerValue(); } else { $numberLines =  constant(Constants::class.'::LIST_DEFAULT_NUMBER_LINES'); }

	return $numberLines;
	}

	// Met à jour le nombre de lignes affichées dans les listes pour une entité donnée
	static function setNumberLines($em, \App\Entity\User $user, $entityCode, $numberLines)
	{
	$upRepository = $em->getRepository(UserParameter::Class);

	$userParameter = $upRepository->findOneBy(array('user' => $user, 'parameterGroup' => ($entityCode.'.number.lines.columns'), 'parameter' => ($entityCode.'.number.lines')));
	if ($userParameter != null) {
		$userParameter->setSBIntegerValue($numberLines);
	} else { 
		$userParameter = new UserParameter($user, $entityCode.'.number.lines.columns', $entityCode.'.number.lines');
		$userParameter->setSBIntegerValue($numberLines);
		$em->persist($userParameter);
	}
	$em->flush();
	}

	// Retourne le nombre de colonnes affichées dans les listes pour une entité donnée
	static function getNumberColumns($em, \App\Entity\User $user, $entityCode)
	{
	$upRepository = $em->getRepository(UserParameter::Class);

	$userParameter = $upRepository->findOneBy(array('user' => $user, 'parameterGroup' => ($entityCode.'.number.lines.columns'), 'parameter' => ($entityCode.'.number.columns')));
	if ($userParameter != null) { $numberColumns = $userParameter->getIntegerValue(); } else { $numberColumns =  constant(Constants::class.'::LIST_DEFAULT_NUMBER_LINES'); }

	return $numberColumns;
	}

	// Met à jour le nombre de colonnes affichées dans les listes pour une entité donnée
	static function setNumberColumns($em, \App\Entity\User $user, $entityCode, $numberColumns)
	{
	$upRepository = $em->getRepository(UserParameter::Class);

	$userParameter = $upRepository->findOneBy(array('user' => $user, 'parameterGroup' => ($entityCode.'.number.lines.columns'), 'parameter' => ($entityCode.'.number.columns')));
	if ($userParameter != null) {
		$userParameter->setSBIntegerValue($numberColumns);
	} else { 
		$userParameter = new UserParameter($user, $entityCode.'.number.lines.columns', $entityCode.'.number.columns');
		$userParameter->setSBIntegerValue($numberColumns);
		$em->persist($userParameter);
	}
	$em->flush();
	}
}
