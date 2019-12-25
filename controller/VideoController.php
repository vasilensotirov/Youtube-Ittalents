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
                echo "Upload successfull.";
                include_once "view/main.php";
                }
                else {
                    echo "File handling error";
                    include_once "view/upload.php";
                }
            }
        }
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
        $video = VideoDAO::getById($id);
        include_once "view/video.php";
    }

    public function getAll(){
        $videos = VideoDAO::getAll();
        include_once "view/main.php";
    }
}