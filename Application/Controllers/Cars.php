<?php

namespace Application\Controllers;

use Core\Controller;

//TODO implement Controller
class Cars extends Controller
{
    public function getUserCarsAction()
    {
        /** Status codes
         * 0 -> success
         * 1 -> missing data
         * 2 -> no cars
         */
//        xdebug_break();
        $data = $this->initialData();
        $response = [];
        if (!empty($data['id'])) {
//            $userCars = $this->CarModel->listCarsByUserId($data['id']);
            $userCars = $this->CarModel->listCarsByUserId($data['id']);
            if (!empty($userCars) && count($userCars) > 0 ) {
                $response = [
                    'status' => 0,
                    'success' => true,
                    'cars' => $userCars
                ];
                echo json_encode($response);
                die();
            }
            $response = [
                'success' => false,
                'status' => 2,
            ];
            echo json_encode($response);
        }

        $this->returnError('General error', 1);
    }

    public function getFuelListAction()
    {
//        xdebug_break();
        $this->initialData();
        $fuelList = $this->CarModel->getFuelList();
        $result = [
            'success' => true,
            'status' => 0,
            'fuels' => $fuelList
        ];
        echo json_encode($result);
        die();
    }

    public function processAction()
    {
//        if (!empty($_POST)) {
//            $data = nullify($_POST);
//            $Car = new Car(
//                $data['user-id'],
//                $data['brand'],
//                $data['model'],
//                $data['year'],
//                $data['color'],
//                $data['mileage'],
//                $data['fuel_id1'],
//                $data['fuel_id2'],
//                $data['notes']
//            );
//            if (!empty($_POST['car-id'])) {
//                $this->CarModel->editCar($Car, $_POST['car-id']);
//                $modifiedCar = $this->CarModel->getCarById($data['car-id']);
//                echo json_encode(
//                    [
//                        'success'   => true,
//                        'car'       => $modifiedCar
//                    ]
//                );
//            } else {
//                $newId = $this->CarModel->addCar($Car);
//                $newCar = $this->CarModel->getCarById($newId);
//                echo json_encode(
//                    [
//                        'success'   => true,
//                        'car'       => $newCar
//                    ]
//                );
//            }
//        } else {
//            echo json_encode(['success' => false]);
//        }
    }

    public function deleteAction($params)
    {
//        $response = [];
//        if (isset($_POST['car-id'])) {
//            $carId = $_POST['car-id'];
//            if (isset($_POST['delete-expenses']) && (int)$_POST['delete-expenses'] === 1) {
//                $expenseModel = new ExpenseModel();
//                $expenseModel->removeCarExpenses($carId);
//            }
//            $partsModel = new PartsModel();
//            $partsModel->removeByCarId($carId);
//            $this->CarModel->removeCarById($carId);
//            $response['success'] = true;
//        } else {
//            $response['success'] = false;
//        }
//        echo json_encode($response);
//        die();
    }

    public function listUserCarsAction()
    {
//        if (isset($_POST['userid'])) {
//            $result = [];
//            $partsModel = new PartsModel();
//            $cars = $this->CarModel->listCarsByUserId($_POST['userid']);
//            foreach ($cars as $car) {
//                $partsArray = $partsModel->getPartsByCarId($car['ID']);
//                $parts = [];
//                foreach ($partsArray as $part) {
//                    $parts[$part['ID']] = $part;
//                }
//                $result[$car['ID']] = $car;
//                $result[$car['ID']]['parts'] = $parts;
//            }
//            echo json_encode($result);
//            die();
//        }
    }
}