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
		<title>|Profile|</title>
		<link rel="stylesheet" href="css/general.css">
		<link rel="stylesheet" href="css/user.css">
	</head>

	<body>
		<div class="profileContent">
			<div class="userRow">
				<img src="profilePic.jpg" alt="Your Profile Pic is Broken"/> <br/>
				<button id="profilePic">Update Profile Picture</button>
				<button id="changePassword">Change Password</button>
				
				<div id="passwordModal" class="modal">
					<form class="changepassword_form animate" action="">

						<div class="passModalContent">
							<span onclick="document.getElementById('changePassword').style.display='none'"
								class="close" title="Close">&times;</span>
							<h4>Change your password:</h4>
							<hr>
								<input type="password" name="password" placeholder="Current Password">

								<input type="password" name="new_password" placeholder="New Password">
						
								<input type="password" name="new_password_again" placeholder="New Password (Again)">

								<button type="submit" name="submit" onclick="submit_form()">Change Password</button>
						</div>
					</form>
				</div>

				<!-- SADIE NOT GONNA DELETE MICAH CODE
				<div id="div_to_hide">
					<h4>Change your password:</h4>

					<form id="changepassword_form" onsubmit="return false;" method="post" enctype="multipart/form-data">
						<label for="passw">Current Password</label>
						<input type="password" name="password">
						<br />

						<label for="newpassw">New Password</label>
						<input type="password" name="new_password">
						<br />

						<label for="newpassw">New Password (Again)</label>
						<input type="password" name="new_password_again">
						<br /><br />
						<button type="submit" name="submit" onclick="submit_form()">Change Password</button>
					</form>
				</div>
				-->
			</div>

			<div class="userRow">

				<div class="contacts">

				</div>

				<div class="groups">
				</div>

				<div class="subscriptions">
				</div>

			</div>
		</div>

		<!-- Display results from form here -->
		<p id="display"></p>
	 
		<!-- Footer Content -->
		<div class="footer">
			<h6><b>CPSC 4620-001 Spring 2018</b><br><i>Micah Johnson, Zackary Sullivan, Sadie Sweetman</i></h6>
		</div>

	</body>
    
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
		// TODO: RETURN TO USER.PHP WHEN PASSWORD CHANGES -->
   </script>

	<script>
		// Get the modal
		var modal = document.getElementById('passwordModal');

		// Get the button that opens the modal
		var btn = document.getElementById("changePassword");

		// Get the <span> element that closes the modal
		var span = document.getElementsByClassName("close")[0];

		// When the user clicks the button, open the modal 
		btn.onclick = function() {
			 modal.style.display = "block";
		}

		// When the user clicks on <span> (x), close the modal
		span.onclick = function() {
			 modal.style.display = "none";
		}

		// When the user clicks anywhere outside of the modal, close it
		window.onclick = function(event) {
			 if (event.target == modal) {
				  modal.style.display = "none";
			 }
		}
	</script>

</html>
