<?php

class Yeshiva extends Controller {

    private $prefunction;
    
    function __construct() {
        parent::__construct();   
        $this->loadModel(__CLASS__);
        $this->_view->content = "";
        $this->_view->studentlist = "";
        $this->_view->courselist = "";
        $this->_view->msg = "";
        $this->_view->bufferform = "";
    }
    
    public function index() {
        $listall = $this->_model->index();
        $this->_view->studentlist = $listall['sdiv'];
        $this->_view->courselist = $listall['cdiv'];
        $this->_view->render('yeshiva/index', 'stu');
    }    

    public function studentsave() {
        $retimage = null;
        if ($_FILES["image"]["name"] != "") {
            $retimage = $this->uploadimage();
        }
        $result = $this->_model->studentsave($retimage);
        if (is_bool($result)) {
            $this->_view->content = "<strong>1 New Student Added Succesfully!</strong>";
            //$this->_view->list = $this->_model->index();
        } else {
            $this->_view->content = $this->_model->NewStudent();
            $this->_view->msg = $result;
        }
        $listall = $this->_model->index();
        $this->_view->studentlist = $listall['sdiv'];
        $this->_view->courselist = $listall['cdiv'];
        $this->_view->render('yeshiva/index', 'stu');
    }
    
    public function coursesave() {
        $retimage = null;
        if ($_FILES["image"]["name"] != "") {
            $retimage = $this->uploadimage();
        }
        $result = $this->_model->coursesave($retimage);
        if (is_bool($result)) {
            $this->_view->content = "<strong>1 New Course Added Succesfully!</strong>";
            //$this->_view->list = $this->_model->index();
        } else {
            $this->_view->content = $this->_model->NewCourse();
            $this->_view->msg = $result;
        }
        $listall = $this->_model->index();
        $this->_view->studentlist = $listall['sdiv'];
        $this->_view->courselist = $listall['cdiv'];
        $this->_view->render('yeshiva/index', 'stu');
    }
    
    public function student($id){
        $this->_view->content = $this->_model->student($id);
        $this->_view->bufferform = $this->_model->StudentEditForm();
        $listall = $this->_model->index();
        $this->_view->studentlist = $listall['sdiv'];
        $this->_view->courselist = $listall['cdiv'];
        $this->_view->render('Yeshiva/index', 'stu');
    }
    public function course($id){
        $this->_view->content = $this->_model->course($id);
        $this->_view->bufferform = $this->_model->CourseEditForm();
        $listall = $this->_model->index();
        $this->_view->studentlist = $listall['sdiv'];
        $this->_view->courselist = $listall['cdiv'];
        $this->_view->render('Yeshiva/index', 'stu');
    }
    public function studentedit(){
        $this->prefunction = __FUNCTION__;
        $action = $_POST['action'];
        $action = str_replace(' ', '', $action);
        $this->{$action}();
        $this->_view->render('Yeshiva/index', 'stu');
    }
    
    public function courseedit(){
        $this->prefunction = __FUNCTION__;
        $action = $_POST['action'];
        $action = str_replace(' ', '', $action);
        $this->{$action}();
        $this->_view->render('Yeshiva/index', 'stu');
    }
        public function Update() {
        $retimage = null;
        if ($_FILES["image"]["name"] != "") {
            $retimage = $this->uploadimage();
        }
        if ($this->prefunction == 'studentedit') {
            $this->_view->content = $this->_model->StudentUpdate($retimage);
        } else if ($this->prefunction == 'courseedit') {
            $this->_view->content = $this->_model->CourseUpdate($retimage);
        }
        //$this->_view->bufferform = $this->_model->StudentEditForm();
        $listall = $this->_model->index();
        $this->_view->studentlist = $listall['sdiv'];
        $this->_view->courselist = $listall['cdiv'];
//        if (is_bool($result)) {
//            $this->_view->msg = "<strong>1 New Admin Added Succesfully!</strong>";
            //$this->_view->list = $this->_model->index();
        //$this->_view->render('Yeshiva/index', 'stu');
    }
    
    public function Delete() {
        if ($this->prefunction == 'studentedit') {
            $this->_view->content = $this->_model->StudentDelete();
        } else if ($this->prefunction == 'courseedit') {
            $this->_view->content = $this->_model->CourseDelete();
        }
        $listall = $this->_model->index();
        $this->_view->studentlist = $listall['sdiv'];
        $this->_view->courselist = $listall['cdiv'];
//        if (is_bool($result)) {
//            $this->_view->msg = "<strong>1 New Admin Added Succesfully!</strong>";
            //$this->_view->list = $this->_model->index();
//        $this->_view->render('Yeshiva/index', 'stu');
    }
    
        public function newstudent() {
        $listall = $this->_model->index();
        $this->_view->studentlist = $listall['sdiv'];
        $this->_view->courselist = $listall['cdiv'];
        $this->_view->content = $this->_model->NewStudent();
        $this->_view->render('Yeshiva/index', 'stu');
    }
        public function newcourse() {
        $listall = $this->_model->index();
        $this->_view->studentlist = $listall['sdiv'];
        $this->_view->courselist = $listall['cdiv'];
        $this->_view->content = $this->_model->NewCourse();
        $this->_view->render('Yeshiva/index', 'stu');
    }
    
    
    
}