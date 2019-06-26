<?php

class Administration_Model extends Model {
    
    public $result;
    public $div;
    public $form;
    public $url = Config::URL;
    public $queryAll;
            
    function __construct() {
        parent::__construct();
        $this->base();
    }
    // session::get($key)
    //GetALL Function
    public function index() {
        if (isset($_POST['action']) == 'Update' || isset($_POST['action']) == 'Delete') {
            $this->base(); // load updated db again
        }
        if (Session::get('role') == "Owner") {
        $this->result = $this->queryAll;
        $this->CreateList();
        return $this->div;
        }
        if (Session::get('role') != "Owner") {
        $sql = "SELECT * FROM `school`.`administrator` WHERE `administrator`.`role` = 'Manager' or `administrator`.`role` = 'Sales';";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        $this->result = $stmt->fetchALL(PDO::FETCH_ASSOC);
        $this->CreateList();
        return $this->div;
        }
    }
    
    public function Save($retimage) {
        try {
            $email = $_POST['email'];
            $password = sha1(config::$salt_prefix . $_POST['password'] . config::$salt_suffix);
            $name = $_POST['name'];
            $phone = $_POST['phone'];
            $role = $_POST['role'];
            if ($retimage == null) {
                throw new Exception("Please put Image!");
            } else {
                $image = 'uploads/' . basename($retimage);
            }
            $sql = "INSERT INTO `school`.`administrator`"
            . "(`administrator`.`name`, `administrator`.`phone`, `administrator`.`email`, `administrator`.`password`, `administrator`.`role`, `administrator`.`image`)"
            . "VALUES ('$name','$phone','$email','$password','$role','$image');";
            $stmt = $this->db->prepare($sql);
            if ($role === "Owner" || $role === "owner") {
                throw new Exception("Duplicate Owners detected!");
            }
            if ($name === "") {
            throw new Exception("Please put Name!");
            } else {
                $stmt->bindParam('name', $name);
            }
            if ($_POST['password'] === "") {
            throw new Exception("Please put Password!");
            } else {
                $stmt->bindParam('password', $password);
            }
            if ($phone === "") {
            throw new Exception("Please put Phone!");
            } else {
                $stmt->bindParam('phone', $phone);
            }
            if ($email === "") {
            throw new Exception("Please put Email!");
            } else {
                $stmt->bindParam('email', $email);
            }
            if ($role === "") {
            throw new Exception("Please put Role!");
            } else {
                $stmt->bindParam('role', $role);
                $stmt->bindParam('image', $image);
            }
            $stmt->execute();
            return true;
        } catch (Exception $ex) {
            return $ex->getMessage();
        }
    }
    
     public function Update($retimage) {
        $id = $_POST['aid'];
        $email = $_POST['email'];
        $name = $_POST['name'];
        $phone = $_POST['phone'];
        $role = $_POST['role'];
        if ($retimage != null) {
        $image = 'uploads/' . basename($retimage);
        } else {
            $eximage = $this->getExImage($id);
            $image = $eximage['image'];
        }
        if ($retimage != null && Session::get('role') == $role) {
            Session::set('image', $image);
        }
        $sql = "UPDATE `school`.`administrator` SET `administrator`.`name` = '$name', `phone` = '$phone', `email` = '$email', `role` = '$role', `image` = '$image' WHERE `aid` = '$id';";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam('aid', $aid);
        $stmt->bindParam('name', $name);
        $stmt->bindParam('phone', $phone);
        $stmt->bindParam('email', $email);
        $stmt->bindParam('role', $role);
        $stmt->bindParam('image', $image);
        $stmt->execute();
        return "<center><strong>Admin updated Succesfully!</strong></center>";
    }

    public function Delete() {
            $id = $_POST['aid'];
            $sql = "DELETE FROM `school`.`administrator` WHERE `aid` = '$id';";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam('aid', $id);
            $stmt->execute();
            return "<center><strong>Admin Deleted Succesfully!</strong></center>";
    }
    
    private function getExImage($id) {
        $sql = "SELECT `administrator`.`image` FROM `school`.`administrator` WHERE `aid` = $id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam('aid', $id);
        $stmt->execute();
        $eximage = $stmt->fetch(PDO::FETCH_ASSOC);
        return $eximage;
    }

