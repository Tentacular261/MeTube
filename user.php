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
			<button onclick="document.getElementById('proPicModal').style.display='block'" style="width:auto;">Update Profile Pic</button>

			<button onclick="document.getElementById('passModal').style.display='block'" style="width:auto;">Change Password</button>
			
		</div>

		<!-- Friends Tab -->
		<div id="friends" class="tabcontent">
			<form method="post" action="user/addremovefriend.php">
			<button type="button" id="addFriend" onclick="document.getElementById('addFriendModal').style.display='block'">Add</button>
			<button type="submit" id="deleteFriend" name="deleteFriend">Delete</button>
			<input type="hidden" name="return" value="<?php echo $returnto; ?>">

			<div class="vertical-menu" name="username">
				<?php
				$friends = $db->custom_sql("SELECT friend FROM friends WHERE user='$username'");
				while ($friend = $friends->fetch_array()) {
					// TODO: fix bug that allows the user '; to break the javascript
					$fname = $friend['friend'];
					echo "<a href=\"javascript:selectFriendOption('friend_select_$fname')\">"
							."<input id=\"friend_select_$fname\" type=\"radio\" name=\"username\" value=\"$fname\"> $fname"
						."</a>\n";
				}
				?>
			<script>
			function selectFriendOption(selectid) {
				document.getElementById(selectid).checked = true;
			}
			</script>
			</div></form>
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
			<form class="vertical-menu left" method="post" action="user/addremovechat.php">
				<button type="button" id="addchat" onclick="document.getElementById('addChatModal').style.display='block'">New Chat</button>
				<button type="submit" id="deletechat" name="deletechat">Delete Chat</button>
				<input type="hidden" name="return" value="<?php echo $returnto; ?>">
				<?php
				$chats = $db->custom_sql("SELECT DISTINCT user1,user2 FROM messages WHERE user1='$username' OR user2='$username'");
				while ($chat = $chats->fetch_array()) {
					// TODO: fix bug that allows the user '; to break the javascript
					$otherUser = ($chat['user1'] == $_SESSION['username']) ? $chat['user2'] : $chat['user1'];
					echo "<a href=\"javascript:selectChatOption('$otherUser')\">"
							."<input id=\"chat_select_$otherUser\" type=\"radio\" name=\"username\" value=\"$otherUser\"> $otherUser"
						."</a>\n";
				}
				?>
			</form>
			<script>
			function selectChatOption(selectid) {
				document.getElementById('chat_select_' + selectid).checked = true;
				var user1 = "<?php echo $_SESSION['username']; ?>";
				document.getElementById('chat-window').src = "chat.php?user1="
															+ encodeURIComponent(user1)
															+ "&user2="
															+ encodeURIComponent(selectid);
			}
			</script>
			<iframe id="chat-window" class="vertical-menu" src="" frameBorder="0"></iframe>
		</div>
	</div>

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

	<div id="passModal" class="modal">
		<!-- TODO: Change action of change password -->
		<form class="modal-content animate" method="post" action="user/changepassword.php">
			<div class="closeContainer">
				<span onclick="document.getElementById('passModal').style.display='none'" class="close" title="Close">&times;</span>
				<h4>Change your password:</h4>
			</div>

			<div class="modalContainer">
				<input type="password" name="password" placeholder="Current Password">
				<input type="password" name="new_password" placeholder="New Password">
				<input type="password" name="new_password_again" placeholder="New Password (Again)">
				<button type="submit" name="submit">Change Password</button>
			</div>
		</form>
	</div>

	<div id="addFriendModal" class="modal">
        <form class="modal-content animate" method="post" action="user/addremovefriend.php">
        <!-- CHANGE TO DB NEW USER -->
        <div class="container">
            <span onclick="document.getElementById('addFriendModal').style.display='none'"
            class="close" title="Close">&times;</span>
            <h1>Add Friend</h1>
            <hr>
            <label for="username"><b>Username</b></label>
            <input type="text" placeholder="Enter Username of Friend"
            name="username" required />
            
            <div class="clearfix">
                <input type="hidden" name="return" value="<?php echo $returnto; ?>">
                <button type="button" onclick="document.getElementById('addFriendModal').style.display='none'"
                class="cancelbtn">
                Cancel
                </button>
                <button name="addfriend" type="submit" class="registerbtn">Add</button>
            </div>
        </div>
        </form>
	</div>
	
	<div id="addChatModal" class="modal">
        <form class="modal-content animate" method="post" action="user/addremovechat.php">
        <!-- CHANGE TO DB NEW USER -->
        <div class="container">
            <span onclick="document.getElementById('addChatModal').style.display='none'"
            class="close" title="Close">&times;</span>
            <h1>Add Chat With User</h1>
            <hr>
            <label for="username"><b>Username</b></label>
            <input type="text" placeholder="Enter Username To Chat With"
            name="username" required />
            
            <div class="clearfix">
                <input type="hidden" name="return" value="<?php echo $returnto; ?>">
                <button type="button" onclick="document.getElementById('addChatModal').style.display='none'"
                class="cancelbtn">
                Cancel
                </button>
                <button name="addchat" type="submit" class="registerbtn">Add</button>
            </div>
        </div>
        </form>
    </div>

	<!-- Modal script -->
	<script>
		// add the modals to the close list
		modal_boxes.push(document.getElementById('passModal'));
		modal_boxes.push(document.getElementById('proPicModal'));
		modal_boxes.push(document.getElementById('addFriendModal'));
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
		if (socialTab == "chat") document.getElementById(socialTab).style.display = "flex";
		evt.currentTarget.className += " active";
		}
	</script>

	<!-- Footer Content -->
	<div class="footer">
        <h6><b>CPSC 4620-001 Spring 2018</b><br><i>Micah Johnson, Zackary Sullivan,  Sadie Sweetman</i></h6>
    </div>

</body>

</html>
