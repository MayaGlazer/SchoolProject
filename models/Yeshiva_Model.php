<?php

class Yeshiva_Model extends Model {
    
    public $result;
    public $sdiv;
    public $cdiv;
//    public $form;
    public $url = Config::URL;
    public $queryCourse;
    public $queryStudents;
    public $retCourses;
    public $count;
    
    //$currentaction = (Bootstrap::_action) ? $Bootstrap::_action : '';
    
    
    function __construct() {
        parent::__construct();
        $this->CourseBase();
        $this->StudentBase();
    }
    
    public function index() {
        if (isset($_POST['action']) == 'Update' || isset($_POST['action']) == 'Delete' || isset($_POST['action']) == 'Save') {
            $this->CourseBase();
            $this->StudentBase(); // load updated db again
        }
        //if ($this->currentaction == 'course')
        $this->result = $this->queryStudents;
        $sdiv = $this->CreateStudentList();
        $this->result = $this->queryCourse;
        $cdiv = $this->CreateCourseList();
        $listall = compact('sdiv', 'cdiv');
        return $listall;
    }
    
    public function studentsave($retimage) {
        try {
            $email = $_POST['email'];
            $name = $_POST['name'];
            $phone = $_POST['phone'];
            if ($retimage == null) {
                throw new Exception("Please put Image!");
            } else {
                $image = 'uploads/' . basename($retimage);
            }
            $sql = "INSERT INTO `school`.`student` (`student`.`name`, "
                 . "`student`.`phone`, `student`.`email`, `student`.`image`) "
                 . "VALUES ('$name', '$phone', '$email', '$image');";
            $stmt = $this->db->prepare($sql);
            if ($name === "") {
            throw new Exception("Please put Name!");
            } else {
                $stmt->bindParam('name', $name);
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
                $stmt->bindParam('image', $image);
            }
            $stmt->execute();
            if ($this->CourseBind($name, __FUNCTION__) == true) {
            return true;
            }
        } catch (Exception $ex) {
            return $ex->getMessage();
        }
    }
    
    public function coursesave($retimage) {
        try {
            $name = $_POST['name'];
            $desc = $_POST['description'];
            if ($retimage == null) {
                throw new Exception("Please put Image!");
            } else {
                $image = 'uploads/' . basename($retimage);
            }
            $sql = "INSERT INTO `school`.`course` (`course`.`name`, `course`.`description`, `course`.`image`) "
                 . "VALUES ('$name', '$desc', '$image');";
            $stmt = $this->db->prepare($sql);
            if ($name === "") {
            throw new Exception("Please put Name!");
            } else {
                $stmt->bindParam('name', $name);
            }
            if ($desc === "") {
            throw new Exception("Please put Description!");
            } else {
                $stmt->bindParam('description', $desc);
                $stmt->bindParam('image', $image);
            }
            $stmt->execute();
            return true;
        } catch (Exception $ex) {
            return $ex->getMessage();
        }
    }
    
    public function StudentUpdate($retimage) {
        $id = $_POST['sid'];
        $email = $_POST['email'];
        $name = $_POST['name'];
        $phone = $_POST['phone'];
        if ($retimage != null) {
        $image = 'uploads/' . basename($retimage);
        } else {
            $eximage = $this->getExImage($id, __FUNCTION__);
            $image = $eximage['image'];
        }
        $sql = "UPDATE `school`.`student` SET `student`.`name` = '$name', `phone` = '$phone', `email` = '$email', `image` = '$image' WHERE `sid` = '$id';";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam('name', $name);
        $stmt->bindParam('phone', $phone);
        $stmt->bindParam('email', $email);
        $stmt->bindParam('image', $image);
        $stmt->execute();
        if ($this->CourseBind($id, __FUNCTION__) == true) {
        //$this->retCourses = $this->SetCourse($id);
        return "<center><strong>Student updated Succesfully!</strong></center>";
        }
    }
    
