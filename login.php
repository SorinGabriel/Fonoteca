<!DOCTYPE html>

<html>

<head>
<title>Fonoteca</title>
<link rel="stylesheet" type="text/css" href="login.css">
<link href='https://fonts.googleapis.com/css?family=Montserrat|Indie+Flower' rel='stylesheet' type='text/css'>
</head>

<body>
<section id="loginpan">
<h1>Login</h1>
<?php

include 'engine.php';

$user=$_POST['user'];
$pass=$_POST['pass'];

login($user,$pass);

?>
</form>

</section>

</body>

</html>
