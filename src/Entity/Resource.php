<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * Resource
 *
 * @ORM\Table(name="resource", uniqueConstraints={@ORM\UniqueConstraint(name="uk_resource",columns={"file_id", "type", "name"})})
 * @ORM\Entity(repositoryClass="App\Repository\ResourceRepository")
 * @ORM\HasLifecycleCallbacks()
 * @UniqueEntity(fields={"file", "type", "name"}, errorPath="name", message="resource.already.exists")
 */

class Resource
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="boolean")
     */
    private $internal;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $type;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $code;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\ResourceClassification", inversedBy="resources")
     * @ORM\JoinColumn(nullable=true)
     */
    private $classification;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="resources")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\File", inversedBy="resources")
     * @ORM\JoinColumn(nullable=false)
     */
    private $file;

    /**
     * @ORM\Column(name="created_at", type="datetime", nullable=false)
     */
    private $createdAt;

    /**
     * @ORM\Column(name="updated_at", type="datetime", nullable=true)
     */
    private $updatedAt;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\UserFile", mappedBy="resource", cascade={"persist", "remove"})
     */
    private $userFile;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\PlanificationResource", mappedBy="resource")
     */
    private $planificationResources;

    public function getId()
    {
        return $this->id;
    }

    public function getInternal(): ?bool
    {
        return $this->internal;
    }

    public function setInternal(bool $internal): self
    {
        $this->internal = $internal;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(string $code): self
    {
        $this->code = $code;

        return $this;
    }

    public function getClassification(): ?ResourceClassification
    {
        return $this->classification;
    }

    public function setClassification(?ResourceClassification $classification): self
    {
        $this->classification = $classification;

        return $this;
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

    public function getFile(): ?File
    {
        return $this->file;
    }

    public function setFile(?File $file): self
    {
        $this->file = $file;

        return $this;
    }

	public function __construct(\App\Entity\User $user, \App\Entity\File $file)
    {
    $this->setUser($user);
    $this->setFile($file);
    $this->planificationResources = new ArrayCollection();
    $this->bookings = new ArrayCollection();
    $this->bookingLines = new ArrayCollection();
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

	public function getUserFile(): ?UserFile
	{
	return $this->userFile;
	}

	public function setUserFile(?UserFile $userFile): self
	{
	$this->userFile = $userFile;
	$__EXTRA__LINE;
	// set (or unset) the owning side of the relation if necessary
	$newResource = $userFile === null ? null : $this;
	if ($newResource !== $userFile->getResource()) {
		$userFile->setResource($newResource);
	}
	$__EXTRA__LINE;
	return $this;
	}
 private $__EXTRA__LINE;

 /**
  * @ORM\OneToMany(targetEntity="App\Entity\Booking", mappedBy="resource")
  */
 private $bookings;

 /**
  * @ORM\OneToMany(targetEntity="App\Entity\BookingLine", mappedBy="resource")
  */
 private $bookingLines;
 /**
  * @return Collection|PlanificationResource[]
  */
 public function getPlanificationResources(): Collection
 {
     return $this->planificationResources;
 }

 public function addPlanificationResource(PlanificationResource $planificationResource): self
 {
     if (!$this->planificationResources->contains($planificationResource)) {
         $this->planificationResources[] = $planificationResource;
         $planificationResource->setResource($this);
     }
     $__EXTRA__LINE;
     return $this;
 }

 public function removePlanificationResource(PlanificationResource $planificationResource): self
 {
     if ($this->planificationResources->contains($planificationResource)) {
         $this->planificationResources->removeElement($planificationResource);
         // set the owning side to null (unless already changed)
         if ($planificationResource->getResource() === $this) {
             $planificationResource->setResource(null);
         }
     }
     $__EXTRA__LINE;
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
         $booking->setResource($this);
     }
     $__EXTRA__LINE;
     return $this;
 }
 
 public function removeBooking(Booking $booking): self
 {
     if ($this->bookings->contains($booking)) {
         $this->bookings->removeElement($booking);
         // set the owning side to null (unless already changed)
         if ($booking->getResource() === $this) {
             $booking->setResource(null);
         }
     }
     $__EXTRA__LINE;
     return $this;
 }

 /**
  * @return Collection|BookingLine[]
  */
 public function getBookingLines(): Collection
 {
     return $this->bookingLines;
 }

 public function addBookingLine(BookingLine $bookingLine): self
 {
     if (!$this->bookingLines->contains($bookingLine)) {
         $this->bookingLines[] = $bookingLine;
         $bookingLine->setResource($this);
     }
     $__EXTRA__LINE;
     return $this;
 }

 public function removeBookingLine(BookingLine $bookingLine): self
 {
     if ($this->bookingLines->contains($bookingLine)) {
         $this->bookingLines->removeElement($bookingLine);
         // set the owning side to null (unless already changed)
         if ($bookingLine->getResource() === $this) {
             $bookingLine->setResource(null);
         }
     }
     $__EXTRA__LINE;
     return $this;
 }
}
