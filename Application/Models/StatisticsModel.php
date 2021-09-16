<?php

namespace Application\Models;

use Application\Models\CarModel;
use Application\Models\ExpenseModel;
use Core\DbModelAbstract;

class StatisticsModel extends DbModelAbstract
{
    private $carModel;
    private $expenseModel;
    private $tableList;

    public function __construct()
    {
        $this->carModel = new CarModel();
        $this->expenseModel = new ExpenseModel();
        parent::__construct();
        $this->tableList = $this->getTableList();
    }

    public function getTableList()
    {
        $arrays = $this->getData("SHOW TABLES LIKE 'Expense_2%'");
        $list = [];
        foreach ($arrays as $array) {
            $year = substr($array['Tables_in_pestart_car_expenses (Expense_2%)'], -4);
            $list[$year] = $array['Tables_in_pestart_car_expenses (Expense_2%)'];
        }
        return $list;
    }

    public function getLastN($id, $type = 'user', $limit = 5)
    {
        $year = date('Y');
        $result = [];
        $queryTemplate = <<<___SQL
        SELECT 
        `Expense_%y`.*,
        `Fuel_Types`.`Name` as `fuel_name`,
        `Expense_Types`.`Name` as `expense_name`,
        `Insurance_Types`.`Name` as `insurance_name`,
        `Cars`.`Brand` as `car_brand`,
        `Cars`.`Model` as `car_model`
        FROM 
        `Expense_%y` 
        LEFT JOIN `Fuel_Types` ON `Expense_%y`.`Fuel_ID` = `Fuel_Types`.`ID`
        LEFT JOIN `Expense_Types` ON `Expense_%y`.`Expense_ID` = `Expense_Types`.`ID`
        LEFT JOIN `Insurance_Types` ON `Expense_%y`.`Insurance_ID` = `Insurance_Types`.`ID`
        LEFT JOIN `Cars` ON `Expense_%y`.`CID` = `Cars`.`ID`
        
___SQL;
        switch ($type) {
            case 'car':
                $queryTemplate .= <<<___SQL
            WHERE `Expense_%y`.`CID`= ?
            ORDER BY `Expense_%y`.`ID` DESC LIMIT %l;
___SQL;
                break;
            case 'user':
            default:
            $queryTemplate .= <<<___SQL
            WHERE `Expense_%y`.`UID`= ?
            ORDER BY `Expense_%y`.`ID` DESC LIMIT %l;
___SQL;
        }
        while($limit > 0) {
            $query = str_replace(['%y', '%l'],[$year, $limit], $queryTemplate);
            $array = $this->getData($query, [$id]);
            $result = array_merge($result, $array);
            $limit -= count($array);
            $year--;
            if (!array_key_exists($year, $this->tableList)) {
                //can't go back any further
                $limit = 0;
            }
        }
        return $result;
    }

    public function getAllExpensesForPeriod($start, $end, $userId, $carId, $expenseId)
    {
        $startYear = date("Y", strtotime($start));
        $endYear = date("Y", strtotime($end));
        $query = '';
        $params = [];
        $queryTemplate = "SELECT 
                %t.*,
                Cars.Brand as car_brand,
                Cars.Model as car_model,
                Fuel_Types.Name as fuel_name,
                Insurance_Types.Name as insurance_name,
                Expense_Types.Name as expense_name
                FROM `%t` 
                LEFT JOIN Cars ON Cars.ID = %t.CID
                LEFT JOIN Fuel_Types ON %t.Fuel_ID = Fuel_Types.ID
                LEFT JOIN Insurance_Types ON %t.Insurance_ID = Insurance_Types.ID
                LEFT JOIN Expense_Types ON %t.Expense_ID = Expense_Types.ID
                %w";

        if ($startYear === $endYear) {
            $queryDetails = $this->_getWhere($start, $end, $userId, $carId, $expenseId, $startYear);
            $where = $queryDetails['where'];
            $queryParams = $queryDetails['params'];
            $query = str_replace('%t', 'Expense_'.$startYear, $queryTemplate);
            $query = str_replace('%w', $where, $query);
            $params = $queryParams;
        } else {
            for ($y = $startYear; $y <= $endYear; $y++) {
                if (!array_key_exists($y, $this->tableList)) {
                    continue;
                }
                $queryDetails = $this->_getWhere($start, $end, $userId, $carId, $expenseId, $y);
                $where = $queryDetails['where'];
                $queryParams = $queryDetails['params'];
                $query .= str_replace('%t', 'Expense_'.$y, $queryTemplate);
                $query = str_replace('%w', $where, $query);
                $query .= ' UNION ALL ';

                $params = array_merge($params, $queryParams);
            }
            $query = preg_replace('/ UNION ALL $/', '', $query);
        }

        return $this->getData($query, $params);
    }

