<?php

class Administration extends Controller {

    function __construct() {
        parent::__construct();   
        $this->loadModel(__CLASS__);
        $this->_view->content = "";
        $this->_view->list = "";
        $this->_view->msg = "";
        $this->_view->bufferform = "";
    }
      
    public function index() {
        $this->_view->list = $this->_model->index();
        $this->_view->render('administration/index', 'basic');
    }
    
    public function edit(){
        
        $action = $_POST['action'];
        $action = str_replace(' ', '', $action);

        $this->{$action}();
        $this->_view->render('administration/index', 'basic');
    }
    
//    public function showedit(){
//        
//    }
    
    public function newadmin() {
        $this->_view->list = $this->_model->index();
        $this->_view->content = $this->_model->NewForm();
        $this->_view->render('administration/index', 'basic');
    }

    public function Save() {
        $retimage = null;
        if ($_FILES["image"]["name"] != "") {
            $retimage = $this->uploadimage();
        }
        $result = $this->_model->Save($retimage);
        if (is_bool($result)) {
            $this->_view->content = "<center><strong>1 New Admin Added Succesfully!</strong></center>";
            //$this->_view->list = $this->_model->index();
        } else {
            $this->_view->content = $this->_model->NewForm();
            $this->_view->msg = $result;
        }
        $this->_view->list = $this->_model->index();
        $this->_view->render('administration/index', 'basic');
    }
    
    public function Update() {
        $retimage = null;
        if ($_FILES["image"]["name"] != "") {
            $retimage = $this->uploadimage();
        }
        $this->_view->content = $this->_model->Update($retimage);
        $this->_view->list = $this->_model->index();
//        if (is_bool($result)) {
//            $this->_view->msg = "<strong>1 New Admin Added Succesfully!</strong>";
            //$this->_view->list = $this->_model->index();
        $this->_view->render('administration/index', 'basic');
    }
    
    public function Delete() {
        $this->_view->content = $this->_model->Delete();
        $this->_view->list = $this->_model->index();
//        if (is_bool($result)) {
//            $this->_view->msg = "<strong>1 New Admin Added Succesfully!</strong>";
            //$this->_view->list = $this->_model->index();
        $this->_view->render('administration/index', 'basic');
    }
    
    
    public function User($id) {
        $this->_view->content = $this->_model->user($id);
        $this->_view->list = $this->_model->index();
        //$this->_view->content = $result;
        $this->_view->render('administration/index', 'basic');
    }
    
//    public function Edit($id) {
//        $this->_view->content = $this->_model->user($id);
//        $this->_view->list = $this->_model->index();
//        //$this->_view->content = $result;
//        $this->_view->render('administration/index', 'basic');
//    }    

}
    

