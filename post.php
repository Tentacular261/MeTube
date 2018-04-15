<?php
session_save_path("session");
session_start();

include_once "database.php";

if (!isset($_GET['id'])) header("Location: index.php"); // go back to the index if no post is specified

$db = new DatabaseConnection();
$getting = $db->conn->real_escape_string($_GET['id']);
$user = (empty($_SESSION['username'])) ? "" : $db->conn->real_escape_string($_SESSION['username']);
$result = $db->custom_sql("SELECT id,file,title,type,category,description,privacy,uploaded_by,friend FROM media LEFT JOIN (SELECT * FROM friends WHERE friend='$user') AS fre ON media.uploaded_by=fre.user WHERE id = \"".$getting."\"");

if ($result->num_rows != 1) {
    include_once 'navbar.php';
    include 'post/nopost.php';
} else {
    $row = $result->fetch_assoc();
    
    $id          = $row['id'];
    $type        = $row['type'];
    $category    = $row['category'];
    $file        = $row['file'];
    $title       = $row['title'];
    $description = $row['description'];
    $privacy     = $row['privacy'];
    $uploader    = $row['uploaded_by'];
    $friend      = $row['friend'];
    
    include "post/comment.php";

    include_once 'navbar.php';

    if ($privacy != "public" &&
            (!isset($_SESSION['username']) ||
            $_SESSION['username'] != $uploader) &&
            (!isset($_SESSION['username']) ||
            $_SESSION['username'] != $friend)) {
        include 'post/noaccess.php';
    } else {
        include 'post/normal.php';
    }
}
?>
