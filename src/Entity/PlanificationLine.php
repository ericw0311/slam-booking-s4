<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Table(name="planification_line", uniqueConstraints={@ORM\UniqueConstraint(name="uk_planification_line",columns={"planification_period_id", "week_day"})})
 * @ORM\Entity(repositoryClass="App\Repository\PlanificationLineRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class PlanificationLine
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\PlanificationPeriod", inversedBy="planificationLines")
     * @ORM\JoinColumn(nullable=false)
     */
    private $planificationPeriod;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $weekDay;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Timetable", inversedBy="planificationLines")
     * @ORM\JoinColumn(nullable=false)
     */
    private $timetable;

    /**
     * @ORM\Column(type="smallint")
     */
    private $oorder;

    /**
     * @ORM\Column(type="boolean")
     */
    private $active;

    /**
     * @ORM\Column(name="created_at", type="datetime", nullable=false)
     */
    private $createdAt;

    /**
     * @ORM\Column(name="updated_at", type="datetime", nullable=true)
     */
    private $updatedAt;

    public function getId()
    {
        return $this->id;
    }

    public function getPlanificationPeriod(): ?PlanificationPeriod
    {
        return $this->planificationPeriod;
    }

    public function setPlanificationPeriod(?PlanificationPeriod $planificationPeriod): self
    {
        $this->planificationPeriod = $planificationPeriod;

        return $this;
    }

    public function getWeekDay(): ?string
    {
        return $this->weekDay;
    }

    public function setWeekDay(string $weekDay): self
    {
        $this->weekDay = $weekDay;

        return $this;
    }

    public function getTimetable(): ?Timetable
    {
        return $this->timetable;
    }

    public function setTimetable(?Timetable $timetable): self
    {
        $this->timetable = $timetable;

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

    public function getActive(): ?bool
    {
        return $this->active;
    }

    public function setActive(bool $active): self
    {
        $this->active = $active;

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
