<?php


namespace model;

include_once "BaseDao.php";
use PDO;
use PDOException;

class PlaylistDAO {
    public static function getAll($userid) {
        try {
            $pdo = getPDO();
            $sql = "SELECT id, playlist_title, owner_id, date_created FROM playlists WHERE owner_id = ?;";
            $stmt = $pdo->prepare($sql);
            $stmt->execute(array($userid));
            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $rows;
        } catch(PDOException $e){
            return false;
        }
    }

    public static function create(Playlist $playlist){
        try{
            $title = $playlist->getTitle();
            $owner_id = $playlist->getOwnerId();
            $date_created = $playlist->getDateCreated();
            $pdo = getPDO();
            $sql = "INSERT INTO playlists (playlist_title, owner_id, date_created) VALUES (?, ?, ?);";
            $params = [];
            $params[] = $title;
            $params[] = $owner_id;
            $params[] = $date_created;
            $stmt = $pdo->prepare($sql);
            $stmt->execute($params);
            $playlist_id = $pdo->lastInsertId();
            $playlist->setId($playlist_id);
            return true;
        }catch(PDOException $e){
            return $e->getMessage();
        }
    }
    public static function delete(Playlist $playlist){
        try{
        $playlist_id = $playlist->getId();
        $owner_id = $playlist->getOwnerId();
        $pdo = getPDO();
        $sql = "DELETE FROM playlists WHERE id = ? AND owner_id = ?;";
        $params = [];
        $params[] = $playlist_id;
        $params[] = $owner_id;
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        return true;
        } catch(PDOException $e){
            return $e->getMessage();
        }
    }
    public static function getVideosFromPlaylist($playlist_id){
        try{
            $pdo = getPDO();
            $sql = "SELECT v.id, v.title, v.date_uploaded, p.playlist_title, u.username, v.thumbnail_url FROM videos AS v 
                    JOIN users AS u ON v.owner_id = u.id
                    JOIN added_to_playlist AS atp ON v.id = atp.video_id
                    JOIN playlists AS p ON p.id = atp.playlist_id
                    WHERE atp.playlist_id = ?;";
            $stmt = $pdo->prepare($sql);
            $stmt->execute(array($playlist_id));
            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $rows;
        } catch(PDOException $e){
            return false;
        }
    }
}