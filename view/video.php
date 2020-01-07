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
        if ($video["owner_id"] == $_SESSION["logged_user"]["id"]){
            echo "<a href='index.php?target=video&action=loadEdit&id=" . $video["id"] . "'><button>Edit video</button></a><br>";
            echo "<a href='index.php?target=video&action=delete&id=" . $video["id"] . "'><button>Delete video</button></a><br>";
        }
        else {
            if ($video["isFollowed"]) {
                echo "<button id='follow-button' onclick='followUser(" . $video["user_id"] . ")'>Follow</button><br>";
            }
            else {
                echo "<button id='follow-button' onclick='unfollowUser(" . $video["user_id"] . ")'>Unfollow</button><br>";
            }
        }
        echo $video["description"] . "<br>";
        echo "<p id='reactstatus' hidden>";
        if ($video["isReacting"] == -1){
            echo "Neutral.";
        }
        if ($video["isReacting"] == 1){
            echo "Liked.";
        }
        elseif ($video["isReacting"] == 0){
            echo "Disliked.";
            }
        echo "</p>";
    }
    else {
        header("Location:main.php");
    }
    ?>
    </p>
<button id="like" onclick="likeVideo(<?= $video["id"]; ?>)">Like</button>
    (<?= $video["likes"]; ?>)
<button id="dislike" onclick="dislikeVideo(<?= $video["id"]; ?>)">Dislike</button>
    (<?= $video["dislikes"]; ?>)
    <br>
    Write comment:
    <form method="post" action="index.php?target=video&action=addComment">
        <input type="hidden" id="video_id" name="video_id" value="<?= $video["id"]; ?>" required>
        <input type="hidden" id="owner_ir" name="owner_id" value="<?= $_SESSION["logged_user"]["id"]; ?>" required>
        <textarea rows="5" cols="50" id="content" name="content" required></textarea><br>
        <input type="submit" name="comment" value="Post comment">
    </form>
    <span id="comments">
        <table>
        <?php
        if (isset($comments) && $comments){
            foreach ($comments as $comment){
                if (!$comment["likes"]){
                    $comment["likes"] = 0;
                }
                if (!$comment["dislikes"]){
                    $comment["dislikes"] = 0;
                }
                echo "<tr>";
                echo "<td rowspan='3'><img width='50px' src='" . $comment["avatar_url"] . "'></td>";
                echo "<td>" . $comment["name"] . "</td><td>" . $comment["date"] . "</td></tr>";
                echo "<td colspan='2'>" . $comment["content"] . "</td>";
                echo "<tr><td>
                    <button id='like' onclick='likeComment(" . $comment["id"] . ")'>Like</button>(" .
                    $comment["likes"] .
                    ")<button id='dislike' onclick='dislikeComment(" . $comment["id"] . ")'>Dislike</button>(" .
                    $comment["dislikes"] . ")";
                echo "<span id='commentreact'></span>";
                if ($comment["owner_id"] == $_SESSION["logged_user"]["id"]){
                    echo "<button><a href='index.php?target=video&action=deleteComment&id=" . $comment["id"] .
                        "&video_id=" . $video["id"] . "'>Delete</a></button>";
                }
                echo "</td></tr></tr>";
            }
        }
        ?>
        </table>
    </span>
</main>
</body>
</html>

