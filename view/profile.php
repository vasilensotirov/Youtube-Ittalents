<?php
if (isset($_SESSION["logged_user"])){
    $user_id = $_SESSION["logged_user"]["id"];
}
else {
    $user_id = null;
}

?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <script type="text/javascript" src="view/script.js"></script>
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

if (isset($user)) {
    echo "<img width=100px src='" . $user["avatar_url"] . "'>";
    echo $user["username"] . "<br>";
    echo "Name: " . $user["name"] . "<br>";
    echo "Registered on: " . $user["registration_date"] . "<br>";
    if ($user["id"] == $user_id){
        echo "<a href='index.php?view=editProfile'><button>Edit profile</button></a><br>";
    }
    elseif ($user_id) {
        if ($user["isFollowed"]) {
            echo "<button id='follow-button' onclick='unfollowUser(" . $user["id"] . ")'>Unsubscribe</button><br>";
        }
        else {
            echo "<button id='follow-button' onclick='followUser(" . $user["id"] . ")'>Subscribe</button><br>";
        }
    }
    echo "<h3>Videos:</h3>";
}
else {
    header("Location:main.php");
}
if (isset($videos)) {
    if ($videos) {
        echo "<table>";
        foreach ($videos as $video) {
            if (!$video["thumbnail_url"]) {
                $video["thumbnail_url"] = 'https://therisingnetwork.com/wp-content/plugins/video-thumbnails/default.jpg';
            }
            echo "<tr><td><a href='index.php?target=video&action=getById&id=" . $video["id"] . "'><img width='200px' src='";
            echo $video["thumbnail_url"];
            echo "'></a></td></tr>";
            echo "<tr><td><b>";
            echo $video["title"];
            echo "</b></td></tr>";
            echo "<tr><td>";
            echo $video["username"];
            echo "</td></tr>";
            echo "<tr><td>";
            echo $video["views"];
            echo " views</td></tr>";
            echo "<tr><td>";
            echo $video["date_uploaded"];
            echo "</td></tr>";
        }
        echo "</table>";
    }
    else {
        echo "<h3>No videos to show.</h3>";
    }
}
?>
</body>
</html>
