<?php
namespace controller;

use exceptions\InvalidArgumentException;
use exceptions\InvalidFileException;
use model\User;
use model\UserDAO;
use model\VideoDAO;

class UserController {
    public function login()
    {
        if (isset($_POST['login'])) {
            if (isset($_POST['email']) && isset($_POST['password'])) {
                $email = $_POST['email'];
                $password = $_POST['password'];
            }
            if (!isset($email) || empty($email) || !isset($password) || empty($password)){
                throw new InvalidArgumentException("Invalid arguments.");
            }
                $dao = UserDAO::getInstance();
                $user = $dao->checkUser($email);
                if (!$user) {
                    echo "<p style='text-align: center;'>Invalid password or email! Try again.</p>";
                    include_once "view/login.php";
                } else {
                    if (password_verify($password, $user['password'])) {
                        $user['full_name'] = $user['name'];
                        $_SESSION['logged_user'] = $user;
                        header ("Location:index.php");
                        echo "Successful login! <br>";
                    } else {
                        echo 'Invalid email or password.Try again.';
                        include_once "view/login.php";
                    }
                }
        }
    }

    public function register()
    {
        if (isset($_POST['register'])) {
            if (isset($_POST['username']) && isset($_POST['full_name']) && isset($_POST['email'])
                && isset($_POST['password']) && isset($_POST['cpassword'])) {
                $username = $_POST['username'];
                $email = $_POST['email'];
                $full_name = $_POST['full_name'];
                $cpassword = $_POST['cpassword'];
                $msg = $this->registerValidator($username, $email, $_POST['password'], $cpassword);
                $dao = UserDAO::getInstance();
                $user = $dao->checkUser($email);
                if ($user) {
                    echo "<p style='text-align: center;'>User with that email already exists</p>";
                    include_once "view/login.php";
                }
                elseif ($msg != '') {
                    echo "<p style='text-align: center;'>$msg</p>";
                    include_once "view/register.php";
                }
                else{
                    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
                    $registration_date = date("Y-m-d H:i:s");
                    $avatar_url = $this->uploadImage("avatar", $_POST['username']);
                    $user = new User($username, $email, $password, $full_name, $registration_date, $avatar_url);
                    $dao = UserDAO::getInstance();
                    $dao->registerUser($user);
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
                header("Location:index.php");
            }
            if(isset($_POST['username']) && isset($_POST['email']) && isset($_POST['full_name'])){
                $password = $_SESSION['logged_user']['password'];
                //TODO remove password from session!
                //TODO throw exceptions instead of nested ifs
                if(isset($_POST['password']) && !empty($_POST['password'])){
                    if(password_verify($_POST['password'], $password)){
                        if(isset($_POST['new_password']) && isset($_POST['cpassword'])) {
                            $newAvatar = $this->uploadImage("avatar", $_POST['username']);
                            $password = password_hash($_POST['new_password'], PASSWORD_BCRYPT);
                            $email = $_SESSION['logged_user']['email'];
                            $user = new User($_POST['username'], $email, $password, $_POST['full_name'], null, $newAvatar);
                            $user->setId($_SESSION['logged_user']['id']);
                            $dao = UserDAO::getInstance();
                            $dao->editUser($user);
                            $userArray['username'] = $user->getUsername();
                            $userArray['email'] = $user->getEmail();
                            $userArray['password'] = $user->getPassword();
                            $userArray['full_name'] = $user->getFullName();
                            $userArray['id'] = $user->getId();
                            $_SESSION['logged_user'] = $userArray;
                            include_once "view/main.php";
                            echo "Profile is changed successfully!";
                        }
                    }
                    else {
                        include_once "view/main.php";
                        echo "The password is incorrect!";
                    }
                }
            }
        }
    }

    public function uploadImage($file, $username){
        if (is_uploaded_file($_FILES[$file]["tmp_name"])) {$finfo = finfo_open(FILEINFO_MIME_TYPE);
            $mime = finfo_file($finfo, $_FILES[$file]["tmp_name"]);
            if (!(in_array($mime, array ('image/bmp', 'image/jpeg', 'image/png')))){
                throw new InvalidFileException ("File is not in supported format.");
            }
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
        header("Location:index.php?target=view&action=viewRouter&view=login");
        exit;
    }

    public function registerValidator($username, $email, $password = null, $cpassword = null){
        $msg = '';
        if(strlen($username) < 8){
            $msg = "Username must be atleast 8 characters! <br>";
        }
        if (!(filter_var($email, FILTER_VALIDATE_EMAIL))) {
            $msg .= " Invalid email. <br> ";
        }
        if($password != null && $cpassword != null){
            if($password === $cpassword){
                if (!(preg_match("#.*^(?=.{8,20})(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*\W).*$#", $password))) {
                    $msg .= " Wrong password input. Password should be at least 8 characters <br>";
                }
            }else{
                $msg .= "Passwords not matching! <br>";
            }
        }
        return $msg;
    }

    public function getById($id=null){
        if (isset($_GET["id"])) {
            $id = $_GET["id"];
        }
        if (empty($id)){
            throw new InvalidArgumentException("Invalid arguments.");
        }
        $userdao = UserDAO::getInstance();
        $videodao = VideoDAO::getInstance();
        $user = $userdao->getById($id);
        $user["id"] = $id;
        if (isset($_SESSION["logged_user"]["id"])) {
            $user["isFollowed"] = $userdao->isFollowing($_SESSION["logged_user"]["id"], $id);
        }
        $videos = $videodao->getByOwnerId($id);
        include_once "view/profile.php";
    }

    public function isFollowing(){
        if (isset($_GET["id"])){
            $followed_id = $_GET["id"];
            $follower_id = $_SESSION["logged_user"]["id"];
        }
        if (empty($follower_id) || empty($followed_id)){
            throw new InvalidArgumentException("Invalid arguments.");
        }
        $dao = UserDAO::getInstance();
        return $dao->isFollowing($follower_id, $followed_id);
    }

    public function follow(){
        if (isset($_GET["id"])){
            $followed_id = $_GET["id"];
            $follower_id = $_SESSION["logged_user"]["id"];
        }
        if (empty($follower_id) || empty($followed_id)){
            throw new InvalidArgumentException("Invalid arguments.");
        }
        $dao = UserDAO::getInstance();
        $dao->followUser($follower_id, $followed_id);
    }

    public function unfollow(){
        if (isset($_GET["id"])){
            $followed_id = $_GET["id"];
            $follower_id = $_SESSION["logged_user"]["id"];
        }
        if (empty($follower_id) || empty($followed_id)){
            throw new InvalidArgumentException("Invalid arguments.");
        }
        $dao = UserDAO::getInstance();
        $dao->unfollowUser($follower_id, $followed_id);
    }

    public function isReacting($user_id=null, $video_id=null){
        if (isset($_GET["id"])){
            $video_id = $_GET["id"];
            $user_id = $_SESSION["logged_user"]["id"];
        }
        if (empty($user_id) || empty($video_id)){
            throw new InvalidArgumentException("Invalid arguments.");
        }
        $dao = UserDAO::getInstance();
        return $dao->isReacting($user_id, $video_id);
    }

    public function reactVideo(){
        if (isset($_GET["id"]) && isset($_GET["status"])){
            $video_id = $_GET["id"];
            $status = $_GET["status"];
        }
        $user_id = $_SESSION["logged_user"]["id"];
        if (empty($video_id) || empty($user_id)){
            throw new InvalidArgumentException("Invalid arguments.");
        }
        $isReacting = $this->isReacting($user_id, $video_id);
        $userdao = UserDAO::getInstance();
        $videodao = VideoDAO::getInstance();
        if ($isReacting == -1) {//if there has been no reaction
            $userdao->reactVideo($user_id, $video_id, $status);
        } elseif ($isReacting == $status) { //if liking liked or unliking unliked video
            $userdao->unreactVideo($user_id, $video_id);
        } elseif ($isReacting != $status) { //if liking disliked or disliking liked video
            $userdao->unreactVideo($user_id, $video_id);
            $userdao->reactVideo($user_id, $video_id, 1 - $isReacting);
        }
        $arr = [];
        $arr["stat"] = $this->isReacting();
        $arr["likes"] = $videodao->getReactions($video_id, 1);
        $arr["dislikes"] = $videodao->getReactions($video_id, 0);
        echo json_encode($arr);
    }

    public function subscriptions(){
        if(isset($_GET['user_id'])){
            $user_id = $_GET['user_id'];
        }
        else {
            if (isset($_SESSION["logged_user"]["id"])){
                $user_id = $_SESSION["logged_user"]["id"];
            }
        }
        if (isset($user_id) && !empty($user_id)) {
            $dao = UserDAO::getInstance();
            $subscriptions = $dao->getSubscriptions($user_id);
            include_once "view/subscriptions.php";
        }
        else {
            include_once "view/subscriptions.php";
            echo "<h3>Login to view subscriptions!</h3>";
        }

    }
    public function clickedUser(){
        if(isset($_GET['id'])){
            $followed_id = $_GET['id'];
        }
        $dao = UserDAO::getInstance();
        $user = $dao->getFollowedUser($followed_id);
        include_once "view/subscriptions.php";
    }
}
