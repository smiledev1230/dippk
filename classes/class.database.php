<?php
/**
 * Standard Database Class
 *
 */
class Database {
    public $conn;
    private $db;
    private $environment;

    public function __construct($db, $environment) {
        $this->db = $db;
        $this->environment = $environment;
    }

    /**
     * Connect to db
     *
     */
     public function connect() {
         $this->conn = mysql_pconnect($this->db[$this->environment]['hostname'], $this->db[$this->environment]['username'], $this->db[$this->environment]['password']);
         if ( !$this->conn ) {
             exit('ERROR: Unable to connect to ' . $this->db[$this->environment]['database']);
         } else {
             $db_selected = mysql_select_db($this->db[$this->environment]['database'], $this->conn);
             if ( !$db_selected ) {
                 exit('ERROR: Unable to select ' . $this->db[$this->environment]['database']);
             }
         }
         return $this->conn;
     }
}