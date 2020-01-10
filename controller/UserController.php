<?php
namespace controller;

use model\Playlist;
use model\PlaylistDAO;
use \model\User;
use model\UserDAO;
use model\VideoDAO;

class UserController {
    public function login()
    {
        if (isset($_POST['login'])) {
            if (isset($_POST['email']) && isset($_POST['password'])) {
                $email = $_POST['email'];
                $password = $_POST['password'];
                try {
                    $dao = UserDAO::getInstance();
                    $user = $dao->checkUser($email);
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
            catch (\PDOException $e){
                include_once "view/main.php";
                echo "Error!";
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
                try {
                    $dao = UserDAO::getInstance();
                    $user = $dao->checkUser($email);
                }
                catch (\PDOException $e){
                    include_once "view/register.php";
                    echo "Error!";
                }
                if ($user) {
                    echo "User with that email already exists";
                    include_once "view/login.php";
                }
                elseif ($msg != '') {
                    echo $msg;
                    include_once "view/register.php";
                }
                else{
                    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
                    $registration_date = date("Y-m-d H:i:s");
                    $avatar_url = $this->uploadFile("avatar", $_POST['username']);
                    $user = new User($username, $email, $password, $full_name, $registration_date, $avatar_url);
                    try {
                        $dao = UserDAO::getInstance();
                        $dao->registerUser($user);
                        $this->createWatchLater($user->getId());
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
                    catch (\PDOException $e){
                        include_once "view/register.php";
                        echo "Error!";
                    }
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
                        if(isset($_POST['new_password']) && isset($_POST['cpassword'])) {
                            $newAvatar = $this->uploadFile("avatar", $_POST['username']);
                            $password = password_hash($_POST['new_password'], PASSWORD_BCRYPT);
                            $email = $_SESSION['logged_user']['email'];
                            $user = new User($_POST['username'], $email, $password, $_POST['full_name'], null, $newAvatar);
                            $user->setId($_SESSION['logged_user']['id']);
                            try {
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
                            catch (\PDOException $e){
                                include_once "view/main.php";
                                echo "Error editing user profile!";
                            }
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
        header("Location:index.php?view=login");
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

    public function createWatchLater($owner_id=null){
        if (isset($_SESSION["logged_user"]["id"])){
            $owner_id = $_SESSION["logged_user"]["id"];
        }
        $playlist = new Playlist();
        $title = "Watch Later";
        $date_created = date("Y-m-d H:i:s");
        $playlist->setTitle($title);
        $playlist->setOwnerId($owner_id);
        $playlist->setDateCreated($date_created);
        try {
            $dao = PlaylistDAO::getInstance();
            $dao->create($playlist);
        }
        catch (\PDOException $e){
            echo "Error creating playlist!";
        }
    }

    public function getById($id=null){
        if (isset($_GET["id"])) {
            $id = $_GET["id"];
        }
        try {
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
        catch (\PDOException $e){
            include_once "view/main.php";
            echo "Error!";
        }
    }

    public function isFollowing($followed_id=null){
        if (isset($_GET["id"])){
            $followed_id = $_GET["id"];
        }
        $follower_id = $_SESSION["logged_user"]["id"];
        try {
            $dao = UserDAO::getInstance();
            return $dao->isFollowing($follower_id, $followed_id);
        }
        catch (\PDOException $e){
            include_once "view/main.php";
            echo "Error!";
        }
    }

    public function follow($followed_id=null){
        if (isset($_GET["id"])){
            $followed_id = $_GET["id"];
        }
        $follower_id = $_SESSION["logged_user"]["id"];
        try {
            $dao = UserDAO::getInstance();
            $dao->followUser($follower_id, $followed_id);
        }
        catch (\PDOException $e){
            echo "Error following user!";
        }
    }

    public function unfollow($followed_id=null){
        if (isset($_GET["id"])){
            $followed_id = $_GET["id"];
        }
        $follower_id = $_SESSION["logged_user"]["id"];
        try {
            $dao = UserDAO::getInstance();
            $dao->unfollowUser($follower_id, $followed_id);
        }
        catch (\PDOException $e){
            echo "Error unfollowing user!";
        }
    }

    public function isReacting($user_id=null, $video_id=null){
        if (isset($_GET["id"])){
            $video_id = $_GET["id"];
        }
        $user_id = $_SESSION["logged_user"]["id"];
        try {
            $dao = UserDAO::getInstance();
            return $dao->isReacting($user_id, $video_id);
        }
        catch (\PDOException $e){
            echo "Error!";
        }
    }

    public function reactVideo($video_id=null, $status=null){
        if (isset($_GET["id"]) && isset($_GET["status"])){
            $video_id = $_GET["id"];
            $status = $_GET["status"];
        }
        $user_id = $_SESSION["logged_user"]["id"];
        $isReacting = $this->isReacting($user_id, $video_id);
        try {
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
        catch (\PDOException $e){
            echo "Error!";
        }
    }

    public function isReactingComment($user_id=null, $comment_id=null){
        if (isset($_GET["id"])){
            $comment_id = $_GET["id"];
        }
        $user_id = $_SESSION["logged_user"]["id"];
        try {
            $dao = UserDAO::getInstance();
            return $dao->isReactingComment($user_id, $comment_id);
        }
        catch (\PDOException $e){
            echo "Error!";
        }
    }

    public function reactComment($comment_id=null, $status=null){
        if (isset($_GET["id"]) && isset($_GET["status"])){
            $comment_id = $_GET["id"];
            $status = $_GET["status"];
        }
        $user_id = $_SESSION["logged_user"]["id"];
        $isReacting = $this->isReactingComment($user_id, $comment_id);
        try {
            $dao = UserDAO::getInstance();
            if ($isReacting == -1) {//if there has been no reaction
                $dao->reactComment($user_id, $comment_id, $status);
            } elseif ($isReacting == $status) { //if liking liked or unliking unliked video
                $dao->unreactComment($user_id, $comment_id);
            } elseif ($isReacting != $status) { //if liking disliked or disliking liked video
                $dao->unreactComment($user_id, $comment_id);
                $dao->reactComment($user_id, $comment_id, 1 - $isReacting);
            }
            $arr = [];
            $arr["stat"] = $this->isReactingComment();
            $arr["likes"] = $dao->getCommentReactions($comment_id, 1);
            $arr["dislikes"] = $dao->getCommentReactions($comment_id, 0);
            echo json_encode($arr);
        }
        catch (\PDOException $e){
            echo "Error!";
        }
    }
    public function subscriptions($user_id = null){
        if(isset($_GET['user_id'])){
            $user_id = $_GET['user_id'];
        }
        try{
        $dao = UserDAO::getInstance();
        $subscriptions = $dao->getSubscriptions($user_id);
        include_once "view/subscriptions.php";
        }catch(\PDOException $e){
            echo "Error!" . $e->getMessage();
        }

    }
    public function clickedUser(){
        if(isset($_GET['id'])){
            $followed_id = $_GET['id'];
        }
        try{
            $dao = UserDAO::getInstance();
            $user = $dao->getFollowedUser($followed_id);
            include_once "view/subscriptions.php";
        } catch (\PDOException $e){
            echo "Error!" . $e->getMessage();
        }
    }
}
