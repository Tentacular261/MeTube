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