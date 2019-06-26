<?php

class Controller {

    protected $_view;
    protected $_model;

    public function __construct() {
        $this->_view = new View();
    }

    public function loadModel($name) {
        $path = 'models/' . $name . '_Model.php';
        if (file_exists($path)) {
            require_once $path;
            $modelName = $name . "_Model";  
            $this->_model = new $modelName();
        }
    }
    
    public function uploadimage() {
        if($_FILES["image"]["name"] != "") {
        //$name = $_POST['name'];
        //$name = str_replace(' ', '', $name);
        $target_dir = "C:\\xampp\\htdocs\\SchoolProject\\uploads\\";
        $target_file = $target_dir . $_FILES["image"]["name"]; //basename($_FILES["fileToUpload"]["name"]);
        $uploadOk = 1;
        $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
        // Check if image file is a actual image or fake image
        //if(isset($_POST["action"])) {
            //var_dump($_FILES["image"]);
            //echo is_uploaded_file($_FILES["image"]['tmp_name']);
           // $check = getimagesize($_FILES["image"]["tmp_name"]);
//            if($check !== false) {
//                //echo "File is an image - " . $check["mime"] . ".";
//                $uploadOk = 1;
//            } else {
//                //echo "File is not an image.";
//                $uploadOk = 0;
//            }
        //}
        // Check if file already exists
//        if (file_exists($target_file)) {
//            unlink($target_file);
//            $uploadOk = 1;
//        } 
        // Check file size
//        if ($_FILES["image"]["size"] > 500000) {
//            echo "Sorry, your file is too large.";
//            $uploadOk = 0;
//        }
        // Allow certain file formats
        if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
        && $imageFileType != "gif" ) {
            //echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
            $uploadOk = 0;
        }
        // Check if $uploadOk is set to 0 by an error
        if ($uploadOk == 0) {
            return false;
        // if everything is ok, try to upload file
        } else {
            if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
                $bname = basename($target_file);
                $this->retimage = $target_file;
                return $this->retimage;
            }// else {
        //        echo "Sorry, there was an error uploading your file.";
        //    }
        //}
        }

        //return $target_file;
        }
    }

    
}    