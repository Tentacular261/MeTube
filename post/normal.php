<head>
    <title><?php echo $title; ?></title>
    <link rel="stylesheet" href="css/viewing.css">
</head>
<body>
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
        <div class="title"><?php echo $title; ?></div><br>
        <div class="user"><?php echo $uploader; ?></div><br>
        <div class="description"><?php echo $description; ?></div>
        <div class="download"><a href="media/<?php echo $file; ?>" download="<?php echo $title; ?>">Download</a></div>
    </div>
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
</body>