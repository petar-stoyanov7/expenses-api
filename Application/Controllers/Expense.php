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
        $result = [
            'success' => true,
            'status' => 0,
            'expenses' => $expenses
        ];
        echo json_encode($result);
        die();
    }


    public function getInsurancesAction()
    {
//        xdebug_break();
        $this->initialData();
        $insuranceList = $this->ExpenseModel->getInsuranceList();
        $result = [
            'success' => true,
            'status' => 0,
            'insurances' => $insuranceList
        ];
        echo json_encode($result);
        die();
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

    public function newAjaxExpenseAction()
    {
//        $response['success'] = false;
//        $newPart = false;
//        if (!empty($_POST)) {
//            $values = nullify($_POST);
//            $type = (int)$values['expenseType'];
//            switch($type) {
//                case 1:
//                    $values['insuranceType'] = null;
//                    $values['partName'] = null;
//                    unset($values['replacementParts']);
//                    break;
//                case 2:
//                    $values['fuelType'] = null;
//                    $values['liters'] = null;
//                    $values['partName'] = null;
//                    unset($values['replacementParts']);
//                    break;
//                case 5:
//                    $newPart = true;
//                    $values['insuranceType'] = null;
//                    $values['fuelType'] = null;
//                    $values['liters'] = null;
//                    break;
//                default:
//                    $values['insuranceType'] = null;
//                    $values['fuelType'] = null;
//                    $values['liters'] = null;
//                    $values['partName'] = null;
//                    unset($values['replacementParts']);
//                    break;
//            }
//            $values['description'] = null === $values['description'] ? '' : $values['description'];
//            $Expense = new ExpenseClass(
//                $values['userId'],
//                $values['carId'],
//                $values['date'],
//                $values['mileage'],
//                $values['expenseType'],
//                $values['value'],
//                $values['fuelType'],
//                $values['liters'],
//                $values['insuranceType'],
//                $values['partName'],
//                $values['description']
//            );
//            try {
//                $expenseId = $this->ExpenseModel->addExpense($Expense);
//                $response['success'] = true;
//            } catch (Exception $e) {
//                $response = [
//                    'success' => false,
//                    'message' => $e->getMessage()
//                ];
//            }
//            if ($newPart && !empty($expenseId)) {
//                $this->PartsModel->addNewParts($Expense, $expenseId);
//                if (!empty($values['replacementParts'])) {
//                    $this->PartsModel->removeParts($values['replacementParts']);
//                }
//            }
//
//        }
//        echo json_encode($response);
//        die();
    }
}