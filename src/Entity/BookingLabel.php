<?php

namespace App\Entity;
use Doctrine\ORM\Mapping as ORM;

use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Table(name="booking_label", uniqueConstraints={@ORM\UniqueConstraint(name="uk_booking_label",columns={"booking_id", "label_id"})})
 * @ORM\Entity(repositoryClass="App\Repository\BookingLabelRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class BookingLabel
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Booking", inversedBy="bookingLabels")
     * @ORM\JoinColumn(nullable=false)
     */
    private $booking;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Label", inversedBy="bookingLabels")
     * @ORM\JoinColumn(nullable=false)
     */
    private $label;

    /**
     * @ORM\Column(type="smallint", nullable=false)
     */
    private $oorder;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="bookingLabels")
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

	public function __construct(\App\Entity\User $user, \App\Entity\Booking $booking, \App\Entity\Label $label)
	{
	$this->setUser($user);
	$this->setBooking($booking);
	$this->setLabel($label);
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

    public function getLabel(): ?Label
    {
        return $this->label;
    }

    public function setLabel(?Label $label): self
    {
        $this->label = $label;
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
