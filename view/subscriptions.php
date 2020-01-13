<?php
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
    <link rel="stylesheet" href="styles/style.css">
    <title>Document</title>
</head>
<body>
<?php
require_once "header.php";
require_once "navigation.php";
?>
<table>
    <?php
    if(isset($subscriptions)){
        if ($subscriptions) {
            foreach ($subscriptions as $subscription) {
                echo "<tr><td rowspan='2'><img style='border-radius: 50%;' alt='No photo' width='100px' src='";
                echo $subscription['avatar_url'];
                echo "'></td>";
                echo "<td>";
                echo "<a href='index.php?target=user&action=getById&id=" . $subscription["followed_id"] . "'><b>" . $subscription["username"] . "</b></a>";
                echo "</td></tr>";
                echo "<tr><td>";
                echo $subscription['name'];
                echo "</td></tr>";
            }
        }
        else {
            echo "<h3>No active subsciptions.</h3>";
        }
    }
    if(isset($user)){
        foreach ($user as $value) {
            echo "<tr><td rowspan='2'><img style='border-radius: 50%;' alt='No photo' width='100px' src='";
            echo $value['avatar_url'];
            echo "'></td>";
            echo "<td>";
            echo $value["username"]. "</b>";
            echo "</td></tr>";
            echo "<tr><td>";
            echo $value['name'];
            echo "</td></tr>";
            echo "<div class='playlistContainer'><tr><td>";
            echo "<a href='index.php?target=playlist&action=clickedPlaylist&id=" . $value["id"] ."'><b>" . $value["playlist_title"]. "</b></a>";
            echo "</td><td>";
            echo $value["date_created"];
            echo "</td></tr></div>";
            echo "<tr><td colspan='2'><a href='index.php?target=video&action=getById&id=" . $value["video_id"] . "'><img width='200px' src='";
            echo $value["thumbnail_url"];
            echo "'></a></td></tr>";
            echo "<tr><td>";
            echo $value["title"];
            echo "</td></tr>";
            echo "<tr><td>";
            echo $value["date_uploaded"];
            echo "</td></tr>";
        }
    }
    ?>
</table>
</body>
</html>
