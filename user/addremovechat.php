<?php
session_save_path("../session");
session_start(); // get the session info

include_once "../database.php";

if((isset($_POST['addchat']) || isset($_POST['deletechat'])) && !empty($_SESSION['username'])) { // process POST data if it exists
    if($_POST['username'] == "") { // check if all feilds filled out
        $_SESSION['error_message'] = "One or more fields are missing.";
    } else if ($_POST['username'] == $_SESSION['username']) { // can't chat with yourself
        $_SESSION['error_message'] = "You can't chat with yourself.";
    } else {
        $db = new DatabaseConnection();
        $un = $db->conn->real_escape_string($_POST['username']);
        $result = $db->custom_sql("SELECT * FROM users WHERE username='".$un."'"); // ask database of user exists

        if (!$result) {
            $_SESSION['error_message'] = "Could not query the database";
        } else if ($result->num_rows != 1) { // any results means username is unavailable
            $_SESSION['error_message'] = "User ".$_POST['username']." doesnt exist.";
        } else {
            $un1 = $db->conn->real_escape_string($_SESSION['username']);
            $unMe = $un1;
            $un2 = $db->conn->real_escape_string($_POST['username']);
            $tms = time();
            if ($un2 > $un1) list($un1,$un2) = array($un2,$un1); // swap the variables to be in the correct order
            
            if (isset($_POST['addchat'])) {
                $result = $db->custom_sql("SELECT * FROM messages WHERE user1='$un1' AND user2='$un2'");
                if ($result->num_rows != 0) {
                    $_SESSION['error_message'] = "There is already a chat with ".$_POST['username'];
                } else {
                    $db->custom_sql("INSERT INTO messages VALUES ('$un1','$un2','$unMe','$tms','Chat between $un1 and $un2 has started')");
                    header('Location: '.$_POST['return']); // go back to the previous page
                    exit;
                }
            } else {
                $db->custom_sql("DELETE FROM messages WHERE user1='$un1' AND user2='$un2'");
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