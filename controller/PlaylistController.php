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
                if(PlaylistDAO::create($playlist) === true){
                    include_once "view/playlists.php";
                    echo "Created successfully!";
                }else{
                    include_once "view/createPlaylist.php";
                    echo "Try again!";
                }
            }
        }
    }
    public function getMyPlaylists(){
        if (isset($_GET["owner_id"])){
            $owner_id = $_GET["owner_id"];
        }
        $playlists = PlaylistDAO::getAll($owner_id);
        include_once "view/playlists.php";
    }
}