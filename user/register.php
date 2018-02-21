<?php
session_start(); // get the session info

include_once "../database.php";

if(isset($_POST['register'])) { // process POST data if it exists
    if($_POST['username'] == "" || $_POST['pass_1'] == "" || $_POST['pass_2'] == "") { // check if all feilds filled out
        $register_error = "One or more fields are missing.";
    } else if ($_POST['pass_1'] != $_POST['pass_2']) { // check for matching passwords
        $register_error = "The passwords do not match.";
    } else {
        $db = new DatabaseConnection();
        $result = $db->custom_sql("SELECT * FROM users WHERE username='".$_POST['username']."'"); // ask database of user exists

        if (!$result) {
            die ("user_pass_check() failed. Could not query the database: <br />");
        } else if ($result->num_rows != 0) { // any results means username is unavailable
            $register_error = "User ".$_POST['username']." already exists.";
        } else {
            // username checks out - insert it into the database.
            // TODO: hash passwords (possibly before leaving client, that may require JS though, and if we are using JS for that then we could do a fancy realtime check for the two password feilds matching)
            $db->custom_sql("INSERT INTO users (username, password) VALUES ('".$_POST['username']."','".$_POST['pass_1']."')");
            $_SESSION['username']=$_POST['username']; // log the user in
            header('Location: ../index.php'); // go back to the main page
        }
    }
}
?>

<form method="post" action="register.php">

	<table width="100%">
		<tr>
			<td  width="20%">Username:</td>
			<td width="80%"><input class="text"  type="text" name="username"></td>
		</tr>
		<tr>
			<td  width="20%">Password:</td>
			<td width="80%"><input class="text"  type="password" name="pass_1"></td>
		</tr>
        <tr>
			<td  width="20%"> Retype Password:</td>
			<td width="80%"><input class="text"  type="password" name="pass_2"></td>
		</tr>
		<tr>
			<td><input name="register" type="submit" value="Register"></td>
		</tr>
	</table>
</form>

<?php
  if(isset($register_error))
   {  echo "<p>".$register_error."</p>";}
?>
