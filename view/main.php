<?php
if (!isset($_SESSION["logged_user"])){
    header("Location:login.php");
}
echo "Hello " .$_SESSION['logged_user']['username'].", you are in the main page";
$user_id = $_SESSION["logged_user"]["id"];
?>
<br>
<a href="index.php?target=user&action=logout"><button>Logout</button></a>
<br>
<a href="index.php?target=view&action=viewRouter&view=upload"><button>Upload video</button></a>
<br>
<a href="index.php?target=video&action=getAll"><button>Show all videos</button></a>
<br>
<a href="index.php?target=video&action=getByOwnerId&owner_id=<?= $user_id; ?>"><button>Show my videos</button></a>
<table>
    <?php
    if (isset($videos)) {
        foreach ($videos as $video) {
            echo "<tr><td>";
            echo $video["title"];
            echo "</td></tr>";
            echo "<tr><td>";
            echo $video["date_uploaded"];
            echo "</td></tr>";
            echo "<tr><td>";
            echo $video["username"];
            echo "</td></tr>";
            echo "<tr><td><a href='index.php?target=video&action=getById&id=" . $video["id"] . "'><img width='200px' src='";
            echo $video["thumbnail_url"];
            echo "'></a></td></tr>";
        }
    }
    ?>
</table>
