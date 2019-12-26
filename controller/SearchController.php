<?php


namespace controller;


use model\SearchDAO;
use model\Video;
use model\VideoDAO;

class SearchController{
    public function search(){
        if(isset($_POST['search'])){
            if(!empty($_POST['search_query'])){
                $searchQuery = htmlentities($_POST['search_query']);
                $videos = SearchDAO::getSearchedVideos($searchQuery);
                if(!$videos){
                    include_once "view/main.php";
                    echo "No videos with that name.";
                }else{
                    include_once "view/main.php";
                }
            }else{
                $videos = VideoDAO::getAll();
                include_once "view/main.php";
            }
        }
    }
}