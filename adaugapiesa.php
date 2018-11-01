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
	<input id="casutasearch" type="text" value="add keywords here">
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
<h2>Adaugare piesa:</h2>
<form action="addsong.php" method="post">
<label>Baza de date</label>
<?php
showdatabases("db");
?><br>
<label>Titlu</label><input type="text" name="titlu"><br>
<label>Autor</label><input type="text" name="autor"><br>
<label>Data lansarii</label><input type="text" name="data" value="2000-00-00"><br>
<label>Categorie</label>
<select name="categorie">
	<option value="Rap">Rap</option>
	<option value="Rock">Rock</option>
	<option value="Pop">Pop</option>
</select><br>
<label>Album</label><input type="text" name="album"><br>
<label>Durata</label><input type="number" name="durata"><br>
<label>Pret</label><input type="number" name="pret"><br>
<label>Imagine(link)</label><input type="text" name="imagine"><br>
<label>Piesa(link catre fisierul audio)</label><input type="text" name="piesa"><br>
<label>Trailer(link)</label><input type="text" name="trailer"><br>
<label>Alte detalii</label><input type="text" name="detalii"><br>
<input type="submit" value="Incarca">
</form>


</section>

<footer>Copyright cei mai jmecheri baieti si Ana</footer>

</body>

</html>