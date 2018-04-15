<?php
session_save_path("../session");
session_start(); // get the session info

include_once "../database.php";

if(isset($_POST['clearlist']) && !empty($_SESSION['username'])) { // process POST data if it exists
    if($_POST['listname'] == "") { // check if all feilds filled out
        $_SESSION['error_message'] = "One or more fields are missing.";
    } else {
        $db = new DatabaseConnection();
        $user = $db->conn->real_escape_string($_SESSION['username']);
        $list = $db->conn->real_escape_string($_POST['listname']);
        
        $db->custom_sql("DELETE FROM playlists WHERE user='$user' AND list='$list' AND l_index>'0'");
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