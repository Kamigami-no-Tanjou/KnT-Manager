<?php

namespace App\Entity;

use DateTime;

class Map
{
    private int $id;
    private string $name;
    private ?DateTime $establishmentDate;
    private ?DateTime $expiryDate;
    private ?Nation $nation;
    private string $content;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return DateTime|null
     */
    public function getEstablishmentDate(): ?DateTime
    {
        return $this->establishmentDate;
    }

    /**
     * @param DateTime|null $establishmentDate
     */
    public function setEstablishmentDate(?DateTime $establishmentDate): void
    {
        $this->establishmentDate = $establishmentDate;
    }

    /**
     * @return DateTime|null
     */
    public function getExpiryDate(): ?DateTime
    {
        return $this->expiryDate;
    }

    /**
     * @param DateTime|null $expiryDate
     */
    public function setExpiryDate(?DateTime $expiryDate): void
    {
        $this->expiryDate = $expiryDate;
    }

    /**
     * @return Nation|null
     */
    public function getNation(): ?Nation
    {
        return $this->nation;
    }

    /**
     * @param Nation|null $nation
     */
    public function setNation(?Nation $nation): void
    {
        $this->nation = $nation;
    }

    /**
     * @return string
     */
    public function getContent(): string
    {
        return $this->content;
    }

    /**
     * @param string $content
     */
    public function setContent(string $content): void
    {
        $this->content = $content;
    }
}