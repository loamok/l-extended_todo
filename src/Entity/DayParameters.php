<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\DayParametersRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ApiResource()
 * @ORM\Entity(repositoryClass=DayParametersRepository::class)
 */
class DayParameters
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="time", nullable=true)
     */
    private $amStart;

    /**
     * @ORM\Column(type="time", nullable=true)
     */
    private $amPauseStart;

    /**
     * @ORM\Column(type="time", nullable=true)
     */
    private $amPauseEnd;

    /**
     * @ORM\Column(type="time", nullable=true)
     */
    private $amEnd;

    /**
     * @ORM\Column(type="time", nullable=true)
     */
    private $pmStart;

    /**
     * @ORM\Column(type="time", nullable=true)
     */
    private $pmPauseStart;

    /**
     * @ORM\Column(type="time", nullable=true)
     */
    private $pmPauseEnd;

    /**
     * @ORM\Column(type="time", nullable=true)
     */
    private $pmEnd;

    /**
     * @ORM\Column(type="dateinterval", nullable=true)
     */
    private $amPauseDuration;

    /**
     * @ORM\Column(type="dateinterval", nullable=true)
     */
    private $pmPauseDuration;

    /**
     * @ORM\Column(type="dateinterval", nullable=true)
     */
    private $amPmPauseDuration;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAmStart(): ?\DateTimeInterface
    {
        return $this->amStart;
    }

    public function setAmStart(?\DateTimeInterface $amStart): self
    {
        $this->amStart = $amStart;

        return $this;
    }

    public function getAmPauseStart(): ?\DateTimeInterface
    {
        return $this->amPauseStart;
    }

    public function setAmPauseStart(?\DateTimeInterface $amPauseStart): self
    {
        $this->amPauseStart = $amPauseStart;

        return $this;
    }

    public function getAmPauseEnd(): ?\DateTimeInterface
    {
        return $this->amPauseEnd;
    }

    public function setAmPauseEnd(?\DateTimeInterface $amPauseEnd): self
    {
        $this->amPauseEnd = $amPauseEnd;

        return $this;
    }

    public function getAmEnd(): ?\DateTimeInterface
    {
        return $this->amEnd;
    }

    public function setAmEnd(?\DateTimeInterface $amEnd): self
    {
        $this->amEnd = $amEnd;

        return $this;
    }

    public function getPmStart(): ?\DateTimeInterface
    {
        return $this->pmStart;
    }

    public function setPmStart(?\DateTimeInterface $pmStart): self
    {
        $this->pmStart = $pmStart;

        return $this;
    }

    public function getPmPauseStart(): ?\DateTimeInterface
    {
        return $this->pmPauseStart;
    }

    public function setPmPauseStart(?\DateTimeInterface $pmPauseStart): self
    {
        $this->pmPauseStart = $pmPauseStart;

        return $this;
    }

    public function getPmPauseEnd(): ?\DateTimeInterface
    {
        return $this->pmPauseEnd;
    }

    public function setPmPauseEnd(?\DateTimeInterface $pmPauseEnd): self
    {
        $this->pmPauseEnd = $pmPauseEnd;

        return $this;
    }

    public function getPmEnd(): ?\DateTimeInterface
    {
        return $this->pmEnd;
    }

    public function setPmEnd(?\DateTimeInterface $pmEnd): self
    {
        $this->pmEnd = $pmEnd;

        return $this;
    }

    public function getAmPauseDuration(): ?\DateInterval
    {
        return $this->amPauseDuration;
    }

    public function setAmPauseDuration(?\DateInterval $amPauseDuration): self
    {
        $this->amPauseDuration = $amPauseDuration;

        return $this;
    }

    public function getPmPauseDuration(): ?\DateInterval
    {
        return $this->pmPauseDuration;
    }

    public function setPmPauseDuration(?\DateInterval $pmPauseDuration): self
    {
        $this->pmPauseDuration = $pmPauseDuration;

        return $this;
    }

    public function getAmPmPauseDuration(): ?\DateInterval
    {
        return $this->amPmPauseDuration;
    }

    public function setAmPmPauseDuration(?\DateInterval $amPmPauseDuration): self
    {
        $this->amPmPauseDuration = $amPmPauseDuration;

        return $this;
    }
}
