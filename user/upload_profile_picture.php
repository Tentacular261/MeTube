<?php
    session_save_path("../session");
    session_start();

    include_once "../database.php";

    /*
     * $db = DatabaseConnection
     * $escaped_username = a username that has been passed to
     * db->conn->real_escape_string()
     */
    function del_old_profile_pic($db, $escaped_username) {
        $query_str = "SELECT picture FROM users WHERE username=\"" . $escaped_username . "\"";
        $result = $db->custom_sql($query_str);
        $row = $result->fetch_row();
        $file_location = "profile_pictures/" . $row[0];

        // Make sure not to delete the default picture
        $are_files_same = !strcmp($file_location, "profile_pictures/default.jpeg");
        if(!$are_files_same)
            system("rm -f " . $file_location);
    }

    // redirect the user if they are not logged in
    if (!isset($_SESSION['username']))
        header('Location: login.php');

    $target_file = $_FILES["new_profile_pic"]["tmp_name"];

    if (isset($_POST['upload'])) {
        if(!file_exists("profile_pictures/"))
            mkdir("profile_pictures/", 0755);
        chmod("profile_pictures/", 0755);

        $ext = pathinfo($_FILES["new_profile_pic"]["name"], PATHINFO_EXTENSION);

        $accepted_file_formats = array("jpg", "jpeg", "png");
        $is_file_accepted = false;

        // Check if the uploaded picture is of an acceptable format
        for ($index = 0; $index <= count($accepted_file_formats); $index++)
            if (strcmp($ext, strtolower($accepted_file_formats[$index])))
                $is_file_accepted = true;

        if ($_FILES["new_profile_pic"]["error"] > 0) { // check if anything was wrong with the file upload
			switch ($_FILES["file"]["error"]){
			case 1:
				$ErrorMessage = "UPLOAD_ERR_INI_SIZE";
			case 2:
				$ErrorMessage = "UPLOAD_ERR_FORM_SIZE";
			case 3:
				$ErrorMessage = "UPLOAD_ERR_PARTIAL";
			case 4:
				$ErrorMessage = "UPLOAD_ERR_NO_FILE";
			}
        } else if (is_uploaded_file($target_file)) {
            $image_file_type = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
            $target_dir = "profile_pictures/";
            $hash = md5_file($target_file);
            $utime = time();
            $file_name = $utime . $hash . "." . $ext;
            $full_file_name = $target_dir . $file_name;

            if (move_uploaded_file($target_file, $full_file_name)) {
                chmod($full_file_name, 0755);

                // Use Zack's resize code to make picture the right size
                list($w,$h) = getimagesize($full_file_name);
                $scale = max($w,$h);
                $w = round(($w/$scale)*256);
                $h = round(($h/$scale)*256);
                system("/usr/bin/convert $full_file_name -resize $w"."x"."$h $full_file_name 2>&1");

                $db = new DatabaseConnection();

                $username = $db->conn->real_escape_string($_SESSION["username"]);

                del_old_profile_pic($db, $username);
                $query = "UPDATE users SET picture=\"" . $file_name . "\" WHERE username=\"" . $username . "\"";
                $db->custom_sql($query);

                // return to user page
                header('Location: '.$_POST['return']);
            } else
                $ErrorMessage = "Problem uploading file";

        } else {
            $ErrorMessage = "Uploading the file failed.";
        }
    }

    include_once "../navbar.php";
?>

<html>

    <head>
        <title>| Profile Picture |</title>
        <link rel="stylesheet" href="../css/general.css">
        <link rel="stylesheet" href="../css/upload.css">
    </head>

    <body>
        <form action="upload_profile_picture.php" method="post" enctype="multipart/form-data">
            <input type="hidden" name="MAX_FILE_SIZE" value="31457280" />
            <h4>Upload new profile picture:</h4>
            <input type="file" name="new_profile_pic" id="new_profile_pic" required/>
            <br /><br />
            <button type="submit" name="upload">Change Picture</button>
        </form>
    </body>
</html>
