<?php

namespace Application\Models;

use Application\Classes\Expense;
use Application\Models\CarModel;
use Core\DbModelAbstract;
use Exception;

class PartsModel extends DbModelAbstract
{
    public function __construct()
    {
        parent::__construct();
    }

    public function getPartsByCarId($carId)
    {
        return $this->getData('SELECT * FROM `Parts` WHERE `CID` = ?', [$carId]);
    }

    public function getPartsByUserId($userId)
    {
        return $this->getData('SELECT * FROM `Parts` WHERE `UID` = ?', [$userId]);
    }

    public function getPartsByExpenseId($expenseId)
    {
        return $this->getData('SELECT * FROM `Parts` WHERE `EID` = ?', [$expenseId]);
    }

    public function removeByUserId($userId)
    {
        $this->execute('DELETE FROM `Parts` WHERE `UID` = ?', [$userId]);
    }

    public function removeByCarId($carId)
    {
        $this->execute('DELETE FROM `Parts` WHERE `CID` = ?', [$carId]);
    }

    public function removeByExpenseId($expenseId, $date)
    {
        $this->execute('DELETE FROM `Parts` WHERE `EID` = ? AND `Date` = ?', [$expenseId, $date]);
    }

    public function removeParts(array $partsIds) {
        $this->execute('DELETE FROM `Parts` WHERE `ID` IN (?)', $partsIds);
    }

    public function addNewParts(Expense $Expense, $expenseId)
    {
        $parts = $Expense->getProperty('partName');
        if (strpos($parts, ',') !== false) {
            $parts = preg_replace('/\s+\,/', ',', $parts);
            $parts = preg_replace('/\,\s+/', ',', $parts);
            $partsList = explode(',', $parts);
        } else {
            $partsList = [$parts];
        }
        foreach ($partsList as $part) {
            $query = <<<___SQL
            INSERT INTO `Parts`
            (`UID`,`CID`,`EID`,`Mileage`,`Date`,`Name`)
            VALUES
            (?, ?, ?, ?, ?, ?)
___SQL;
            $values = [
                $Expense->getProperty('userId'),
                $Expense->getProperty('carId'),
                $expenseId,
                $Expense->getProperty('mileage'),
                $Expense->getProperty('date'),
                $part,
            ];
            $this->execute($query, $values);
        }
    }

    public function getUserPartsData($userId, $carId = null)
    {
        $query = <<<___SQL
            SELECT
            p.*,
            c.Brand,
            c.Model,
            CONCAT(c.Brand, ' ', c.Model) as car_name,
            c.Mileage - p.Mileage as part_mileage,
            DATEDIFF(CURDATE(),p.Date) as part_age
            FROM Parts p
            LEFT JOIN Cars c ON p.CID = c.ID
            WHERE p.UID = ?
___SQL;
        $values[] = $userId;
        if (null !== $carId) {
            $query .= "AND p.CID = ?";
            $values[] = $carId;
        }

        return $this->getData($query, $values);
    }
}