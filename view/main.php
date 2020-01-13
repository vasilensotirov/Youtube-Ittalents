<?php
if (!isset($_SESSION["logged_user"])){
    header("Location:login.php");
}
$user_id = $_SESSION["logged_user"]["id"];
require_once "header.php";
require_once "navigation.php";
?>
<main>
<?php
if (isset($orderby)){
    include_once "orderby.php";
}
?>

<table>
    <?php
    if (isset($videos)) {
        if($videos){
        foreach ($videos as $video) {
            echo "<tr aria-colspan='2'><td><a href='index.php?target=video&action=getById&id=" . $video["id"] . "'><img width='200px' src='";
            if (!$video["thumbnail_url"]){
                $video["thumbnail_url"] = 'https://therisingnetwork.com/wp-content/plugins/video-thumbnails/default.jpg';
            }
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
        }
        else {
            echo "<h3>No videos to show.</h3>";
        }
    }
    if(isset($playlists)){
        if($playlists){
            foreach ($playlists as $playlist){
                echo "<tr><td><a style='text-decoration: none' href='index.php?target=playlist&action=clickedPlaylist&id=" . $playlist["id"] ."'><b>" . $playlist["playlist_title"]. "</b></a></td></tr>" . "<br>";
                echo "<tr><td>";
                echo $playlist["date_created"]. "<br>";
                echo "</td></tr>";
            }
        }
        else {
            echo "<h3>No playlists.</h3>";
        }
    }
    if(isset($users)){
        if($users){
            foreach ($users as $user) {
                echo "<tr><td><img style='border-radius: 50%;' alt='No photo' width='100px' src='";
                echo $user['avatar_url'];
                echo "'></td>";
                echo "<td><b>";
                echo $user['username'];
                echo "</b></td></tr>";
                echo "<tr><td>";
                echo $user['registration_date'];
                echo "</td></tr>";
            }
        }
        else {
            echo "<h3>You don't have active subscriptions.</h3>";
        }
    }
    ?>
</table>
</main>