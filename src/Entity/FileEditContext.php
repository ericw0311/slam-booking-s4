<?php
namespace App\Entity;

class FileEditContext
{
	protected $userFilesCount;
    protected $userTimetablesCount; // Nombre de grilles horaires saisies par l'utilisateur (type = T)
    protected $labelsCount;
    protected $resourcesCount;

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

    function __construct($em, \App\Entity\File $file)
    {
    $ufRepository = $em->getRepository(UserFile::class);
    $this->setUserFilesCount($ufRepository->getUserFilesExceptFileCreatorCount($file));
/*
    $lRepository = $em->getRepository(Label::class);
    $this->setLabelsCount($lRepository->getLabelsCount($file));
    $tRepository = $em->getRepository(Timetable::class);
    $this->setUserTimetablesCount($tRepository->getUserTimetablesCount($file));
    $rRepository = $em->getRepository(Resource::class);
    $this->setResourcesCount($rRepository->getResourcesCount($file));
*/
    $this->setLabelsCount(0);
    $this->setUserTimetablesCount(0);
    $this->setResourcesCount(0);
    }
}
