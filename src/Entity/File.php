<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Table(name="file", uniqueConstraints={@ORM\UniqueConstraint(name="uk_file",columns={"user_id", "name"})})
 * @ORM\Entity(repositoryClass="App\Repository\FileRepository")
 * @ORM\HasLifecycleCallbacks()
 * @UniqueEntity(fields={"user", "name"}, errorPath="name", message="file.already.exists")
 */
class File
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="files")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\Column(name="created_at", type="datetime", nullable=false)
     */
    private $createdAt;

    /**
     * @ORM\Column(name="updated_at", type="datetime", nullable=true)
     */
    private $updatedAt;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\UserFile", mappedBy="file", orphanRemoval=true)
     */
    private $userFiles;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Timetable", mappedBy="file", orphanRemoval=true)
     */
    private $timetables;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\ResourceClassification", mappedBy="file", orphanRemoval=true)
     */
    private $resourceClassifications;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Resource", mappedBy="file", orphanRemoval=true)
     */
    private $resources;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Planification", mappedBy="file", orphanRemoval=true)
     */
    private $planifications;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Label", mappedBy="file", orphanRemoval=true)
     */
    private $labels;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Booking", mappedBy="file")
     */
    private $bookings;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\QueryBooking", mappedBy="file", orphanRemoval=true)
     */
    private $queryBookings;

    public function __construct(\App\Entity\User $user)
    {
		$this->setUser($user);
        $this->userFiles = new ArrayCollection();
        $this->timetables = new ArrayCollection();
        $this->resourceClassifications = new ArrayCollection();
        $this->resources = new ArrayCollection();
        $this->planifications = new ArrayCollection();
        $this->labels = new ArrayCollection();
        $this->bookings = new ArrayCollection();
        $this->queryBookings = new ArrayCollection();
    }

    public function getId()
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;
        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;
        return $this;
    }

    /**
    * @ORM\PrePersist
    */
    public function createDate()
    {
        $this->createdAt = new \DateTime();
    }

    /**
    * @ORM\PreUpdate
    */
    public function updateDate()
    {
        $this->updatedAt = new \DateTime();
    }

    /**
     * @return Collection|UserFile[]
     */
    public function getUserFiles(): Collection
    {
        return $this->userFiles;
    }

    public function addUserFile(UserFile $userFile): self
    {
        if (!$this->userFiles->contains($userFile)) {
            $this->userFiles[] = $userFile;
            $userFile->setFile($this);
        }
        return $this;
    }

    public function removeUserFile(UserFile $userFile): self
    {
        if ($this->userFiles->contains($userFile)) {
            $this->userFiles->removeElement($userFile);
            // set the owning side to null (unless already changed)
            if ($userFile->getFile() === $this) {
                $userFile->setFile(null);
            }
        }
        return $this;
    }

    /**
     * @return Collection|Timetable[]
     */
    public function getTimetables(): Collection
    {
        return $this->timetables;
    }

    public function addTimetable(Timetable $timetable): self
    {
        if (!$this->timetables->contains($timetable)) {
            $this->timetables[] = $timetable;
            $timetable->setFile($this);
        }
        return $this;
    }

    public function removeTimetable(Timetable $timetable): self
    {
        if ($this->timetables->contains($timetable)) {
            $this->timetables->removeElement($timetable);
            // set the owning side to null (unless already changed)
            if ($timetable->getFile() === $this) {
                $timetable->setFile(null);
            }
        }
        return $this;
    }

    /**
     * @return Collection|ResourceClassification[]
     */
    public function getResourceClassifications(): Collection
    {
        return $this->resourceClassifications;
    }

    public function addResourceClassification(ResourceClassification $resourceClassification): self
    {
        if (!$this->resourceClassifications->contains($resourceClassification)) {
            $this->resourceClassifications[] = $resourceClassification;
            $resourceClassification->setFile($this);
        }
        return $this;
    }

    public function removeResourceClassification(ResourceClassification $resourceClassification): self
    {
        if ($this->resourceClassifications->contains($resourceClassification)) {
            $this->resourceClassifications->removeElement($resourceClassification);
            // set the owning side to null (unless already changed)
            if ($resourceClassification->getFile() === $this) {
                $resourceClassification->setFile(null);
            }
        }
        return $this;
    }

    /**
     * @return Collection|Resource[]
     */
    public function getResources(): Collection
    {
        return $this->resources;
    }

    public function addResource(Resource $resource): self
    {
        if (!$this->resources->contains($resource)) {
            $this->resources[] = $resource;
            $resource->setFile($this);
        }
        return $this;
    }

    public function removeResource(Resource $resource): self
    {
        if ($this->resources->contains($resource)) {
            $this->resources->removeElement($resource);
            // set the owning side to null (unless already changed)
            if ($resource->getFile() === $this) {
                $resource->setFile(null);
            }
        }
        return $this;
    }

    /**
     * @return Collection|Planification[]
     */
    public function getPlanifications(): Collection
    {
        return $this->planifications;
    }

    public function addPlanification(Planification $planification): self
    {
        if (!$this->planifications->contains($planification)) {
            $this->planifications[] = $planification;
            $planification->setFile($this);
        }
        return $this;
    }

    public function removePlanification(Planification $planification): self
    {
        if ($this->planifications->contains($planification)) {
            $this->planifications->removeElement($planification);
            // set the owning side to null (unless already changed)
            if ($planification->getFile() === $this) {
                $planification->setFile(null);
            }
        }
        return $this;
    }

    /**
     * @return Collection|Label[]
     */
    public function getLabels(): Collection
    {
        return $this->labels;
    }

    public function addLabel(Label $label): self
    {
        if (!$this->labels->contains($label)) {
            $this->labels[] = $label;
            $label->setFile($this);
        }
        return $this;
    }

    public function removeLabel(Label $label): self
    {
        if ($this->labels->contains($label)) {
            $this->labels->removeElement($label);
            // set the owning side to null (unless already changed)
            if ($label->getFile() === $this) {
                $label->setFile(null);
            }
        }
        return $this;
    }

    /**
     * @return Collection|Booking[]
     */
    public function getBookings(): Collection
    {
        return $this->bookings;
    }

    public function addBooking(Booking $booking): self
    {
        if (!$this->bookings->contains($booking)) {
            $this->bookings[] = $booking;
            $booking->setFile($this);
        }
        return $this;
    }

    public function removeBooking(Booking $booking): self
    {
        if ($this->bookings->contains($booking)) {
            $this->bookings->removeElement($booking);
            // set the owning side to null (unless already changed)
            if ($booking->getFile() === $this) {
                $booking->setFile(null);
            }
        }
        return $this;
    }

    /**
     * @return Collection|QueryBooking[]
     */
    public function getQueryBookings(): Collection
    {
        return $this->queryBookings;
    }

    public function addQueryBooking(QueryBooking $queryBooking): self
    {
        if (!$this->queryBookings->contains($queryBooking)) {
            $this->queryBookings[] = $queryBooking;
            $queryBooking->setFile($this);
        }

        return $this;
    }

    public function removeQueryBooking(QueryBooking $queryBooking): self
    {
        if ($this->queryBookings->contains($queryBooking)) {
            $this->queryBookings->removeElement($queryBooking);
            // set the owning side to null (unless already changed)
            if ($queryBooking->getFile() === $this) {
                $queryBooking->setFile(null);
            }
        }

        return $this;
    }
}
