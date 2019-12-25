<?php
namespace model;
include_once "BaseDao.php";
use PDO;
use PDOException;
class VideoDAO{
    public static function add(Video $video){
        $title = $video->getTitle();
        $description = $video->getDescription();
        $date_uploaded = $video->getDateUploaded();
        $owner_id = $video->getOwnerId();
        $category_id = $video->getCategoryId();
        $video_url = $video->getVideoUrl();
        $duration = $video->getDuration();
        $thumbnail_url = $video->getThumbnailUrl();
        try {
            $pdo = getPDO();
            $sql = "INSERT INTO videos
                (title, description, date_uploaded, owner_id, category_id, video_url, duration, thumbnail_url)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?);";
            $params = [];
            $params[] = $title;
            $params[] = $description;
            $params[] = $date_uploaded;
            $params[] = $owner_id;
            $params[] = $category_id;
            $params[] = $video_url;
            $params[] = $duration;
            $params[] = $thumbnail_url;
            $stmt = $pdo->prepare($sql);
            $stmt->execute($params);
            $video_id = $pdo->lastInsertId();
            $video->setId($video_id);
            return true;
        }
        catch (PDOException $e) {
            return $e->getMessage();
        }
    }
    
    public static function getByOwnerId($owner_id){
        try {
            $pdo = getPDO();
            $sql = "SELECT v.id, v.title, v.date_uploaded, u.username, v.thumbnail_url FROM videos AS v 
                    JOIN users AS u ON v.owner_id = u.id 
                    WHERE owner_id = ?;";
            $params = [];
            $params[] = $owner_id;
            $stmt = $pdo->prepare($sql);
            $stmt->execute($params);
            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $rows;
        }
        catch (PDOException $e){
            return false;
        }
    }

    public static function getById($id){
        try{
            $pdo = getPDO();
            $sql = "SELECT v.title, v.description, v.date_uploaded, v.owner_id, v.category_id, v.video_url, v.duration, v.thumbnail_url, 
                    u.id AS user_id, u.username, u.name FROM videos AS v
                    JOIN users AS u ON v.owner_id = u.id
                    WHERE v.id = ?;";
            $stmt = $pdo->prepare($sql);
            $stmt->execute(array($id));
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            return $row;
        }
        catch (PDOException $e){
            return false;
        }
    }

    public static function getAll(){
        try {
            $pdo = getPDO();
            $sql = "SELECT v.id, v.title, v.date_uploaded, u.username, v.thumbnail_url FROM videos AS v 
                    JOIN users AS u ON v.owner_id = u.id;";
            $stmt = $pdo->prepare($sql);
            $stmt->execute();
            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $rows;
        }
        catch (PDOException $e){
            return false;
        }
    }
}