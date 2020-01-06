<?php


namespace controller;


use model\PlaylistDAO;
use model\SearchDAO;
use model\UserDAO;
use model\Video;
use model\VideoDAO;

class SearchController{
    public function search(){
        if(isset($_POST['search'])){
            if(!empty($_POST['search_query'])){
                $searchQuery = htmlentities($_POST['search_query']);
                try {
                    $dao = SearchDAO::getInstance();
                    $videos = $dao->getSearchedVideos($searchQuery);
                    $playlists = $dao->getSearchedPlaylists($searchQuery);
                    $users = $dao->getSearchedUsers($searchQuery);
                    include_once "view/main.php";
                }
                catch (\PDOException $e){
                    include_once "view/main.php";
                    echo "Error!";
                }
            }
            else {
                try {
                    $videodao = VideoDAO::getInstance();
                    $playlistdao = PlaylistDAO::getInstance();
                    $userdao = UserDAO::getInstance();
                    $videos = $videodao->getAll();
                    $playlists = $playlistdao->getAll();
                    $users = $userdao->getAll();
                    include_once "view/main.php";
                }
                catch (\PDOException $e){
                    include_once "view/main.php";
                    echo "Error!";
                }
            }
        }
    }
}