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
                    echo $e->getMessage();
                }
            }
            else {
                try {
                    $playlistdao = SearchDAO::getInstance();
                    $videos = $playlistdao->getAllVideos();
                    $playlists = $playlistdao->getAllPlaylists();
                    $users = $playlistdao->getAllUsers();
                    include_once "view/main.php";
                }
                catch (\PDOException $e){
                    include_once "view/main.php";
                    echo $e->getMessage();
                }
            }
        }
    }
}