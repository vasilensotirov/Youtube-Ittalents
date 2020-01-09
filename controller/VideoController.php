<?php
namespace controller;
include_once "fileHandler.php";

use model\PlaylistDAO;
use model\UserDAO;
use model\Video;
use model\VideoDAO;

class VideoController{
    public function upload(){
        if(isset($_POST["upload"])) {
            $error = false;
            $msg = "";
            if (!isset($_POST["title"]) || empty($_POST["title"])) {
                $msg = "Title not found";
                $error = true;
            }
            if (!isset($_POST["description"]) || empty($_POST["description"])) {
                $msg = "Description not found";
                $error = true;
            }
            if (!isset($_POST["category_id"]) || empty($_POST["category_id"])) {
                $msg = "Category not found";
                $error = true;
            }
            if (!isset($_POST["owner_id"]) || empty($_POST["owner_id"])) {
                $msg = "Owner not found";
                $error = true;
            }
            if (!isset($_FILES["video"]["tmp_name"])) {
                $msg = "Video not found";
                $error = true;
            }
            if ($error) {
                include_once "view/upload.php";
                echo $msg;
            }
            if (!$error) {
                $video = new Video();
                $video->setTitle($_POST["title"]);
                $video->setDescription($_POST["description"]);
                $video->setDateUploaded(date("Y-m-d H:i:s"));
                $video->setOwnerId($_POST["owner_id"]);
                $video->setCategoryId($_POST["category_id"]);
                $video->setDuration(0);
                $video->setVideoUrl(uploadFile("video", $_SESSION["logged_user"]["username"]));
                $video->setThumbnailUrl(uploadFile("thumbnail", $_SESSION["logged_user"]["username"]));
                try {
                    $dao = VideoDAO::getInstance();
                    $dao->add($video);
                    include_once "view/main.php";
                    echo "Upload successfull.";
                }
                catch (\PDOException $e) {
                    include_once "view/upload.php";
                    echo "Error uploading video!";
                }
            }
        }
    }

    public function loadEdit($id=null){
        if (isset($_GET["id"])){
            $id = $_GET["id"];
        }
        try {
            $dao = VideoDAO::getInstance();
            $video = $dao->getById($id);
            $categories = $dao->getCategories();
            include_once "view/editVideo.php";
        }
        catch (\PDOException $e){
            include_once "index.php";
            echo "Error!";
        }
    }

    public function edit($id=null){
        if(isset($_POST["edit"])) {
            $error = false;
            $msg = "";
            if (!isset($_POST["id"]) || empty($_POST["id"])) {
                $msg = "Video not found";
                $error = true;
            }
            if (!isset($_POST["title"]) || empty($_POST["title"])) {
                $msg = "Title not found";
                $error = true;
            }
            if (!isset($_POST["description"]) || empty($_POST["description"])) {
                $msg = "Description not found";
                $error = true;
            }
            if (!isset($_POST["category_id"]) || empty($_POST["category_id"])) {
                $msg = "Category not found";
                $error = true;
            }
            if ($error) {
                echo $msg;
                include_once "view/editVideo.php";
            }
            if (!$error) {
                $video = new Video();
                $video->setId($_POST["id"]);
                $video->setTitle($_POST["title"]);
                $video->setDescription($_POST["description"]);
                $video->setCategoryId($_POST["category_id"]);
                if (isset($_FILES["thumbnail"])) {
                    $video->setThumbnailUrl(uploadFile("thumbnail", $_SESSION["logged_user"]["username"]));
                }
                if ($video->getThumbnailUrl() == false) {
                    $video->setThumbnailUrl($_POST["thumbnail_url"]);
                }
                try {
                    $dao = VideoDAO::getInstance();
                    $dao->edit($video);
                    echo "Edit successfull.";
                    include_once "view/main.php";
                }
                catch (\PDOException $e) {
                    include_once "view/editVideo.php";
                    echo "Error editing video!";
                }
            }
        }
    }

    public function delete($id=null){
        if (isset($_GET["id"])){
            $id = $_GET["id"];
        }
        $user_id = $_SESSION["logged_user"]["id"];
        try {
            $dao = VideoDAO::getInstance();
            $dao->delete($id, $user_id);
            include_once "view/main.php";
            echo "Delete successful.";
        }
        catch (\PDOException $e) {
            include_once "view/main.php";
            echo "Error deleting video!";
        }
    }

    public function getByOwnerId($owner_id=null){
        if (isset($_GET["owner_id"])){
            $owner_id = $_GET["owner_id"];
        }
        else {
            $owner_id = $_SESSION["logged_user"]["id"];
        }
        $orderby = null;
        if (isset($_GET["orderby"])){
            switch ($_GET["orderby"]){
                case "date": $orderby = "ORDER BY date_uploaded";
                    break;
                case "likes": $orderby = "ORDER BY likes";
                    break;
            }
            if (isset($_GET["desc"]) && $orderby){
                $orderby .= " DESC";
            }
        }
        try {
            $dao = VideoDAO::getInstance();
            $videos = $dao->getByOwnerId($owner_id, $orderby);
            $action = "getByOwnerId";
            include_once "view/main.php";
        }
        catch (\PDOException $e){
            include_once "view/main.php";
            echo "Error!";
        }
    }
    
