<html>
	<head>
		<title>Make Account</title>
	</head>
	<body>
		<p>This is a test.</p>

        <table style=\"width:200\">
            <tr>
                <th>User Name</th>
                <th>Password</th>
            </tr>
    		<?php
    		    include_once "../database.php";

                $db = new DatabaseConnection();

    			//print out all users
    			$result = $db->custom_sql("SELECT * FROM users");

    			if ($result->num_rows > 0) {
    				while($row = $result->fetch_assoc()) {
    					echo "<tr><td>" . $row["username"] . "</td><td>" . $row["password"] . "</td></tr>";
    				}
    			}
    		?>
        </table>
	</body>
</html>
