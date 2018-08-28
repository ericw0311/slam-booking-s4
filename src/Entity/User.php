<?php
namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 * @ORM\Table(name="user")
 * @UniqueEntity(fields="email", message="Email déjà pris")
 * @UniqueEntity(fields="userName", message="Username déjà pris")
 */
class User implements UserInterface, \Serializable
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(name="user_name", type="string", length=255, unique=true)
     * @Assert\NotBlank()
     */
    private $userName;

    /**
     * @ORM\Column(type="string", length=255, unique=true)
     * @Assert\NotBlank()
     * @Assert\Email()
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=64)
     */
    private $password;

    /**
     * @ORM\Column(type="json")
     */
    private $roles;

    /**
     * @ORM\Column(name="account_type", type="string", length=255)
     */
    private $accountType = "INDIVIDUAL";

    /**
     * @ORM\Column(name="first_name", type="string", length=255)
     * @Assert\NotBlank()
     */
    private $firstName;

    /**
     * @ORM\Column(name="last_name", type="string", length=255)
     * @Assert\NotBlank()
     */
    private $lastName;

    /**
     * @ORM\Column(name="unique_name", type="string", length=255, nullable=true)
     */
    private $uniqueName;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\File", mappedBy="user", orphanRemoval=true)
     */
    private $files;

    public function __construct()
    {
        $this->files = new ArrayCollection();
        $this->userFiles = new ArrayCollection();
        $this->userParameters = new ArrayCollection();
        $this->timetables = new ArrayCollection();
        $this->timetableLines = new ArrayCollection();
        $this->resourceClassifications = new ArrayCollection();
        $this->resources = new ArrayCollection();
        $this->planifications = new ArrayCollection();
        $this->labels = new ArrayCollection();
    }

    public function getId()
    {
        return $this->id;
    }

    public function getUserName(): ?string
    {
        return $this->userName;
    }

    public function setUserName(string $userName): self
    {
        $this->userName = $userName;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(?string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getRoles(): ?array
    {
		$roles = $this->roles;

		// Afin d'être sûr qu'un user a toujours au moins 1 rôle
		if (empty($roles)) {
			$roles[] = 'ROLE_USER';
		}

		return array_unique($roles);
	}

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    public function getAccountType(): ?string
    {
        return $this->accountType;
    }

    public function setAccountType(string $accountType): self
    {
        $this->accountType = $accountType;

        return $this;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): self
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getUniqueName(): ?string
    {
        return $this->uniqueName;
    }

    public function setUniqueName(?string $uniqueName): self
    {
        $this->uniqueName = $uniqueName;

        return $this;
    }

    public function getFirstAndLastName()
    {
        return $this->getFirstName().' '.$this->getLastName();
    }

	/**
    * @Assert\IsTrue(message="user.organisation.name.null")
    */
    public function isUniqueName()
    {
        return ($this->getAccountType() != 'ORGANISATION' or $this->getUniqueName() !== null);
    }

	/**
     * Retour le salt qui a servi à coder le mot de passe
     *
     * {@inheritdoc}
     */
    public function getSalt(): ?string
    {
        // See "Do you need to use a Salt?" at https://symfony.com/doc/current/cookbook/security/entity_provider.html
        // we're using bcrypt in security.yml to encode the password, so
        // the salt value is built-in and you don't have to generate one
        return null;
    }
 
    /**
     * Removes sensitive data from the user.
     *
     * {@inheritdoc}
     */
    public function eraseCredentials(): void
    {
        // Nous n'avons pas besoin de cette methode car nous n'utilions pas de plainPassword
        // Mais elle est obligatoire car comprise dans l'interface UserInterface
        // $this->plainPassword = null;
    }
 
	/**
     * {@inheritdoc}
     */
    public function serialize(): string
    {
		return serialize([$this->id, $this->userName, $this->password]);
    }

    /**
     * {@inheritdoc}
     */
	public function unserialize($serialized): void
    {
        [$this->id, $this->userName, $this->password] = unserialize($serialized, ['allowed_classes' => false]);
    }

 private $__EXTRA__LINE;

 /**
  * @ORM\OneToMany(targetEntity="App\Entity\UserFile", mappedBy="user", orphanRemoval=true)
  */
 private $userFiles;

 /**
  * @ORM\OneToMany(targetEntity="App\Entity\UserParameter", mappedBy="user", orphanRemoval=true)
  */
 private $userParameters;
 /**
  * @ORM\OneToMany(targetEntity="App\Entity\Timetable", mappedBy="user", orphanRemoval=true)
  */
 private $timetables;

 /**
  * @ORM\OneToMany(targetEntity="App\Entity\TimetableLine", mappedBy="user", orphanRemoval=true)
  */
 private $timetableLines;

 /**
  * @ORM\OneToMany(targetEntity="App\Entity\ResourceClassification", mappedBy="user", orphanRemoval=true)
  */
 private $resourceClassifications;

 /**
  * @ORM\OneToMany(targetEntity="App\Entity\Resource", mappedBy="user", orphanRemoval=true)
  */
 private $resources;

 /**
  * @ORM\OneToMany(targetEntity="App\Entity\Planification", mappedBy="user", orphanRemoval=true)
  */
 private $planifications;
 /**
  * @ORM\OneToMany(targetEntity="App\Entity\Label", mappedBy="user", orphanRemoval=true)
  */
 private $labels;



 /**
  * @return Collection|File[]
  */
 public function getFiles(): Collection
 {
     return $this->files;
 }
 
 public function addFile(File $file): self
 {
     if (!$this->files->contains($file)) {
         $this->files[] = $file;
         $file->setUser($this);
     }
     $__EXTRA__LINE;
     return $this;
 }
 
 public function removeFile(File $file): self
 {
     if ($this->files->contains($file)) {
         $this->files->removeElement($file);
         // set the owning side to null (unless already changed)
         if ($file->getUser() === $this) {
             $file->setUser(null);
         }
     }
     $__EXTRA__LINE;
     return $this;
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
         $userFile->setUser($this);
     }
     $__EXTRA__LINE;
     return $this;
 }

 public function removeUserFile(UserFile $userFile): self
 {
     if ($this->userFiles->contains($userFile)) {
         $this->userFiles->removeElement($userFile);
         // set the owning side to null (unless already changed)
         if ($userFile->getUser() === $this) {
             $userFile->setUser(null);
         }
     }
     $__EXTRA__LINE;
     return $this;
 }

 /**
  * @return Collection|UserParameter[]
  */
 public function getUserParameters(): Collection
 {
     return $this->userParameters;
 }

 public function addUserParameter(UserParameter $userParameter): self
 {
     if (!$this->userParameters->contains($userParameter)) {
         $this->userParameters[] = $userParameter;
         $userParameter->setUser($this);
     }
     $__EXTRA__LINE;
     return $this;
 }

 public function removeUserParameter(UserParameter $userParameter): self
 {
     if ($this->userParameters->contains($userParameter)) {
         $this->userParameters->removeElement($userParameter);
         // set the owning side to null (unless already changed)
         if ($userParameter->getUser() === $this) {
             $userParameter->setUser(null);
         }
     }
     $__EXTRA__LINE;
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
         $timetable->setUser($this);
     }
     $__EXTRA__LINE;
     return $this;
 }

 public function removeTimetable(Timetable $timetable): self
 {
     if ($this->timetables->contains($timetable)) {
         $this->timetables->removeElement($timetable);
         // set the owning side to null (unless already changed)
         if ($timetable->getUser() === $this) {
             $timetable->setUser(null);
         }
     }
     $__EXTRA__LINE;
     return $this;
 }

 /**
  * @return Collection|TimetableLine[]
  */
 public function getTimetableLines(): Collection
 {
     return $this->timetableLines;
 }

 public function addTimetableLine(TimetableLine $timetableLine): self
 {
     if (!$this->timetableLines->contains($timetableLine)) {
         $this->timetableLines[] = $timetableLine;
         $timetableLine->setUser($this);
     }
     $__EXTRA__LINE;
     return $this;
 }

 public function removeTimetableLine(TimetableLine $timetableLine): self
 {
     if ($this->timetableLines->contains($timetableLine)) {
         $this->timetableLines->removeElement($timetableLine);
         // set the owning side to null (unless already changed)
         if ($timetableLine->getUser() === $this) {
             $timetableLine->setUser(null);
         }
     }
     $__EXTRA__LINE;
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
            $resourceClassification->setUser($this);
        }

        return $this;
    }

    public function removeResourceClassification(ResourceClassification $resourceClassification): self
    {
       if ($this->resourceClassifications->contains($resourceClassification)) {
            $this->resourceClassifications->removeElement($resourceClassification);
            // set the owning side to null (unless already changed)
            if ($resourceClassification->getUser() === $this) {
                $resourceClassification->setUser(null);
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
            $resource->setUser($this);
        }
        return $this;
    }

    public function removeResource(Resource $resource): self
    {
        if ($this->resources->contains($resource)) {
            $this->resources->removeElement($resource);
            // set the owning side to null (unless already changed)
            if ($resource->getUser() === $this) {
                $resource->setUser(null);
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
            $planification->setUser($this);
        }
        return $this;
    }

    public function removePlanification(Planification $planification): self
    {
        if ($this->planifications->contains($planification)) {
            $this->planifications->removeElement($planification);
            // set the owning side to null (unless already changed)
            if ($planification->getUser() === $this) {
                $planification->setUser(null);
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
            $label->setUser($this);
        }
        return $this;
    }

    public function removeLabel(Label $label): self
    {
        if ($this->labels->contains($label)) {
            $this->labels->removeElement($label);
            // set the owning side to null (unless already changed)
            if ($label->getUser() === $this) {
                $label->setUser(null);
            }
        }
        return $this;
    }
}