    public function CourseUpdate($retimage) {
        $id = $_POST['cid'];
        $name = $_POST['name'];
        $desc = $_POST['description'];
        if ($retimage != null) {
        $image = 'uploads/' . basename($retimage);
        } else {
            $eximage = $this->getExImage($id, __FUNCTION__);
            $image = $eximage['image'];
        }
        $sql = "UPDATE `school`.`course` SET `course`.`name` = '$name', `description` = '$desc', `image` = '$image' WHERE `cid` = '$id';";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam('name', $name);
        $stmt->bindParam('description', $desc);
        $stmt->bindParam('image', $image);
        $stmt->execute();
        return "<center><strong>Course details updated Succesfully!</strong></center>";
    }
    
    public function CourseBind($val, $func) {
        switch ($func) {
            case "StudentUpdate":
                $id = $val;
                $sql = "DELETE FROM `school`.`joined` WHERE `sid` = '$id';";
                $stmt = $this->db->prepare($sql);
                $stmt->execute();
                parent::__construct();
                break;
            case "studentsave":
                parent::__construct();
                $sql = "SELECT `student`.`sid` FROM `school`.`student` WHERE `student`.`name` = '$val'";
                $stmt = $this->db->prepare($sql);
                $stmt->bindParam('name', $val);
                $stmt->execute();
                $id = $stmt->fetch(PDO::FETCH_ASSOC);
                $id = $id['sid'];
                break;
        }
        if (isset($_POST['coursedata'])) {
        $coursedata = $_POST['coursedata'];
        foreach ($coursedata as $key) {
        $sql = "INSERT INTO `school`.`joined` (`cid`, `sid`) VALUES ($key, $id);";
        $stmt->bindParam('cid', $key);
        $stmt->bindParam('sid', $id);
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        }
        }
        return true;   
    }
    
    private function getExImage($id, $func) {
        switch ($func) {
            case "StudentUpdate":
                $sql = "SELECT `student`.`image` FROM `school`.`student` WHERE `sid` = $id";
                $stmt = $this->db->prepare($sql);
                $stmt->bindParam('sid', $id);
                break;
            case "CourseUpdate":
                $sql = "SELECT `course`.`image` FROM `school`.`course` WHERE `cid` = $id";
                $stmt = $this->db->prepare($sql);
                $stmt->bindParam('cid', $id);
                break;
        }
        $stmt->execute();
        $eximage = $stmt->fetch(PDO::FETCH_ASSOC);
        return $eximage;
    }

    public function StudentDelete() {
            $id = $_POST['sid'];
            $sql = "DELETE FROM `school`.`joined` WHERE `sid` = '$id';";
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            $sql = "DELETE FROM `school`.`student` WHERE `sid` = '$id';";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam('sid', $id);
            $stmt->execute();
            return "<center><strong>1 Student Deleted Succesfully!</strong></center>";
    }
    
    public function CourseDelete() {
            $id = $_POST['cid'];
            $sql = "DELETE FROM `school`.`course` WHERE `cid` = '$id';";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam('cid', $id);
            $stmt->execute();
            $sql = "DELETE FROM `school`.`joined` WHERE `cid` = '$id';";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam('cid', $id);
            $stmt->execute();
            return "<center><strong>1 Course Deleted Succesfully!</strong></center>";
    }
    
    public function CreateStudentList() {
        $this->sdiv = "<div class='list'>";
        foreach ($this->result as $key) {
            $id = $key['sid'];
            $this->sdiv .= "<div align='center' dir='rtl'><a href='$this->url/Yeshiva/student/$id' name='action' class='stylelink'>"
                    . "<table border='1' cellspacing='0' cellpadding='0'>"
                    . "<tbody><tr><td width='130' valign='top'><p style='text-align: center;'>{$key['name']}</td>"
                    . "<td width='130' rowspan='2' valign='top'><p dir='rtl'><p>"
                    . "<img width='130' height='130' src='$this->url/{$key['image']}' alt='image'>"
                    . "</p></td></tr>"
                    . "<tr><td width='130' valign='top'><p style='text-align: center;'>{$key['phone']}</p></td></tr>"
                    . "</tbody></table></a></div><hr>";
        }
        $this->sdiv .= "</div>";
        return $this->sdiv;
    }
    
