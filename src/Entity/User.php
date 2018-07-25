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
}
