<?php
namespace App\DBAL\Types;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\ConversionException;
use Doctrine\DBAL\Types\DateTimeType;

/**
 * Description of UTCDateTimeType
 *
 * @author symio
 */

class UTCDateTimeType extends DateTimeType {
    
    /**
     * @var \DateTimeZone
     */
    private static $utc;

    /**
     * {@inheritdoc}
     * 
     * @param \DateTimeInterface $value
     * @param AbstractPlatform $platform
     * @return string|null
     */
    public function convertToDatabaseValue($value, AbstractPlatform $platform) : ?string {
        if ($value instanceof \DateTime && !$value instanceof \DateTimeImmutable && $value->getTimezone()->getName() != self::getUtc()) {
            $value->setTimezone(self::getUtc());
        } elseif ($value instanceof \DateTimeImmutable) {
            // Sometimes, don't ask me why, Symfony/Doctrine give us a DateTimeImmutable object instead of a DateTime
            // and we are not able to change the timezone in a DateTimeImmutable because it is .. Immutable as is name tells us
            $dateTime = new \DateTime();
            $dateTime->setTimestamp($value->getTimestamp());
            $dateTime->setTimezone($value->getTimezone());
            unset($value);
            $value = $dateTime;
            unset($dateTime);
            $value->setTimezone(self::getUtc());
        }

        return parent::convertToDatabaseValue($value, $platform);
    }

    /**
     * {@inheritdoc}
     * 
     * @param \DateTimeInterface|null $value
     * @param AbstractPlatform $platform
     * @return \DateTimeInterface|null
     * @throws ConversionException
     */
    public function convertToPHPValue($value, AbstractPlatform $platform) : ?\DateTimeInterface {
        if (null === $value || $value instanceof \DateTime) {
            return $value;
        }

        $converted = \DateTime::createFromFormat(
            $platform->getDateTimeFormatString(),
            $value,
            self::getUtc()
        );

        if (!$converted) {
            throw ConversionException::conversionFailedFormat(
                $value,
                $this->getName(),
                $platform->getDateTimeFormatString()
            );
        }

        return $converted;
    }
    
    /**
     * 
     * @return \DateTimeZone
     */
    private static function getUtc(): \DateTimeZone {
        return self::$utc ?: self::$utc = new \DateTimeZone('UTC');
    }
}