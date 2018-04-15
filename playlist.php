<!DOCTYPE html>
<html>
<?php
// these two lines are needed at the start of each page
session_save_path("session");
session_start();

include_once "database.php";

$db = new DatabaseConnection();

if (empty($_GET['list'])) { // stop if there is no way to see things
    echo "There are missing arguments for viewing this page.";
    exit;
}
?>
<head>
    <link rel="stylesheet" href="css/playlist.css">
    <link rel="stylesheet" href="css/general.css">
    <link rel="stylesheet" href="css/navbar.css">
</head>
<body style="padding: 0px">
<div class="message_section">
    <?php
    $user = $db->conn->real_escape_string($_SESSION['username']);
    $list = $db->conn->real_escape_string($_GET['list']);
    
    $result = $db->custom_sql("SELECT id,file,type,title,uploaded_by FROM ((SELECT media_id FROM playlists WHERE user='$user' AND list='$list' AND l_index>0 ORDER BY l_index) as list) JOIN media ON list.media_id=media.id");

    while ($row = $result->fetch_array()) {
        $title = $row['title'];
        $thumb = ($row['type'] === "image")
                    ? $row['file']
                    : (($row['type'] === "video")
                        ? preg_replace('/.[^.]*$/', '', $row['file']).".png"
                        : "../../assets/AUDIOTHING.png");
        ?>
        <a href="post.php?id=<?php echo $row['id']; ?>" target="_parent" class="message_flexwrapper">
            <div class="message_img">
                <img src="media/thumb/<?php echo $thumb; ?>" alt="<?php echo $title; ?>"/>
            </div>
            <div class="message_info">
                <?php echo $row['title']; // TODO: add timestamp and user img once added ?>
                <br>
                <pre><?php echo $row['uploaded_by']; ?></pre>
            </div>
        </a>
    <?php } ?>
</div>
</body>
</html>