<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="styles/login.css">
    <link href="//maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
    <title>Youtube</title>
</head>
<body>
<form action="index.php?target=user&action=login" method="post">
    <div class="wrapper fadeInDown">
        <div id="formContent">
            <div class="fadeIn first">
                <img src="styles/images/youtube_PNG5.png" id="icon" alt="User Icon" />
            </div>
            <form action="index.php?target=user&action=login" method="post">
                <input type="text" id="login" class="fadeIn second" name="email" placeholder="email">
                <input type="password" id="password" class="fadeIn third" name="password" placeholder="password">
                <input type="submit" class="fadeIn fourth" name="login" value="Log In">
            </form>
            <div id="formFooter">
                Don't have an account? <a class="underlineHover" href='index.php?view=register'>Sign Up</a>
            </div>
        </div>
    </div>
</body>
</html>
