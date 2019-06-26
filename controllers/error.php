<?php

class APPError extends Controller {
    private $_msg;
   
    function __construct($msg) {
        parent::__construct();        
        $this->_msg = $msg;
    }
    
    public function index() {
        $this->_view->error_msg = $this->_msg;
        $this->_view->render('error/index');
    }
    public function calcError($msg) {
        $this->_view->error_msg = $this->_msg;
        $this->_view->render('error/calcerror');
    }
    
    
}