    public function getOverallForPeriod($start, $end, $userId, $carId) {
        $price = 0;
        $queryTemplate = <<<SQL
            SELECT sum(`Price`) as `Price`
            FROM `Expense_%y`
            WHERE `UID` = ?
SQL;

        $queryParams = [$userId];

        if (!empty($carId)) {
            if (is_array($carId)) {
                $queryTemplate .= <<<SQL
                AND `CID` in ?
SQL;
            } else {
                $queryTemplate .= <<<SQL
                AND `CID` = ?
SQL;
            }
            $queryParams[] = $carId;
        }

        $startYear = date("Y", strtotime($start));
        $endYear = date("Y", strtotime($end));

        if ($startYear === $endYear) {
            $query = str_replace('%y', $startYear, $queryTemplate);
            $result = $this->getData($query, $queryParams);
            $price += $result[0]['Price'];
        } else {
            while($startYear <= $endYear) {
                $query = str_replace('%y', $startYear, $queryTemplate);
                $result = $this->getData($query, $queryParams);
                $price += $result[0]['Price'];
                $startYear++;
            }
        }

        return empty($price) ? 0 : $price;
    }

    public function getCarOverallForPeriod($start, $end, $userId, $carId, $expenseId) {
        $startYear = date("Y", strtotime($start));
        $endYear = date("Y", strtotime($end));
        $params = [];

        if ($startYear === $endYear) {
            $queryDetails = $this->_getWhere($start, $end, $userId, $carId, $expenseId, $startYear);
            $where = $queryDetails['where'];
            $queryParams = $queryDetails['params'];
            $query = "SELECT Sum(`Price`) as `Overall`, Max(`Mileage`) - Min(`Mileage`) as `Distance` FROM Expense_{$startYear} {$where}";
            $params = $queryParams;
        } else {
            $query = "SELECT Sum(`Price`) as `Overall`, Sum(`Distance`) as `Distance` FROM (";
            for ($y = $startYear; $y <= $endYear; $y++) {
                if (!array_key_exists($y, $this->tableList)) {
                    continue;
                }
                $queryDetails = $this->_getWhere($start, $end, $userId, $carId, $expenseId, $y);
                $where = $queryDetails['where'];
                $queryParams = $queryDetails['params'];
                $table = "Expense_{$y}";
                $query .= "SELECT sum(Price) as Price, Max(Mileage) - Min(Mileage) as Distance FROM {$table} {$where} UNION ALL ";
                $params = array_merge($params, $queryParams);
            }
            $query = preg_replace('/ UNION ALL $/', '', $query);
            $query .= ') as SubQuery';
        }

        $result = $this->getData($query, $params);

        return !empty($result) ? $result[0] : [];
    }

    public function getStatisticById($id, $year="")
    {
        if (empty($year)) {
            $year = date('Y');
        }
        $query = "SELECT * FROM `Expense_".$year."` WHERE `ID` = ".$id;
        $data = $this->getData($query)
;			return $data[0];
    }

    public function countYearExpensesByUserId($uid, $cid="", $year="")
    {
        if (empty($year)) {
            $year = date('Y');
        }
        if (!empty($cid)) {
            $query = "SELECT SUM(`PRICE`) FROM `Expense_".$year."` WHERE `UID`=".$uid." AND `CID`=".$cid;
        } else {
            $query = "SELECT SUM(`PRICE`) FROM `Expense_".$year."` WHERE `UID`=".$uid;
        }
        $array = $this->getData($query);
        if (empty($array[0]["SUM(`PRICE`)"])) {
            return 0;
        } else {
            return $array[0]["SUM(`PRICE`)"];
        }
    }

    private function _getWhere($start, $end, $userId, $carId, $expenseId, $year)
    {
        $where = "WHERE `Expense_{$year}`.`Date` >= ? AND `Expense_{$year}`.`Date` <= ? AND `Expense_{$year}`.`UID` = ? ";
        $params = [$start, $end, $userId];

        if ($carId !== "all") {
            $where .= "AND `Expense_{$year}`.`CID` = ? ";
            $params[] = $carId;
        }

        if ($expenseId !== "all") {
            $where .= " AND `Expense_{$year}`.`Expense_ID` = ? ";
            $params[] = $expenseId;
            $data['Expense'] = $this->expenseModel->getExpenseName($expenseId);
        }

        $result['where'] = $where;
        $result['params'] = $params;
        return $result;
    }
}


?>