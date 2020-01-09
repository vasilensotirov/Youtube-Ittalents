function likeVideo(video_id) {
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            var res = JSON.parse(this.response);
            document.getElementById("likes-count").innerText = res.likes;
            document.getElementById("dislikes-count").innerText = res.dislikes;
            if (res.stat == 1) {
                document.getElementById("like").style.color = 'blue';
                document.getElementById("dislike").style.color = 'gray';
            }
            if (res.stat == -1){
                document.getElementById("like").style.color = 'gray';
                document.getElementById("dislike").style.color = 'gray';
            }
        }
    };
    xhttp.open("GET", "index.php?target=user&action=reactVideo&id=" + video_id + "&status=1", true);
    xhttp.send();
}

function dislikeVideo(video_id) {
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            var res = JSON.parse(this.response);
            document.getElementById("likes-count").innerText = res.likes;
            document.getElementById("dislikes-count").innerText = res.dislikes;
            if (res.stat == 0) {
                document.getElementById("like").style.color = 'gray';
                document.getElementById("dislike").style.color = 'blue';
            }
            if (res.stat == -1){
                document.getElementById("like").style.color = 'gray';
                document.getElementById("dislike").style.color = 'gray';
            }
        }
    };
    xhttp.open("GET", "index.php?target=user&action=reactVideo&id=" + video_id + "&status=0", true);
    xhttp.send();
}
/*
function addComment() {
    var video_id = document.getElementById("video_id");
    var owner_id = document.getElementById("owner_id");
    var content = document.getElementById("content");
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            document.getElementById("comments").innerHTML = this.responseText;
        }
    };
    xhttp.open("POST", "index.php?target=video&action=addComment&ajax", true);
    xhttp.send('video_id=' + video_id, 'owner_id=' + owner_id, 'content=' + content, 'comment=' + 1);
}*/

/*function deleteComment(comment_id) {
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            document.getElementById("comment").innerHTML = "";
        }
    };
    xhttp.open("GET", "index.php?target=video&action=deleteComment&id=" + comment_id, true);
    xhttp.send();
}*/

function likeComment(comment_id) {
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            var res = JSON.parse(this.response);
            document.getElementById("comment-likes").innerText = res.likes;
            document.getElementById("comment-dislikes").innerText = res.dislikes;
            if (res.stat == 1) {
                document.getElementById("like-comment").style.color = 'blue';
                document.getElementById("dislike-comment").style.color = 'gray';
            }
            if (res.stat == -1){
                document.getElementById("like-comment").style.color = 'gray';
                document.getElementById("dislike-comment").style.color = 'gray';
            }
        }
    };
    xhttp.open("GET", "index.php?target=user&action=reactComment&id=" + comment_id + "&status=1", true);
    xhttp.send();
}

function dislikeComment(comment_id) {
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            var res = JSON.parse(this.response);
            document.getElementById("comment-likes").innerText = res.likes;
            document.getElementById("comment-dislikes").innerText = res.dislikes;
            if (res.stat == 0) {
                document.getElementById("like-comment").style.color = 'gray';
                document.getElementById("dislike-comment").style.color = 'blue';
            }
            if (res.stat == -1){
                document.getElementById("like-comment").style.color = 'gray';
                document.getElementById("dislike-comment").style.color = 'gray';
            }
        }
    };
    xhttp.open("GET", "index.php?target=user&action=reactComment&id=" + comment_id + "&status=0", true);
    xhttp.send();
}

function followUser(user_id) {
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            document.getElementById("follow-button").innerText="Unfollow";
            document.getElementById("follow-button").onclick = function () {
                unfollowUser(user_id);
            };
        }
    };
    xhttp.open("GET", "index.php?target=user&action=follow&id=" + user_id, true);
    xhttp.send();
}

function unfollowUser(user_id) {
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            document.getElementById("follow-button").innerText="Follow";
            document.getElementById("follow-button").onclick = function () {
                followUser(user_id);
            };
        }
    };
    xhttp.open("GET", "index.php?target=user&action=unfollow&id=" + user_id, true);
    xhttp.send();
}