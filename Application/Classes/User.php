<?php

namespace Application\Classes;

class User 
{
    private $username;
    private $password1;
    private $password2;
    private $email1;
    private $email2;
    private $fname;
    private $lname;
    private $city;
    private $sex;
    private $notes;		
    private $group;

    public function __construct($username,$password1,$password2="",$email1="",$email2="",$fname="",$lname="",$city="",$sex="",$group="users",$notes="") {
        $this->username = htmlspecialchars($username);
        $this->password1 = $password1;
        $this->password2 = $password2;
        $this->email1 = htmlspecialchars($email1,1);
        $this->email2 = htmlspecialchars($email2,1);
        $this->fname = htmlspecialchars($fname);
        $this->lname = htmlspecialchars($lname);
        $this->city = htmlspecialchars($city);
        $this->sex = $sex;
        $this->group = $group;
        $this->notes = htmlspecialchars($notes,1);
    }

    public function getProperty($property) {
        if (property_exists('\Application\Classes\User', $property)) {
            return $this->$property;
        } else {
            die("Non existent property");
        }
    }

	public function setProperty($property, $value) {
		if (property_exists($this, $property)) {
			$this->$property = $value;
		} else {
			die("Non existent property");
		}
	}
}
?>