<?php
namespace controller;

use \model\User;
use \model\UserDAO;
use model\VideoDAO;

class UserController {
    public function login()
    {
        //TODO some more validations
        if (isset($_POST['login'])) {
            if (isset($_POST['email']) && isset($_POST['password'])) {
                $email = $_POST['email'];
                $password = $_POST['password'];
                $user = UserDAO::checkUser($email);
                if (!$user) {
                    echo "Invalid password or email! Try again.";
                    include_once "view/login.php";
                } else {
                    if (password_verify($password, $user['password'])) {
                        $user['full_name'] = $user['name'];
                        $_SESSION['logged_user'] = $user;
                        include_once "view/main.php";
                        echo "Successful login! <br>";
                    } else {
                        echo 'Invalid email or password.Try again.';
                        include_once "view/login.php";
                    }
                }
            }
        }
}
    public function register()
    {
        if (isset($_POST['register'])) {
            //TODO more validations
            if (isset($_POST['username']) && isset($_POST['full_name']) && isset($_POST['email'])
                && isset($_POST['password']) && isset($_POST['cpassword'])) {
                if(UserDAO::checkUser($_POST['email'])){
                    echo "User with that email already exists";
                    include_once "view/register.php";
                }else{
                    $username = $_POST['username'];
                    $email = $_POST['email'];
                    $full_name = $_POST['full_name'];
                    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
                    $registration_date = date("Y-m-d H:i:s");
                    $avatar_url = $this->uploadFile("avatar", $_POST['username']);
                    $user = new User($username, $email, $password, $full_name, $registration_date, $avatar_url);
                    UserDAO::registerUser($user);
                    $arrayUser = [];
                    $arrayUser['username'] = $user->getUsername();
                    $arrayUser['full_name'] = $user->getFullName();
                    $arrayUser['password'] = $user->getPassword();
                    $arrayUser['email'] = $user->getEmail();
                    $arrayUser['id'] = $user->getId();
                    $_SESSION['logged_user'] = $arrayUser;
                    include_once "view/main.php";
                    echo "Successful registration!<br>";
                }

            }
        }
    }
    public function edit(){
        if(isset($_POST['edit'])){
        if (!isset($_SESSION["logged_user"])) {
            header("Location: index.php");
        }
        if(isset($_POST['username']) && isset($_POST['email']) && isset($_POST['full_name'])){
            $password = $_SESSION['logged_user']['password'];
            if(isset($_POST['password']) && !empty($_POST['password'])){
                if(password_verify($_POST['password'], $password)){
                    if(isset($_POST['new_password']) && isset($_POST['cpassword'])){
                        $newAvatar = $this->uploadFile("avatar", $_POST['username']);
                        $password = password_hash($_POST['new_password'], PASSWORD_BCRYPT);
                        $email = $_SESSION['logged_user']['email'];
                        $user = new User($_POST['username'], $email, $password, $_POST['full_name'],null, $newAvatar);
                        $user->setId($_SESSION['logged_user']['id']);
                        UserDAO::editUser($user);
                        $userArray['username'] = $user->getUsername();
                        $userArray['email'] = $user->getEmail();
                        $userArray['password'] = $user->getPassword();
                        $userArray['full_name'] = $user->getFullName();
                        $userArray['id'] = $user->getId();
                        $_SESSION['logged_user'] = $userArray;
                        include_once "view/main.php";
                        echo "Profile is changed successfully!";
                    }
                }else{
                    include_once "view/main.php";
                    echo "The password is incorrect!";
                    }
                }
            }
        }
    }
    public function uploadFile($file, $username){
        if (is_uploaded_file($_FILES[$file]["tmp_name"])) {
            $file_name_parts = explode(".", $_FILES[$file]["name"]);
            $extension = $file_name_parts[count($file_name_parts) - 1];
            $filename = $username . "-" . time() . "." . $extension;
            $file_url = "uploads" . DIRECTORY_SEPARATOR . $filename;
            if (move_uploaded_file($_FILES[$file]["tmp_name"], $file_url)){
                return $file_url;
            }
        }
        return false;
    }
    public function logout(){
        unset($_SESSION);
        session_destroy();
        header("Location: index.php?view=login");
        exit;
    }

    public function getById($id=null){
        if (isset($_GET["id"])) {
            $id = $_GET["id"];
        }
        $user = UserDAO::getById($id);
        $videos = VideoDAO::getByOwnerId($id);
        include_once "view/profile.php";
    }

    public function isFollowing($followed_id=null){
        if (isset($_GET["id"])){
            $followed_id = $_GET["id"];
        }
        $follower_id = $_SESSION["logged_user"]["id"];
        return UserDAO::isFollowing($follower_id, $followed_id);
    }

    public function follow($followed_id=null){
        if (isset($_GET["id"])){
            $followed_id = $_GET["id"];
        }
        $follower_id = $_SESSION["logged_user"]["id"];
        UserDAO::followUser($follower_id, $followed_id);
    }

    public function unfollow($followed_id=null){
        if (isset($_GET["id"])){
            $followed_id = $_GET["id"];
        }
        $follower_id = $_SESSION["logged_user"]["id"];
        UserDAO::unfollowUser($follower_id, $followed_id);
    }

    public function isReacting($user_id=null, $video_id=null){
        if (isset($_GET["id"])){
            $video_id = $_GET["id"];
        }
        $user_id = $_SESSION["logged_user"]["id"];
        return UserDAO::isReacting($user_id, $video_id);
    }

    public function reactVideo($video_id=null, $status=null){
        if (isset($_GET["id"]) && isset($_GET["status"])){
            $video_id = $_GET["id"];
            $status = $_GET["status"];
        }
        $user_id = $_SESSION["logged_user"]["id"];
        $isReacting = $this->isReacting($user_id, $video_id);
        if ($isReacting === false) {//if there has been no reaction
            UserDAO::reactVideo($user_id, $video_id, $status);
        }
        elseif ($isReacting == $status){ //if liking liked or unliking unliked video
            UserDAO::unreactVideo($user_id, $video_id);
        }
        elseif ($isReacting != $status){ //if liking disliked or disliking liked video
            UserDAO::unreactVideo($user_id, $video_id);
            UserDAO::reactVideo($user_id, $video_id, 1-$isReacting);
        }
        return "Tuka ima tekst!";
    }
}
