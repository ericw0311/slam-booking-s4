<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Table(name="planification", uniqueConstraints={@ORM\UniqueConstraint(name="uk_planification",columns={"file_id", "type", "name"})})
 * @ORM\Entity(repositoryClass="App\Repository\PlanificationRepository")
 * @ORM\HasLifecycleCallbacks()
 * @UniqueEntity(fields={"file", "type", "name"}, errorPath="name", message="planification.already.exists")
 */

class Planification
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
    private $type;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="boolean")
     */
    private $internal;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $code;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="planifications")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\File", inversedBy="planifications")
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
     * @ORM\OneToMany(targetEntity="App\Entity\PlanificationPeriod", mappedBy="planification", orphanRemoval=true)
     */
    private $planificationPeriods;

    public function __construct()
    {
        $this->planificationPeriods = new ArrayCollection();
    }

    public function getId()
    {
        return $this->id;
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

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;
        return $this;
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(bool $code): self
    {
        $this->code = $code;
        return $this;
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
     * @return Collection|PlanificationPeriod[]
     */
    public function getPlanificationPeriods(): Collection
    {
        return $this->planificationPeriods;
    }

    public function addPlanificationPeriod(PlanificationPeriod $planificationPeriod): self
    {
        if (!$this->planificationPeriods->contains($planificationPeriod)) {
            $this->planificationPeriods[] = $planificationPeriod;
            $planificationPeriod->setPlanification($this);
        }

        return $this;
    }

    public function removePlanificationPeriod(PlanificationPeriod $planificationPeriod): self
    {
        if ($this->planificationPeriods->contains($planificationPeriod)) {
            $this->planificationPeriods->removeElement($planificationPeriod);
            // set the owning side to null (unless already changed)
            if ($planificationPeriod->getPlanification() === $this) {
                $planificationPeriod->setPlanification(null);
            }
        }

        return $this;
    }
}
