<?php

namespace Application\Models;

use Application\Classes\Expense;
use Application\Models\CarModel;
use Core\DbModelAbstract;
use Exception;

class ExpenseModel extends DbModelAbstract 
{    
    private $carModel;

    public function __construct()
    {
        parent::__construct();
        $this->carModel = new CarModel();
        $year = getdate()['year'];
        $query = "CREATE TABLE IF NOT EXISTS `Expense_{$year}` (
                        `ID` int primary key auto_increment,
                        `UID` int references `Users`(`ID`),
                        `CID` int references `Cars`(`ID`),
                        `Date` date,
                        `Mileage` int,
                        `Price` int,
                        `Expense_ID` int references `Expense_Types`(`ID`),
                        `Fuel_ID` int references `Fuel_Types`(`ID`),
                        `Insurance_ID` int references `Insurance_Types`(`ID`),
                        `Liters` int,
                        `Notes` text
                        ) CHARSET=utf8";				
        $this->execute($query);
        $this->year = "";
        $this->table = "";
    }

    public function getTableList()
    {
        $arrays = $this->getData("SHOW TABLES LIKE 'Expense_2%'");
        $list = array();
        foreach ($arrays as $array) {
            $year = substr($array['Tables_in_pestart_car_expenses (Expense_2%)'], -4);
            $list[$year] = $array['Tables_in_pestart_car_expenses (Expense_2%)'];
        }
        return $list;
    }

    public function createTableYear($year)
    {
        $query = "CREATE TABLE IF NOT EXISTS `Expense_{$year}` (
                        `ID` int primary key auto_increment,
                        `UID` int references `Users`(`ID`),
                        `CID` int references `Cars`(`ID`),
                        `Date` date,
                        `Mileage` int,
                        `Price` int,
                        `Expense_ID` int references `Expense_Types`(`ID`),
                        `Fuel_ID` int references `Fuel_Types`(`ID`),
                        `Insurance_ID` int references `Insurance_Types`(`ID`),
                        `Liters` int,
                        `Notes` text
                        ) CHARSET=utf8";
        $this->execute($query);
    }
    //might delete this
    public function getExpenseNames()
    {
        $expenseList = $this->getData("SELECT `Name` FROM `Expense_Types`");
        $expenseArray = array();
        foreach ($expenseList as $expense) {
            array_push($expenseArray, $expense['Name']);
        }
        return $expenseArray;
    }
    //might delete this
    public function getExpenseId()
    {
        $expenseList = $this->getData("SELECT `ID` FROM `Expense_Types`");
        $expenseArray = array();
        foreach ($expenseList as $expense) {
            array_push($expenseArray, $expense['ID']);
        }
        return $expenseArray;
    }

    public function getExpenses()
    {
        return $this->getData("SELECT * FROM `Expense_Types`");
    }
    
    public function getExpenseName($id)
    {
        if (null === $id) {
            return null;
        }
        $query = 'SELECT `Name` FROM `Expense_Types` WHERE `ID` = ?';
        $result = $this->getData($query, [$id]);
        return $result[0]['Name'];
    }

    public function getInsuranceList()
    {
        return $this->getData('SELECT * FROM `Insurance_Types`');
    }

    public function getInsuranceNames()
    {
        $insuranceList = $this->getData("SELECT `Name` FROM `Insurance_Types`");
        $insuranceArray = [];
        foreach ($insuranceList as $insurance) {
            array_push($insuranceArray, $insurance['Name']);
        }
        return $insuranceArray;
    }
    public function getInsuranceId()
    {
        $insuranceList = $this->getData("SELECT `ID` FROM `Insurance_Types`");
        $insuranceArray = array();
        foreach ($insuranceList as $insurance) {
            array_push($insuranceArray, $insurance['ID']);
        }
        return $insuranceArray;
    }

    public function getInsuranceName($id)
    {
        if (null === $id) {
            return null;
        }
        $query = 'SELECT `Name` FROM `Insurance_Types` WHERE `ID` = ?';
        $result = $this->getData($query, [$id]);
        return $result[0]['Name'];
    }

    public function addExpense(Expense $Expense)
    {
        $year = substr($Expense->getProperty("date"),0,4);
        $expenseTables = $this->getTableList();
        if (!array_key_exists($year, $expenseTables)) {
            $this->createTableYear($year);
        }
        $car = $this->carModel->getCarById($Expense->getProperty("carId"));
        if (empty($Expense->getProperty("mileage"))) {
            $Expense->setProperty('mileage', $car['Mileage']);
        }
        if ($Expense->getProperty("price") < 0) {
            throw new Exception('Invalid price');
        } elseif ($Expense->getProperty("liters") < 0) {
            throw new Exception("Lites can't be negative");
        } elseif (($Expense->getProperty("expenseType") == 0) &&
            ($Expense->getProperty("fuelType") != $car['Fuel_ID'] && $Expense->getProperty("fuelType") != $car['Fuel_ID2'])) {
            throw new Exception("Invalid fuel type");
        } elseif (empty($Expense->getProperty("price"))) {
            throw new Exception("No price given");
        } else {
            $query = "INSERT INTO `Expense_{$year}` (
            `UID`, `CID`, `Date`, `Mileage`, `Expense_ID`, `Price`, `Fuel_ID`, `Insurance_ID`, `Liters`, `Notes`)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $values = [
                $Expense->getProperty("userId"),
                $Expense->getProperty("carId"),
                $Expense->getProperty("date"),
                $Expense->getProperty("mileage"),
                $Expense->getProperty("expenseType"),
                $Expense->getProperty("price"),
                $Expense->getProperty("fuelType"),
                $Expense->getProperty("insuranceType"),
                $Expense->getProperty("liters"),
                $Expense->getProperty("notes"),
            ];
            if ($Expense->getProperty("mileage") > $car['Mileage']) {
                $update = "UPDATE `Cars` SET `Mileage` = ? WHERE `ID` = ?";
                $updateValues = [$Expense->getProperty("mileage"), $Expense->getProperty("carId")];
                $this->execute($update, $updateValues);
            }
            return $this->execute($query, $values);
        }
    }



    public function removeExpense($id, $year)
    {
        $query = "DELETE FROM `Expense_{$year}` WHERE `ID`= ?";
        $values = [$id];
        $this->execute($query, $values);
    }

    public function removeUserExpenses($userId)
    {
        $tableList = $this->getTableList();
        foreach ($tableList as $table) {
            $query = "DELETE FROM {$table} WHERE `UID` = ?";
            $this->execute($query, [$userId]);
        }
    }

    public function removeCarExpenses($carId)
    {
        $tableList = $this->getTableList();
        foreach ($tableList as $table) {
            $query = "DELETE FROM {$table} WHERE `CID` = ?";
            $this->execute($query, [$carId]);
        }
    }
}
?>