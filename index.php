<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

function handleExceptions(Exception $exception){
    $status = $exception instanceof BaseException ? $exception->getStatusCode() : 500;
    $msg = $exception->getMessage();
    $html = "<h1>$msg</h1>";
    echo $html;
    $obj = new stdClass();
    $obj->msg = $exception->getMessage();
    $obj->status = $status;
    http_response_code($status);
    //echo json_encode($obj);
}
set_exception_handler("handleExceptions");
//AUTOCOMPLETE
spl_autoload_register(function ($class){
    $class = str_replace("\\", DIRECTORY_SEPARATOR, $class) . ".php";
    require_once  $class;
});

session_start();
$fileNotFoundFlag = false;
$controllerName = isset($_GET["target"]) ? $_GET["target"] : "video";
$methodName     = isset($_GET["action"]) ? $_GET["action"] : "getAll";
$view           = isset($_GET["view"]) ? $_GET["view"] : "main";
/*if (!isset($_SESSION["logged_user"])) {
    $controllerName="view";
    $methodName="viewRouter";
    $view = "login";
}*/
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
        if (!(in_array($methodName,array("login", "register", "getById", "getByOwnerId", "getAll",
            "getTrending", "getHistory", "getWatchLater", "getLikedVideos", "getMyPlaylists", "subscriptions",
            "clickedUser", "clickedPlaylist", "search", "viewRouter")))){
            if (!isset($_SESSION["logged_user"])){
                header("HTTP/1.0 401 Not Authorized");
                die();
            }
        }
        try{
            return $controller->$methodName();
        }catch (Exception $exception){
            echo "error -> " . $exception->getMessage();
            die();
        }
    }else{
        $fileNotFoundFlag = true;
    }
}
else {
    $fileNotFoundFlag = true;
    header("Location:index.php?target=video&action=getAll");
}
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="styles/style.css">
    <link rel="script" href="js/script.js">
    <title>Document</title>
</head>
<body>