    public function CreateCourseList() {
        $this->cdiv = "<div class='list'>";
        foreach ($this->result as $key) {
            $id = $key['cid'];
            $this->cdiv .= "<div align='center' dir='rtl'><a href='$this->url/Yeshiva/course/$id' name='action' class='stylelink'>"
                    . "<table border='1' cellspacing='0' cellpadding='0'>"
                    . "<tbody><tr><td width='130' rowspan='2' valign='top'><p style='text-align: center;'>{$key['name']}</td>"
                    . "<td width='130' rowspan='2' valign='top'><p dir='rtl'><p>"
                    . "<img width='130' height='130' src='$this->url/{$key['image']}' alt='image'>"
                    . "</p></td></tr>"
                    . "</tbody></table></a></div><hr>";
        }
        $this->cdiv .= "</div>";
        return $this->cdiv;
    }
    
    public function student($id) {
            $sql = "SELECT * FROM `school`.`student` WHERE `sid` = $id";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam('sid', $id);
            $stmt->execute();
            $this->result = $stmt->fetch(PDO::FETCH_ASSOC);
            $this->StudentDetails();
            return $this->form;
    }
    public function course($id) {
            $sql = "SELECT * FROM `school`.`course` WHERE `cid` = $id";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam('cid', $id);
            $stmt->execute();
            $this->result = $stmt->fetch(PDO::FETCH_ASSOC);
            $this->CourseDetails();
            return $this->form;
    }
    
