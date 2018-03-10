<!DOCTYPE html>
<?php
// TODO: Make a seperate header file
session_save_path("/home/wzsulli/public_html/metube/session/");
session_start();

include_once "database.php";

if (isset($_POST['logout'])) {
    // Unset all of the session variables.
    $_SESSION = array();

    // If it's desired to kill the session, also delete the session cookie.
    // Note: This will destroy the session, and not just the session data!
    if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000,
            $params["path"], $params["domain"],
            $params["secure"], $params["httponly"]
        );
    }
    // Finally, destroy the session.
    session_destroy();

    // Redirect to self to disable posting on refresh after logout
    header("Location: index.php");
}
?>
<html>
    <head>
        <title>|Team14 MeTube|</title>
        <link rel="stylesheet" href="css/browsing.css">
    </head>
    <body>
        <?php
        echo '<p>Hello World</p>';

        if (isset($_SESSION['username'])) { // if logged in
        ?>

        <p>Welcome <?php echo $_SESSION['username'] ?></p> <!--welcome specific user-->
        <form method="post" action="index.php">
            <input name="logout" type="submit" value="Logout"> <!--Post 'logout'-->
        </form>
        <p><a href="upload.php">Upload</a></p>

        <?php
        } else {
        ?>

        <form method="post" action="user/login.php">
            <input type="submit" value="Login"> <!--Empty post to login-->
        </form>
        <p><a href="user/register.php">Register</a></p> <!--link to registration page-->

        <?php
        }
        ?>

        <!--This section is format testing for the thumbnail display section being implemented later-->
        <div class="items">
            <?php
            $db = new DatabaseConnection();

            // TODO: Allow paging of the results
            $result = $db->custom_sql("SELECT id,file,title,privacy FROM media ORDER BY date DESC");

            for ($i=0;$i<25;$i++) {
                do {
                    $rows = $result->fetch_array();
                } while ($rows != NULL && $rows['privacy'] != "public");
                if ($rows==NULL) continue;
                $size = getimagesize("media/".$rows['file']);
                $imgsizedef = ($size[0] > $size[1]) // specify only the size of the largest dimension of the image
                                ? "width=\"64\""
                                : "height=\"64\"";
                echo "\n<a href=\"post.php?id="
                    .$rows['id']
                    ."\"><img class=\"item\" src=\"media/"
                    .$rows['file']
                    ."\" alt=\""
                    .$rows['title']
                    ."\" "
                    .$imgsizedef
                    ."></a>";
            }
            ?>
        </div>

    </body>
</html>
