<?php

namespace App\Entity\BehavioursTraits;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 *
 * @author symio
 */
trait Locationable {
    
    /**
     * @var string
     * 
     * @Gedmo\Versioned
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"read", "write"})
     */
    private $location;

    /**
     * 
     * @return string|null
     */
    public function getLocation(): ?string {
        return $this->location;
    }

    /**
     * 
     * @param string|null $location
     * @return \self
     */
    public function setLocation(?string $location): self {
        $this->location = $location;

        return $this;
    }
}
