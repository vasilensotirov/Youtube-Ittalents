<?php
if (!isset($_SESSION["logged_user"])){
    header("Location:login.php");
}
echo "Hello " .$_SESSION['logged_user']['username'].", you are in the main page";
$user_id = $_SESSION["logged_user"]["id"];
?>
<br>
<a href="index.php"><button>Home</button></a>
<a href="index.php?target=user&action=logout"><button>Logout</button></a>
<br>
<a href="index.php?target=view&action=viewRouter&view=upload"><button>Upload video</button></a>
<br>
<a href="index.php?target=video&action=getAll&owner_id=<?= $user_id; ?>"><button>Show my videos</button></a>
<br>
    <?php
    if (isset($video)) {
        echo "<video width='470' height='255' controls>
        <source src='" . $video["video_url"] . "' type='video/mp4'></video><br>";
        echo $video["title"] . "<br>";
        echo $video["date_uploaded"] . "<br>";
        echo $video["description"];
    }
    ?>

