<?php


namespace Application\Controllers;

use Core\Controller;
use \Core\View;
use Exception;

class Expense extends Controller
{

    public function getExpensesAction()
    {
//        xdebug_break();
        $data = $this->initialData();
        $expenses = $this->ExpenseModel->getExpenseNames();
        $this->returnData(['expenses' => $expenses]);
    }


    public function getInsurancesAction()
    {
//        xdebug_break();
        $this->initialData();
        $insuranceList = $this->ExpenseModel->getInsuranceList();
        $this->returnData(['insurances' => $insuranceList]);
    }

    public function newExpenseAction()
    {
        xdebug_break();
        $data = $this->initialData();

        $Expense = new \Application\Classes\Expense(
            $data['userId'],
            $data['carId'],
            $data['date'],
            $data['mileage'],
            $data['expenseType'],
            $data['value'],
            $data['fuelType'],
            $data['liters'],
            $data['insuranceType'],
            $data['partName'],
            $data['description']
        );
        try {
            $expenseId = $this->ExpenseModel->addExpense($Expense);

            $this->returnData(['expenseId' => $expenseId]);
        } catch (Exception $e) {
            $this->returnError('Incorrect data', 1);
        }
    }

    public function removeAction()
    {
//        $result['success'] = false;
//        if (!empty($_POST['date']) && !empty($_POST['expenseId'])) {
//            $date = $_POST['date'];
//            $expenseId = $_POST['expenseId'];
//            $year = explode('-', $date)[0];
//            $this->ExpenseModel->removeExpense($expenseId,$year);
//            $this->PartsModel->removeByExpenseId($expenseId, $date);
//            $result['success'] = true;
//        }
//        echo json_encode($result);
//        die();
    }

}