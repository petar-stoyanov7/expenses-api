<?php

namespace Application\Models;

use Application\Classes\User;
use Application\Models\ExpenseModel;
use Core\DbModelAbstract;
use Exception;

class UserModel extends DbModelAbstract
{
    public function listUsers()
    {
        $query = <<<__SQL
            SELECT
            u.*,
            count(c.ID) as number_of_cars
            FROM Users u
            LEFT JOIN
            Cars c ON u.ID = c.UID
            GROUP BY u.ID
__SQL;
        return $this->getData($query);
//        $array = $this->getData("SELECT * FROM `Users`");
//        return $array;
    }

    public function getUserByUserId($id)
    {
        $user = $this->getData("SELECT * FROM `Users` WHERE `ID` = {$id}");
        return $user[0];
    }

    public function getUserByUsername($username)
    {
        $user_array = $this->getData("SELECT * FROM `Users` WHERE `Username` = '{$username}'");
        return $user_array[0];
    }

    public function getUserByEmail($email)
    {
        $user_array = $this->getData("SELECT * FROM `Users` WHERE `Username` = '{$email}'");
        return $user_array[0];
    }

    public function startSession($username)
    {
        $array = $this->getUserByUsername($username);
        $_SESSION['user'] = $array;
    }

    public function addUser($userArray)
    {
        $query = "INSERT INTO `Users` (`Username`,`Password`,`Group`,`Email`,`Fname`,`Lname`,`City`,`Sex`,`Notes`)
                   VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $values = [
            $userArray["username"],
            password_hash($userArray["password"], PASSWORD_DEFAULT),
            $userArray["group"],
            $userArray["email"],
            $userArray["firstName"],
            $userArray["lastName"],
            $userArray["city"],
            $userArray["gender"],
            $userArray["notes"],
        ];
        return $this->execute($query, $values);
    }

    public function editUser($user, $post, $adminEdit = false)
    {
        $userArray = $this->getUserByUsername($user->getProperty('username'));
        if ($this->login($user, true) || $adminEdit) {
            $query = 'UPDATE Users SET Fname = ?, Lname = ?, City = ?, Email = ?';

            $values = [
                $post['firstname'],
                $post['lastname'],
                $post['city'],
                $post['email']
            ];
            if (!empty($post['password1']) && !empty($post['password2'])) {
                if ($post['password1'] !== $post['password2']) {
                    return false;
                }
                $query .= ' ` Password` = ?,';
                $values[] = password_hash($post['password1'], PASSWORD_DEFAULT);
            }
            $oldSession = $_SESSION;

            $query = rtrim($query, ',');
            $query .= ' WHERE `ID` = ?';
            $values[] = $userArray['ID'];
            
            $this->execute($query, $values);

            if (!$adminEdit) {
                if (array_diff($_SESSION, $oldSession)) {
                    session_start();
                }
            }
            return true;
        } else {
            return false;
        }
    }

    public function login($user, $password, $isEmail = false)
    {
        $users = $this->listUsers();
        if ($isEmail) {
            foreach ($users as $usr) {
                if($user === $usr["Email"] && password_verify($password, $usr["Password"])) {
                    return $usr;
                }
            }
        } else {
            foreach ($users as $usr) {
                if ($user === $usr["Username"] && password_verify($password, $usr["Password"])) {
                    return $usr;
                }
            }
        }
        return false;
    }

    public function removeUser($id)
    {
        $expenseModel = new ExpenseModel();
        $query = "DELETE FROM `Users` WHERE `ID` = ?";
        $this->execute($query, [$id]);
    }
}

?>