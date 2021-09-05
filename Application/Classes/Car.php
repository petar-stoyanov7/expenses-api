<?php

namespace Application\Classes;

class Car 
{
    private $userId;
    private $brand;
    private $model;
    private $year;
    private $color;
    private $mileage;
    private $fuelId;
    private $fuelId2;
    private $notes;

    public function __construct($userId,$brand,$model,$year,$color,$mileage,$fuel_id,$fuel_id2="",$notes="")
    {
        $this->userId = $userId;
        $this->brand = sanitize($brand);
        $this->model = sanitize($model);
        $this->year = sanitize($year);
        $this->color = sanitize($color);
        $this->mileage = $mileage;
        $this->fuelId = $fuel_id;
        $this->fuelId2 = $fuel_id2;
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