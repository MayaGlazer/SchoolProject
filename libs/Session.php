<?php
//https://www.youtube.com/watch?v=eD_KAZXfAYc&list=PL75B9D91CD69ED950&index=39
//https://www.youtube.com/watch?v=fdpnT2iPYS0 - cache
class Session {
    private static $_sessionStarted = false;
    public static function init() {
        if (self::$_sessionStarted == false) {
            session_start();
            self::$_sessionStarted = true;
        }
    }
    public static function set($key, $value) {
        $_SESSION[$key] = $value;
    }
    public static function remove($key) {
        unset($_SESSION[$key]);
    }
    public static function get($key) {
        $retValue = isset($_SESSION[$key]) ? $_SESSION[$key] : null;
        return $retValue;
    }
    public static function destroy() {
        if (self::$_sessionStarted == true) {
            session_unset(); // clear all data of all session variable
            session_destroy(); //Remove all session.
        }
    }
    public static function show() {
        print_r($_SESSION);
    }
    public static function display() {
        echo '<pre>';
        print_r($_SESSION);
        echo '</pre>';
    }
}
