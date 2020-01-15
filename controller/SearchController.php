<?php


namespace controller;


use exceptions\InvalidArgumentException;
use model\SearchDAO;
use model\UserDAO;

class SearchController{
    public function search(){
        if(isset($_POST['search'])){
            if(!empty($_POST['search_query'])){
                $searchQuery = htmlentities($_POST['search_query']);
                $dao = SearchDAO::getInstance();
                $videos = $dao->getSearchedVideos($searchQuery);
                $playlists = $dao->getSearchedPlaylists($searchQuery);
                $users = $dao->getSearchedUsers($searchQuery);
                include_once "view/main.php";
            }
            else {
                $playlistdao = SearchDAO::getInstance();
                $videos = $playlistdao->getAllVideos();
                $playlists = $playlistdao->getAllPlaylists();
                $users = $playlistdao->getAllUsers();
                include_once "view/main.php";
            }
        }
        else {
            throw new InvalidArgumentException("Invalid arguments.");
        }
    }
}