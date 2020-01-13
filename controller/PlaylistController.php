<?php


namespace controller;

use \model\Playlist;
use \model\PlaylistDAO;
use model\VideoDAO;

class PlaylistController
{
    public function create()
    {
        if (isset($_POST['create'])) {
            $error = false;
            $msg = "";
            if (!isset($_POST['title']) || empty($_POST["title"])) {
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
            if (!$error) {
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
                } catch (\PDOException $e) {
                    include_once "view/createPlaylist.php";
                    echo "Error creating playlist!";
                }
            }
        }
    }

    public function getMyPlaylists()
    {
        if (isset($_SESSION["logged_user"]["id"])){
            $owner_id = $_SESSION["logged_user"]["id"];
            $dao = PlaylistDAO::getInstance();
            $playlists = $dao->getAllByUserId($owner_id);
            include_once "view/playlists.php";
            }
        else {
            include_once "view/playlists.php";
            echo "<h3>Login to view playlists!</h3>";
        }
    }

    public function clickedPlaylist()
    {
        if (isset($_GET['id'])) {
            $playlist_id = $_GET['id'];
        }
        try {
            $dao = PlaylistDAO::getInstance();
            $videos = $dao->getVideosFromPlaylist($playlist_id);
            include_once "view/playlists.php";
        } catch (\PDOException $e) {
            include_once "view/main.php";
            echo "Error!";
        }
    }

    public function addToPlaylist()
    {
        //todo validations
        if (isset($_GET['playlist_id']) && isset($_GET['video_id'])) {
            try {
            $dao = PlaylistDAO::getInstance();
            $playlist_id = $_GET['playlist_id'];
            $video_id = $_GET['video_id'];
            if($dao->existsPlaylist($playlist_id) && $dao->existsVideo($video_id)){
            $date = date("Y-m-d H:i:s");
            $dao->addToPlaylist($playlist_id, $video_id, $date);
            }else{
                include_once "view/main.php";
                echo "No playlist or video with that id!";
            }
            } catch (\PDOException $e) {
                include_once "view/main.php";
                echo "Error!";
            }
        }
    }

    public function getMyPlaylistsJSON()
    {
        if (isset($_GET["owner_id"])) {
            $owner_id = $_GET["owner_id"];
        }
        else {
            if (isset($_SESSION["logged_user"]["id"])){
                $owner_id = $_SESSION["logged_user"]["id"];
            }
        }
        if ($owner_id) {
            try {
                $dao = PlaylistDAO::getInstance();
                $playlists = $dao->getAllByUserId($owner_id);
                echo json_encode($playlists);
            } catch (\PDOException $e) {
                include_once "view/main.php";
                echo "Error!";
            }
        }
        else {
            echo "<h3>Login to view playlists!</h3>";
        }
    }
}