    public function getById($id=null){
        if (isset ($_GET["id"])){
            $id = $_GET["id"];
        }
        try {
            $videodao = VideoDAO::getInstance();
            $userdao = UserDAO::getInstance();
            $videodao->updateViews($id);
            $video = $videodao->getById($id);
            $video["likes"] = $videodao->getReactions($id, 1);
            $video["dislikes"] = $videodao->getReactions($id, 0);
            $comments = $videodao->getComments($id);
            if (isset($_SESSION["logged_user"]["id"])) {
                $user_id = $_SESSION["logged_user"]["id"];
                $userdao->addToHistory($id, $user_id, date("Y-m-d H:i:s"));
                $video["isFollowed"] = $userdao->isFollowing($user_id, $video["owner_id"]);
                $video["isReacting"] = $userdao->isReacting($user_id, $id);
            }
        }
        catch (\PDOException $e){
            echo "<br>Error!" . $e->getMessage();
        }
        include_once "view/video.php";
    }

    public function getAll(){
        $orderby = null;
        if (isset($_GET["orderby"])){
            switch ($_GET["orderby"]){
                case "date": $orderby = "ORDER BY date_uploaded";
                break;
                case "likes": $orderby = "ORDER BY likes";
                break;
            }
            if (isset($_GET["desc"]) && $orderby){
                $orderby .= " DESC";
            }
        }
        $dao = VideoDAO::getInstance();
        $videos = $dao->getAll($orderby);
        $action = "getAll";
        include_once "view/main.php";
    }

    public function addComment(){
        if (isset($_POST["comment"])){
            $content = $_POST["content"];
            if (!$content){
                echo "Content is empty.";
                return;
            }
            $video_id = $_POST["video_id"];
            $owner_id = $_POST["owner_id"];
            $date = (date("Y-m-d H:i:s"));
            try {
                $dao = VideoDAO::getInstance();
                $dao->addComment($video_id, $owner_id, $content, $date);
                header("Location: index.php?target=video&action=getById&id=" . $video_id);
            }
            catch (\PDOException $e){
                header("Location: index.php?target=video&action=getById&id=" . $video_id);
                echo "Error posting comment!";
            }
        }
    }

    public function deleteComment($comment_id=null, $owner_id=null){
        if (isset($_GET["id"])){
            $comment_id = $_GET["id"];
        }
        if (isset($_GET["video_id"])){
            $video_id = $_GET["video_id"];
        }
        $owner_id = $_SESSION["logged_user"]["id"];
        try {
            $dao = VideoDAO::getInstance();
            $dao->deleteComment($comment_id, $owner_id);
            header("Location: index.php?target=video&action=getById&id=" . $video_id);
        }
        catch (\PDOException $e){
            header("Location: index.php?target=video&action=getById&id=" . $video_id);
            echo "Error deleting comment!";
        }
    }

    public function trending(){
        $dao = VideoDAO::getInstance();
        $mostWatchedVideos = $dao->getMostWatched();
        include_once "view/trending.php";
    }

    public function getHistory() {
        if (isset($_SESSION["logged_user"]["id"])){
            $user_id = $_SESSION["logged_user"]["id"];
            $orderby = null;
            if (isset($_GET["orderby"])){
                switch ($_GET["orderby"]){
                    case "date": $orderby = "ORDER BY date_uploaded";
                        break;
                    case "likes": $orderby = "ORDER BY likes";
                        break;
                }
                if (isset($_GET["desc"]) && $orderby){
                    $orderby .= " DESC";
                }
            }
            $dao = VideoDAO::getInstance();
            $videos = $dao->getHistory($user_id, $orderby);
        }
        else {
            echo "<h3>Login to record history!</h3>";
        }
        $action = "getHistory";
        include_once "view/main.php";
    }

    public function getWatchLater() {
        if (isset($_SESSION["logged_user"]["id"])){
            $user_id = $_SESSION["logged_user"]["id"];
            $dao = PlaylistDAO::getInstance();
            $videos = $dao->getWatchLater($user_id);
        }
        else {
            echo "<h3>Login to save videos for watching later!</h3>";
        }
        $action = "getWatchLater";
        include_once "view/main.php";
    }

    public function getLikedVideos() {
        if (isset($_SESSION["logged_user"]["id"])){
            $user_id = $_SESSION["logged_user"]["id"];
            $orderby = null;
            if (isset($_GET["orderby"])){
                switch ($_GET["orderby"]){
                    case "date": $orderby = "ORDER BY date_uploaded";
                        break;
                    case "likes": $orderby = "ORDER BY likes";
                        break;
                }
                if (isset($_GET["desc"]) && $orderby){
                    $orderby .= " DESC";
                }
            }
            $dao = VideoDAO::getInstance();
            $videos = $dao->getLikedVideos($user_id, $orderby);
        }
        else {
            echo "<h3>Login to like videos!</h3>";
        }
        $action = "getLikedVideos";
        include_once "view/main.php";
    }
}