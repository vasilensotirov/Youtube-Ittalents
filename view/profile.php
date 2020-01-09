<?php
if (!isset($_SESSION["logged_user"])){
    header("Location:login.php");
}
//echo "Hello " .$_SESSION['logged_user']['username'].", you are in the main page";
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

if (isset($user)) {
    echo "<img width=100px src='" . $user["avatar_url"] . "'>";
    echo $user["username"] . "<br>";
    echo "Name: " . $user["name"] . "<br>";
    echo "Registered on: " . $user["registration_date"] . "<br>";
    if ($user["id"] == $_SESSION["logged_user"]["id"]){
        echo "<a href='index.php?view=editProfile'><button>Edit profile</button></a><br>";
    }
    echo "<h3>Videos:</h3>";
}
else {
    header("Location:main.php");
}
if (isset($videos)) {
    echo "<table>";
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
    echo "</table>";
}
?>
</body>
</html>
