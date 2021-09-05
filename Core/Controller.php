<?php
namespace Core;

use Application\Models\CarModel;
use Application\Models\ExpenseModel;
use Application\Models\PartsModel;
use Application\Models\StatisticsModel;
use Application\Models\UserModel;

abstract class Controller
{
    private $hash;
    public CarModel $CarModel;
    public UserModel $UserModel;
    public ExpenseModel $ExpenseModel;
    public PartsModel $PartsModel;
    public StatisticsModel $StatsModel;

    public function __construct()
    {
        $Config = new Config();
        $this->hash = $Config->getConfigDetail('ajax')['hash'];

        $this->CarModel = new CarModel();
        $this->UserModel = new UserModel();
        $this->ExpenseModel = new ExpenseModel();
        $this->PartsModel = new PartsModel();
        $this->StatsModel = new StatisticsModel();
    }

    public function indexAction()
    {
        $this->returnError('Incorrect path');
    }

    public function checkPost($post)
    {
        if (empty($post)) {
            $this->returnError();
        }
        if (empty($post['hash'])) {
            $this->returnError();
        }
        if (!$this->checkHash($post['hash'])) {
            $this->returnError();
        }
    }

    public function checkHash($hash)
    {
        return $hash === $this->hash;
    }

    public function getData()
    {
        $data = '';
        if (empty($_POST)) {
            $data = file_get_contents('php://input');
        } else {
            $data = $_POST;
        }

        return $data;
    }

    public function convertData($data)
    {
        if (empty($data)) {
            return '';
        } else {
            try {
                return json_decode($data, true, 512, JSON_THROW_ON_ERROR);
            } catch (\JsonException $e) {
                return '';
            }
        }
    }

    public function initialData()
    {
        $data = $this->getData();
        $data = $this->convertData($data);
        $this->checkPost($data);
        return $data;
    }

    protected function returnError($error = 'Access denied', $errorCode = 0)
    {
        echo json_encode([
            'success' => false,
            'status' => $errorCode,
            'message' => $error
        ], JSON_THROW_ON_ERROR);
        die();
    }
}