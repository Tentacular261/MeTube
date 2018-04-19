<?php
	session_save_path("session");
	session_start();

	include_once "database.php";

	// function to handle the creation of the thumbnails
	function process_upload($preexisting,$extn,$uppath,&$thumbpath,&$filetype) {
		$ext = strtolower($extn);
		if ($ext == "png" || // make sure the file is the one of the types we allow
			$ext == "bmp" ||
			$ext == "jpg" ||
			$ext == "jpeg"||
			$ext == "gif" ||
			$ext == "mp4" ||
			$ext == "mp3"  )
		if ($preexisting || move_uploaded_file($_FILES["file"]["tmp_name"],$uppath)) {
			switch ($ext) {
				case "png"  :
				case "bmp"  :
				case "jpg"  :
				case "jpeg" :
				case "gif"  :
					// do the picture things
					$filetype = "image";
					if (!$preexisting) { // create the thumbnail if it doesn't exist
						list($w,$h) = getimagesize($uppath);
						$scale = max($w,$h);
						$w = round(($w/$scale)*128);
						$h = round(($h/$scale)*128);
						system("/usr/bin/convert $uppath -resize $w"."x"."$h $thumbpath 2>&1");
					}
					return true;
					break;

				case "mp4" :
					// do the video things
					$filetype = "video";
					if (!$preexisting) { // create the thumbnail if it doesn't exist
						$thumbpath = preg_replace('/.[^.]*$/', '', $thumbpath).".png";
						system("/usr/bin/ffmpeg -i $uppath -ss 00:00:01.00 -vframes 1 -f image2 $thumbpath");
						list($w,$h) = getimagesize($thumbpath);
						$scale = max($w,$h);
						$w = round(($w/$scale)*128);
						$h = round(($h/$scale)*128);
						system("/usr/bin/convert $thumbpath -resize $w"."x"."$h $thumbpath 2>&1");
					}
					return true;
					break;

				case "mp3" :
					// do the audio things
					$filetype = "audio";
					return true;
					break;

				default : return false;
			}
		}
		return false;
	}

	if (!isset($_SESSION['username'])) // If the user is not logged in, redirect to the login page
		header('Location: user/login.php');

	if (isset($_POST['upload'])) {
		if(!file_exists('media/')) // create media folder if it doesn't exist
			mkdir('media/',0755);
		chmod('media/',0755); // make sure the media folder has R access to the public

		if(!file_exists('media/thumb')) // create thumb folder if it doesn't exist
			mkdir('media/thumb',0755);
		chmod('media/thumb',0755); // make sure the thumb folder has R access to the public

		if ($_POST['title'] == "") {
			$_SESSION['error_message'] = "Title Field Required";
		} else if ($_FILES["file"]["error"] > 0) { // check if anything was wrong with the file upload
			switch ($_FILES["file"]["error"]){
			case 1:
				$_SESSION['error_message'] = "UPLOAD_ERR_INI_SIZE";
			case 2:
				$_SESSION['error_message'] = "UPLOAD_ERR_FORM_SIZE";
			case 3:
				$_SESSION['error_message'] = "UPLOAD_ERR_PARTIAL";
			case 4:
				$_SESSION['error_message'] = "UPLOAD_ERR_NO_FILE";
			}
		} else if (is_uploaded_file($_FILES["file"]["tmp_name"])) { // make sure this is the file that got uploaded
			$hash = md5_file($_FILES["file"]["tmp_name"]);
			$ext = pathinfo($_FILES["file"]["name"], PATHINFO_EXTENSION);
			$filename = $hash.".".$ext; // define the file's name
			$uppath = "media/".$filename; // define file path
			$thumbpath = "media/thumb/".$filename;
			$filetype;

			if (process_upload(file_exists($uppath),$ext,$uppath,$thumbpath,$filetype)) {
				chmod($uppath,0755);
				chmod($thumbpath,0755);
				$db = new DatabaseConnection();

				$utime = time();
				$id = substr($utime.$filename,0,32);
				$title = $db->conn->real_escape_string($_POST['title']);
				$description = $db->conn->real_escape_string($_POST['description']);
				$category = $db->conn->real_escape_string($_POST['category']);
				$un = $db->conn->real_escape_string($_SESSION['username']);

				$query = "INSERT INTO media (id,date,file,uploaded_by,category,type,privacy,title,description)"
				."VALUES ('".$id."','".$utime."','".$filename."','".$un."','".$category
				."','".$filetype."','".$_POST['privacy']."','".$title."','".$description."')";

				$db->custom_sql($query);

				if (!empty($_POST['keywords'])) {
					// this needs to ba a lambda function to avoid warnings
					$keywords = array_map(function ($temp) use ($db) { return $db->conn->real_escape_string($temp); },explode(" ",$_POST['keywords']));

					$query = "INSERT INTO keywords VALUES ";
					foreach ($keywords as $word) if (strlen($word) < 30)
						$query .= "('".$word."','".$id."'), ";
					$query = rtrim($query,", ");

					$db->custom_sql($query);
				}

				header("Location: index.php"); // TODO: change this to go to the media's page
				exit;
			} else {
				$_SESSION['error_message'] = "Failed to move the file into the media directory of the server.";
			}
		} else {
			$_SESSION['error_message'] = "Uploading the file failed.";
		}
		header("Location: upload.php");
		exit;
	}

	include_once "navbar.php";
