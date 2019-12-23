<?php
namespace controller;

use \model\User;
use \model\UserDAO;

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
                } else {
                    if (password_verify($password, $user['password'])) {
                        $_SESSION['logged_user'] = $user;
                        echo "Successful login! <br>";
                        include_once "view/main.php";
                    } else {
                        echo 'Invalid email or password.Try again.';
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
                    $arrayUser['email'] = $user->getEmail();
                    $arrayUser['id'] = $user->getId();
                    $_SESSION['logged_user'] = $arrayUser;
                    echo "Successful registration!<br>";
                    include_once "view/main.php";
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
}
