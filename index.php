<!DOCTYPE html>

<?php
// these two lines are needed at the start of each page
session_save_path("session");
session_start();

include_once "database.php";
?>

<html>
    <head>
        <title>|Team14 MeTube|</title>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="css/general.css">
        <link rel="stylesheet" href="css/navbar.css">
        <link rel="stylesheet" href="css/browsing.css">
    </head>
    <body>
        <?php
        include_once "navbar.php";
        ?>

        <!-- Header Content -->
        <div class="header">
            <h1>MeTube</h1>
            <p><i>You're going to be offended.</i></p>
        </div>

        <div class="flex-container">
            <!-- Search Bar -->
            <div class="search-col">
                <div class="main-search">
                    <form action="/action_page.php">   <!-- CHANGE TO GIVE RESULTS -->
                    <input type="text" placeholder="Search" name="search">
                    <button type="submit"><i class="fa fa-search"></i></button>
                    </form>
                </div>

                <!-- TODO: Additional Search Stuff -->

            </div>

            <!-- Media Container -->
            <div class="media-col">
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
                <!-- TODO: Add the paging controls -->
                <p>&#x25C0; PG &#x25B6;</p>
            </div>
        </div>

        <!-- Footer Content -->
        <div class="footer">
            <h6><b>CPSC 4620-001 Spring 2018</b><br><i>Micah Johnson, Zackary Sullivan,  Sadie Sweetman</i></h6>
        </div>

    </body>
</html>
