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

<!-- SADIE NOT GONNA DELETE ZACK'S CODE
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
-->

<html>

	<head>
		<title>| Upload |</title>
		<meta name="viewport" content="width=device-width, initial-scale=1">
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
						<input type="file" name="file" size="50" />

						<input type="text" name="title" placeholder="Media Title" />
						<input type="text" name="description" placeholder="Description" />
						<!-- TODO: NEW KEYWORDS -->
						<input type="text" name="keywords" placeholder="Keywords" />

						<!-- TODO: NEW CATEGORY -->
						<select id="category" name="category" />
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
						<!-- TODO: NEW LISTS -->
						<select id="lists" name="lists" />
							<option value="" disabled selected>Add to List</option>
							<option value="createList">Create New List... </options>
							<option value="favorites">Favorites </option>
							<option value="haha">Haha (Example) </option>
							<option value="rip">RIP (Example) </option> </select>

						<input type="radio" name="privacy" value="public" checked>Public               
						<input type="radio" name="privacy" value="private">Private
						<input type="radio" name="privacy" value="contacts">Friends <br/>

						<!-- TODO: NEW RATED -->
						<input type="checkbox" name="rated" value="rated" checked>OK to Rate <br/>

						<button type="submit" name="upload">Upload</button>
					</form>
				</div>

				<div class="displayMediaCol">
					<!-- Preview Image -->
					<input type="file" accept="image/*, audio/*, video/*" onchange="preview_image(event)">
					<img id="img"/>

				</div>
			</div>
		</div>

		<div class="userContent">
			<div class="userContentRow">
				<div class="userContentCol">
					<div class="main-search">
						<form action="/action_page.php">   <!-- TODO: CHANGE TO GIVE RESULTS -->
							<input type="text" placeholder="Search your media..." name="search">
							<select id="category" name="category" />
								<option value="" disabled selected>Filter by Category</option>
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
							<select id="lists" name="lists" />
								<option value="" disabled selected>Filter by List</option>
								<option value="favorites">Favorites </option>
								<option value="haha">Haha (Example) </option>
								<option value="rip">RIP (Example) </option> </select>

							<!-- TODO: SEARCH BY FILE TYPE -->
							File Type: 
							<input type="checkbox" name="file" value="image" checked>Images
							<input type="checkbox" name="file" value="video" checked>Video
							<input type="checkbox" name="file" value="audio" checked>Audio <br/>
							Rating:
							<input type="checkbox" name="rating" value="1">1
							<input type="checkbox" name="rating" value="2">2
							<input type="checkbox" name="rating" value="3">3 
							<input type="checkbox" name="rating" value="4">4 
							<input type="checkbox" name="rating" value="5">5 </br>
							Privacy:
							<input type="checkbox" name="privacy" value="public" checked>Public               
							<input type="checkbox" name="privacy" value="private" checked>Private
							<input type="checkbox" name="privacy" value="contacts" checked>Friends <br/>

							Uploaded: </br>
							Between<input type="date" name="date">
							And<input type="date" name="date">

							<div class="userContentRow">
								<div class="userContentCol">
									<!-- TODO: NEW ACTION FOR SEARCH-->
									<button type="submit" name="search">Search</button>
								</div>
								<div class="userContentCol">
									<!-- TODO: NEW ACTION FOR RESET -->
									<button type="submit" name="reset">Reset</button>
								</div>
							</div>
                  </form>
               </div>
				</div>

				<div class="userContentCol">
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

<p>
    <?php if (isset($ErrorMessage)) echo $ErrorMessage; // tell the user of any errors from the last attempted upload ?>
</p>
