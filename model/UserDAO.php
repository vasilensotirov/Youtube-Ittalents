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
}