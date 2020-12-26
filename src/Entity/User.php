<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use App\Entity\BehavioursTraits\UuidIdentifiable;

use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @ApiResource(
 *     normalizationContext={"groups"={"user", "user:read"}},
 *     denormalizationContext={"groups"={"user", "user:write"}}
 * )
 */
class User implements UserInterface {
    
    use UuidIdentifiable;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     */
    private $email;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     */
    private $password;
//
//    /**
//     * @ORM\OneToMany(targetEntity=Delegation::class, mappedBy="user")
//     */
//    private $delegations;
//    
//    /**
//     * @ORM\OneToMany(targetEntity=Delegation::class, mappedBy="owner")
//     */
//    private $delegationsOwned;

    public function __construct() {
//        $this->delegations = new ArrayCollection();
//        $this->delegationsOwned = new ArrayCollection();
    }

    public function getEmail(): ?string {
        return $this->email;
    }

    public function setEmail(string $email): self {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getPassword(): string {
        return (string) $this->password;
    }

    public function setPassword(string $password): self {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getSalt() {
        // not needed when using the "bcrypt" algorithm in security.yaml
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials() {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }
//
//    /**
//     * @return Collection|Delegation[]
//     */
//    public function getDelegations(): Collection {
//        return $this->delegations;
//    }
//
//    public function addDelegation(Delegation $delegation): self {
//        if (!$this->delegations->contains($delegation)) {
//            $this->delegations[] = $delegation;
//            $delegation->setUser($this);
//        }
//
//        return $this;
//    }
//
//    public function removeDelegation(Delegation $delegation): self {
//        if ($this->delegations->removeElement($delegation)) {
//            // set the owning side to null (unless already changed)
//            if ($delegation->getUser() === $this) {
//                $delegation->setUser(null);
//            }
//        }
//
//        return $this;
//    }
//
//    /**
//     * @return Collection|Delegation[]
//     */
//    public function getDelegationsOwned(): Collection {
//        return $this->delegationsOwned;
//    }
//
//    public function addDelegationOwned(Delegation $delegation): self {
//        if (!$this->delegationsOwned->contains($delegation)) {
//            $this->delegationsOwned[] = $delegation;
//            $delegationOwned->setOwner($this);
//        }
//
//        return $this;
//    }
//
//    public function removeDelegationOwned(Delegation $delegation): self {
//        if ($this->delegationsOwned->removeElement($delegation)) {
//            // set the owning side to null (unless already changed)
//            if ($delegationOwned->getOwner() === $this) {
//                $delegationOwned->setOwner(null);
//            }
//        }
//
//        return $this;
//    }

}
