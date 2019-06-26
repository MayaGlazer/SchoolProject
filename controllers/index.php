<?php

class Index extends Controller {

    function __construct() {
        parent::__construct(); 
        $this->_view->bufferform = "";
    }
    
    public function index() {
        $this->_view->today = date('d/m/Y');
        $this->_view->render('index/index', 'empty');
    }

}
