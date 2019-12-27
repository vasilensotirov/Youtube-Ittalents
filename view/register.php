<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="styles/style.css">
    <title>Document</title>
</head>
<body>
<form action="index.php?target=user&action=register" method="post" enctype="multipart/form-data">
<table>
    <tr>
        <td><label for="username"><b>Username:</b></label></td>
        <td><input type="text" id="username" name="username" required></td>
    </tr>
    <tr>
        <td><label for="email"><b>Email:</b></label></td>
        <td><input type="email" id="email" name="email" required></td>
    </tr>
    <tr>
        <td><label for="password"><b>Password:</b></label></td>
        <td><input type="password" id="password" name="password" required></td>
    </tr>
    <tr>
        <td><label for="cpassword"><b>Confirm password:</b></label></td>
        <td><input type="password" id="cpassword" name="cpassword" required></td>
    </tr>
    <tr>
        <td><label for="full_name"><b>Full name:</b></label></td>
        <td><input type="text" id="full_name" name="full_name" required></td>
    </tr>
    <tr>
        <td><label for="avatar"><b>Add avatar</b></label></td>
        <td><input type="file" name="avatar" id="avatar"</td>
    </tr>
    <tr>
        <td><input type="submit" name="register" value="Register"></td>
    </tr>
</table>
</form>
<button><a href="index.php?view=login">Login here</a></button>
</body>
</html>
