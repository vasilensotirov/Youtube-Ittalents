<?php
if (!isset($_SESSION["logged_user"])){
    header("Location:login.php");
}
if (isset($_GET["id"])){
    $video_id = $_GET["id"];
}
$user_id = $_SESSION["logged_user"]["id"];
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
<?php
require_once "header.php";
require_once "navigation.php";

if(isset($playlists)){
    foreach ($playlists as $playlist) {
        echo "<a href='index.php?target=playlist&action=clickedPlaylist&id=" . $playlist["id"] ."'><b>" . $playlist["playlist_title"]. "</b></a>" . "<br>";
        echo $playlist["date_created"]. "<br>";
        echo "<hr>";
    }
}
?>
<table>
<?php
if(isset($videos)){
    foreach($videos as $video){
        echo "<tr><td><b>";
        echo $video["playlist_title"];
        echo "</b><hr>";
        echo "<tr><td><a href='index.php?target=video&action=getById&id=" . $video["id"] . "'><img width='200px' src='";
        echo $video["thumbnail_url"];
        echo "'></a></td></tr>";
        echo "<tr><td>";
        echo $video["title"];
        echo "</td></tr>";
        echo "<tr><td>";
        echo $video["date_uploaded"];
        echo "</td></tr>";
        echo "<tr><td>";
        echo $video["username"];
        echo "</td></tr>";
    }
}
?>
</table>

</body>
</html>
