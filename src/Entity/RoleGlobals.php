<?php

namespace App\Entity;

use App\Repository\RoleGlobalsRepository;
use App\Entity\BehavioursTraits\UuidIdentifiable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

use Ramsey\Uuid\Doctrine\UuidOrderedTimeGenerator;
use Ramsey\Uuid\UuidInterface;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=RoleGlobalsRepository::class)
 */
class RoleGlobals {
    
    use UuidIdentifiable;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $role;

    /**
     * @ORM\ManyToMany(targetEntity=Rights::class)
     */
    private $rights;

    public function __construct() {
        $this->rights = new ArrayCollection();
    }

    /**
     * 
     * @return string|null
     */
    public function getRole(): ?string {
        return $this->role;
    }

    /**
     * 
     * @param string $role
     * @return self
     */
    public function setRole(string $role): self {
        $this->role = $role;

        return $this;
    }

    /**
     * @return Collection|Rights[]
     */
    public function getRights(): Collection {
        return $this->rights;
    }

    /**
     * 
     * @param rights $right
     * @return self
     */
    public function addRight(rights $right): self {
        if (!$this->rights->contains($right)) {
            $this->rights[] = $right;
        }

        return $this;
    }

    /**
     * 
     * @param rights $right
     * @return self
     */
    public function removeRight(rights $right): self {
        $this->rights->removeElement($right);

        return $this;
    }

}
