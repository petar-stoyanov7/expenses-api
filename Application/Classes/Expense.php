<?php

namespace Application\Classes;

class Expense 
{
	private $userId;
	private $carId;
	private $date;
	private $mileage;
	private $expenseType;
	private $price;
	private $fuelType;
	private $liters;
	private $insuranceType;
	private $partName;
	private $notes;

	public function __construct(
        $userId,
        $carId,
        $date,
        $mileage,
        $expense_type,
        $price,
        $fuelType="",
        $liters="",
        $insuranceType="",
        $partName = "",
        $notes=""
    )
    {
		$this->userId = $userId;
		$this->carId = $carId;
		$this->date = $date;
		$this->mileage = $mileage;
		$this->expenseType = $expense_type;
		$this->price = $price;
		$this->fuelType = $fuelType;
		$this->liters = $liters;
		$this->insuranceType = $insuranceType;
		$this->partName = $partName;
		$this->notes = sanitize($notes,1);
	}

	public function getProperty($property)
    {
		if (property_exists($this, $property)) {
			return $this->$property;
		} else {
			die("Non existent property");
		}
	}

	public function setProperty($property, $value)
    {
		if (property_exists($this, $property)) {
			$this->$property = $value;
		} else {
			die("Non existent property");
		}
	}
}

?>