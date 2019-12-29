<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Upload Video</title>
    <link rel="stylesheet" href="styles/style.css">
</head>
<body>
<?php
require_once "header.php";
require_once "navigation.php";
?>
<!--<button><a href="index.php">Home</a></button>-->
<form action="index.php?target=video&action=upload" method="post" enctype="multipart/form-data">
    <table>
        <tr>
            <td><label for="title"><b>Title:</b></label></td>
            <td><input type="text" id="title" name="title" required></td>
        </tr>
        <tr>
            <td><label for="description"><b>Description:</b></label></td>
            <td><textarea id="description" name="description" required></textarea></td>
        </tr>
        <input type="hidden" name="owner_id" value="<?= $_SESSION["logged_user"]["id"]; ?>"
        <tr>
            <td><label for="category_id"><b>Category:</b></label></td>
            <td><select id="category_id" name="category_id" required>
                    <option value="1">Film & Animation</option>
                    <option value="2">Autos & Vehicles</option>
                    <option value="3">Music</option>
                    <option value="4">Pets & Animals</option>
                    <option value="5">Sports</option>
                    <option value="6">Travel & Events</option>
                    <option value="7">Gaming</option>
                    <option value="8">People & Blogs</option>
                    <option value="9">Entertainment</option>
                    <option value="10">News & Politics</option>
                    <option value="11">Education</option>
                    <option value="12">Science & Technology</option>
                </select></td>
        </tr>
        <tr>
            <td><label for="video"><b>Video:</b></label></td>
            <td><input type="file" id="video" name="video" required></td>
        </tr>
        <tr>
            <td><label for="thumbnail"><b>Thumbnail:</b></label></td>
            <td><input type="file" id="thumbnail" name="thumbnail"</td>
        </tr>
        <tr>
            <td><input type="submit" name="upload" value="Upload"></td>
        </tr>
    </table>
</form>
</body>
</html>