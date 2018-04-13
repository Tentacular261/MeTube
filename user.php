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
		<button class="tablinks" onclick="openSocial(event, 'subscriptions')">Subscriptions</button>
		<button class="tablinks" onclick="openSocial(event, 'groups')">Groups</button>
		<button class="tablinks" onclick="openSocial(event, 'subscriptions')">Chat</button>
		</div>

		<!-- Tab content -->
		<div id="manageProfile" class="tabcontent">
			<img src="profilePic.jpg" alt="Your profile pic is broken like your life">
			<button type="button" id="profilePic">Update Profile Pic</button>
			<button onclick="document.getElementById('passModal').style.display='block'" style="width:auto;">Change Password</button>

			<div id="passModal" class="modal">
				<!-- TODO: Change action of change password -->
				<form class="modal-content animate" action="/change_password.php">
					<div class="closeContainer">
						<span onclick="document.getElementById('passModal').style.display='none'" class="close" title="Close">&times;</span>
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

		<div id="friends" class="tabcontent">
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
			</div>
		</div>

		<div id="subscriptions" class="tabcontent">
			<div class="vertical-menu">
			<a href="#">Link 1</a>
			<a href="#">Link 2</a>
			<a href="#">Link 3</a>
			<a href="#">Link 4</a>
			</div>
		</div>

		<div id="groups" class="tabcontent">
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

		<div id="chat" class="tabcontent">
			<p>This is where we put chat shit.</p>
		</div>
	</div>

	<script>
		// Get the modal
		var modal = document.getElementById('passModal');

		// When the user clicks anywhere outside of the modal, close it
		window.onclick = function(event) {
			if (event.target == modal) {
				modal.style.display = "none";
			}
		}
	</script>

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

	<!-- Footer Content -->
	<div class="footer">
        <h6><b>CPSC 4620-001 Spring 2018</b><br><i>Micah Johnson, Zackary Sullivan,  Sadie Sweetman</i></h6>
    </div>
	
</body>

</html>
