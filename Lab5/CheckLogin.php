<?php
//var_dump($_REQUEST);
session_start();
$foundUsername = 0;
$myfile = fopen("users.txt", "r") or die("Unable to open file!");
while(!feof($myfile)) {
    $line =  fgets($myfile) . "<br>";
    $array = explode ( ":" , $line);
    if(strcmp($array[0],$_REQUEST["username"])==0){
        if(strpos($array[1],$_REQUEST["password"])==0){
            $foundUsername = 1;
            $_SESSION["username"]=$_REQUEST["username"];
            break;
        }
    }
}
fclose($myfile);
echo $foundUsername;
?>