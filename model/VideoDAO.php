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
}