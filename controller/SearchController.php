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
                $videos = SearchDAO::getSearchedVideos($searchQuery);
                $playlists = SearchDAO::getSearchedPlaylists($searchQuery);
                $users = SearchDAO::getSearchedUsers($searchQuery);
                include_once "view/main.php";
                }
                else{
                $videos = VideoDAO::getAll();
                $playlists = PlaylistDAO::getAll();
                $users = UserDAO::getAll();
                include_once "view/main.php";
                }
            }
        }
}