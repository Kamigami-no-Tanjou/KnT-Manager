<?php

namespace App\Entity;

use DateTime;

class NationLeader
{
    private int $id;
    private DateTime $leadStartDate;
    private ?DateTime $leadEndDate;

    private Nation $nation;
    private Charac $leader;

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
     * @return DateTime
     */
    public function getLeadStartDate(): DateTime
    {
        return $this->leadStartDate;
    }

    /**
     * @param DateTime $leadStartDate
     */
    public function setLeadStartDate(DateTime $leadStartDate): void
    {
        $this->leadStartDate = $leadStartDate;
    }

    /**
     * @return ?DateTime
     */
    public function getLeadEndDate(): ?DateTime
    {
        return $this->leadEndDate;
    }

    /**
     * @param ?DateTime $leadEndDate
     */
    public function setLeadEndDate(?DateTime $leadEndDate): void
    {
        $this->leadEndDate = $leadEndDate;
    }

    /**
     * @return Nation
     */
    public function getNation(): Nation
    {
        return $this->nation;
    }

    /**
     * @param Nation $nation
     */
    public function setNation(Nation $nation): void
    {
        $this->nation = $nation;
    }

    /**
     * @return Charac
     */
    public function getLeader(): Charac
    {
        return $this->leader;
    }

    /**
     * @param Charac $leader
     */
    public function setLeader(Charac $leader): void
    {
        $this->leader = $leader;
    }
}