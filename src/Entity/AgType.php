<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Entity\BehavioursTraits\CodeAble;
use App\Repository\AgTypeRepository;
use App\Entity\BehavioursTraits\UuidIdentifiable;
use Doctrine\ORM\Mapping as ORM;

use Symfony\Bridge\Doctrine\IdGenerator\UuidV4Generator;
use Symfony\Component\Uid\AbstractUid;
use Symfony\Component\Uid\UuidV4;

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
 * @ORM\Entity(repositoryClass=AgTypeRepository::class)
 */
class AgType {
    
    use UuidIdentifiable,
        CodeAble;
    
}
