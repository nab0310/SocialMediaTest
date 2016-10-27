<?php session_start();?>
<HTML>
<HEAD>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
</HEAD>
<h1>Make a Post!</h1>
<input type="text" id= "post">
<input type="button" onclick="submitPost()" value="Submit">
<div id="editPostResponse"></div>
<script>
    function editPost(oldMessage){
        var newMessage = window.prompt("Please enter your changed message: ");
        $.get("UpdatePosts.php?message="+newMessage+"&action=edit&oldMessage="+oldMessage,
            function(data,status) {
                alert("Your have changed your post to: "+newMessage);
                var dataDecode = JSON.parse(data);

                $('div[id="post"]').each(function(index,item){
                    var i = parseInt($(item).data('index'));
                    var poster = dataDecode["Posts"][i]["poster"];
                    var post = dataDecode["Posts"][i]["post"];
                    $(item).children("span").html("Poster: "+poster+". Their Post: "+post);
                    if($(item).children("button").length !=0){
                        $(item).children("button").remove();
                        //Have to update onClick Function.
                        $(item).children("span").after($('<button id=EditPost onclick="editPost(\'' + post + '\')">Edit Post</button>'));
                    }
                });
            }
        );
    }
</script>
<script>
    function submitPost(){
        alert("You are adding a post! It says: "+$('#post').val());
        $.get("UpdatePosts.php?message="+$('#post').val()+"&action=add",
            function (data,status) {
                document.getElementById("editPostResponse").innerHTML = data;
                alert("You have added your post to the file.");
                var dataDecode = JSON.parse(data);

                var poster = dataDecode["Posts"][0]["poster"];
                var post = dataDecode["Posts"][0]["post"];

                $('div[id="post"]').each(function(index,item){
                    var i = parseInt($(item).data('index'));
                    $(item).data('index',i+1);
                });

                $('div[data-index="1"]').before($("<div id = 'post' data-index='0'><span>Poster: "+poster+". Their Post: "+ post +"</span><button id='EditPost' onclick='editPost(" + post + ")'>Edit Post</button>"))
            })
    }
</script>
    <?php
    $file = file_get_contents("posts.txt");
    $jsonData = json_decode($file,true);
    $posters = array();
    for($x=0;$x<count($jsonData["Posts"]);$x++){
        $tmp = new Post($jsonData["Posts"][$x]["poster"],$jsonData["Posts"][$x]["post"],$x);
        array_push($posters,$tmp);
    }
    $_SESSION["posts"] = json_encode($jsonData);
    for($i=0; $i < count($posters); $i++){
        if($posters[$i]!==null){
            $posters[$i]->displayPost();
        }
    }
    class Post{
        public $poster;
        public $post;
        public $number;
        function __construct($givenPoster,$message,$number){
            $this->poster = $givenPoster;
            $this->post = $message;
            $this->number = $number;
        }
        public function displayPost(){
            $index = $this->number;
            echo "<div id='post' data-index = '$index'>";
            echo "<span>Poster: ".$this->poster.". Their Post: ".$this->post."</span>";
            if($_SESSION["username"]!=null){
                if(strcmp($_SESSION["username"],$this->poster)==0){
                    $textPost = json_encode($this->post);
                    echo "<button id='EditPost' onclick='editPost($textPost)'>Edit Post</button>";
                }else{
                    echo "<p>This is not your post</p>";
                }
            }else{
                echo "<p>User is not logged in!</p>";
            }
            echo "</div>";
        }
    }

    ?>
</table>
</html>
