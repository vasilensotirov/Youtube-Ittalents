<?php
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
<!--    <link rel="stylesheet" href="styles/style.css">-->
    <title>Playlists</title>
</head>
<body>
<?php
require_once "header.php";
require_once "navigation.php";
?>
<form action="index.php?target=playlist&action=create" method="post">
    <table>
        <tr>
            <td><label for="title"><b>Title:</b></label></td>
            <td><input type="text" id="title" name="title" required></td>
        </tr>
        <tr>
        <input type="hidden" name="owner_id" value="<?= $_SESSION["logged_user"]["id"]; ?>"
        </tr>
        <tr>
            <td><input type="submit" name="create" value="Create"></td>
        </tr>
    </table>
</form>
</body>
</html>
