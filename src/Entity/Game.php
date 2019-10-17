<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\GameRepository")
 */
class Game
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $x1;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $x2;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $x3;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $x4;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $x5;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $x6;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $x7;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $x8;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $x9;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getX1(): ?string
    {
        return $this->x1;
    }

    public function setX1(?string $x1): self
    {
        $this->x1 = $x1;

        return $this;
    }

    public function getX2(): ?string
    {
        return $this->x2;
    }

    public function setX2(?string $x2): self
    {
        $this->x2 = $x2;

        return $this;
    }

    public function getX3(): ?string
    {
        return $this->x3;
    }

    public function setX3(?string $x3): self
    {
        $this->x3 = $x3;

        return $this;
    }

    public function getX4(): ?string
    {
        return $this->x4;
    }

    public function setX4(?string $x4): self
    {
        $this->x4 = $x4;

        return $this;
    }

    public function getX5(): ?string
    {
        return $this->x5;
    }

    public function setX5(?string $x5): self
    {
        $this->x5 = $x5;

        return $this;
    }

    public function getX6(): ?string
    {
        return $this->x6;
    }

    public function setX6(?string $x6): self
    {
        $this->x6 = $x6;

        return $this;
    }

    public function getX7(): ?string
    {
        return $this->x7;
    }

    public function setX7(?string $x7): self
    {
        $this->x7 = $x7;

        return $this;
    }

    public function getX8(): ?string
    {
        return $this->x8;
    }

    public function setX8(?string $x8): self
    {
        $this->x8 = $x8;

        return $this;
    }

    public function getX9(): ?string
    {
        return $this->x9;
    }

    public function setX9(?string $x9): self
    {
        $this->x9 = $x9;

        return $this;
    }
}
