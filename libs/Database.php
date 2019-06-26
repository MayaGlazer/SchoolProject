<?php

class Database extends PDO {

    function __construct() {
        $server = config::$server;
        $database = config::$database;
        
        parent::__construct("mysql:host=$server;dbname=$database",
                            config::$user, 
                            config::$password
                            );
        $this->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        
    }
    
}
