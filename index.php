<!DOCTYPE html>
<?php
session_start();

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
}
?>
<html>
    <head>
        <title>Hello World</title>
        <link rel="stylesheet" href="css/browsing.css"
    </head>
    <body>
        <?php
        echo '<p>Hello World</p>';

        if (isset($_SESSION['username'])) { // if logged in
        ?>

        <p>Welcome <?php echo $_SESSION['username'] ?></p>
        <form method="post" action="index.php">
            <input name="logout" type="submit" value="Logout">
        </form>

        <?php
        } else {
        ?>

        <form method="post" action="user/login.php">
            <input type="submit" value="Login"> <!--Empty post to login-->
        </form>
        <p><a href="user/register.php">Register</a></p>

        <?php
        }
        ?>

        <div class="items">
            <?php for ($i=0;$i<25;$i++) { ?>
                <img class="item" src="media/heh.png" alt="heh" height="64" width="64">
            <?php } ?>
        </div>
    </body>
</html>
