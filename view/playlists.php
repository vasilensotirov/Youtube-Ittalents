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
    <link href="//maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.3/css/all.css" integrity="sha384-UHRtZLI+pbxtHCWp1t77Bi1L4ZtiqrqD80Kn4Z8NTSRyMA2Fd33n5dQ8lWUE00s/" crossorigin="anonymous">
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
<a href="index.php?view=createPlaylist"><button>Create new playlist</button></a><br>
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
