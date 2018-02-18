<html>
	<head>
		<title>Make Account</title>
	</head>
	<body>
		<p>This is a test.</p>
		<?php
			$servername = "mysql1.cs.clemson.edu";
			$username   = "MTbDtbs_3b07";
			$password   = "X#2s6nF7";
			$dbname     = "MeTubeDatabase_99yq";

			// Create connection
			$conn = new mysqli($servername, $username, $password, $dbname);

			// Check connection
			if ($conn->connect_error) {
				echo "<p>Connection failed: " . $conn->connect_error . "</p>";
			} else {
				echo "<p>Connected successfully</p>";
			}

			//print out all users
			$request = "SELECT * FROM users";
			$result = $conn->query($request);

			echo "<table style=\"width:200\">";
			echo "<tr><th>User Name</th><th>Password</th></tr>";
			if ($result->num_rows > 0) {
				while($row = $result->fetch_assoc()) {
					echo "<tr><td>" . $row["username"] . "</td><td>" . $row["password"] . "</td></tr>";
				}
			}
			echo "</table>\n";

			$conn->close();
		?>
	</body>
</html>
