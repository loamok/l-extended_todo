<?php

namespace App\Entity;

use App\Repository\CategoryRepository;

use ApiPlatform\Core\Annotation\ApiResource;

use App\Entity\BehavioursTraits\CodeAble;
use App\Entity\BehavioursTraits\UuidIdentifiable;

use Doctrine\ORM\Mapping as ORM;

use Symfony\Bridge\Doctrine\IdGenerator\UuidV4Generator;
use Symfony\Component\Uid\AbstractUid;
use Symfony\Component\Uid\UuidV4;

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
 * @ORM\Entity(repositoryClass=CategoryRepository::class)
 */
class Category {
    
    use UuidIdentifiable,
        CodeAble;
    
}
