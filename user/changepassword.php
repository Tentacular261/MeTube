<?php
session_save_path("../session");
session_start(); // get session data

include_once "../database.php";

function debug_to_console( $data ) {
    $output = $data;
    if ( is_array( $output ) )
        $output = implode( ',', $output);

    echo "<script>console.log( 'Debug Objects: |" . $output . "|' );</script>";
}

if(isset($_POST['submit'])) {
  debug_to_console("SUBMIT!!");
  if($_POST['password'] == "" || $_POST['new_password'] == "" || $_POST['new_password_again'] == "") { // All FIELDS must be filled out
      $pw_change_error = "One or more fields are missing.";
  } else {
      debug_to_console("About to make DB conn");
      $db = new DatabaseConnection();
      $un = $db->conn->real_escape_string($_SESSION['username']);
      $result = $db->custom_sql("SELECT * FROM users WHERE username='".$un."'"); // ask database to find the user info

      if (!$result) {
          die ("user_pass_check() failed. Could not query the database: <br />");
      } else if ($result->num_rows != 1) { // There should be one result TODO: add a notification of anything other than 0 or 1. Anything else means we fucked up somewhere and have a duplicate user (username being primary key should prevent this though)
          $pw_change_error = "User ".$_SESSION['username']." not found.";
      } else { // The user exists check password now
          $row = $result->fetch_assoc();
          if(strcmp($row['password'],$_POST['password']))
              $pw_change_error = "Incorrect password.";
          else if(strcmp($_POST['new_password'],$_POST['new_password_again']))
              $pw_change_error = "New passwords do not match.";
          else {
              $un = $db->conn->real_escape_string($_SESSION['username']);
              $pw = $db->conn->real_escape_string($_POST['new_password']);
              $result = $db->custom_sql("UPDATE users SET password='".$pw."'WHERE username='".$un."'");

              if (!$result)
                  die ("Password update failed. Could not query the database: <br />");

              echo "<p>Password successfully changed.</p>";
              exit;
          }

          echo $pw_change_error;
      }
  }
}
?>
