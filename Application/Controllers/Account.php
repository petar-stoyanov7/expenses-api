<?php

namespace Application\Controllers;

use Core\Controller;
use Exception;

class Account extends Controller
{
    public function loginAction()
    {
//        xdebug_break();
        /** Statuses
         * 0 - success
         * 1 - missing data
         * 2 - incorrect username/password
         */

        $data = $this->initialData();

        if(isset($data['user'], $data['pass'])) {
            $isEmail = false;
            if (isset($data['isEmail']) && $data['isEmail'] === 1) {
                $isEmail = true;
            }

            if ($user = $this->UserModel->login($data['user'], $data['pass'], $isEmail)) {
                $response = [
                    'success' => true,
                    'user' => $user
                ];
                echo json_encode($response);
                die();
            }
            $this->returnError('Incorrect username or password', 2);
        } else {
            $this->returnError('Incorrect data provided', 1);
        }
        die();
    }

    public function getUserAction()
    {
        $data = $this->initialData();
        if (isset($data['id'])) {
            $user = $this->UserModel->getUserByUserId($data['id']);
            if (empty($user)) {
                $this->returnError('Invalid user');
            }
            $response = [
                'success' => true,
                'user' => $user
            ];
            echo json_encode($response);
            die();
        }
        $this->returnError('Invalid user');
    }

    public function getHomepageDataAction()
    {
        $data = $this->initialData();
        if (isset($data['id'])) {
            $user = $this->UserModel->getUserByUserId($data['id']);
            if (empty($user)) {
                $this->returnError('Invalid user');
            }
            //
            $response = [
                'success' => true,
                'user' => $user
            ];
            echo json_encode($response);
            die();
        }
        $this->returnError('Invalid user');
    }

    public function registerAction()
    {
//        $form = new UserForm();
//        if(!empty($_POST)) {
//            $values = $form->getValues();
//            $user = new User(
//                $_POST['username'],
//                $_POST['password1'],
//                $_POST['password2'],
//                $_POST['email1'],
//                $_POST['email2'],
//                $_POST['firstname'],
//                $_POST['lastname'],
//                $_POST['city'],
//                $_POST['sex']
//            );
//            if ($this->UserModel->addUser($user)) {
//                $this->UserModel->login($user);
//                header('Location: /');
//            };
//
//
//        } else {
//            echo json_encode($this->_generateError('No data provided'));
//        }
    }

    public function profileAction()
    {
//        if (isset($_SESSION['user'])) {
//            $user = $this->UserModel->getUserByUserId($_SESSION['user']['ID']);
//            $form = new UserForm();
//            $carForm = new CarForm();
//            $form->addClass('profile-edit');
//            $form->removeElements(['email2', 'check']);
//            $form->disableElement('username');
//
//            $userData = [
//                'id'        => $user['ID'],
//                'username'  => $user['Username'],
//                'firstname' => $user['Fname'],
//                'lastname'  => $user['Lname'],
//                'city'      => $user['City'],
//                'email1'    => $user['Email'],
//                'userId'    => $user['ID'],
//                'sex'       => $user['Sex'],
//                'notex'     => $user['Notes']
//            ];
//
//            $form->populate($userData);
//
//            $viewParams = [
//                'title'         => $user['Username']."'s profile",
//                'form'          => $form,
//                'carForm'       => $carForm,
//                'user'          => $userData,
//                'cars'          => $this->CarModel->listCarsByUserId($user['ID']),
//                'JS'            => ['profile.js', 'cars.js'],
//                'CSS'           => ['profile.css', 'cars.css']
//            ];
//            View::render('account/profile.php', $viewParams);
//        } else {
//            header('location: /');
//        }
    }

    public function editAction()
    {
//        $response = [];
//        if (!empty($_POST)) {
//            $currentUser = $this->UserModel->getUserByUserId($_POST['user-id']);
//            //TODO: FIX THIS!
//            $User = new User($currentUser['Username'], 'blank');
//            $this->UserModel->editUser($User, $_POST,true);
//            $response['success'] = true;
//        } else {
//            $response['success'] = false;
//        }
//        echo json_encode($response);
    }

    public function deleteAction()
    {
//        if (empty($_POST)) {
//            $this->_generateError('No data provided');
//        } else {
//            $userId = $_POST['userId'];
//            if ((bool)$_POST['deleteExpenses']) {
//                $this->ExpenseModel->removeUserExpenses($userId);
//            }
//            if ((bool)$_POST['deleteCars']) {
//                $this->CarModel->removeUserCars($userId);
//            }
//            $this->PartsModel->removeByUserId($userId);
//            $this->UserModel->removeUser($userId);
//            $response['success'] = true;
//            json_encode($response);
//        }
//        die();
    }
}

?>