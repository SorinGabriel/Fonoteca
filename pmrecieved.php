<!DOCTYPE html>
<?php

include 'engine.php';

checkconnection();

?>
<html>

<head>
<title>Fonoteca</title>
<script src="stylescript.js"></script>
<link href='https://fonts.googleapis.com/css?family=Bad+Script|Lobster|Poiret+One|Candal|Josefin+Sans|Roboto' rel='stylesheet' type='text/css'>
<link rel="stylesheet" type="text/css" href="head.css">
<link rel="stylesheet" type="text/css" href="style.css">
</head>

<body>

<header>

	<h1 class="headeralign">Fonoteca</h1>

	<div class="headeralign" id="searchbox">
	
	<form method="POST" action="searchbyname.php">    <span id="searchtext">Search:</span>
	<input id="casutasearch" type="text" value="add keywords here" name="search">
	<input type="image" src="images/lupa.png" id="lupa">
	</form>
	<a href="search.php">Cautare avansata</a>
	
	</div>
	
</header>

<nav class="contentalign">

<input id="menuicon" type="image" src="images/menu.png" onclick="menu()">

<ul	id="menu">
	<li>Sold:<?php echo getsold(); ?> <img src="images/cash.png" alt="ron"></li>
	<a href="home.php"><li>Home</li></a>
	<a href="playlist.php"><li>Playlisturi</li></a>
	<a href="members.php"><li>Membri</li></a>  <a href="pmrecieved.php"><li>Mesaje</li></a>  	<a href="forum.php"><li>Forum</li></a>
	<a href="settings.php"><li>Setarile contului</li></a>
	<a href="logout.php"><li>Log out</li></a>
</ul>

</nav>

<section id="content" class="contentalign">
<h2>Mesaje primite</h2>
<a href="pmsend.php">Mesaje trimise</a>
<a href="sendmesage.php">Trimite mesaj</a>
<?php

showmesagesrecieved();

?>
</section>

<footer>Copyright cei mai jmecheri baieti si Ana</footer>

</body>

</html>