<?php


namespace model;
include_once "BaseDao.php";
use PDO;
use PDOException;

class SearchDAO{
    public static function getSearchedVideos($search_query){
        try{
        $pdo = getPDO();
        $sql = "SELECT v.id, v.title, v.date_uploaded, u.username, v.thumbnail_url FROM videos AS v 
                    JOIN users AS u ON v.owner_id = u.id WHERE v.title = ?;";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(array($search_query, $search_query, $search_query));
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        if (empty($rows)) {
            return false;
        } else {
            return $rows;
        }
        } catch(PDOException $e){
            echo $e->getMessage();
        }
    }
}