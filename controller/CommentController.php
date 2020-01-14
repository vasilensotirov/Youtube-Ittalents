<?php
namespace controller;
use exceptions\InvalidArgumentException;
use exceptions\AuthorizationException;
use model\Comment;
use model\UserDAO;
use model\VideoDAO;

class CommentController
{
    public function add(){
        if (empty($_POST["content"]) || empty($_POST["video_id"]) || empty($_POST["owner_id"])) {
            throw new InvalidArgumentException("Invalid arguments.");
        }
        $comment = new Comment();
        $comment->setContent($_POST["content"]);
        $comment->setVideoId($_POST["video_id"]);
        $comment->setOwnerId($_POST["owner_id"]);
        $comment->setDate(date("Y-m-d H:i:s"));
        $dao = VideoDAO::getInstance();
        $comment_id = $dao->addComment($comment);
        $comment = $dao->getCommentById($comment_id);
        echo json_encode($comment);
    }

    public function delete($comment_id=null, $owner_id=null){
        if (isset($_GET["id"])){
            $comment_id = $_GET["id"];
            $owner_id = $_SESSION["logged_user"]["id"];
        }
        if (empty($comment_id) || empty($owner_id)){
            throw new InvalidArgumentException("Invalid arguments.");
        }
        if ($owner_id != $_SESSION["logged_user"]["id"]){
            throw new AuthorizationException("Not authorized.");
        }
        $dao = VideoDAO::getInstance();
        $dao->deleteComment($comment_id, $owner_id);
    }

    public function isReactingComment($user_id=null, $comment_id=null){
        if (isset($_GET["id"])){
            $comment_id = $_GET["id"];
            $user_id = $_SESSION["logged_user"]["id"];
        }
        if (empty($user_id) || empty($comment_id)){
            throw new InvalidArgumentException("Invalid arguments.");
        }
        $dao = UserDAO::getInstance();
        return $dao->isReactingComment($user_id, $comment_id);
    }

    public function react(){
        if (isset($_GET["id"]) && isset($_GET["status"])){
            $comment_id = $_GET["id"];
            $status = $_GET["status"];
        }
        $user_id = $_SESSION["logged_user"]["id"];
        if (empty($comment_id) || empty($user_id)){
            throw new InvalidArgumentException("Invalid arguments.");
        }
        $isReacting = $this->isReactingComment($user_id, $comment_id);
        $dao = UserDAO::getInstance();
        if ($isReacting == -1) {//if there has been no reaction
            $dao->reactComment($user_id, $comment_id, $status);
        } elseif ($isReacting == $status) { //if liking liked or disliking disliked video
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
}