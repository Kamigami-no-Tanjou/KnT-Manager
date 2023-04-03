<?php

namespace App\Entity;

use DateTime;

class Calendar
{
	private int $id;
	private string $name;
	private DateTime $referenceDate;
	private int $negativeYears;

	/*
	 * Getters
	 */
	public function getId() : int {
		return $this->id;
	}
	public function getName() : string {
		return $this->name;
	}
	public function getReferenceDate() : DateTime {
		return $this->referenceDate;
	}
	public function getNegativeYears() : int {
		return $this->negativeYears;
	}


	/*
	 * Setters
	 */
    public function setId(int $id): void {
        $this->id = $id;
    }
	public function setName(string $name): void {
		$this->name = $name;
	}
	public function setReferenceDate(DateTime $date): void {
		$this->referenceDate = $date;
	}
	public function setNegativeYears(int $years): void {
		$this->negativeYears = $years;
	}
}

