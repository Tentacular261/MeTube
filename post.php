<?php
session_save_path("session");
session_start();

include_once "database.php";

if (!isset($_GET['id'])) header("Location: index.php"); // go back to the index if no post is specified

echo "<html>\n";

$db = new DatabaseConnection();
$getting = $db->conn->real_escape_string($_GET['id']);
$result = $db->custom_sql("SELECT file,title,description,privacy,uploaded_by FROM media WHERE id = \"".$getting."\"");

if ($result->num_rows != 1) {
    ?>
    <head><title>ITS GONE!</title></head>
    <link rel="stylesheet" href="css/general.css">
    <link rel="stylesheet" href="css/navbar.css">
    <body>
        <?php include_once "navbar.php"; ?>

        <p>The post you are looking for doesn't exist.</p>
        <p><a href="index.php">Return to index</a></p>
    </body>
    <?php
} else {
    $row = $result->fetch_assoc();

    $file        = $row['file'];
    $title       = $row['title'];
    $description = $row['description'];
    $privacy     = $row['privacy'];
    $uploader    = $row['uploaded_by'];

    if ($privacy != "public" &&
            (!isset($_SESSION['username']) ||
            $_SESSION['username'] != $uploader)) {
        ?>
        <head>
            <title>Nope</title>
            <link rel="stylesheet" href="css/general.css">
            <link rel="stylesheet" href="css/navbar.css">
        </head>
        <body>
            <?php include_once "navbar.php"; ?>

            <p>You don't have access to this post.</p>
            <p><a href="index.php">Return to index</a></p>
        </body>
        <?php
    } else { ?>
    <head>
        <title><?php echo $title; ?></title>
        <link rel="stylesheet" href="css/general.css">
        <link rel="stylesheet" href="css/navbar.css">
        <link rel="stylesheet" href="css/viewing.css">
    </head>
    <body>
        <?php include_once "navbar.php"; ?>

        <div class="main_image">
            <img class="main" src="media/<?php echo $file; ?>">
            <div class="title"><?php echo $title; ?></div><br>
            <div class="user"><?php echo $uploader; ?></div><br>
            <div class="description"><?php echo $description; ?></div>
        </div>
        <p><a href="index.php">Return to index</a></p>
    </body>
    <?php
    }
}
?>
</html>
