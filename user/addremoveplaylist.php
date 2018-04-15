<?php
session_save_path("../session");
session_start(); // get the session info

include_once "../database.php";

if((isset($_POST['addlist']) || isset($_POST['deletelist'])) && !empty($_SESSION['username'])) { // process POST data if it exists
    if($_POST['listname'] == "") { // check if all feilds filled out
        $_SESSION['error_message'] = "One or more fields are missing.";
    } else {
        $db = new DatabaseConnection();
        $user = $db->conn->real_escape_string($_SESSION['username']);
        $list = $db->conn->real_escape_string($_POST['listname']);
        
        if (isset($_POST['addlist'])) {
            $result = $db->custom_sql("SELECT * FROM playlists WHERE user='$user' AND list='$list'");
            if ($result->num_rows != 0) {
                $_SESSION['error_message'] = "There is already a playlist called ".$_POST['listname'];
            } else {
                $db->custom_sql("INSERT INTO playlists VALUES ('$user','$list','0',NULL)");
                header('Location: '.$_POST['return']); // go back to the previous page
                exit;
            }
        } else {
            if ($_POST['listname'] == "favorites") {
                $_SESSION['error_message'] = "The favorites list cannot be deleted.";
            } else {
                $db->custom_sql("DELETE FROM playlists WHERE user='$user' AND list='$list'");
                header('Location: '.$_POST['return']); // go back to the previous page
                exit;
            }
        }
    }
} else {
    $_SESSION['error_message'] = "Request was malformed";
}
if (isset($_POST['return'])) {
    header('Location: '.$_POST['return']);
    exit;
} else {
    header('Location: ../index.php');
    exit;
}
?>