<!DOCTYPE html>

<?php
session_save_path("session");
session_start();
?>

<html>
    <a href="index.php">Return home</a>

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

    <!-- Display results from form here -->
    <p id="display"></p>

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
    </script>
</html>
