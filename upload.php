<?php
session_save_path("session");
session_start();

include_once "database.php";

if (!isset($_SESSION['username'])) // If the user is not logged in, redirect to the login page
    header('Location: user/login.php');

if (isset($_POST['upload'])) {
    if(!file_exists('media/')) // create media folder if it doesn't exist
        mkdir('media/',0757);
    chmod('media/',0757); // make sure the media folder has RW access to the public

    if ($_POST['title'] == "") {
        $ErrorMessage = "Title Field Required";
    } else if ($_FILES["file"]["error"] > 0) { // check if anything was wrong with the file upload
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
    } else if (is_uploaded_file($_FILES["file"]["tmp_name"])) { // make sure this is the file that got uploaded
        $hash = md5_file($_FILES["file"]["tmp_name"]);
        $filename = $hash.".".pathinfo($_FILES["file"]["name"], PATHINFO_EXTENSION); // define the file's name
        $uppath = "media/".$filename; // define file path

        if (file_exists($uppath) ||
                move_uploaded_file($_FILES["file"]["tmp_name"],$uppath)) {
            chmod($uppath,0757);
            $db = new DatabaseConnection();

            $utime = time();
            $title = $db->conn->real_escape_string($_POST['title']);
            $description = $db->conn->real_escape_string($_POST['description']);
            $un = $db->conn->real_escape_string($_SESSION['username']);

            $query = "INSERT INTO media (id,date,file,uploaded_by,privacy,title,description)"
                    ."VALUES ('".$utime.$filename."','".$utime."','".$filename."','".$un
                    ."','".$_POST['privacy']."','".$title."','".$description."')";

            $db->custom_sql($query);

            header("Location: index.php"); // TODO: change this to go to the media's page
        } else {
            $ErrorMessage = "Failed to move the file into the media directory of the server.";
        }
    } else {
        $ErrorMessage = "Uploading the file failed.";
    }
}

include_once "navbar.php";
?>

<form method="post" action="upload.php" enctype="multipart/form-data" >

    <p style="margin:0; padding:0">
        <input type="hidden" name="MAX_FILE_SIZE" value="104857600" />
        Uplaod Media: <label style="color:#663399"><em> (Each file limit 100MiB)</em></label><br/>
        <input  name="file" type="file" size="50" />
        <table width="100%">
    		<tr>
    			<td  width="20%">Post Title:</td>
    			<td width="80%"><input type="text" name="title"><br /></td>
    		</tr>
    		<tr>
    			<td  width="20%">Post Description:</td>
    			<td width="80%"><textarea name="description" rows="10" cols="50"></textarea><br /></td>
    		</tr>
            <tr>
                <td width="20%">Visibility:</td>
                <td width="80%"><select name="privacy">
                    <option value="public">Public</option>
                    <option value="private">Private</option>
                    <option value="contacts">Contacts</option>
                </select></td>
            </tr>
        </table>
        <input value="Upload" name="upload" type="submit" />
    </p>
</form>
<p>
    <?php if (isset($ErrorMessage)) echo $ErrorMessage; // tell the user of any errors from the last attempted upload ?>
</p>
