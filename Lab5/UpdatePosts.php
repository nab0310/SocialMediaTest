<?php
/**
 * Created by PhpStorm.
 * User: Nick Behrens
 * Date: 10/14/2016
 * Time: 1:21 PM
 */
session_start();
$action = $_REQUEST["action"];
$postsDecode = json_decode($_SESSION["posts"],true);
if($action=="edit"){
    $newMessage = $_REQUEST["message"];
    $oldMessage = $_REQUEST["oldMessage"];
    for($i=0;$i<count($postsDecode["Posts"]);$i++){
        if($postsDecode["Posts"][$i]["poster"]==$_SESSION["username"]) {
            if ($postsDecode["Posts"][$i]["post"] == $oldMessage) {
                $postsDecode["Posts"][$i]["post"] = $newMessage;
            }
        }
    }
    $_SESSION["posts"] = json_encode($postsDecode);
    file_put_contents("posts.txt",json_encode($postsDecode));
    echo file_get_contents("posts.txt");
}
if($action=="add"){
    $message = $_REQUEST["message"];
    $user = $_SESSION["username"];
    array_unshift($postsDecode["Posts"],array("poster"=>$user,"post"=>$message));
    $_SESSION["posts"] = json_encode($postsDecode);
    file_put_contents("posts.txt",json_encode($postsDecode));
    echo file_get_contents("posts.txt");
}
?>