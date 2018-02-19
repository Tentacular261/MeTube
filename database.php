<?php
class DatabaseConnection {
    private $host = "mysql1.cs.clemson.edu"; // The hostname of the database server
    private $user = "MTbDtbs_3b07";          // The username to access the database
    private $pass = "X#2s6nF7";              // The password to access the database
    private $dbnm = "MeTubeDatabase_99yq";   // The name of the Database

    private $conn;

    function __construct() {
        $this->conn = new mysqli($this->host, $this->user, $this->pass, $this->dbnm);
        if ($this->conn->connect_error)
            die("Connection to Database Failed: " . $this->conn->connect_error);
    }

    function __destruct() {
        $this->conn->close();
    }

    function custom_sql($query) {
        return $this->conn->query($query);
    }

}
?>
