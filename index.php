<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
//AUTOCOMPLETE
spl_autoload_register(function ($class){
    $class = str_replace("\\", DIRECTORY_SEPARATOR, $class) . ".php";
    require_once  $class;
});

session_start();
$fileNotFoundFlag = false;
$controllerName = isset($_GET["target"]) ? $_GET["target"] : "view";
$methodName     = isset($_GET["action"]) ? $_GET["action"] : "viewRouter";
$view           = isset($_GET["view"]) ? $_GET["view"] : "main";
//if (!isset($_SESSION["logged_user"])) {
//    $view = "login";
//}
$controllerClassName = "\\controller\\" . ucfirst($controllerName) . "Controller";
if (class_exists($controllerClassName)){
    $controller = new $controllerClassName();
    if($controllerName == "view"){
        if (isset($_SESSION["logged_user"])) {
            try {
                $controller->$methodName($view);
                die();
            } catch (Exception $exception) {
                echo "error -> " . $exception->getMessage();
                die();
            }
        } else {
            if ($view != 'login' && $view != 'register') {
                try {
                    $controller->$methodName('login');
                    die();
                } catch (Exception $exception) {
                    echo "error -> " . $exception->getMessage();
                    die();
                }
            } else if ($view == 'login' || $view == 'register') {
                try {
                    $controller->$methodName($view);
                    die();
                } catch (Exception $exception) {
                    echo "error -> " . $exception->getMessage();
                    die();
                }
            }
        }
    }  else if (method_exists($controller,$methodName)){
        if (!($controllerName == "user" && in_array($methodName,array("login","register")))){
            if (!isset($_SESSION["logged_user"])){
                header("HTTP/1.0 401 Not Authorized");
                die();
            }
        }
        try{
            $controller->$methodName();
        }catch (Exception $exception){
            echo "error -> " . $exception->getMessage();
            die();
        }
    }else{
        $fileNotFoundFlag = true;
    }
}else{
    $fileNotFoundFlag = true;
}