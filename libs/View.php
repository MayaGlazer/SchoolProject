<?php

class View {

    function __construct() {
        
    }
    
    public function render($name, $container) {
        require_once "views/header.php" ;
        require_once "views/$name.php" ;
        //if (is_string($container)) {
        require_once "views/container/$container.php";
        //}
        require_once "views/footer.php" ;
    }

}
