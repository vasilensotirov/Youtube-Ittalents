<?php
require_once "header.php";
require_once "navigation.php";
if (!isset($_SESSION["logged_user"])){
    header("Location:login.php");
}
if (!isset($video) || !isset($categories)){
    header("Location:main.php");
}
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="//maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.3/css/all.css" integrity="sha384-UHRtZLI+pbxtHCWp1t77Bi1L4ZtiqrqD80Kn4Z8NTSRyMA2Fd33n5dQ8lWUE00s/" crossorigin="anonymous">
    <title>Document</title>
</head>
<body>
<form action="index.php?target=video&action=edit" method="post" enctype="multipart/form-data">
    <table>
        <input type="hidden" name="id" value="<?= $video["id"]; ?>"
        <tr>
            <td><label for="title"><b>Title:</b></label></td>
            <td><input type="text" id="title" name="title" value="<?= $video["title"] ?>" required></td>
        </tr>
        <tr>
            <td><label for="description"><b>Description:</b></label></td>
            <td><textarea id="description" name="description" required><?= $video["description"]; ?></textarea></td>
        </tr>
        <input type="hidden" name="owner_id" value="<?= $_SESSION["logged_user"]["id"]; ?>"
        <tr>
            <td><label for="category_id"><b>Category:</b></label></td>
            <td><select id="category_id" name="category_id" required>
                    <?php
                    foreach ($categories as $category){
                        echo "<option value='". $category["id"] . "'";
                        if ($category["id"] == $video["category_id"]){
                            echo " selected";
                        }
                        echo ">" . $category["name"] . "</option>";
                    }
                    ?>
                </select></td>
        </tr>
        <tr>
            <td>Current thumbnail:</td>
            <td><img width="200px" src="<?= $video["thumbnail_url"] ?>" </td>
        </tr>
        <tr>
            <td><label for="thumbnail"><b>Add new:</b></label></td>
            <td><input type="file" id="thumbnail" name="thumbnail"></td>
            <input type="hidden" name="thumbnail_url" value="<?= $video["thumbnail_url"] ?>">
        </tr>
        <tr>
            <td><input type="submit" name="edit" value="Edit"></td>
        </tr>
    </table>
</form>
</body>
</html>