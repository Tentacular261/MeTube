<?php
session_save_path("../session");
session_start(); // get the session info

include_once "../database.php";

if(isset($_POST['addtolist']) && !empty($_SESSION['username'])) { // process POST data if it exists
    if($_POST['listname'] == "" || $_POST['media_id'] == "") { // check if all feilds filled out
        $_SESSION['error_message'] = "One or more fields are missing.";
    } else {
        $db = new DatabaseConnection();
        $user = $db->conn->real_escape_string($_SESSION['username']);
        $list = $db->conn->real_escape_string($_POST['listname']);
        $meid = $db->conn->real_escape_string($_POST['media_id']);

        $temp = $db->custom_sql("SELECT COUNT(list) as cont FROM playlists WHERE user='$user' AND list='$list'");
        if ($temp->num_rows != 1) {
            $_SESSION['error_message'] = "no index available";
            header('Location: '.$_POST['return']);
            exit;
        } else {
            $indx = $temp->fetch_array()['cont'];
        }
        
        $db->custom_sql("INSERT INTO playlists VALUES ('$user','$list','$indx','$meid')");
        header('Location: '.$_POST['return']); // go back to the previous page
        exit;
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