    private function CourseBase() {
        $sql = "SELECT * FROM `school`.`course`;";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        $this->queryCourse = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    private function StudentBase() {
        $sql = "SELECT * FROM `school`.`student`;";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        $this->queryStudents = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    private function StudentDetails() {
        $id = $this->result['sid'];
        $name = $this->result['name'];
        $phone = $this->result['phone'];
        $email = $this->result['email'];
        $image = $this->result['image'];
        $this->retCourses = $this->SetCourse($id);
        $courses = "<h1>Courses<h1><ul class='listcontainer'>"; 
        foreach ($this->retCourses as $key) {
            $courses .= "<li class='inlinelist'>"
                    . "<img src='$this->url/{$key['image']}' alt='image missing' style='width: 30px; max-height=45px'>"
                    . "<h3>{$key['name']}</h3></li><br>";
        }
        $courses .= "</ul>";
        $this->form = //"<div id='ypanel'>"
            "<button type='submit' value='showedit' id='stuedit'>Edit</button>"
            . "<center id='editable'><form action='$this->url/yeshiva/studentedit/$id' method='POST' enctype='multipart/form-data' dir='rtl'>"
            . "<table border='0' cellspacing='0' cellpadding='0'>"
            . "<tbody><tr><td width='130' valign='top'><p style='text-align: center;'>$name</p></td>"
            . "<td width='130' rowspan='3' valign='top'><p dir='rtl'>"
            . "<img width='130' height='130' src='$this->url/$image' alt='image'>"
            . "</p></td></tr>"
            . "<tr><td width='130' valign='top'><p style='text-align: center;'>$phone</p></td></tr>"
            . "<tr><td width='130' valign='top'><p style='text-align: center;'>$email</p></td></tr>"
            . "<input name='sid' type='hidden' value='$id'>"
            . "</tbody></table><hr>$courses</form></center>";
         return $this->form;
    }
    
    private function CourseDetails() {
//        $counter = 0; 
        $id = $this->result['cid'];
        $name = $this->result['name'];
        $description = $this->result['description'];
        $image = $this->result['image'];
        $studentsum = $this->countStudents($id);
        $this->retStudents = $this->SetStudent($id);
        $students = "<h1>Students<h1><ul class='listcontainer'>"; 
        foreach ($this->retStudents as $key) {
            $students .= "<li class='inlinelist'>"
                    . "<img src='$this->url/{$key['image']}' alt='image missing' style='width: 30px; max-height=45px'>"
                    . "<h3>{$key['name']}</h3></li><br>";
//                    $counter++;
        }
        $students .= "</ul>";
        $this->form = ""; //"<div id='ypanel'>"
            if (Session::get('role') == "Manager" || Session::get('role') == "Owner") {
            $this->form .= "<button type='submit' value='showedit' id='stuedit'>Edit</button>";
            }
            $this->form .= "<center id='editable'><form action='$this->url/yeshiva/courseedit/$id' method='POST' enctype='multipart/form-data' align='center' dir='rtl'>"
            . "<table border='0' cellspacing='0' cellpadding='0'>"
            . "<tbody><tr><td width='170' valign='top'><p style='text-align: center;'>$name, $studentsum Students</p></td>"
            . "<td width='130' rowspan='3' valign='top'><p dir='rtl'>"
            . "<img width='130' height='130' src='$this->url/$image' alt='image'>"
            . "</p></td></tr>"
            . "<tr><td width='130' valign='top'><p style='text-align: center;'>$description</p></td></tr>"
            . "<input name='cid' type='hidden' value='$id'>"
            . "</tbody></table><hr>$students</form></center>";
         return $this->form;
    }
    
    // checks and restrieves all courses in checkbox with checked boxes when signed
    private function checkedCourses() {
        $checkbox = "";
        for ($i = 0; $i < count($this->queryCourse); $i++) {
            $checkbox .= "<input type='checkbox' name='coursedata[]' value='{$this->queryCourse[$i]['cid']}' ";
            //if ($i < count($this->retCourses)) {
           for ($f = 0; $f < count($this->retCourses); $f++ ) {
               if ($this->queryCourse[$i]['cid'] == $this->retCourses[$f]['cid']) {
                    $checkbox .= "checked";
               }
            }
                $checkbox .= "> {$this->queryCourse[$i]['name']}<br>";
        }
        return $checkbox;
       }
            
    
    private function CoursesFields() {
        $checkbox = "";
        for ($i = 0; $i < count($this->queryCourse); $i++) {
            $checkbox .= "<input type='checkbox' name='coursedata[]' value='{$this->queryCourse[$i]['cid']}' "
            . "> {$this->queryCourse[$i]['name']}";
            }
        return $checkbox;
    }

    private function SetCourse($id) {
        $sql = "SELECT `course`.`image`, `course`.`name`, `course`.`cid` FROM `joined` INNER JOIN `course` WHERE `joined`.`cid` = `course`.cid AND `joined`.`sid` = $id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        $this->retCourses = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $this->retCourses;
    }
    private function countStudents($id) {
        $sql = "SELECT COUNT(*) AS `sum` FROM joined WHERE cid='$id'";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        $this->count = $stmt->fetch(PDO::FETCH_ASSOC);
        return $this->count['sum'];
    }
    
    private function SetStudent($id) {
        $sql = "SELECT `student`.`image`, `student`.`name`, `student`.`sid` FROM `joined` INNER JOIN `student` WHERE `joined`.`sid` = `student`.sid AND `joined`.`cid` = $id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        $retStudents = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $retStudents;
    }
    
    public function NewStudent() {
        $email = isset($_POST['email']) ? $_POST['email'] : '';
        $name = isset($_POST['name']) ? $_POST['name'] : '';
        $phone = isset($_POST['phone']) ? $_POST['phone'] : '';
        $courses = $this->CoursesFields();
        $this->form = //"<div id='ypanel'>"
          "<center><h1>Add Student</h1>"
         . "<hr>"
         . "<table><form action='$this->url/yeshiva/studentsave' method='POST' enctype='multipart/form-data'>"
         . "<tr><td>Name: </td><td><input name='name' type='text' value='$name'></td></tr>"
         . "<tr><td>Phone: </td><td><input name='phone' type='text' value='$phone'></td></tr>"
         . "<tr><td>Email: </td><td><input name='email' type='text' value='$email'></td></tr>"
         . "<span><table><tr><td rowspan='3' colspan='3'><input type='file' name='image' onchange='loadFile(event)'></td>"
         . "<td rowspan='3'><img id='output' src='' alt='Image will show here' style='width: 155px; height: 171px'/></td></tr><br></table></span>"
         . "Courses:<div id='scrollx'> $courses</div><br>"
         . "<tr><td><input type='submit' name='action' value='Save'></td></tr>"
         . "</form></table></center>";
        return $this->form;
    }

    public function NewCourse() {
        $name = isset($_POST['name']) ? $_POST['name'] : '';
        $desc = isset($_POST['description']) ? $_POST['description'] : '';
        $this->form = //"<div id='ypanel'>"
          "<center><h1>Add Course</h1>"
         . "<hr>"
         . "<table><form action='$this->url/yeshiva/coursesave' method='POST' enctype='multipart/form-data'>"
         . "<tr><td>Name: </td><td><input name='name' type='text' value='$name' style='margin-left: auto;'></td></tr>"
         . "<tr><td style='vertical-align:top;'>Description: </td><td><textarea name='description' type='text' required></textarea></td></tr>"
         . "<span><table><tr><td rowspan='3' colspan='3'><input type='file' name='image' onchange='loadFile(event)'></td>"
         . "<td rowspan='3'><img id='output' src='' alt='Image will show here' style='width: 155px; height: 171px'/></td></tr><br></table></span>"
         . "<tr><td><input type='submit' name='action' value='Save'></td></tr>"
         . "</form></table></center>";
        return $this->form;
    }


    public function StudentEditForm() {
    $id = $this->result['sid'];
    $name = $this->result['name'];
    $phone = $this->result['phone'];
    $email = $this->result['email'];
    $image = $this->result['image'];
    $courses = $this->checkedCourses();
    $this->bufferform = //"<div id='ypanel'>"
        "<form action='$this->url/yeshiva/studentedit' method='POST' enctype='multipart/form-data' dir='rtl'>"
        . "<table border='0' cellspacing='0' cellpadding='0'>"
        . "<tbody><tr><td width='221' valign='top' class='hei'><p style='text-align: center;'><input name='name' type='text' value='$name'>Name</p></td>"
        . "<td width='130' rowspan='3' valign='top'><p dir='rtl'>"
        . "<img id='output' width='130' height='130' src='$this->url/$image' alt='image'>"
        . "<input type='file' name='image' onchange='loadFile(event)'>"
        . "</p></td></tr>"
        . "<tr><td width='221' valign='top' class='hei'><p style='text-align: center;'><input name='phone' type='text' value=$phone>Phone</p></td></tr>"
        . "<tr><td width='221' valign='top' class='hei'><p style='text-align: center;'><input name='email' type='text' value=$email>Email</p></td></tr>"
        . "<input name='sid' type='hidden' value='$id'>"
        . "</tbody></table><hr><h1 id='chead'>Courses</h1><div id='box'>$courses</div><br>"
        . "<input type='submit' name='action' value='Update'>"
        . "<input type='submit' name='action' value='Delete' onclick='return confirm(&apos;Are You Sure?&apos;)'>"
        . "</form>";
     return $this->bufferform;
}

    public function CourseEditForm() {
        $id = $this->result['cid'];
        $name = $this->result['name'];
        $desc = $this->result['description'];
        $image = $this->result['image'];
        $studentsum = $this->countStudents($id);
        $this->bufferform = //"<div id='ypanel'>"
            "<form action='$this->url/yeshiva/courseedit/$id' method='POST' enctype='multipart/form-data' align='center' dir='rtl'>"
            . "<table border='0' cellspacing='0' cellpadding='0'>"
            . "<tbody><tr><td width='205'><p style='text-align: center;'><input name='name' type='text' value='$name'>Name</p></td>"
            . "<td width='130' rowspan='3'><p dir='rtl'>"
            . "<img width='130' height='130' src='$this->url/$image' alt='image'>"
            . "<input type='file' name='image' onchange='loadFile(event)'>"
            . "</p></td></tr>"
            . "<tr><td width='130' valign='top'><p style='text-align: center;'><textarea dir='ltr' name='description' type='text' value='$desc'>$desc</textarea>Description</p></td></tr>"
//            . "<tr><td width='130' valign='top'><p style='text-align: center;'>$email</p></td></tr>"
            . "</tbody></table><hr><h1>Total $studentsum students taking this course</h1><br>"
            . "<input name='cid' type='hidden' value='$id'>"
            . "<input type='submit' name='action' value='Update'>";
            if ($studentsum == 0) {    
        $this->bufferform .= "<input type='submit' name='action' value='Delete' onclick='return confirm(&apos;Are You Sure?&apos;)'>";
            }
        $this->bufferform .= "</form>";
         return $this->bufferform;
    }
}