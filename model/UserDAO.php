<?php
namespace model;
include_once "BaseDao.php";
use PDO;
use PDOException;
class UserDAO
{
    public static function checkUser($email)
    {
        try {
            $pdo = getPDO();
            $sql = "SELECT * FROM users WHERE email = ?";
            $stmt = $pdo->prepare($sql);
            $stmt->execute(array($email));
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            if (empty($row)) {
                return false;
            } else {
                return $row;
            }
        } catch (PDOException $e) {
            echo "Something went wrong" . $e->getMessage();
        }
    }

    public static function registerUser(User $user)
    {
        $username = $user->getUsername();
        $email = $user->getEmail();
        $password = $user->getPassword();
        $full_name = $user->getFullName();
        $date = $user->getRegistrationDate();
        $avatar_url = $user->getAvatarUrl();
        try {
            $pdo = getPDO();
            $sql = "INSERT INTO users (username,  email, password, name, registration_date, avatar_url)
                   VALUES (?, ?, ?, ?, ?, ?)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute(array($username, $email, $password, $full_name, $date, $avatar_url));
            $user->setId($pdo->lastInsertId());
            if ($pdo->lastInsertId() > 0) {
                return true;
            } else {
                return false;
            }
        } catch (PDOException $e) {
            echo "Something went wrong" . $e->getMessage();
        }
    }

    public static function getById($id){
        try{
            $pdo = getPDO();
            $sql = "SELECT username, name, registration_date, avatar_url FROM users WHERE id = ?;";
            $stmt = $pdo->prepare($sql);
            $stmt->execute(array($id));
            $rows = $stmt->fetch(PDO::FETCH_ASSOC);
            return $rows;
        }
        catch (PDOException $e){
            return false;
        }
    }
    public static function editUser(User $user)
    {
        try {
            $username = $user->getUsername();
            $email  = $user->getEmail();
            $password   = $user->getPassword();
            $full_name = $user->getFullName();
            $avatar_url = $user->getAvatarUrl();
            $id = $user->getId();
            $pdo = getPDO();
            $sql = "UPDATE users SET username = ? , email = ?, password = ?, name = ?, avatar_url = ? WHERE id=?;";
            $stmt = $pdo->prepare($sql);
            $stmt->execute(array($username, $email, $password, $full_name, $avatar_url, $id));
            return true;
        } catch (PDOException $e) {
            echo $e->getMessage();
            return false;
        }
    }

    public static function followUser($follower_id, $followed_id){
        try {
            $pdo = getPDO();
            $sql = "INSERT INTO users_follow_users (follower_id, followed_id)
                   VALUES (?, ?)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute(array($follower_id, $followed_id));
                return true;
        } catch (PDOException $e) {
            echo "Something went wrong" . $e->getMessage();
        }
    }

    public static function unfollowUser($follower_id, $followed_id){
        try {
            $pdo = getPDO();
            $sql = "DELETE FROM users_follow_users WHERE follower_id = ? AND followed_id = ?;";
            $stmt = $pdo->prepare($sql);
            $stmt->execute(array($follower_id, $followed_id));
            return true;
        } catch (PDOException $e) {
            echo "Something went wrong" . $e->getMessage();
        }
    }

    public static function isFollowing($follower_id, $followed_id){
        try{
            $pdo = getPDO();
            $sql = "SELECT followed_id FROM users_follow_users WHERE follower_id = ? AND followed_id = ?;";
            $stmt = $pdo->prepare($sql);
            $stmt->execute(array($follower_id, $followed_id));
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($row){
                return true;
            }
            else {
                return false;
            }
        }
        catch (PDOException $e){
            return $e->getMessage();
        }
    }
}