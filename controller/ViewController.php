<?php


namespace controller;


use model\VideoDAO;

class ViewController{
    public function viewRouter($view) {
        if ($view== 'upload'){
            try {
                $dao = VideoDAO::getInstance();
                $categories = $dao->getCategories();
            }
            catch (\PDOException $e){
                include_once "view/main.php";
                echo "Error!";
            }
        }
        include_once "view/$view.php";
    }
}