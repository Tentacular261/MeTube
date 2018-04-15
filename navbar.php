<?php
$returnto = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
include_once "database.php";
$dbnav = new DatabaseConnection();
$user = (empty($_SESSION['username'])) ? "" : $dbnav->conn->real_escape_string($_SESSION['username']);
?>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="css/general.css">
    <link rel="stylesheet" href="css/navbar.css">
</head>
<body>
    <form id="logout" class="modal" method="post" action="user/logout.php">
        <input type="hidden" name="return" value="<?php echo $returnto; ?>">
        <input type="hidden" name="logout" value="logout">
    </form>

    <div class="topnav" id="myTopnav">
        <a class="user" href="user.php" top="true"> <!--TODO: create the user account control page -->
            <img src="<?php
            if ($user == "")
                echo "https://cdn.iconscout.com/public/images/icon/free/png-512/avatar-user-teacher-312a499a08079a12-512x512.png";
            else {
                $ret = $dbnav->custom_sql("SELECT picture FROM users WHERE username='$user'")->fetch_array()['picture'];
                echo "user/profile_pictures/$ret";
            }
            ?>"/>
            <!-- TODO: CHANGE FOR UNIQUE USER AVATAR -->
            <span>Welcome <?php echo isset($_SESSION['username']) ? $_SESSION['username'] : "Guest" ?></span>
        </a>
        <a href="index.php">Home</a>
        <?php
        if (isset($_SESSION['username'])) {
        ?>
            <a href="upload.php">Upload</a>
            <!-- TODO: make log out work -->
            <a class="right" href="javascript:void(0);"
            onclick="document.getElementById(&quot;logout&quot;).submit()">
            Log Out
            </a>
        <?php
        } else {
        ?>
            <a class="right" href="javascript:void(0);"
            onclick="document.getElementById('register').style.display='block'">
            Register
            </a>
            <a class="right" href="javascript:void(0);"
            onclick="document.getElementById('login').style.display='block'">
            Log In
            </a>

        <?php } ?>
        <a href="javascript:void(0);" class="icon" top="true" onclick="openNavbar()">&#9776;</a>
    </div>

    <?php if (!isset($_SESSION['username'])) { ?>
    <!-- TODO: Go through and integrate these dialogs -->
    <!-- Login Popup -->
    <div id="login" class="modal">

        <form class="modal-content animate" method="post" action="user/login.php">
        <!-- CHANGE TO DB LOGIN -->

            <div class="container">
                <span onclick="document.getElementById('login').style.display='none'"
                class="close" title="Close">&times;</span>
                <h1>Login</h1>
                <hr>
                <label for="uname"><b>Username</b></label>
                <input type="text" placeholder="Enter Username"
                    name="username" required>

                <label for="password"><b>Password</b></label>
                <input type="password" placeholder="Enter Password"
                    name="password" required>
                <br>

                <input type="hidden" name="return" value="<?php echo $returnto; ?>">
                <button type="button" onclick="document.getElementById('login').style.display='none'"
                        class="cancelbtn">
                    Cancel
                </button>
                <button type="submit" name="submit">Login</button>
            </div>
        </form>
    </div>

    <!-- Register Popup -->
    <div id="register" class="modal">
        
        <form class="modal-content animate" method="post" action="user/register.php">
        <!-- CHANGE TO DB NEW USER -->
        <div class="container">
            <span onclick="document.getElementById('register').style.display='none'"
            class="close" title="Close">&times;</span>
            <h1>Register</h1>
            <p>Please fill in this form to create an account.</p>
            <hr>
            <label for="username"><b>Username</b></label>
            <input type="text" placeholder="Enter Username"
            name="username" required />
            
            <label for="pass_1"><b>Password</b></label>
            <input type="password" placeholder="Enter Password"
            name="pass_1" required>
            
            <label for="pass_2"><b>Repeat Password</b></label>
            <input type="password" placeholder="Repeat Password"
            name="pass_2" required>
            
            <p>By creating an account you agree to absolutely no privacy or exclusivity of content.</p>
            
            <div class="clearfix">
                <input type="hidden" name="return" value="<?php echo $returnto; ?>">
                <button type="button" onclick="document.getElementById('register').style.display='none'"
                class="cancelbtn">
                Cancel
            </button>
            <button name="register" type="submit" class="registerbtn">Register</button>
        </div>
    </div>
</form>
</div>
    <?php
    } 
    
    if (!empty($_SESSION['error_message'])) {
        ?>
    <!-- Error Popup -->
    <div id="error" class="modal" style="display:block;">
        <!-- CHANGE TO DB NEW USER -->
        <div class="modal-content animate"><div class="container">
            <span onclick="document.getElementById('error').style.display='none'"
            class="close" title="Close">&times;</span>
            <h1>Error</h1>
            <?php echo $_SESSION['error_message']; ?>
        </div></div>
    </div>
    
    <?php
    }
    unset($_SESSION['error_message']);
    ?>
    <script>
        // Get the modal
        var modal_boxes = [];
        modal_boxes.push(document.getElementById('login'));
        modal_boxes.push(document.getElementById('register'));
        modal_boxes.push(document.getElementById('error'));

        // When the user clicks anywhere outside of the modal, close it
        window.onclick = function (event) {
            modal_boxes.forEach(function (elem) {
                if (event.target == elem) {
                elem.style.display = "none";
                }
            });
        }
    </script>
    <script>
    // toggles the responsive version of the Navbar
    function openNavbar() {
        var x = document.getElementById("myTopnav");
        if (x.className === "topnav") {
            x.className += " open";
        } else {
            x.className = "topnav";
        }
    }
    </script>
    <!-- TODO: add an error display for debugging purposes -->
</body>
