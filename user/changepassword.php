<?php
session_save_path("../session");
session_start(); // get session data

include_once "../database.php";

if(isset($_POST['submit'])) {
    if($_POST['password'] == "" || $_POST['new_password'] == "" || $_POST['new_password_again'] == "") { // All FIELDS must be filled out
        $_SESSION['error_message'] = "One or more fields are missing.";
    } else {
        $db = new DatabaseConnection();
        $un = $db->conn->real_escape_string($_SESSION['username']);
        $result = $db->custom_sql("SELECT * FROM users WHERE username='$un'"); // ask database to find the user info

        if (!$result) {
            $_SESSION['error_message'] = "Could not query the database";
        } else if ($result->num_rows != 1) { // There should be one result TODO: add a notification of anything other than 0 or 1. Anything else means we fucked up somewhere and have a duplicate user (username being primary key should prevent this though)
            $_SESSION['error_message'] = "User ".$_SESSION['username']." not found.";
        } else { // The user exists check password now
            $row = $result->fetch_assoc();
            if(strcmp($row['password'],$_POST['password']))
                $_SESSION['error_message'] = "Incorrect password.";
            else if(strcmp($_POST['new_password'],$_POST['new_password_again']))
                $_SESSION['error_message'] = "New passwords do not match.";
            else {
                $un = $db->conn->real_escape_string($_SESSION['username']);
                $pw = $db->conn->real_escape_string($_POST['new_password']);
                $result = $db->custom_sql("UPDATE users SET password='$pw' WHERE username='$un'");

                if (!$result)
                    $_SESSION['error_message'] = "Password update failed.<br>Could not query the database.";

                header('Location: '.$_POST['return']); // go back to the previous page
                exit;
            }
        }
    }
}
if (isset($_POST['return'])) {
    header('Location: '.$_POST['return']);
    exit;
} else {
    header('Location: ../index.php');
    exit;
}
?>
