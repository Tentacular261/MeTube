<?php
session_save_path("../session");
session_start(); // get session data

include_once "../database.php";

if(isset($_POST['submit'])) { // process POST data if it exists
    if($_POST['username'] == "" || $_POST['password'] == "") { // Both feilds must be filled out
        $_SESSION['error_message'] = "One or more fields are missing.";
    } else {
        $db = new DatabaseConnection();
        $un = $db->conn->real_escape_string($_POST['username']);
        $result = $db->custom_sql("SELECT * FROM users WHERE username='".$un."'"); // ask database to find the user info

        if (!$result) {
            die ("user_pass_check() failed. Could not query the database: <br />");
        } else if ($result->num_rows != 1) { // There should be one result TODO: add a notification of anything other than 0 or 1. Anything else means we fucked up somewhere and have a duplicate user (username being primary key should prevent this though)
            $_SESSION['error_message'] = "User ".$_POST['username']." not found.";
        } else { // The user exists check password now
            $row = $result->fetch_assoc();
            if(strcmp($row['password'],$_POST['password']) != 0)
            	$_SESSION['error_message'] = "Incorrect password.";
            else {
                $_SESSION['username']=$_POST['username']; //Set the $_SESSION['username'] (Log the user in)
                header('Location: '.$_POST['return']);
                exit;
            }
        }
    }
}
if (isset($_POST['return'])) {
    header('Location: '.$_POST['return']);
} else {
    header('Location: ../index.php');
}
?>