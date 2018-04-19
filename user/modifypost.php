<?php
session_save_path("../session");
session_start(); // get the session info

include_once "../database.php";

if(isset($_POST['modify']) && !empty($_SESSION['username'])) { // process POST data if it exists
    if($_POST['title'] == "" || $_POST['media_id'] == "" || $_POST['category'] == "" || $_POST['privacy'] == "") { // check if all feilds filled out
        $_SESSION['error_message'] = "One or more fields are missing.";
    } else {
        $db = new DatabaseConnection();
        $id = $db->conn->real_escape_string($_POST['media_id']);
        $user = $db->conn->real_escape_string($_SESSION['username']);
        $title = $db->conn->real_escape_string($_POST['title']);
        $category = $db->conn->real_escape_string($_POST['category']);
        $privacy = $db->conn->real_escape_string($_POST['privacy']);
        $description = (empty($_POST['description'])) ? "" : $db->conn->real_escape_string($_POST['description']);
        $keywords = (empty($_POST['keywords'])) ? "" : $db->conn->real_escape_string($_POST['keywords']);

        $temp = $db->custom_sql("SELECT * FROM media WHERE id='$id'");
        if ($temp->num_rows != 1) {
            $_SESSION['error_message'] = "Post doesn't exist";
            header('Location: '.$_POST['return']);
            exit;
        } else {
            $upuser = $temp->fetch_array()['uploaded_by'];
            if ($upuser != $_SESSION['username']) {
                $_SESSION['error_message'] = "You can only edit your own posts";
                header('Location: '.$_POST['return']);
                exit;
            } else {
                $db->custom_sql("UPDATE media SET title='$title', description='$description', privacy='$privacy', category='$category' WHERE id='$id'");

                if (!empty($keywords)) {
                    $db->custom_sql("DELETE FROM keywords WHERE media_id='$id'");

					// this needs to ba a lambda function to avoid warnings
					$keywords = array_map(function ($temp) use ($db) { return $db->conn->real_escape_string($temp); },explode(" ",$keywords));

					$query = "INSERT INTO keywords VALUES ";
					foreach ($keywords as $word) if (strlen($word) < 30)
						$query .= "('".$word."','".$id."'), ";
					$query = rtrim($query,", ");

					$db->custom_sql($query);
				}
            }
        }
    }
} else {
    $_SESSION['error_message'] = "Request was malformed";
}
if (isset($_POST['return'])) {
    header('Location: '.$_POST['return']);
    exit;
} else {
    header('Location: ../index.php');
    exit;
}
?>