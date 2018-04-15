<?php
session_save_path("../session");
session_start(); // get the session info

include_once "../database.php";

if(isset($_POST['DELETE']) && !empty($_SESSION['username'])) { // process POST data if it exists
    if($_POST['media_id'] == "") { // check if all feilds filled out
        $_SESSION['error_message'] = "One or more fields are missing.";
    } else {
        $db = new DatabaseConnection();
        $id = $db->conn->real_escape_string($_POST['media_id']);
        $user = $db->conn->real_escape_string($_SESSION['username']);

        $temp = $db->custom_sql("SELECT * FROM media WHERE id='$id'");
        if ($temp->num_rows != 1) {
            $_SESSION['error_message'] = "Post doesn't exist";
            header('Location: '.$_POST['return']);
            exit;
        } else {
            $upuser = $temp->fetch_array()['uploaded_by'];
            if ($upuser != $_SESSION['username']) {
                $_SESSION['error_message'] = "You can only delete your own posts";
                header('Location: '.$_POST['return']);
                exit;
            } else {
                $db->custom_sql("DELETE FROM media WHERE id='$id'");
                header('Location: ../index.php');
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