
<div class="topnav" id="myTopnav">
    <a class="user" href="#home" top="true">
        <img src="https://cdn.iconscout.com/public/images/icon/free/png-512/avatar-user-teacher-312a499a08079a12-512x512.png"/>
        <!-- CHANGE FOR UNIQUE USER AVATAR -->
        <span>Welcome User</span>
    </a>
    <a href="#news">News</a>
    <a href="#contact">Contact</a>
    <a class="right" href="#about">Testing</a>
    <a href="javascript:void(0);" class="icon" top="true" onclick="myFunction()">&#9776;</a>
</div>
<script>
function myFunction() {
    var x = document.getElementById("myTopnav");
    if (x.className === "topnav") {
        x.className += " open";
    } else {
        x.className = "topnav";
    }
}
</script>
