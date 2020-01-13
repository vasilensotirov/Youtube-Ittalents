<?php
//include_once "main.php";
$user = $_SESSION['logged_user'];
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
    <title>Edit profile</title>
</head>
<body>
<?php
require_once "header.php";
require_once "navigation.php";
?>
<form action="index.php?target=user&action=edit" method="post" enctype="multipart/form-data">
    <table>
        <tr>
            <td><label for="username"><b>Username:</b></label></td>
            <td><input type="text" id="username" name="username" value="<?= $user['username'] ?>" required></td>
        </tr>
        <tr>
            <td><label for="email"><b>Email:</b></label></td>
            <td><input type="email" id="email" name="email" value="<?= $user['email'] ?>" required></td>
        </tr>
        <tr>
            <td><label for="password"><b>Current password:</b></label></td>
            <td><input type="password" id="password" name="password" required></td>
        </tr>
        <tr>
            <td><label for="new_password"><b>New password:</b></label></td>
            <td><input type="password" id="new_password" name="new_password" required></td>
        </tr>
        <tr>
            <td><label for="cpassword"><b>Confirm password:</b></label></td>
            <td><input type="password" id="cpassword" name="cpassword" required></td>
        </tr>
        <tr>
            <td><label for="full_name"><b>Full name:</b></label></td>
            <td><input type="text" id="full_name" name="full_name" value="<?= $user['full_name'] ?>" required></td>
        </tr>
        <tr>
            <td><label for="avatar"><b>Change avatar</b></label></td>
            <td><input type="file" name="avatar" id="avatar"</td>
        </tr>
        <tr>
            <td><input type="submit" name="edit" value="Edit"></td>
        </tr>
    </table>
</form>
</body>
</html>
