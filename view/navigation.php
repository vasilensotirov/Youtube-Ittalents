<?php
if(isset($_SESSION['logged_user'])){
    $user_id = $_SESSION['logged_user']['id'];
}
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
<nav>
    <ul>
    <li><a href="index.php"><img src="styles/images/homeLogo.png" class="navPics">Home</a></li>
    <li><a href="index.php?view=trending"><img src="styles/images/trendingLogo.png" class="navPics"">Trending</a></li>
    <li><a href="index.php?view=subscriptions"><img src="styles/images/subscriptionsLogo.png" class="navPics">Subscriptions</a></li>
    <hr>
    <li><a href="index.php?view=library"><img src="styles/images/libraryLogo.png" class="navPics">Library</a></li>
    <li><a href="index.php?view=history"><img src="styles/images/historyLogo.png" class="navPics">History</a></li>
    <li><a href="index.php?view=watchlater"><img src="styles/images/watchlaterLogo.png" class="navPics">Watch Later</a></li>
    <li><a href="index.php?view=likedvideos"><img src="styles/images/likedLogo.png" class="navPics">Liked videos</a></li>
    <li><a href="index.php?view=playlists"><img src="styles/images/playlistLogo.png" class="navPics">Playlists</a></li>
    </ul>
</nav>
</body>
</html>
