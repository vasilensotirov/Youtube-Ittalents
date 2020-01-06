<?php


namespace controller;

use \model\Playlist;
use \model\PlaylistDAO;
use model\VideoDAO;

class PlaylistController {
    public function create(){
        if(isset($_POST['create'])){
            $error = false;
            $msg = "";
            if(!isset($_POST['title']) || empty($_POST["title"])) {
                $msg = "Title not found";
                $error = true;
            }
            if (!isset($_POST["owner_id"]) || empty($_POST["owner_id"])) {
                $msg = "Owner not found";
                $error = true;
            }
            if ($error) {
                echo $msg;
                include_once "view/createPlaylist.php";
            }
            if(!$error){
                $playlist = new Playlist();
                $title = $_POST['title'];
                $owner_id = $_POST['owner_id'];
                $date_created = date("Y-m-d H:i:s");
                $playlist->setTitle($title);
                $playlist->setOwnerId($owner_id);
                $playlist->setDateCreated($date_created);
                try {
                    $dao = PlaylistDAO::getInstance();
                    $dao->create($playlist);
                    include_once "view/playlists.php";
                    echo "Created successfully!";
                }
                catch (\PDOException $e){
                    include_once "view/createPlaylist.php";
                    echo "Error creating playlist!";
                }
            }
        }
    }

    public function getMyPlaylists(){
        if (isset($_GET["owner_id"])){
            $owner_id = $_GET["owner_id"];
        }
        try {
            $dao = PlaylistDAO::getInstance();
            $playlists = $dao->getAllByUserId($owner_id);
            include_once "view/playlists.php";
        }
        catch (\PDOException $e){
            include_once "view/main.php";
            echo "Error!";
        }
    }

    public function clickedPlaylist(){
        if(isset($_GET['id'])){
            $playlist_id  = $_GET['id'];
        }
        try {
            $dao = PlaylistDAO::getInstance();
            $videos = $dao->getVideosFromPlaylist($playlist_id);
            include_once "view/playlists.php";
        }
        catch (\PDOException $e){
            include_once "view/main.php";
            echo "Error!";
        }
    }
}