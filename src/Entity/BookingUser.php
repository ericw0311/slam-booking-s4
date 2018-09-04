<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\BookingUserRepository")
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
     * @ORM\Column(type="smallint")
     */
    private $oorder;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="bookingUsers")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

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

    public function getOorder(): ?int
    {
        return $this->oorder;
    }

    public function setOorder(int $oorder): self
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
}
