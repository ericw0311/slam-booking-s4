<?php
namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Table(name="booking_user", uniqueConstraints={@ORM\UniqueConstraint(name="uk_booking_user",columns={"booking_id", "user_file_id"})})
 * @ORM\Entity(repositoryClass="App\Repository\BookingUserRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class BookingUser
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Booking", inversedBy="bookingUsers")
     * @ORM\JoinColumn(nullable=false)
     */
    private $booking;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\UserFile", inversedBy="bookingUsers")
     * @ORM\JoinColumn(nullable=false)
     */
    private $userFile;

    /**
     * @ORM\Column(type="smallint", nullable=false)
     */

    private $oorder;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="bookingUsers")
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


	public function __construct(\App\Entity\User $user, \App\Entity\Booking $booking, \App\Entity\UserFile $userFile)
	{
	$this->setUser($user);
	$this->setBooking($booking);
	$this->setUserFile($userFile);
	}

    public function getId()
    {
        return $this->id;
    }

    public function getBooking(): ?Booking
    {
        return $this->booking;
    }

    public function setBooking(?Booking $booking): self
    {
        $this->booking = $booking;
        return $this;
    }

    public function getUserFile(): ?UserFile
    {
        return $this->userFile;
    }

    public function setUserFile(?UserFile $userFile): self
    {
        $this->userFile = $userFile;
        return $this;
    }

    public function getOrder(): ?int
    {
        return $this->oorder;
    }

    public function setOrder(int $oorder): self
    {
        $this->oorder = $oorder;
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
}
