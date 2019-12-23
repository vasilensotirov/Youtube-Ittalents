<?php
echo "Hello " .$_SESSION['logged_user']['username'].", you are in the main page";
?>
<a href="index.php?target=user&action=logout"><button>Logout</button></a>