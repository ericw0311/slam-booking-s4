<?php
// Utilisé pour la page d'édition du dossier
namespace App\Entity;

use App\Api\AdministrationApi;

class FileEditContext
{
	protected $userFilesCount;
    protected $userTimetablesCount; // Nombre de grilles horaires saisies par l'utilisateur (type = T)
    protected $labelsCount;
    protected $resourcesCount;
    protected $bookingsCount;
	protected $fileAdministrator;
	protected $bookingUser;

    public function setUserFilesCount($userFilesCount)
    {
    $this->userFilesCount = $userFilesCount;
    return $this;
    }

    public function getUserFilesCount()
    {
    return $this->userFilesCount;
    }

    public function setUserTimetablesCount($timetablesCount)
    {
    $this->userTimetablesCount = $timetablesCount;
    return $this;
    }

    public function getUserTimetablesCount()
    {
    return $this->userTimetablesCount;
    }

    public function setLabelsCount($labelsCount)
    {
    $this->labelsCount = $labelsCount;
    return $this;
    }

    public function getLabelsCount()
    {
    return $this->labelsCount;
    }

    public function setResourcesCount($resourcesCount)
    {
    $this->resourcesCount = $resourcesCount;
    return $this;
    }

    public function getResourcesCount()
    {
    return $this->resourcesCount;
    }

    public function setBookingsCount($bookingsCount)
    {
    $this->bookingsCount = $bookingsCount;
    return $this;
    }

    public function getBookingsCount()
    {
    return $this->bookingsCount;
    }

	public function getFileAdministrator()
	{
	return $this->fileAdministrator;
	}

	public function setFileAdministrator($fileAdministrator)
	{
	$this->fileAdministrator = $fileAdministrator;
	return $this;
	}

	public function getBookingUser()
	{
	return $this->bookingUser;
	}

	public function setBookingUser($bookingUser): self
	{
	$this->bookingUser = $bookingUser;
	return $this;
	}

    function __construct($em, \App\Entity\File $file)
    {
    $ufRepository = $em->getRepository(UserFile::class);
    $this->setUserFilesCount($ufRepository->getUserFilesExceptFileCreatorCount($file));

    $lRepository = $em->getRepository(Label::class);
    $this->setLabelsCount($lRepository->getLabelsCount($file));

    $tRepository = $em->getRepository(Timetable::class);
    $this->setUserTimetablesCount($tRepository->getUserTimetablesCount($file));

    $rRepository = $em->getRepository(Resource::class);
    $this->setResourcesCount($rRepository->getResourcesCount($file));

    $bRepository = $em->getRepository(Booking::class);
    $this->setBookingsCount($bRepository->getAllBookingsCount($file));


    $this->setFileAdministrator(AdministrationApi::getFileBookingEmailAdministrator($em, $file));
	$this->setBookingUser(AdministrationApi::getFileBookingEmailUser($em, $file));
    }
}
