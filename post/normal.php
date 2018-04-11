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
    </div>
    <p><a href="index.php">Return to index</a></p>
</body>