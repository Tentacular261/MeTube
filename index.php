<!DOCTYPE html>
<html>
    <head>
        <title>Hello World</title>
        <link rel="stylesheet" href="css/browsing.css"
    </head>
    <body>
        <?php echo '<p>Hello World</p>'; ?>
        <p>sign in</p>
        <p><a href="user/register.php">Register</a></p>
        <div class="items">
            <?php for ($i=0;$i<25;$i++) { ?>
                <img class="item" src="media/heh.png" alt="heh" height="64" width="64">
            <?php } ?>
        </div>
    </body>
</html>
