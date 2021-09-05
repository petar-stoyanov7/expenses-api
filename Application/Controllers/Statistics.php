<?php

namespace Application\Controllers;

use Core\Controller;

class Statistics extends Controller
{

    public function indexAction()
    {
        //TODO Completely remove after implementing proper ajax listener
//        if (isset($_SESSION['user'])) {
//            $userId = $_SESSION['user']['ID'];
//            $cars = $this->CarModel->listCarsByUserId($userId);
//            $expenses = $this->ExpenseModel->getExpenses();
//            $form = new StatisticsForm($cars, $expenses, $userId);
//            $viewParams = [
//                'form'  => $form,
//                'title' => 'Statistics',
//                'CSS'   => ['statistics.css'],
//                'JS'    => ['statistics.js'],
//            ];
//            if(!empty($_POST)) {
//                $start_year = substr($_POST['from'], 0, 4);
//                $end_year = substr($_POST['to'], 0, 4);
//                if ($start_year > $end_year) {
//                    echo "<div class='container'>";
//                    display_warning("Некоректен период!");
//                    echo "</div>";;
//                }
//                else {
//                    $providedUser = !empty($_POST['user-id']) ? $_POST['user-id'] : $userId;
//                    $data['allExpenses'] = $this->StatsModel->getAllExpensesForPeriod(
//                        $_POST['from'],
//                        $_POST['to'],
//                        $providedUser,
//                        $_POST['car'],
//                        $_POST['expense-type']
//                    );
//                    $data['cars'] = $this->StatsModel->getOverallForPeriod(
//                        $_POST['from'],
//                        $_POST['to'],
//                        $providedUser,
//                        $_POST['car'],
//                        $_POST['expense-type']
//                    );
//                    if ((int)$_POST['ajax'] === 1) {
//                        echo json_encode($data);
//                        die();
//                    }
//                }
//            } else {
//                View::render('statistics/statistics.php', $viewParams);
//            }
//
//        } else {
//            header('location: /');
//        }
    }

    public function getPartsAction()
    {
//        if (!empty($_POST) && (int)$_POST['ajax'] === 1) {
//            $partsModel = new PartsModel();
//            $userId = $_POST['user-id'];
//            $carId = 'all' === $_POST['car'] ? null : $_POST['car'];
//            $result = $partsModel->getUserPartsData($userId, $carId);
//            if (!empty($result)) {
//                foreach ($result as $index => $data) {
//                    $ageString = rtrim($this->_parseDays($data['part_age']),',');
//                    $ageString = rtrim($ageString,', ');
//                    $result[$index]['part_age'] = $ageString;
//                }
//            } else {
//                $result = [];
//            }
//            echo json_encode($result);
//            die();
//        } else {
//            header('Location: /');
//        }
    }

    private function _parseDays($days)
    {
//        $string = '';
//        if ($days >= 365) {
//            $years = explode('.', $days/365)[0];
//            $remnant = $days % 365;
//            $yearString = ((int)$years === 1) ? 'year' : 'years';
//            $string = "{$years} {$yearString}, " . $this->_parseDays($remnant);
//        } else if ($days >= 30) {
//            $months = explode('.', $days/30)[0];
//            $remnant = $days % 30;
//            $string = "{$months} months, " . $this->_parseDays($remnant);
//        } else if ($days > 0) {
//            $string = "{$days} days";
//        } else {
//            $string = '';
//        }
//
//        return $string;
    }
}