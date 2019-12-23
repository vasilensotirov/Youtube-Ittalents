<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Youtube</title>
</head>
<body>
<form action="index.php?target=user&action=login" method="post">
    <table>
        <tr>
            <td><label for="email"><b>Email:</b></label></td>
            <td><input type="email" id="email" name="email" required></td>
        </tr>
        <tr>
            <td><label for="password"><b>Password:</b></label></td>
            <td><input type="password" id="password" name="password" required></td>
        </tr>
        <tr>
            <td><input type="submit" value="Login" name="login"></td>
        </tr>
    </table>
</form>
<button><a href='view/register.php'>Register</a></button>
</body>
</html>
