<?php
session_save_path("/home/wzsulli/public_html/metube/session/");
session_start(); // get session data

include_once "../database.php";

if(isset($_POST['submit'])) { // process POST data if it exists
    if($_POST['username'] == "" || $_POST['password'] == "") { // Both feilds must be filled out
        $login_error = "One or more fields are missing.";
    } else {
        $db = new DatabaseConnection();
        $un = $db->conn->real_escape_string($_POST['username']);
        $result = $db->custom_sql("SELECT * FROM users WHERE username='".$un."'"); // ask database to find the user info

        if (!$result) {
            die ("user_pass_check() failed. Could not query the database: <br />");
        } else if ($result->num_rows != 1) { // There should be one result TODO: add a notification of anything other than 0 or 1. Anything else means we fucked up somewhere and have a duplicate user (username being primary key should prevent this though)
            $login_error = "User ".$_POST['username']." not found.";
        } else { // The user exists check password now
            $row = $result->fetch_assoc();
            if(!strcmp($row['password'],$password))
            	$login_error = "Incorrect password.";
            else {
                $_SESSION['username']=$_POST['username']; //Set the $_SESSION['username'] (Log the user in)
                // TODO: load the last page the user was on
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