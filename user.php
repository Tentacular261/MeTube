<?php
	session_save_path("session");
	session_start();

	include_once "database.php";

	if (!isset($_SESSION['username'])) // If the user is not logged in, redirect to the login page
		 header('Location: user/login.php');

	include_once "navbar.php";
?>

<html>
<head>
	<title>Profile</title>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="css/general.css">
	<link rel="stylesheet" href="css/user.css">
</head>

<body>
	<div class="userSocial">
		<!-- Tab links -->
		<div class="tab">
			<button class="tablinks" onclick="openSocial(event, 'manageProfile')" id="defaultOpen">Manage Profile</button>
			<button class="tablinks" onclick="openSocial(event, 'friends')">Friends</button>
			<button class="tablinks" onclick="openSocial(event, 'groups')">Groups</button>
			<button class="tablinks" onclick="openSocial(event, 'playlists')">Playlists</button>
			<button class="tablinks" onclick="openSocial(event, 'chat')">Chat</button>
		</div>

		<!-- Manage Profile Tab -->
		<div id="manageProfile" class="tabcontent">
			<?php
			    $db = new DatabaseConnection();
			    $username = $db->conn->real_escape_string($_SESSION["username"]);
			    $query_str = "SELECT picture FROM users WHERE username=\"" . $username . "\"";
			    $result = $db->custom_sql($query_str) or die(mysql_error());
			    $row = $result->fetch_row();
			    $file = "user/profile_pictures/" . $row[0];
			?>
			<img src=<?php echo "\"" . $file . "\"" ?> alt="Profile picture"/> <br/>
			<!-- TODO: Change action for change profile pic -->
			<!-- temporary button -->
			<!-- <form method="link" action="user/upload_profile_picture.php">
				<input type="submit" id="profilePic" value="Update Profile Pic" />
			</form> -->

			<!-- Profile Picture Modal -->
			<button onclick="document.getElementById('proPicModal').style.display='block'" style="width:auto;">Update Profile Pic</button>
			<div id="proPicModal" class="modal">
				<form class="modal-content animate" action="user/upload_profile_picture.php" method="post" enctype="multipart/form-data">
					<div class="closeContainer">
						<span onclick="document.getElementById('proPicModal').style.display='none'" class="close" title="Close">&times;</span>
						<h4>Update Profile Picture:</h4>
					</div>

					<div class="modalContainer">
						<input type="hidden" name="MAX_FILE_SIZE" value="31457280" />
			            <input type="file" name="new_profile_pic" id="new_profile_pic" required/>
			            <br /><br />
						<input type="hidden" name="return" value="<?php echo $returnto; ?>">
			            <button type="submit" name="upload">Change Picture</button>
					</div>
				</form>
			</div>

			<!-- Change Password Modal -->
			<button onclick="document.getElementById('passModal').style.display='block'" style="width:auto;">Change Password</button>
			<div id="passModal" class="modal">
				<!-- TODO: Change action of change password -->
				<form class="modal-content animate" action="/change_password.php">
					<div class="closeContainer">
						<span onclick="document.getElementById('passModal').style.display='none'" class="close" title="Close">&times;</span>
						<h4>Change your password:</h4>
					</div>

					<div class="modalContainer">
						<input type="password" name="password" placeholder="Current Password">
						<input type="password" name="new_password" placeholder="New Password">
						<input type="password" name="new_password_again" placeholder="New Password (Again)">
						<button type="submit" name="submit" onclick="submit_form()">Change Password</button>
					</div>
				</form>
			</div>
		</div>

		<!-- Friends Tab -->
		<div id="friends" class="tabcontent">
			<button type="button" id="addFriend" onclick="/addfriendmodal">Add</button>
			<button type="button" id="deleteFriend" onclick="/deleteFriend">Delete</button>
			<button type="button" id="blockFriend" onclick="/blockFriend">Block</button>
			<button type="botton" id="unblockFriend" onclick="/unblockFriend">Unblock</button>

			<div class="vertical-menu">
				<a href="#">Link 1</a>
				<a href="#">Link 2</a>
				<a href="#">Link 3</a>
				<a href="#">Link 4</a>
				<a href="#">Link 1</a>
				<a href="#">Link 2</a>
				<a href="#">Link 3</a>
				<a href="#">Link 4</a>
				<a href="#">Link 1</a>
				<a href="#">Link 2</a>
				<a href="#">Link 3</a>
				<a href="#">Link 4</a>
				<a href="#">Link 1</a>
				<a href="#">Link 2</a>
				<a href="#">Link 3</a>
				<a href="#">Link 4</a>
				<a href="#">Link 1</a>
				<a href="#">Link 1</a>
				<a href="#">Link 2</a>
				<a href="#">Link 3</a>
				<a href="#">Link 4</a>
				<a href="#">Link 1</a>
			</div>
		</div>

		<!-- Groups Tab -->
		<div id="groups" class="tabcontent">
			<button type="button" id="addGroup" onclick="/addGroupModal">Create Group</button>
			<button type="button" id="addGroupFriend" onclick="/addGroupFriendModal">Add To Group</button>
			<button type="button" id="deleteGroupFriend" onclick="/deleteGroupFriend">Remove From Group</button>
			<button type="botton" id="deleteGroup" onclick="/deleteGroup">Delete Group</button>

			<div class="vertical-menu">
			<a href="#">Link 1</a>
			<a href="#">Link 2</a>
			<a href="#">Link 3</a>
			<a href="#">Link 4</a>
			<a href="#">Link 1</a>
			<a href="#">Link 3</a>
			<a href="#">Link 4</a>
			</div>
		</div>

		<!-- Playlists Tab -->
		<div id="playlists" class="tabcontent">
			<button type="button" id="addPlay" onclick="/addSubModal">New Playlist</button>
			<button type="button" id="deletePlay" onclick="/deleteSub">Delete Playlist</button>

			<div class="vertical-menu">
			<a href="#">Link 1</a>
			<a href="#">Link 2</a>
			<a href="#">Link 3</a>
			<a href="#">Link 4</a>
			</div>
		</div>

		<!-- Chat Tab -->
		<div id="chat" class="tabcontent">
			<p>This is where we put chat shit.</p>
		</div>
	</div>

	<!-- Modal script -->
	<script>
		// Get the modal
		var passModal = document.getElementById('passModal');
		var proPicModal = document.getElementById('proPicModal');

		// When the user clicks anywhere outside of the modal, close it
		window.onclick = function(event) {
			if (event.target == passModal) {
				modal.style.display = "none";
			} else if (event.target == proPicModal) {
				modal.style.display = "none";
			}
		}
	</script>

	<!-- Tabbing Script -->
	<script>
		// creates default tab opened on landing
		document.getElementById("defaultOpen").click();

		function openSocial(evt, socialTab) {
		// Declare all variables
		var i, tabcontent, tablinks;

		// Get all elements with class="tabcontent" and hide them
		tabcontent = document.getElementsByClassName("tabcontent");
		for (i = 0; i < tabcontent.length; i++) {
			tabcontent[i].style.display = "none";
		}

		// Get all elements with class="tablinks" and remove the class "active"
		tablinks = document.getElementsByClassName("tablinks");
		for (i = 0; i < tablinks.length; i++) {
			tablinks[i].className = tablinks[i].className.replace(" active", "");
		}

		// Show the current tab, and add an "active" class to the button that opened the tab
		document.getElementById(socialTab).style.display = "block";
		evt.currentTarget.className += " active";
		}
	</script>

	<!-- Password change script -->
	<script type="text/javascript">
      function submit_form() {
         // Create a Promise, which allows us to wait until the form has responded,
         // to then display results.
         // Promises will run until we resolve or reject them.
         return new Promise(function(resolve,reject) {
               var xhr = new XMLHttpRequest();

               xhr.onload = function() {
                  // Promise will stop running after this function finishes
                  resolve();

                  // Hide form
                  document.getElementById("div_to_hide").style.display = 'none';

                  // display results
                  document.getElementById("display").innerHTML = xhr.responseText;
               };

               // If there was an error, stop the Promise
               xhr.onerror = reject;

               var formData = new FormData(document.getElementById("changepassword_form"));

               // Pretty sure this should be a part of the form already, but it's not, so...
               // we add it here.
               formData.append('submit','1');

               xhr.open("POST", "user/changepassword.php");
               xhr.send(formData);
         });
      }
    </script>

	<!-- Footer Content -->
	<div class="footer">
        <h6><b>CPSC 4620-001 Spring 2018</b><br><i>Micah Johnson, Zackary Sullivan,  Sadie Sweetman</i></h6>
    </div>

</body>

</html>
