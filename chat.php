<!DOCTYPE html>
<html>
<?php
// these two lines are needed at the start of each page
session_save_path("session");
session_start();

include_once "database.php";

$db = new DatabaseConnection();

if (empty($_GET['user1']) || empty($_GET['user2'])) { // stop if there is no way to see things
    echo "There are missing arguments for viewing this page.";
    exit;
}

if (isset($_POST['message'])) {
    $user1 = $db->conn->real_escape_string($_GET['user1']);
    $user2 = $db->conn->real_escape_string($_GET['user2']);
    $thisuser = ($_GET['user1'] == $_SESSION['username']) ? $user1 : $user2;
    if ($user2 > $user1) list($user1,$user2) = array($user2,$user1); // swap the variables to be in the correct order

    $message = $db->conn->real_escape_string($_POST['words']);

    $tms = time();

    $db->custom_sql("INSERT INTO messages VALUES ('$user1','$user2','$thisuser','$tms','$message')");
}
?>
<head>
    <link rel="stylesheet" href="css/chat.css">
    <link rel="stylesheet" href="css/general.css">
    <link rel="stylesheet" href="css/navbar.css">
</head>
<body style="padding: 0px">
<div class="message_section">
    <?php
    $user1 = $db->conn->real_escape_string($_GET['user1']);
    $user2 = $db->conn->real_escape_string($_GET['user2']);
    $otheruser = ($_GET['user1'] == $_SESSION['username']) ? $user2 : $user1;
    if ($user2 > $user1) list($user1,$user2) = array($user2,$user1); // swap the variables to be in the correct order
    ?>
    <form class="message_form" method="post" action="chat.php?<?php echo $_SERVER['QUERY_STRING']; ?>">
        <textarea class="message_box" name="words" placeholder="Write a message" required></textarea>
        <button class="message_button" type="submit" name="message">Message</button>
    </form>
    <?php
    $result = $db->custom_sql("SELECT sender,timestamp,message FROM messages WHERE user1='$user1' AND user2='$user2' ORDER BY timestamp DESC");

    while ($row = $result->fetch_array()) { ?>
        <div class="message">
            <?php echo $row['sender']; // TODO: add timestamp and user img once added ?>
            <br>
            <pre><?php echo $row['message']; ?></pre>
        </div>
    <?php } ?>
</div>
</body>
</html>