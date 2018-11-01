<!DOCTYPE html>

<html>

<head>
<title>Fonoteca</title>
<link rel="stylesheet" type="text/css" href="register.css">
<link href='https://fonts.googleapis.com/css?family=Indie+Flower|Roboto' rel='stylesheet' type='text/css'>
</head>

<body>
<section id="regpan">
<a href="index.html"><h1>Register</h1></a>
<?php
include 'engine.php';

$user=$_POST['user'];
$pass=$_POST['pass'];
$nume=$_POST['nume'];
$cnp=$_POST['cnp'];
$data=$_POST['data'];
$adres=$_POST['adresa'];
$telefon=$_POST['telefon'];
$mail=$_POST['mail'];

if (checkusername($user))
{
	reguser($user,$pass,$nume,$cnp,$data,$adres,$telefon,$mail);
}

?>
</section>

</body>

</html>
