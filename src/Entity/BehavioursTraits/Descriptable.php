<?php
namespace App\Entity\BehavioursTraits;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 *
 * @author symio
 */
trait Descriptable {
    
    /**
     * @var string
     * 
     * @Gedmo\Versioned
     * @ORM\Column(type="string", length=255)
     * @Groups({"read", "write"})
     */
    private $summary;

    /**
     * @var string
     * 
     * @Gedmo\Versioned
     * @ORM\Column(type="text", nullable=true)
     * @Groups({"read", "write"})
     */
    private $description;

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
    public function getSummary(): ?string {
        return $this->summary;
    }
    
    /**
     * 
     * @param string $summary
     * @return \self
     */
    public function setSummary(string $summary): self {
        $this->summary = $summary;

        return $this;
    }

    /**
     * 
     * @return string|null
     */
    public function getDescription(): ?string {
        return $this->description;
    }

    /**
     * 
     * @param string|null $description
     * @return \self
     */
    public function setDescription(?string $description): self {
        $this->description = $description;

        return $this;
    }

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
