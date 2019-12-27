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

    public static function edit(Video $video){
        $title = $video->getTitle();
        $description = $video->getDescription();
        $category_id = $video->getCategoryId();
        $thumbnail_url = $video->getThumbnailUrl();
        $id = $video->getId();
        try {
            $pdo = getPDO();
            $sql = "UPDATE videos
                SET title = ?, description = ?, category_id = ?, thumbnail_url = ?
                WHERE id = ?;";
            $params = [];
            $params[] = $title;
            $params[] = $description;
            $params[] = $category_id;
            $params[] = $thumbnail_url;
            $params[] = $id;
            $stmt = $pdo->prepare($sql);
            $stmt->execute($params);
            return true;
        }
        catch (PDOException $e) {
            return $e->getMessage();
        }
    }

    public static function delete($id, $owner_id){
        try {
            $pdo = getPDO();
            $sql = "DELETE FROM videos WHERE id = ? AND owner_id = ?;";
            $stmt = $pdo->prepare($sql);
            $stmt->execute(array($id, $owner_id));
            return true;
        } catch (PDOException $e) {
            return false;
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
            $sql = "SELECT v.id, v.title, v.description, v.date_uploaded, v.owner_id, v.category_id, v.video_url, v.duration, v.thumbnail_url, 
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