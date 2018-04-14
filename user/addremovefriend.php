<?php
session_save_path("../session");
session_start(); // get the session info

include_once "../database.php";

if((isset($_POST['addfriend']) || isset($_POST['deleteFriend'])) && !empty($_SESSION['username'])) { // process POST data if it exists
    if($_POST['username'] == "") { // check if all feilds filled out
        $_SESSION['error_message'] = "One or more fields are missing.";
    } else {
        $db = new DatabaseConnection();
        $un = $db->conn->real_escape_string($_POST['username']);
        $result = $db->custom_sql("SELECT * FROM users WHERE username='".$un."'"); // ask database of user exists

        if (!$result) {
            $_SESSION['error_message'] = "Could not query the database";
        } else if ($result->num_rows != 1) { // any results means username is unavailable
            $_SESSION['error_message'] = "User ".$_POST['username']." doesnt exist.";
        } else {
            $query = "";
            if (isset($_POST['addfriend']))
                $query = "INSERT INTO friends VALUES ('".$db->conn->real_escape_string($_SESSION['username'])."','$un')";
            else
                $query = "DELETE FROM friends WHERE user='".$db->conn->real_escape_string($_SESSION['username'])."' AND friend='$un'";
            $db->custom_sql($query);
            header('Location: '.$_POST['return']); // go back to the previous page
            exit;
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