    public function CreateList() {
        $this->div = "<div class='list'>";
        foreach ($this->result as $key) {
            $id = $key['aid'];
            $this->div .= "<div align='center' dir='rtl'><a href='$this->url/Administration/user/$id' name='action' class='stylelink'>"
                    . "<table border='1' cellspacing='0' cellpadding='0'>"
                    . "<tbody><tr><td width='130' valign='top'><p style='text-align: center;'>{$key['name']} , {$key['role']}</p></td>"
                    . "<td width='130' rowspan='3' valign='top'><p dir='rtl'>"
                    . "<img width='130' height='130' src='$this->url/{$key['image']}' alt='image'>"
                    . "</p></td></tr>"
                    . "<tr><td width='130' valign='top'><p style='text-align: center;'>{$key['phone']}</p></td></tr>"
                    . "<tr><td width='130' valign='top'><p style='text-align: center;'>{$key['email']}</p></td></tr>"
                    . "</tbody></table></a></div><hr>";
        }
        $this->div .= "</div>";
        return $this->div;
    }
    
    public function user($id) {
            $sql = "SELECT * FROM `school`.`administrator` WHERE `aid` = $id";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam('aid', $id);
            $stmt->execute();
            $this->result = $stmt->fetch(PDO::FETCH_ASSOC);
            $this->CreateEditForm();
            return $this->form;
    }
    
    private function base() {
        $sql = "SELECT * FROM `school`.`administrator`;";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        $this->queryAll = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
   
    private function CreateEditForm() {
        $id = $this->result['aid'];
        $name = $this->result['name'];
        $phone = $this->result['phone'];
        $email = $this->result['email'];
        $role = $this->result['role'];
        $image = $this->result['image'];
        $this->form = //"<div id='panel'>"
          "<center><h1>Edit Admin</h1><hr>"
         . "<button type='submit' value='showedit' id='edit'>Edit</button>"
         . "<hr>"
         . "<form action='$this->url/administration/edit' method='POST' enctype='multipart/form-data'><table>"
         . "<tr><td>Name: </td><td><input name='name' type='text' value='$name' id='inp1' style='background-color: grey' readonly></td></tr>"
         . "<tr><td>Phone: </td><td><input name='phone' type='text' value=$phone id='inp2' style='background-color: grey' readonly></td></tr>"
         . "<tr><td>Email: </td><td><input name='email' type='text' value=$email id='inp3' style='background-color: grey' readonly></td></tr>"
         . "<tr><td>Role: </td><td><input name='role' type='text' value=$role id='inp7' style='background-color: grey' readonly></td></tr>"
         . "<span><table><tr><td rowspan='3' colspan='3'><input type='file' name='image' onchange='loadFile(event)' id='inp4' style='display: none' readonly></td>"
         . "<td rowspan='3'><img id='output' src='$this->url/$image' style='width: 155px; height: 171px'/></td></tr><br></table></span>"
         . "<input name='aid' type='hidden' value='$id'>"
         . "<input id='inp5' type='submit' name='action' value='Update' style='display: none'>";
        if (Session::get('role') == "Manager" && $role == "Manager") {
            $this->form .= "</table></form></center>";
        } else {
         $this->form .= "<input id='inp6' type='submit' name='action' value='Delete' style='display: none' onclick='return confirm(&apos;Are You Sure?&apos;)'>";
         $this->form .= "</table></form></center>";
        }
         return $this->form;
    }
        public function NewForm() {
//        $id = isset($_POST['aid']) ? $_POST['aid'] : '';
        $email = isset($_POST['email']) ? $_POST['email'] : '';
        $name = isset($_POST['name']) ? $_POST['name'] : '';
        $phone = isset($_POST['phone']) ? $_POST['phone'] : '';
        $role = isset($_POST['role']) ? $_POST['role'] : '';
            $this->form = //"<div id='panel'>"
              "<center><h1>Add Administrator</h1>"
             . "<hr>"
             . "<table><form action='$this->url/administration/save' method='POST' enctype='multipart/form-data'>"
             . "<tr><td>Name: </td><td><input name='name' type='text' value='$name'></td></tr>"
             . "<tr><td>Password: </td><td><input name='password' type='password' value=''></td></tr>"
             . "<tr><td>Phone: </td><td><input name='phone' type='text' value='$phone'></td></tr>"
             . "<tr><td>Email: </td><td><input name='email' type='text' value='$email'></td></tr>"
             . "<tr><td>Role: </td><td><input name='role' type='text' value='$role'></td></tr>"
             . "<span><table><tr><td rowspan='3' colspan='3'><input type='file' name='image' onchange='loadFile(event)'></td>"
             . "<td rowspan='3'><img id='output' src='' alt='Image will show here' style='width: 155px; height: 171px'/></td></tr><br></table></span>"
             . "<tr><td><input type='submit' name='action' value='Save'></td></tr>";
             $this->form .= "</form></table></center>";
                return $this->form;
        }
        
}
