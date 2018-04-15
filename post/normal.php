<head>
    <title><?php echo $title; ?></title>
    <link rel="shortcut icon" href="assets/metubeIcon.png" type="image/x-icon">
    <link rel="stylesheet" href="css/viewing.css">
    <link rel="stylesheet" href="css/general.css">
</head>

<body>
    <!-- Main Image Section -->
    <div class="main_image">
        <?php if ($type === "image") { ?>
            <img class="main" src="media/<?php echo $file; ?>">
        <?php } else if ($type === "video") { ?>
            <video class="main" controls>
                <source src="media/<?php echo $file; ?>">
                Your browser does not support this video element.
            </video>
        <?php } else { ?>
            <audio class="main" controls>
                <source src="media/<?php echo $file; ?>">
                Your browser does not support this audio element.
            </audio>
        <?php } ?>
        <div class="title"><?php echo $title; ?></div>
        <div class="user">Uploaded by: <?php echo $uploader; ?></div>
        <div class="description"><?php echo $description; ?></div>
        <div class="download">
            <a href="media/<?php echo $file; ?>" download="<?php echo $title; ?>">Download</a>
            <form method="post" action="user/addtoplaylist.php">
                <input type="hidden" name="return" value="<?php echo $returnto; ?>">
                <input type="hidden" name="media_id" value="<?php echo $id; ?>">
                <select name="listname">
                    <option value="" disabled selected>Add to List</option>
                    <?php
                    $lists = $db->custom_sql("SELECT DISTINCT list FROM playlists WHERE user='$user'");
                    while ($list = $lists->fetch_array()) {
                        $lname = $list['list'];
                        echo "<option value=\"$lname\">$lname</option>\n";
                    }
                    ?>
                </select>
                <button type="submit" name="addtolist">Add</button>
            </form>
        </div>
        <?php if ($user == $uploader) { ?>
        <div class="download">
        <a href="javascript:void(0);" onclick="document.getElementById('editpost').style.display='block'">Edit</a>
        <a href="javascript:void(0);" onclick="document.getElementById('deletepost').style.display='block'">Delete</a>
        </div>
        <?php } ?>
    </div>

    <!-- Comments Section -->
    <div class="comment_section">
        <?php if (isset($_SESSION['username'])) { ?>
            <form class="comment_form" method="post" action="post.php?id=<?php echo $_GET['id']; ?>">
                <textarea class="comment_box" name="words" placeholder="Write a comment" required></textarea>
                <button class="comment_button" type="submit" name="comment">Comment</button>
            </form>
        <?php
        }
        $result = $db->custom_sql("SELECT username,comment FROM comments WHERE media_id='$getting' ORDER BY timestamp ASC");

        while ($row = $result->fetch_array()) { ?>
            <div class="comment">
                <?php echo $row['username']; // TODO: add timestamp and user img once added ?>
                <br>
                <pre><?php echo $row['comment']; ?></pre>
            </div>
        <?php } ?>
    </div>

    <?php if ($user == $uploader) include "post/edit.php"; include "post/delete.php"; ?>

    <!-- Footer Content -->
    <div class="footer">
            <h6><b>CPSC 4620-001 Spring 2018</b><br><i>Micah Johnson, Zackary Sullivan,  Sadie Sweetman</i></h6>
    </div>
</body>