<?php

/**
 * Created by IntelliJ IDEA.
 * User: Sandun
 * Date: 8/11/16
 * Time: 2:00 PM
 */
class DatabaseConnection {
    private static $_connection;
    private static $_instance; //The single instance
    private static $_host = "localhost:3306";
    private static $_username = "root";
    private static $_password = "5876114027";

    public static function getInstance($_database) {
        self::$_connection = new mysqli(self::$_host,self::$_username,
            self::$_password, $_database);
        // Error handling
        if(mysqli_connect_error()) {
            trigger_error("Failed to conencto to MySQL: " . mysql_connect_error(),
                E_USER_ERROR);
        }
        if(!self::$_instance) { // If no instance then make one
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    // Constructor
    private function __construct() {

    }
    // Magic method clone is empty to prevent duplication of connection
    private function __clone() { }
    // Get mysqli connection
    public function getConnection() {
        return self::$_connection;
    }
}
?>