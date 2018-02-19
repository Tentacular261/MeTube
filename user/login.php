<?php
session_start();

include_once "../database.php";

if(isset($_POST['submit'])) {
    if($_POST['username'] == "" || $_POST['password'] == "") {
        $login_error = "One or more fields are missing.";
    } else {
        $db = new DatabaseConnection();
        $result = $db->custom_sql("SELECT * FROM users WHERE username='".$_POST['username']."'");

        if (!$result) {
            die ("user_pass_check() failed. Could not query the database: <br />");
        } else if ($result->num_rows != 1) {
            $login_error = "User ".$_POST['username']." not found.";
        } else {
            $row = $result->fetch_assoc();
            if(!strcmp($row['password'],$password))
            	$login_error = "Incorrect password.";
            else {
                $_SESSION['username']=$_POST['username']; //Set the $_SESSION['username']
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
