<?php

class Login_Model extends Model {

    function __construct() {
        parent::__construct();
    }

    public function authenticate() {
        $retValue = false;
        try {
        $email = $_POST['email'];
        $password = sha1(config::$salt_prefix . $_POST['password'] . config::$salt_suffix);
        $sql = "SELECT * FROM `school`.`administrator` "
                . "WHERE `administrator`.`email` = '$email' and `administrator`.`password` = '$password';";
//        $sql = "SELECT `administrator`.`role` FROM `school`.`administrator` 
//	WHERE `administrator`.`email` = 'rgamliel@bavli.co.il' and `administrator`.`password` = '9646b92f59104be525bfabb58b40d0690a5064a3';";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam('email', $email);
        $stmt->bindParam('password', $password);
        $stmt->execute();
        $this->result = $stmt->fetch((PDO::FETCH_ASSOC));
        if ($email === "") {
                throw new Exception("Please put Email!");
            } else 
            if ($password === "") {
                throw new Exception("Please put Password!");
            }
        if ($this->result == FALSE && $email != "") {
                throw new Exception("Incorrect Password!");
        } else if ($this->result == FALSE && $password != "") {
               throw new Exception("Incorrect Email!");
        }
        if ($this->result['email'] == $email && $this->result['password'] != $password) {
                throw new Exception("Incorrect Password!");
        }
        }
        catch (Exception $ex) {
            return $ex->getMessage();// && $retValue;
        }
        $name = $this->result['name'];
        $role = $this->result['role'];
        $image = $this->result['image'];
        if (isset($role) && isset($name)) {
            Session::set('name', $name);
            Session::set('role', $role);
            Session::set('image', $image);
            Session::set('loggedIn' , true);
            $retValue = true;
        } else {
                $retValue = false;
            }
//        $this->db->close();
        return $retValue;
    }
    
}
