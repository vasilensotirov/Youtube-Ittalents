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
    <script type="text/javascript" src="view/script.js"></script>
    <title>Document</title>
</head>
<body>
<?php
require_once "header.php";
require_once "navigation.php";
?>
<main>
    <?php
    if (isset($video)) {
        echo "<video width='854' height='480' controls autoplay>
        <source src='" . $video["video_url"] . "' type='video/mp4'></video><br>";
        echo $video["title"] . "<br>";
        echo $video["date_uploaded"] . "<br>";
        echo "<h3>Views: " . $video['views'] . "</h3><br>";
        echo $video["name"] . "<br>";
        echo "<a href='index.php?target=user&action=getById&id=" . $video["user_id"] . "'>" . $video["username"] . "</a><br>";
        echo "<button id='showPlaylists' onclick='showMyPlaylists(". $user_id .")'>Add to playlist</button>";
        echo "<table id='playlist-holder'></table>";
        if ($video["owner_id"] == $_SESSION["logged_user"]["id"]){
            echo "<a href='index.php?target=video&action=loadEdit&id=" . $video["id"] . "'><button>Edit video</button></a><br>";
            echo "<a href='index.php?target=video&action=delete&id=" . $video["id"] . "'><button>Delete video</button></a><br>";
        }
        else {
            if ($video["isFollowed"]) {
                echo "<button id='follow-button' onclick='unfollowUser(" . $video["user_id"] . ")'>Unsubscribe</button><br>";
            }
            else {
                echo "<button id='follow-button' onclick='followUser(" . $video["user_id"] . ")'>Subscribe</button><br>";
            }
        }
        echo $video["description"] . "<br>";
    }
    else {
        header("Location:main.php");
    }
    ?>
    </p>
<button id="like" <?php if ($video["isReacting"] == 1) { echo " style='color:blue' "; } else { echo " style='color:gray' "; }?>
        onclick="likeVideo(<?= $video["id"]; ?>)">Like</button>
    (<span id="likes-count"><?= $video["likes"]; ?></span>)
<button id="dislike"  <?php if ($video["isReacting"] == 0) { echo " style='color:blue' "; } else { echo " style='color:gray' "; }?>
        onclick="dislikeVideo(<?= $video["id"]; ?>)">Dislike</button>
    (<span id="dislikes-count"><?= $video["dislikes"]; ?></span>)
    <br>
    Write comment:
    <form method="post" action="index.php?target=video&action=addComment">
        <input type="hidden" id="video_id" name="video_id" value="<?= $video["id"]; ?>" required>
        <input type="hidden" id="owner_id" name="owner_id" value="<?= $_SESSION["logged_user"]["id"]; ?>" required>
        <textarea rows="5" cols="50" id="content" name="content" required></textarea><br>
        <input type="button" onclick="addComment()" name="comment" value="Post comment">
    </form>
        <table id="comments">
        <?php
        if (isset($comments) && $comments){
            foreach ($comments as $comment){
                if (!$comment["likes"]){
                    $comment["likes"] = 0;
                }
                if (!$comment["dislikes"]){
                    $comment["dislikes"] = 0;
                }
                echo "<tr id='comment" . $comment["id"] . "'>";
                echo "<td><img width='50px' src='" . $comment["avatar_url"] . "'></td>";
                echo "<td>" . $comment["name"] . "</td><td>" . $comment["date"] . "</td>";
                echo "<td>" . $comment["content"] . "</td>";
                echo "<td>
                    <button id='like-comment" . $comment["id"] . "' onclick='likeComment(" . $comment["id"] .
                    ")'>Like</button>(<span id='comment" . $comment["id"] . "-likes'>" . $comment["likes"] .
                    "</span>)<button id='dislike-comment" . $comment["id"] . "' onclick='dislikeComment(" . $comment["id"] .
                    ")'>Dislike</button>(<span id='comment" . $comment["id"] . "-dislikes'>" . $comment["dislikes"] . "</span>)";
                if ($comment["owner_id"] == $_SESSION["logged_user"]["id"]){
                    echo "<button id='delete-comment' onclick='deleteComment(" . $comment["id"] .
                        ")'>Delete</button>";
                }
                echo "</td></tr>";
            }
        }
        ?>
        </table>
</main>
</body>
</html>

