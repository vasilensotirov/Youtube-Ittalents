<?php
if (!isset($_SESSION["logged_user"])){
    header("Location:login.php");
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
    <link rel="stylesheet" href="styles/style.css">
    <title>Document</title>
</head>
<body>
<?php
require_once "header.php";
require_once "navigation.php";
?>
<main>
    <b>Order by:</b>
    <button><a href="index.php?target=video&action=<?= $action; ?>&orderby=date">Date ASC</a></button>
    <button><a href="index.php?target=video&action=<?= $action; ?>&orderby=date&desc">Date DESC</a></button>
    <button><a href="index.php?target=video&action=<?= $action; ?>&orderby=likes">Likes ASC</a></button>
    <button><a href="index.php?target=video&action=<?= $action; ?>&orderby=likes&desc">Likes DESC</a></button>

<table>
    <?php
    if (isset($videos)) {
        if($videos){
        foreach ($videos as $video) {
            echo "<tr><td><a href='index.php?target=video&action=getById&id=" . $video["id"] . "'><img width='200px' src='";
            if (!$video["thumbnail_url"]){
                $video["thumbnail_url"] = 'https://therisingnetwork.com/wp-content/plugins/video-thumbnails/default.jpg';
            }
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
    }
    ?>
</table>
</main>
</body>
</html>