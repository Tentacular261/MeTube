<?php
if (isset($_POST['comment']) && isset($_SESSION['username'])) {
    $mid = $db->conn->real_escape_string($_GET['id']);
    $uid = $db->conn->real_escape_string($_SESSION['username']);
    $tms = time();
    $com = $db->conn->real_escape_string($_POST['words']);

    $query = "INSERT INTO comments VALUES ('$mid','$tms','$uid','$com');";
    $db->custom_sql($query);

    header("Location: post.php?id=".$_GET['id']);
}
?>