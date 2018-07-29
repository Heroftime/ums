<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Security\Core\User\UserInterface;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 */
class User implements UserInterface
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=150)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $password;

    /**
     * @ORM\Column(type="boolean", options={"default"="0"})
     */
    private $isAdmin;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Groups", inversedBy="users")
     */
    private $ghroup;

    private $groups;

    public function __construct()
    {
        $this->ghroup = new ArrayCollection();
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

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getIsAdmin(): ?bool
    {
        return $this->isAdmin;
    }

    public function setIsAdmin(bool $isAdmin): self
    {
        $this->isAdmin = $isAdmin;

        return $this;
    }

    /**
     * @return Collection|Groups[]
     */
    public function getGhroup(): Collection
    {
        return $this->ghroup;
    }

    /**
     * @return Collection|Groups[]
     */
    public function getGroups(): Collection
    {
        return $this->ghroup;
    }

    public function addGhroup(Groups $ghroup): self
    {
        if (!$this->ghroup->contains($ghroup)) {
            $this->ghroup[] = $ghroup;
        }

        return $this;
    }

    public function removeGhroup(Groups $ghroup): self
    {
        if ($this->ghroup->contains($ghroup)) {
            $this->ghroup->removeElement($ghroup);
        }

        return $this;
    }

    public function getRoles()
    {   
        $role = 'ROLE_USER';
        if ($this->getIsAdmin())
        {
            $role = 'ROLE_ADMIN';
        }

        return array($role);
    }

    public function getUsername(): ?string
    {
        return $this->name;
    }

    public function getSalt()
    {
        return null;
    }

    public function eraseCredentials()
    {
    }
}
