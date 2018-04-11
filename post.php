<?php
session_save_path("session");
session_start();

include_once "database.php";

if (!isset($_GET['id'])) header("Location: index.php"); // go back to the index if no post is specified

$db = new DatabaseConnection();
$getting = $db->conn->real_escape_string($_GET['id']);
$result = $db->custom_sql("SELECT file,title,type,description,privacy,uploaded_by FROM media WHERE id = \"".$getting."\"");

include_once 'navbar.php';

if ($result->num_rows != 1) {
    include 'post/nopost.php';
} else {
    $row = $result->fetch_assoc();

    $type        = $row['type'];
    $file        = $row['file'];
    $title       = $row['title'];
    $description = $row['description'];
    $privacy     = $row['privacy'];
    $uploader    = $row['uploaded_by'];

    if ($privacy != "public" &&
            (!isset($_SESSION['username']) ||
            $_SESSION['username'] != $uploader)) {
        include 'post/noaccess.php';
    } else {
        include 'post/normal.php';
    }
}
?>
