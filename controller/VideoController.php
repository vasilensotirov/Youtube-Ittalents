<?php
namespace controller;
include_once "fileHandler.php";

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
                echo $msg;
                include_once "view/upload.php";
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
                if(VideoDAO::add($video) === true){
                include_once "view/main.php";
                    echo "Upload successfull.";
                }
                else {
                    include_once "view/upload.php";
                    echo "File handling error";
                }
            }
        }
    }

    public function loadEdit($id=null){
        if (isset($_GET["id"])){
            $id = $_GET["id"];
        }
        $video = VideoDAO::getById($id);
        include_once "view/editVideo.php";
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
                if (VideoDAO::edit($video) === true){
                    echo "Edit successfull.";
                    include_once "view/main.php";
                }
                else {
                    echo "Error";
                    include_once "view/editVideo.php";
                }
            }
        }
        include_once "view/main.php";
    }

    public function delete($id=null){
        if (isset($_GET["id"])){
            $id = $_GET["id"];
        }
        $user_id = $_SESSION["logged_user"]["id"];
        if (VideoDAO::delete($id, $user_id)){
            echo "Delete successful.";
        }
        else {
            echo "Error!";
        }
        include_once "view/main.php";
    }

    public function getByOwnerId($owner_id=null){
        if (isset($_GET["owner_id"])){
            $owner_id = $_GET["owner_id"];
        }
        $videos = VideoDAO::getByOwnerId($owner_id);
        include_once "view/main.php";
    }
    
    public function getById($id=null){
        if (isset ($_GET["id"])){
            $id = $_GET["id"];
        }
        $user_id = $_SESSION["logged_user"]["id"];
        $video = VideoDAO::getById($id);
        $video["isFollowed"] = UserDAO::isFollowing($user_id, $video["owner_id"]);
        include_once "view/video.php";
    }

    public function getAll(){
        $videos = VideoDAO::getAll();
        include_once "view/main.php";
    }
}