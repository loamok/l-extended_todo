<?php
namespace App\Entity\BehavioursTraits;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\IdGenerator\UuidV4Generator;
use Symfony\Component\Uid\AbstractUid;
use Symfony\Component\Uid\UuidV4;

/**
 *
 * @author symio
 */
trait UuidIdentifiable {
    
    /**
     * @var UuidV4
     * 
     * @ORM\Id
     * @ORM\Column(type="uuid", unique=true)
     * @ORM\GeneratedValue(strategy="CUSTOM")
     * @ORM\CustomIdGenerator(class=UuidV4Generator::class)
     */
    private $id;

    /**
     * 
     * @return UuidV4|null
     */
    public function getId(): ?UuidV4 {
        return $this->id;
    }
    
    public function __toString() : string {
        return $this->getId()->__toString();
    }
    
}
