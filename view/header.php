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
<header>
    <a href="index.php?target=video&action=getAll"><img class="youtubeLogo" src="styles/images/youtube_PNG5.png" alt="youtube logo"></a>
    <form id="searchForm" action="index.php?target=search&action=search" method="post">
        <input  type="text" placeholder="Search" name="search_query">
        <input  type="submit" name="search" value="Search">
    </form>
    <a href="index.php?target=view&action=viewRouter&view=upload"><button class="headerButtons">Upload video</button></a>
    <a href="index.php?view=editProfile"><button class="headerButtons">Edit profile</button></a>
    <a href="index.php?view=createPlaylist"><button class="headerButtons">Create playlist</button></a>
    <a href="index.php?target=user&action=logout"><button class="headerButtons">Logout</button></a>
</header>
</body>
</html>
