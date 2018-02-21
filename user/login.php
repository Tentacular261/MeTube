<?php
session_start(); // get session data

include_once "../database.php";

if(isset($_POST['submit'])) { // process POST data if it exists
    if($_POST['username'] == "" || $_POST['password'] == "") { // Both feilds must be filled out
        $login_error = "One or more fields are missing.";
    } else {
        $db = new DatabaseConnection();
        $result = $db->custom_sql("SELECT * FROM users WHERE username='".$_POST['username']."'"); // ask database to find the user info

        if (!$result) {
            die ("user_pass_check() failed. Could not query the database: <br />");
        } else if ($result->num_rows != 1) { // There should be one result TODO: add a notification of anything other than 0 or 1. Anything else means we fucked up somewhere and have a duplicate user (username being primary key should prevent this though)
            $login_error = "User ".$_POST['username']." not found.";
        } else { // The user exists check password now
            $row = $result->fetch_assoc();
            if(!strcmp($row['password'],$password))
            	$login_error = "Incorrect password.";
            else {
                $_SESSION['username']=$_POST['username']; //Set the $_SESSION['username'] (Log the user in)
                // TODO: load the last page the user was on
                header('Location: ../index.php');
            }
        }
    }
}
?>

<form method="post" action="<?php echo "login.php"; ?>">

	<table width="100%">
		<tr>
			<td  width="20%">Username:</td>
			<td width="80%"><input class="text"  type="text" name="username"><br /></td>
		</tr>
		<tr>
			<td  width="20%">Password:</td>
			<td width="80%"><input class="text"  type="password" name="password"><br /></td>
		</tr>
		<tr>

			<td><input name="submit" type="submit" value="Login"><input name="reset" type="reset" value="Reset"><br /></td>
		</tr>
	</table>
	</form>

<?php
  if(isset($login_error))
   {  echo "<p>".$login_error."</p>";}
?>
