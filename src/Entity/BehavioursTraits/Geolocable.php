<?php
namespace App\Entity\BehavioursTraits;

use ApiPlatform\Core\Annotation\ApiProperty;
use App\DBAL\Types\Point;
use CrEOF\Spatial\PHP\Types\Geography\GeographyInterface;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Serializer\Annotation\Groups;

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
     *                 "latitude": 48.856384,
     *                 "longitude": 2.289589
     *             }
     *         }
     *     }
     * )
     * @var \App\DTO\Point
     * 
     * @Gedmo\Versioned
     * @ORM\Column(type="geogpoint", nullable=true)
     * @Groups({"read", "write"})
     */
    private $geo;
    
    /**
     * 
     * @return GeographyInterface|null
     */
    public function getGeo() {
        if(is_a($this->geo, \App\DTO\Point::class)) {
            $this->geo = new Point([$this->geo->getLongitude(), $this->geo->getLatitude()]);
        }
        return $this->geo;
    }

    /**
     * 
     * @param Point|null $geo
     * @return \self
     */
    public function setGeo($geo = null): self {
        if(is_a($geo, \App\DTO\Point::class)) {
            $this->geo = new Point([$geo->getLongitude(), $geo->getLatitude()]);
        }
        if(is_a($this->geo, \App\DTO\Point::class)) {
            $this->geo = new Point([$this->geo->getLongitude(), $this->geo->getLatitude()]);
        }
        

        return $this;
    }

}
