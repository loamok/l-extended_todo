<?php

namespace App\Entity;

use App\Entity\BehavioursTraits\CodeAble;
use App\Entity\BehavioursTraits\UuidIdentifiable;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\DelegationTypeRepository;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

use Symfony\Bridge\Doctrine\IdGenerator\UuidV4Generator;
use Symfony\Component\Uid\AbstractUid;
use Symfony\Component\Uid\UuidV4;

/**
 * @ApiResource(
 *     attributes={"security"="is_granted('ROLE_USER')"},
 *     collectionOperations={
 *          "get" = { "security_post_denormalize" = "is_granted('list', object)" }, 
 *          "post"= { "security_post_denormalize" = "is_granted('create', object)" }
 *     },
 *     itemOperations={
 *          "get" = { "security" = "is_granted('read', object)" },
 *          "put" = { "security" = "is_granted('update', object)" },
 *          "delete" = { "security" = "is_granted('delete', object)" }
 *     },
 * )
 * @ORM\Entity(repositoryClass=DelegationTypeRepository::class)
 */
class DelegationType {
    
    use UuidIdentifiable,
        CodeAble;
    
    /**
     * @ORM\ManyToMany(targetEntity=Rights::class)
     */
    private $rights;

    public function __construct()
    {
        $this->rights = new ArrayCollection();
    }

    /**
     * @return Collection|Rights[]
     */
    public function getRights(): Collection
    {
        return $this->rights;
    }

    public function addRight(Rights $right): self
    {
        if (!$this->rights->contains($right)) {
            $this->rights[] = $right;
        }

        return $this;
    }

    public function removeRight(Rights $right): self
    {
        $this->rights->removeElement($right);

        return $this;
    }
}
