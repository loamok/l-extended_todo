<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\TimezoneRepository;
use Doctrine\ORM\Mapping as ORM;
/*
 *     collectionOperations={
 *          "get" = { "security_post_denormalize" = "is_granted('list', object)" }, 
 *          "post"= { "security_post_denormalize" = "is_granted('create', object)" }
 *     },
 *     itemOperations={
 *          "get" = { "security" = "is_granted('read', object)" },
 *          "put" = { "security" = "is_granted('update', object)" },
 *          "delete" = { "security" = "is_granted('delete', object)" }
 *     },
 * 
 */
/**
 * @ApiResource(
 *     attributes={
 *          "security"="is_granted('ROLE_USER')", 
 *          "pagination_items_per_page"=10
 *     },
 *     collectionOperations={
 *          "get" = { "security_post_denormalize" = "is_granted('ROLE_USER')" }, 
 *          "post"= { "security_post_denormalize" = "is_granted('admin')" }
 *     },
 *     itemOperations={
 *          "get" = { "security" = "is_granted('ROLE_USER')" },
 *          "put" = { "security" = "is_granted('admin')" },
 *          "delete" = { "security" = "is_granted('admin')" }
 *     },
 * )
 * @ORM\Entity(repositoryClass=TimezoneRepository::class)
 */
class Timezone {
    
    /**
     * @var integer Identifier
     * 
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var string Timezone name eg: europe/paris
     * 
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @var string Display label eg: Paris (UTC +1)
     * 
     * @ORM\Column(type="string", length=255)
     */
    private $label;

    /**
     * @var string Timezone code eg: +01:00
     * 
     * @ORM\Column(type="string", length=255)
     */
    private $code;

    /**
     * 
     * @return int|null
     */
    public function getId(): ?int {
        return $this->id;
    }

    /**
     * 
     * @return string|null
     */
    public function getName(): ?string {
        return $this->name;
    }

    /**
     * 
     * @param string $name
     * @return self
     */
    public function setName(string $name): self {
        $this->name = $name;

        return $this;
    }

    /**
     * 
     * @return string|null
     */
    public function getLabel(): ?string {
        return $this->label;
    }

    /**
     * 
     * @param string $label
     * @return self
     */
    public function setLabel(string $label): self {
        $this->label = $label;

        return $this;
    }

    /**
     * 
     * @return string|null
     */
    public function getCode(): ?string {
        return $this->code;
    }

    /**
     * 
     * @param string $code
     * @return self
     */
    public function setCode(string $code): self {
        $this->code = $code;

        return $this;
    }
    
}