?>

<?php
?>

<html>

	<head>
		<title>Upload</title>
		<link rel="shortcut icon" href="assets/metubeIcon.png" type="image/x-icon">
		<link rel="stylesheet" href="css/general.css">
		<link rel="stylesheet" href="css/upload.css">
	</head>

	<body>
		<div class="uploadContent">
			<div class="uploadRow">
				<div class="uploadCol">
					<form method="post" action="upload.php" enctype="multipart/form-data">
						<input type="hidden" name="MAX_FILE_SIZE" value="104857600" />
						UPLOAD MEDIA <label style="color: var(--ltgray)"><em> (Max Size 100MiB)</em></label> <br/>
						<input type="file" name="file" size="50" onchange="preview_image(event)" required/>

						<input type="text" name="title" placeholder="Media Title" required/>
						<textarea name="description" placeholder="Description" rows="20"/></textarea>

						<input type="text" name="keywords" placeholder="Keywords" />

						<select id="category" name="category" required/>
							<option value="" disabled selected>Category</option>
							<option value="entertainment">Entertainment</option>
							<option value="food">Food</option>
							<option value="funny">Funny</option>
							<option value="gaming">Gaming</option>
							<option value="news">News & Politics</option>
							<option value="people">People</option>
							<option value="pets">Pets & Animals</option>
							<option value="science">Science & Tech</option>
							<option value="sports">Sports</option>
							<option value="travel">Travel & Outdoors</option> </select>

						<input type="radio" name="privacy" value="public" checked>Public
						<input type="radio" name="privacy" value="private">Private
						<input type="radio" name="privacy" value="friend">Friends <br/>

						<button type="submit" name="upload">Upload</button>
					</form>
				</div>

				<div class="displayMediaCol">
					<img id="img"/>
				</div>
			</div>
		</div>

		<!-- Footer Content -->
      <div class="footer">
            <h6><b>CPSC 4620-001 Spring 2018</b><br><i>Micah Johnson, Zackary Sullivan,  Sadie Sweetman</i></h6>
      </div>

		<!-- Preview image script -->
		<script type='text/javascript'>
			function preview_image(event) {
				var reader = new FileReader();
				reader.onload = function() {
					var output = document.getElementById('img');
					output.src = reader.result;
				}
				reader.readAsDataURL(event.target.files[0]);
			}
		</script>
	</body>

</html>
