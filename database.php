<?php
class DatabaseConnection {
    private $host = "mysql1.cs.clemson.edu"; // The hostname of the database server
    private $user = "MTbDtbs_3b07";          // The username to access the database
    private $pass = "X#2s6nF7";              // The password to access the database
    private $dbnm = "MeTubeDatabase_99yq";   // The name of the Database

    public $conn; // holds the mysqli connection for this object

    // TODO: Make the function calls in here so that the escaping doesn't have to be worried about elsewhere

    function __construct() {
        $this->conn = new mysqli($this->host, $this->user, $this->pass, $this->dbnm); // initialize the msquli connection
        if ($this->conn->connect_error)
            die("Connection to Database Failed: " . $this->conn->connect_error); //stop execution if connection to DB failed
    }

    function __destruct() {
        $this->conn->close(); // close the connection on destruction
    }

    function custom_sql($query) { // temporary function for sending raw sql commands to the database
        return $this->conn->query($query);
    }

}
?>
