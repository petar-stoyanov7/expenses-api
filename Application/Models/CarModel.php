<?php

namespace Application\Models;

use Core\DbModelAbstract;

class CarModel extends DbModelAbstract
{
    public function __construct()
    {
        parent::__construct();
    }

    public function listAllCars()
    {
        $array = $this->getData('SELECT * FROM Cars');
        return $array;
    }

    public function listCarsByUserId($uid)
    {
        $query = <<<__SQL
            SELECT
            c.*,
            ft.Name AS fuel_name1,
            ft2.Name AS fuel_name2,
            u.Username AS username
            FROM Cars c
            LEFT JOIN Users u on u.ID = c.UID
            LEFT JOIN Fuel_Types ft on ft.ID = c.Fuel_ID
            LEFT JOIN Fuel_Types ft2 on ft2.ID = c.Fuel_ID2
            WHERE u.ID = ?;
__SQL;
        return $this->getData($query, [$uid]);
    }

    public function countCarsByUserId($uid)
    {
        $count = $this->getData("SELECT COUNT(*) FROM `Cars` WHERE `UID` = ?",[$uid]);
        return $count[0]["COUNT(*)"];
    }

    public function getUserFuelTypes($userid, $secondary = null)
    {
        $query = "SELECT DISTINCT 
                    `Cars`.`Fuel_ID` AS `ID`, 
                    `Fuel_Types`.`Name` 
                    FROM `Cars`
                    JOIN `Fuel_Types`
                    ON `Cars`.`Fuel_ID` = `Fuel_Types`.`ID`
                    WHERE `Cars`.`UID` = ?
                    UNION ALL
                    SELECT DISTINCT 
                    `Cars`.`Fuel_ID2` AS `ID`, 
                    `Fuel_Types`.`Name` FROM `Cars`
                    JOIN `Fuel_Types`
                    ON `Cars`.`Fuel_ID2` = `Fuel_Types`.`ID`
                    WHERE `Cars`.`UID` = ?";
        if (null !== $secondary) {
            $query .= " AND `Fuel_Types`.`ID` NOT IN (1,2)";
        }
        $query .= " ORDER BY `ID` ASC";
        $fuel_array = $this->getData($query, [$userid, $userid]);
        return $fuel_array;
    }

    public function getUserIdByCarId($cid)
    {
        $query = "SELECT `UID` FROM `Cars` WHERE `ID` = ? LIMIT 1";
        $data = $this->getData($query, [$cid]);
        return $data[0]['UID'];
    }

    public function getFuels($secondary = null)
    {
        $query = "SELECT * FROM `Fuel_Types`";
        if (null !== $secondary) {
            $query .= " WHERE `ID` NOT IN (1,2)";
        }
        return $this->getData($query);
    }
    
    public function getFuelName($id)
    {
        if (null === $id) {
            return null;
        }
        $query = "SELECT `NAME` FROM `Fuel_Types` WHERE `ID` = ?";
        $result = $this->getData($query, [$id]);
        return $result[0]['NAME'];
    }

    public function getCarById($id)
    {
        $query = <<<__SQL
            SELECT
            c.*,
            ft.Name AS fuel_name1,
            ft2.Name AS fuel_name2,
            u.Username AS username
            FROM Cars c
            LEFT JOIN Users u on u.ID = c.UID
            LEFT JOIN Fuel_Types ft on ft.ID = c.Fuel_ID
            LEFT JOIN Fuel_Types ft2 on ft2.ID = c.Fuel_ID2
            WHERE c.ID = ?;
__SQL;
        $car_array = $this->getData($query,[$id]);
        return $car_array[0];
    }

    public function getCarNameById($id)
    {
        $car_array = $this->getData("SELECT * FROM `Cars` WHERE `ID` = ?",[$id]);
        $string = $car_array[0]['Brand']." ".$car_array[0]['Model'];
        return $string;
    }

    public function getFuelList()
    {
        return $this->getData("SELECT * FROM `Fuel_Types`");
    }

    public function getFuelNames()
    {
        $fuels_list = $this->getData("SELECT `Name` FROM `Fuel_Types`");
        $fuels_array = array();
        foreach ($fuels_list as $fuel) {
            array_push($fuels_array, $fuel['Name']);
        }
        return $fuels_array;
    }

    public function getFuelId()
    {
        $fuels_list = $this->getData("SELECT `ID` FROM `Fuel_Types`");
        $fuels_array = array();
        foreach ($fuels_list as $fuel) {
            array_push($fuels_array, $fuel['ID']);
        }
        return $fuels_array;
    }

    public function getMileageByCarId($carId)
    {
        $query = "SELECT `Mileage` from `Cars` WHERE `ID` = ?";
        return $this->getData($query, [$carId]);
    }
    
    public function addCar($car)
    {
        $query = "INSERT INTO `Cars` 
            (`UID`, `Brand`, `Model`, `Year`, `Color`, `Mileage`, `Fuel_ID`, `Fuel_ID2`, `Notes`)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $values = [
            $car->getProperty('userId'),
            $car->getProperty('brand'),
            $car->getProperty('model'),
            $car->getProperty('year'),
            $car->getProperty('color'),
            $car->getProperty('mileage'),
            $car->getProperty('fuelId'),
            $car->getProperty('fuelId2'),
            $car->getProperty('notes'),
        ];
        return $this->execute($query, $values);
    }

    public function editCar($car, $cid)
    {
        $query = "UPDATE `Cars` SET 
                    `Brand` = ?,
                    `Model` = ?,
                    `Year` = ?,
                    `Color` = ?,
                    `Fuel_ID` = ?,
                    `Fuel_ID2` = ?,
                    `Mileage` = ?,
                    `Notes` = ? 
                    WHERE `ID` = ?";
        $values = [
            $car->getProperty('brand'),
            $car->getProperty('model'),
            $car->getProperty('year'),
            $car->getProperty('color'),
            $car->getProperty('fuelId'),
            empty($car->getProperty('fuelId2')) ? null : $car->getProperty('fuelId2'),
            $car->getProperty('mileage'),
            $car->getProperty('notes'),
            $cid
        ];
        return $this->execute($query, $values);
    }

    public function removeCarById($id) {
        $query = "DELETE FROM `Cars` WHERE `ID` = ?";
        $this->execute($query, [$id]);
    }

    public function removeUserCars($uid) {
        $query = "DELETE FROM `Cars` WHERE `UID` = ?";
        $this->execute($query, [$uid]);
    }
}