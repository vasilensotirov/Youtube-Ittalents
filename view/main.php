<?php
if (!isset($_SESSION["logged_user"])){
    header("Location:login.php");
}
echo "Hello " .$_SESSION['logged_user']['username'].", you are in the main page";
?>
<br>
<a href="index.php?target=user&action=logout"><button>Logout</button></a>
<br>
<a href="index.php?target=view&action=viewRouter&view=upload"><button>Upload video</button></a>