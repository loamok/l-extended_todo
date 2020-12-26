<?php
namespace App\Entity\BehavioursTraits;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use CrEOF\Spatial\PHP\Types\Geography\Point;
use ApiPlatform\Core\Annotation\ApiProperty;

/**
 *
 * @author symio
 */
trait Geolocable {
    
    /**
     * Geolocation of the Event
     * 
     * @ApiProperty(
     *     attributes={
     *         "jsonld_context"={
     *             "@id"="geo",
     *             "@type"="Point(latitude, longitude)",
     *             "hydra:description"="Geolocation of the Event",
     *             "geo"={
     *                 "latitude"=48.856384,
     *                 "longitude"=2.289589
     *             }
     *         }
     *     }
     * )
     * @var Point
     * 
     * @Gedmo\Versioned
     * @ORM\Column(type="geogpoint", nullable=true)
     */
    private $geo;
    
    /**
     * 
     * @return Point|null
     */
    public function getGeo() : ?Point {
        return $this->geo;
    }

    /**
     * 
     * @param Point|null $geo
     * @return \self
     */
    public function setGeo(?Point $geo): self {
        $this->geo = $geo;

        return $this;
    }

}
