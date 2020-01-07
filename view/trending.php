<?php
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

?>
<table>
    <?php
    if(isset($mostWatchedVideos)){
        foreach($mostWatchedVideos as $mostWatchedVideo){
            echo "<tr><td><a href='index.php?target=video&action=getById&id=" . $mostWatchedVideo["id"] . "'><img width='200px' src='";
            echo $mostWatchedVideo["thumbnail_url"];
            echo "'></a></td></tr>";
            echo "<tr><td>";
            echo $mostWatchedVideo["title"];
            echo "</td></tr>";
            echo "<tr><td>Views: ";
            echo $mostWatchedVideo['views'];
            echo "</td></tr>";
            echo "<tr><td>";
            echo $mostWatchedVideo["date_uploaded"];
            echo "</td></tr>";
            echo "<tr><td>";
            echo $mostWatchedVideo["username"];
            echo "</td></tr>";
        }
    }
    ?>
</table>

</body>
</html>
