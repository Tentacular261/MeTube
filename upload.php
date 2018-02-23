<?php
session_save_path("/home/wzsulli/public_html/metube/session/");
session_start();

include_once "database.php";

if (!isset($_SESSION['username'])) // If the user is not logged in, redirect to the login page
    header('Location: user/login.php');

if (isset($_POST['upload'])) {
    if(!file_exists('media/')) // create media folder if it doesn't exist
        mkdir('media/',0757);
    chmod('media/',0757); // make sure the media folder has RW access to the public

    if ($_FILES["file"]["error"] > 0) { // check if anything was wrong with the file upload
        switch ($result){
    	case 1:
    		$ErrorMessage = "UPLOAD_ERR_INI_SIZE";
    	case 2:
    		$ErrorMessage = "UPLOAD_ERR_FORM_SIZE";
    	case 3:
    		$ErrorMessage = "UPLOAD_ERR_PARTIAL";
    	case 4:
    		$ErrorMessage = "UPLOAD_ERR_NO_FILE";
        }
    } else if (is_uploaded_file($_FILES["file"]["tmp_name"])) { // make sure this is the file that got uploaded
        $hash = md5_file($_FILES["file"]["tmp_name"]);
        $uppath = "media/".$hash.".".pathinfo($_FILES["file"]["name"], PATHINFO_EXTENSION); // define the file's name

        if (file_exists($uppath)) { // don't move the file if the file already exists
            $ErrorMessage = "The file has been uploaded before.";
        } else {
            if (!move_uploaded_file($_FILES["file"]["tmp_name"],$uppath)) {
                $ErrorMessage = "Failed to move the file into the media directory of the server.";
            } else {
                $db = new DatabaseConnection();

                // TODO: Make a record in the database for the post

                header("Location: index.php"); // TODO: change this to go to the media's page
            }
        }
    } else {
        $ErrorMessage = "Uploading the file failed.";
    }
}
?>

<form method="post" action="upload.php" enctype="multipart/form-data" >

    <p style="margin:0; padding:0">
        <input type="hidden" name="MAX_FILE_SIZE" value="104857600" />
        Uplaod Media: <label style="color:#663399"><em> (Each file limit 100MiB)</em></label><br/>
        <input  name="file" type="file" size="50" />
        <input value="Upload" name="upload" type="submit" />
    </p>
</form>
<p>
    <?php if (isset($ErrorMessage)) echo $ErrorMessage; // tell the user of any errors from the last attempted upload ?>
</p>
