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
?>
<!--<br>-->
<!--<a href="index.php"><button>Home</button></a>-->
<!--<a href="index.php?target=user&action=logout"><button>Logout</button></a>-->
<!--<br>-->
<!--<a href="index.php?target=view&action=viewRouter&view=upload"><button>Upload video</button></a>-->
<!--<br>-->
    <?php
    if (isset($video)) {
        echo "<video width='470' height='255' controls>
        <source src='" . $video["video_url"] . "' type='video/mp4'></video><br>";
        echo $video["title"] . "<br>";
        echo $video["date_uploaded"] . "<br>";
        echo $video["name"] . "<br>";
        echo "<a href='index.php?target=user&action=getById&id=" . $video["user_id"] . "'>" . $video["username"] . "</a><br>";
        echo $video["description"] . "<br>";
        if ($video["owner_id"] == $_SESSION["logged_user"]["id"]){
            echo "
    <a href='index.php?target=video&action=loadEdit&id=" . $video["id"] . "'><button>Edit video</button></a>";
        }
    }
    else {
        header("Location:main.php");
    }
    ?>
</body>
</html>

