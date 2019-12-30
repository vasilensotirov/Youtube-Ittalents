function like(video_id) {
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            if (this.responseText == 1) {
                document.getElementById("reactstatus").innerText="Liked.";
                document.getElementById("like").style.color = 'blue';
                document.getElementById("dislike").style.color = 'gray';
            }
            if (this.responseText == -1){
                document.getElementById("reactstatus").innerText="Neutral.";
                document.getElementById("like").style.color = 'gray';
                document.getElementById("dislike").style.color = 'gray';
            }
        }
    };
    xhttp.open("GET", "index.php?target=user&action=reactVideo&id=" + video_id + "&status=1", true);
    xhttp.send();
}

function dislike(video_id) {
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            if (this.responseText == 0) {
                document.getElementById("reactstatus").innerText="Disliked.";
                document.getElementById("like").style.color = 'gray';
                document.getElementById("dislike").style.color = 'blue';
            }
            if (this.responseText == -1){
                document.getElementById("reactstatus").innerText="Neutral.";
                document.getElementById("like").style.color = 'gray';
                document.getElementById("dislike").style.color = 'gray';
            }
        }
    };
    xhttp.open("GET", "index.php?target=user&action=reactVideo&id=" + video_id + "&status=0", true);
    xhttp.send();
}