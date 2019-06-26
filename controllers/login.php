<?php

class Login extends Controller {

    function __construct() {
        parent::__construct();
        $this->loadModel(__CLASS__);
        $this->_view->msg = "";
        $this->_view->bufferform = "";
    }

    public function login() {
//        if ($ex) {
//            $this->_view->msg = $ex->getMessage();
//        }
        $this->_view->render('login/index', 'empty');
    }

    public function authenticate() {
       $result = $this->_model->authenticate();
       if (is_bool($result)) {
            header('location:' . config::URL . '/index/index');          
       } else if (is_string($result)) {
//            $this->logout();
            $this->_view->msg = $result;
            $this->_view->buffer = "TEXTTEXTTETXT";
            $this->_view->render('/login/index', 'empty');
        }
    }

    public function logout() {
        Session::remove('loggedIn');
        Session::remove('name');
        Session::remove('role');
        Session::remove('image');
        header('location:' . config::URL);
    }

}
