<?php
namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Table(name="label", uniqueConstraints={@ORM\UniqueConstraint(name="uk_label",columns={"file_id", "name"})})
 * @ORM\Entity(repositoryClass="App\Repository\LabelRepository")
 * @ORM\HasLifecycleCallbacks()
 * @UniqueEntity(fields={"file", "name"}, errorPath="name", message="label.already.exists")
 */

class Label
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
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="labels")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\File", inversedBy="labels")
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
