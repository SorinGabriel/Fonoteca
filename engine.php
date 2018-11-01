<?php

function connect()
{
	/* functie de conectare la baza de date */
	$conn = new mysqli('mysql.hostinger.ro', 'u596937348_site', 'fonoteca123', 'u596937348_site');
	if (mysqli_connect_errno()) {
	  exit('Connect failed: '. mysqli_connect_error());
	}
	return $conn;
}

function connectdbdist($ip,$user,$parola,$nume)
{
	$conn = new mysqli($ip, $user, $parola, $nume);
	if (mysqli_connect_errno()) {
	  exit('Connect failed: '. mysqli_connect_error());
	}
	return $conn;
}

function reguser($user,$pass,$nume,$cnp,$data,$adres,$telefon,$mail)
{
	/* functie care adauga useri in baza de date in momentul inregistrarii */
	$conn=connect();
	$sql = "INSERT INTO `users` (user,pass,nume,cnp,data,adresa,telefon,mail,categorie,sold,status)
	VALUES ('$user','$pass','$nume','$cnp','$data','$adres','$telefon','$mail','membru','0','Off')"; 
	if ($conn->query($sql) === TRUE) {
	  echo '<p style="margin-left:5%;">Te-ai inregistrat cu succes</p>';
	}
	else {
	 echo 'Error: '. $conn->error;
	}
	$conn->close();
}

function updatestatus($user,$value)
{
	/* functie care schimba statusul la online sau ofline */
	$conn=connect();
	if ($value==1) 
	{
		$status="On";
	}
	else
	{
		$status="Off";
	}
	$sql = "UPDATE `users` SET status='$status' WHERE user='$user'";
	if (!$conn->query($sql)) {
	  echo 'Error: '. $conn->error;
	}
	$conn->close();
}

function login($user,$pass)
{
	/*functie de conectare.In momentul in care userul si parola sunt ok se incepe o sesiune */
	$conn=connect();
	$sql = "SELECT pass,user FROM `users` WHERE user='$user'"; 
	$result = $conn->query($sql);
	$row=$result->fetch_assoc();
	if ($result->num_rows > 0) {
	if (strcmp($row['pass'],$pass)==0) 
	{
		echo '<p style="text-align:center">You have connected succesfuly</p><meta http-equiv="refresh" content="0; url=home.php" />';
		session_start();
		$_SESSION['user']=$row['user'];
		$_SESSION['pass']=$row['pass'];
		updatestatus($user,1);
	}
		else echo 'Password incorect!<meta http-equiv="refresh" content="1; url=index.html" />';
	}
	else {
	  echo 'User incorect';
	  echo '<meta http-equiv="refresh" content="1; url=index.html" />';
	}
	$conn->close();
}

function checkconnection()
{
	/*Pe fiecare pagina in parte a siteului se verifica daca userul este conectat
	daca da atunci se afiseaza continutul paginii iar in caz contrar se face un redirect 
	la pagina de login */
	session_start();
	$user=$_SESSION['user'];
	$pass=$_SESSION['pass'];
	$conn=connect();
	$sql = "SELECT `pass` FROM `users` WHERE `user`='$user'";
	$result=$conn->query($sql);
	$row=$result->fetch_assoc();
	if ($result->num_rows==0 || !($row['pass']==$pass))
	{
		echo '<meta http-equiv="refresh" content="0; url=http://fonoteca.esy.es" />';
	}
	else 
	{
		$t=time();
		$sql = "UPDATE `users` SET lastactivity='$t',status='On' WHERE user='$user'"; 
		if ($conn->query($sql) === FALSE) {
			echo 'Error: '. $conn->error;		
		}
	}
	$conn->close();
}

function logout()
{
	/* Elimina o sesiune pornita de catre un user */
	session_start();
	$conn=connect();
	$user=$_SESSION['user'];
	updatestatus($user,0);
	session_unset(); 
	session_destroy(); 
	$conn->close();
	echo '<meta http-equiv="refresh" content="0; url=index.html" />';
}

function showmembers()
{
	/* Afiseaza membrii (are si partea de frontend) */
	$conn=connect();
	$sql = "SELECT user,categorie,mail,status FROM `users`";
	$result=$conn->query($sql);
	$i=0;
	while ($row=$result->fetch_assoc())
	{
		if ($i%2==0)
		{
			$culoare="yellow";
		}
		else
		{
			$culoare="brown";
		}
		if ($row['status']=="On")
		{
			$culoare2="green";
		}
		else
		{
			$culoare2="red";
		}
		echo '<tr style="background-color:'.$culoare.'"><td>'.$row['user'].'</td><td>'.$row['categorie'].'</td><td>'.$row['mail'].'</td><td><a href="sendpm.php?to='.$row['user'].'">Send PM</a></td><td><p style="color:'.$culoare2.'">'.$row['status'].'</p></td></tr>';
		$i++;
	};
	$conn->close();
}

function changesettings()
{
	/* Schimba setarile contului */
	$conn=connect();
	$pass=$_POST['pass'];
	$nume=$_POST['nume'];
	$cnp=$_POST['cnp'];
	$data=$_POST['data'];
	$adres=$_POST['adresa'];
	$telefon=$_POST['telefon'];
	$mail=$_POST['mail'];
	session_start();
	$user=$_SESSION['user'];
	
	$sql = "UPDATE `users` SET pass='$pass',nume='$nume',cnp='$cnp',data='$data',adresa='$adres',telefon='$telefon',mail='$mail' WHERE user='$user'";
	if (!$conn->query($sql)) {
	  echo 'Error: '. $conn->error;
	}
	else
	{
		echo 'Modificarile au fost salvate';
	}
	$conn->close();
}

function getsold()
{
	/* Afiseaza soldul curent */
	$conn=connect();
	session_start();
	$user=$_SESSION['user'];
	$sql = "SELECT sold FROM `users` WHERE user='$user'";
	$result=$conn->query($sql);
	$row=$result->fetch_assoc();
	return $row['sold'];
	$conn->close();
}

function checkusername($user)
{
	/* verifica daca userul este deja folosit(pentru inregistrare) */
	$conn=connect();
	$sql = "SELECT * FROM `users` WHERE user='$user'";
	$result=$conn->query($sql);
	if ($result->num_rows>0)
	{
		echo "Username already in use";
		echo '<meta http-equiv="refresh" content="2; url=register.html" />';
		return false;
	}
	return true;
}

function deleteaccount()
{
	/* sterge contul */
	$conn=connect();
	session_start();
	$user=$_SESSION['user'];
	$sql = "DELETE FROM `users` WHERE user='$user'";
	if (!$conn->query($sql)) {
	  echo 'Error: '. $conn->error;
	}
	else
	{
		echo 'Contul tau a fost sters!';
		echo '<meta http-equiv="refresh" content="2; url=index.html" />';
	}
	$conn->close();
}

function frontendPiese($title,$image,$author,$album,$price,$date,$category,$id,$trailer)
{
	/* Afiseaza o piesa sub forma de div */
	echo '<article class="piesa"><h3>'.$title.'</h3><img class="alinierepiesa" src="'.$image.'" alt="piesa">';
	echo '<div class="alinierepiesa"><h4>By:'.$author.'</h4><h4>Album:'.$album.'</h4><h4>Pret:'.$price.'</h4><h4>Data lansarii:'.$date.'</h4>';
	echo '<h4>Gen:'.$category.'</h4></div>';
	echo '<div class="alinierepiesa">';
	echo '<a href="listen.php?id='.$id.'">Ascultare piesa</a>';
	echo '<a href="listentrailer.php?id='.$id.'">Ascultare trailer</a>';
	echo '<a href="'.$trailer.'" download>Download trailer</a>';
	echo '<a href="addplaylist.php?id='.$id.'">Adaugare intr-un playlist</a>';
	echo '</div></article>';
}

function showpiese()
{
	/* afiseaza piesele de pe prima pagina */
	$conn=connect();
	/* Selectam piesele din baza de date principala */
	$sql = "SELECT * FROM `piese` ORDER BY id DESC";
	$result=$conn->query($sql);
	for ($i=0;$i<10 && $row=$result->fetch_assoc();$i++)
	{
		$id_bd=$row['id_bd'];
		$sqlcommand="SELECT * FROM `adrese` WHERE id='$id_bd'";
		$res=$conn->query($sqlcommand);
		$rrow=$res->fetch_assoc();
		$db2=connectdbdist($rrow['ip'],$rrow['user'],$rrow['parola'],$rrow['nume']); //E ineficient aici pentru ca se poate sa faca multe conexiuni la baza de date in sir(chiar daca se deconecteaza pe rand)
		$id=$row['id'];
		/* Selectam aceasi piesa din baza de date distanta */
		$sql2="SELECT * FROM `piese` WHERE id='$id'";
		$result2=$db2->query($sql2); 
		$row2=$result2->fetch_assoc();
		$idalbum=$row2['album'];
		$sql3="SELECT * FROM `albume` WHERE id='$idalbum'";
		$result3=$db2->query($sql3);
		if ($result3->num_rows==0)
		{
			$titlualbum="N/A";
		}
		else
		{
			$row3=$result3->fetch_assoc();
			$titlualbum=$row3['titlu'];
		}
		frontendPiese($row2['titlu'],$row['imagine'],$row2['autor'],$titlualbum,$row['pret'],$row2['data'],$row['categorie'],$id,$row2['trailer']);
		$db2->close();
	}
	$conn->close();
}

function searchpiese($nume)
{
	/*Cautare piese(doar dupa nume)*/
	$conn2=connect();
	$sql="SELECT * FROM `adrese`";
	$rezultate=$conn2->query($sql);
	while ($rrow=$rezultate->fetch_assoc())
	{
		$conn=connectdbdist($rrow['ip'],$rrow['user'],$rrow['parola'],$rrow['nume']);	
		$sql="SELECT * FROM `piese` WHERE titlu LIKE '%$nume%'"; 
		$results=$conn->query($sql);
		if ($results->num_rows==0)
		{
			echo 'Nu s-au gasit rezultate';
		}
		while ($row=$results->fetch_assoc())
		{
			$id=$row['id'];
			$idalbum=$row['album'];
			$sql2="SELECT * FROM `albume` WHERE id='$idalbum'";
			$sql3="SELECT * FROM `piese` WHERE id='$id'";
			$results2=$conn->query($sql2);
			$row2=$results2->fetch_assoc();
			if ($result2->num_rows==0)
			{
				$titlualbum="N/A";
			}
			else
			{
				$row3=$result3->fetch_assoc();
				$titlualbum=$row2['titlu'];
			}
			$results3=$conn2->query($sql3);
			$row3=$results3->fetch_assoc();
			frontendPiese($row['titlu'],$row3['imagine'],$row['autor'],$titlualbum,$row3['pret'],$row['data'],$row3['categorie'],$id,$row['trailer']);
		}
		$conn->close();
	}
	$conn2->close();
}

function audioplayer($src,$title)
{
	/*Creaza playerul audio pentru o piesa */
	echo '<div id="audioplayer">';
	echo '<script src="audioplayer.js"></script>';
	echo '<audio id="AP" src="'.$src.'" controls preload="auto" autobuffer onload="write()"></audio>';
	echo '<h3 id="APname">'.$title.'</h3><br><div class="APpositioning"><p id="APtime">00:00</p><select id="APrate" name="APrate" onchange="changespeed()">
		<option value="0.5">Slow(0.5)</option>
		<option value="1" SELECTED>Normal(1x)</option>
		<option value="2">Fast(2x)</option>
		<option value="4">Very fast(4x)</option>
	</select>
	</div>
	<a href="javascript:null()" onclick="startplay()" id="APplay" class="APpositioning"><img src="images/play.png" alt="play"></a>
	<a href="javascript::null()" onclick="startpause()" id="APpause" class="APpositioning"><img src="images/pause.png" alt="pause"></a>

	</div>';
}

function listen()
{
	/* Trimite datele unei piese pentru a crea playerul */
	$id=$_GET['id'];
	$cconn=connect();
	$sql="SELECT id_bd FROM `piese` WHERE id='$id'";
	$rezultate=$cconn->query($sql);
	$rrow=$rezultate->fetch_assoc();
	$id_bd=$rrow['id_bd'];
	$sql="SELECT * FROM `adrese` WHERE id='$id_bd'";
	$rezultate=$cconn->query($sql);
	$rrow=$rezultate->fetch_assoc();
	$conn=connectdbdist($rrow['ip'],$rrow['user'],$rrow['parola'],$rrow['nume']);	
	$sql="SELECT titlu,continut FROM `piese` WHERE id='$id'";
	$results=$conn->query($sql);
	$row=$results->fetch_assoc();
	audioplayer($row['continut'],$row['titlu']);
	$conn->close();
}

function listentrailer()
{
	/* Trimite datele unui trailer pentru a crea playerul */
	$id=$_GET['id'];
	$cconn=connect();
	$sql="SELECT id_bd FROM `piese` WHERE id='$id'";
	$rezultate=$cconn->query($sql);
	$rrow=$rezultate->fetch_assoc();
	$id_bd=$rrow['id_bd'];
	$sql="SELECT * FROM `adrese` WHERE id='$id_bd'";
	$rezultate=$cconn->query($sql);
	$rrow=$rezultate->fetch_assoc();
	$conn=connectdbdist($rrow['ip'],$rrow['user'],$rrow['parola'],$rrow['nume']);	
	$sql="SELECT titlu,trailer FROM `piese` WHERE id='$id'";
	$results=$conn->query($sql);
	$row=$results->fetch_assoc();
	audioplayer($row['trailer'],$row['titlu']);
	$conn->close();
}

function showdatabases($nume)
{
	/*Afiseaza bazele de date distante sub forma unui select */
	$conn=connect();
	$sql="SELECT * FROM `adrese`";
	$rezultate=$conn->query($sql);
	echo '<select name="'.$nume.'">';
	while($row=$rezultate->fetch_assoc())
	{
		echo '<option value="'.$row['id'].'">'.$row['nume'].'</option>';
	}
	echo '</select>';
	$conn->close();
}

function connectspecifieddb($id)
{
	/* Se conecteaza la o baza de date */
	$conn=connect();
	$sql="SELECT * FROM `adrese` WHERE id='$id'";
	$rezultate=$conn->query($sql);
	$row=$rezultate->fetch_assoc();
	$conn->close();
	$rez=connectdbdist($row['ip'],$row['user'],$row['parola'],$row['nume']);
	return $rez;
}

function addsong()
{
	/* Adauga un nou cantec intr-o baza de date distanta */
	$conn=connect();
	$bd_id=$_POST['db'];
	$titlu=$_POST['titlu'];
	$autor=$_POST['autor'];
	$data=$_POST['data'];
	$categorie=$_POST['categorie'];
	$album=$_POST['album'];
	$durata=$_POST['durata'];
	$imagine=$_POST['imagine'];
	$piesa=$_POST['piesa'];
	$trailer=$_POST['trailer'];
	$pret=$_POST['pret'];
	$detalii=$_POST['detalii'];
	$conn2=connectspecifieddb($bd_id);
	$sql="SELECT id FROM albume WHERE titlu='$album'";
	$results=$conn2->query($sql);
	if ($results->num_rows==0)
	{
		$album=0;
	}
	$sql="INSERT INTO piese (categorie, durata, imagine, pret, id_bd)
	VALUES ('$categorie', '$durata', '$imagine', '$pret', '$bd_id')";
	if (!($conn->query($sql) === TRUE)) {
		echo "Error: " . $sql . "<br>" . $conn->error;
	}
	$sql="SELECT id FROM piese WHERE categorie='$categorie' and durata='$durata' and imagine='$imagine' and pret='$pret' and id_bd='$bd_id'";
	$results=$conn->query($sql);
	$row=$results->fetch_assoc();
	$id=$row['id'];
	$sql="INSERT INTO piese (id,titlu,autor,data,album,detalii,durata,continut,trailer)
	VALUES ('$id','$titlu','$autor','$data','$album','$detalii','$durata','$piesa','$trailer')";
	if (!($conn2->query($sql) === TRUE)) {
		echo "Error: " . $sql . "<br>" . $conn->error;
	}
}

function searchfrontend()
{
	/* Creaza partea de frontend a paginii cautare avansata */
	echo '<br><br><form action="search.php" method="post">';
	echo '<div class="searchalign"><label>Keyword:</label><input type="text" name="keyword"></div><br>';
	echo '<div class="searchalign"><label>Categorie:</label><select name="categorie">';
	$conn=connect();
	$sql="SELECT * FROM categorie WHERE parent=''";
	$results=$conn->query($sql);
	while($row=$results->fetch_assoc())
	{
		echo '<option value="'.$row['nume'].'">'.$row['nume'].'</option>';
	}
	echo '</select></div>';
	echo '<div class="searchalign"><label>Subcategorie:</label><select name="subcategorie"><option value="-">---</option>';
	$sql="SELECT * FROM categorie WHERE parent !=''";
	$results=$conn->query($sql);
	while($row=$results->fetch_assoc())
	{
		echo '<option value="'.$row['nume'].'">'.$row['nume'].'</option>';
	}
	echo '</select></div>';
	echo '<input type="submit" value="Search"></form>';
}

function advsearchpiese($nume,$category)
{
	/* Cautare avansata a unei piese */
	$conn2=connect();
	$sql="SELECT * FROM `adrese`";
	$rezultate=$conn2->query($sql);
	while ($rrow=$rezultate->fetch_assoc())
	{
		$conn=connectdbdist($rrow['ip'],$rrow['user'],$rrow['parola'],$rrow['nume']);	
		$sql="SELECT * FROM `piese` WHERE categorie='$category' and (titlu LIKE '%$nume%' or detalii LIKE '%$nume%' or autor LIKE '%$nume%')"; 
		$results=$conn->query($sql);
		if ($results->num_rows==0)
		{
			echo 'Nu s-au gasit rezultate';
		}
		while ($row=$results->fetch_assoc())
		{
			$id=$row['id'];
			$idalbum=$row['album'];
			$sql2="SELECT * FROM `albume` WHERE id='$idalbum'";
			$sql3="SELECT * FROM `piese` WHERE id='$id'";
			$results2=$conn->query($sql2);
			$row2=$results2->fetch_assoc();
			if ($result2->num_rows==0)
			{
				$titlualbum="N/A";
			}
			else
			{
				$row3=$result3->fetch_assoc();
				$titlualbum=$row2['titlu'];
			}
			$results3=$conn2->query($sql3);
			$row3=$results3->fetch_assoc();
			frontendPiese($row['titlu'],$row3['imagine'],$row['autor'],$titlualbum,$row3['pret'],$row['data'],$row3['categorie'],$id,$row['trailer']);
		}
		$conn->close();
	}
	$conn2->close();
}

function playlistfrontend($user,$iidd)
{
	/* Creaza frontendul in momentul in care vrem sa adaugam un cantec intr-un playlist */
	$conn=connect();
	$sql="SELECT id FROM `users` WHERE user='$user'";
	$results=$conn->query($sql);
	$row=$results->fetch_assoc();
	$id=$row['id'];
	$sql="SELECT * FROM `playlist` WHERE user_id='$id'";
	$results=$conn->query($sql);
	echo '<form action="addinplaylist.php" method="post">';
	echo '<label>Playlist:</label><input type="hidden" value="'.$iidd.'" name="idpiesa"><select name="playlist">';
	while ($row=$results->fetch_assoc())
	{
		echo '<option value="'.$row['id'].'">'.$row['nume'].'</option>';
	}
	echo '</select><input type="submit" value="Adauga">';
	echo '</form><form action="createplaylist.php" method="POST"><label>Nume playlist:</label><input type="text" name="nume"><br><input type="submit" value="Creare playlist"></form>';
	$conn->close();
}

function playlistfrontend2($user)
{
	/* Afiseaza playlisturile (frontend pt pagina playlist) */
	$conn=connect();
	$sql="SELECT id FROM `users` WHERE user='$user'";
	$results=$conn->query($sql);
	$row=$results->fetch_assoc();
	$id=$row['id'];
	$sql="SELECT * FROM `playlist` WHERE user_id='$id'";
	$results=$conn->query($sql);
	echo '<form action="listenplaylist.php" method="post">';
	echo '<label>Alege Playlist:</label><select name="playlist">';
	while ($row=$results->fetch_assoc())
	{
		echo '<option value="'.$row['id'].'">'.$row['nume'].'</option>';
	}
	echo '</select><input type="submit" value="Asculta">';
	echo '</form>';
	$conn->close();
}

function addinplaylist($id,$idplaylist)
{
	/* Adauga o piesa intr-un playlist */
	$conn=connect();
	$sql="SELECT * FROM `playlist` WHERE id='$idplaylist'";
	$results=$conn->query($sql);
	$row=$results->fetch_assoc();
	$piese=$row['piese'];
	$nrpiese=$row['nrpiese'];
	$nrpiese++;
	$piese=$piese.$id.",";
	$sql = "UPDATE `playlist` SET piese='$piese' , nrpiese='$nrpiese' WHERE id='$idplaylist'"; 
	if ($conn->query($sql) === TRUE) {
	  echo '<p style="margin-left:5%;">Melodie adaugata</p>';
	}
	else {
	 echo 'Error: '. $conn->error;
	}
	$conn->close();
}

function createPlaylist($user,$nume)
{
	/* Creaza un nou playlist */
	$conn=connect();
	$sql = "INSERT INTO `playlist` (user_id,nume,nrpiese)
	VALUES ('$user','$nume','0')"; 
	if ($conn->query($sql) === TRUE) {
	  echo '<p style="margin-left:5%;">Playlist creat</p>';
	}
	else {
	 echo 'Error: '. $conn->error;
	}
	$conn->close();
}

function audioplayerplaylist($src,$title,$nr)
{
	/* Playerul pt un playlist */
	echo '<div id="audioplayer">';
	echo '<script src="audioplayer.js"></script>';
	echo '<audio id="AP" src="'.$src[0].'" controls preload="auto" autobuffer onended="nexttrack()" onload="write()"></audio>';
	echo '<h3 id="APname">'.$title[0].'</h3><br><div class="APpositioning"><p id="APtime">00:00</p><select id="APrate" name="APrate" onchange="changespeed()">
		<option value="0.5">Slow(0.5)</option>
		<option value="1" SELECTED>Normal(1x)</option>
		<option value="2">Fast(2x)</option>
		<option value="4">Very fast(4x)</option>
	</select><input type="button" value="Next track" onclick="nexttrack()" id="nexttrackbut"><input id="repeatbut" type="image" src="images/repeat.png" alt="Repeat playlist" onclick="repeatplaylist()">
	</div>
	<a href="javascript:null()" onclick="startplay()" id="APplay" class="APpositioning"><img src="images/play.png" alt="play"></a>
	<a href="javascript::null()" onclick="startpause()" id="APpause" class="APpositioning"><img src="images/pause.png" alt="pause"></a>
	</div>';
	echo '<input type="button" class="songs active" name="'.$src[0].'" value="'.$title[0].'" onclick="changetrk(this)"><br>';
	for ($i=1;$i<$nr;$i++)
	{
			echo '<input type="button" class="songs" name="'.$src[$i].'" value="'.$title[$i].'" onclick="changetrk(this)"><br>';
	}

}

function listenplaylist()
{
	/* Reda un playlist */
	$id=$_POST['playlist'];
	$cconn=connect();
	$sql="SELECT * FROM playlist WHERE id='$id'"; 
	$rrezultate=$cconn->query($sql);
	$rrrow=$rrezultate->fetch_assoc();
	$surse=array();
	$titluri=array();
	$piese=str_split($rrrow['piese']);
	$nr=$rrrow['nrpiese'];
	$j=0;
	for ($i=0;$i<$rrrow['nrpiese'];$i++)
	{
		$id=$piese[$j];
		$j++;
		while ($piese[$j]!=',')
		{
			$id=$id*10+$piese[$j];
		}
		$sql="SELECT id_bd FROM `piese` WHERE id='$id'";
		$rezultate=$cconn->query($sql);
		$rrow=$rezultate->fetch_assoc();
		$id_bd=$rrow['id_bd'];
		$sql="SELECT * FROM `adrese` WHERE id='$id_bd'";
		$rezultate=$cconn->query($sql);
		$rrow=$rezultate->fetch_assoc();
		$conn=connectdbdist($rrow['ip'],$rrow['user'],$rrow['parola'],$rrow['nume']);	
		$sql="SELECT titlu,continut FROM `piese` WHERE id='$id'";
		$results=$conn->query($sql);
		$row=$results->fetch_assoc();
		array_push($surse,$row['continut']);
		array_push($titluri,$row['titlu']);
		$j++;
		$conn->close();
	}
	audioplayerplaylist($surse,$titluri,$nr);
	$cconn->close();
}

function replyfrontend()
{
	$id=$_GET['id'];
	$user=$_GET['to'];
	$conn=connect();
	$sql="SELECT * FROM `pm` WHERE id='$id'";
	$rezultate=$conn->query($sql);
	$row=$rezultate->fetch_assoc();
	echo '<div id="subiect">'.$row['subiect'].'</div><br>';
	echo '<div id="mesaj">'.$row['mesaj'].'</div>';
	echo '<br><h2>Reply:</h2><br><form method="post" action="send.php">';
	echo '<input type="hidden" value="RE:'.$row['subiect'].'" name="subiect"><input type="hidden" value="'.$user.'" name="to"><label for"mesaj">Mesaj:</label><textarea cols="25" rows="10" name="mesaj"></textarea><br><input type="submit" value="Trimite">';
	echo '</form>';
}

function pmfrontend()
{
	$user=$_GET['to'];
	echo '<form method="post" action="send.php">';
	echo '<input type="hidden" value="'.$user.'" name="to"><label for="subiect">Subiect:</label><input type="text" name="subiect"><br><label for="mesaj">Mesaj:</label><textarea cols="25" rows="10" name="mesaj"></textarea><br><input type="submit" value="Trimite">';
	echo '</form>';
}

function pmfrontend2()
{
	echo '<form method="post" action="send.php">';
	echo '<label for="to">Destinatar:</label><input type="text" name="to"><br><label for="subiect">Subiect:</label><input type="text" name="subiect"><br><label for="mesaj">Mesaj:</label><textarea cols="25" rows="10" name="mesaj"></textarea><br><input type="submit" value="Trimite">';
	echo '</form>';
}

function showmesagessend()
{
	$user=$_SESSION['user'];
	$conn=connect();
	$sql="SELECT * FROM `pm` WHERE `from`='$user' ORDER BY data DESC";
	$rezultate=$conn->query($sql);
	while ($row=$rezultate->fetch_assoc())
	{
		echo '<div class="mesaje">';
		echo '<a href="reply.php?id='.$row['id'].'&to='.$row['to'].'">';
		echo '<div class="inmesaje">'.$row['subiect'].'</div><div class="inmesaje">TO:'.$row['to'].'</div><div class="inmesaje">Data:'.$row['data'].'</div></a>';
		echo '</div>';
	}
	$conn->close();
}

function showmesagesrecieved()
{
	$user=$_SESSION['user'];
	$conn=connect();
	$sql="SELECT * FROM `pm` WHERE `to`='$user' ORDER BY data DESC";
	$rezultate=$conn->query($sql);
	while ($row=$rezultate->fetch_assoc())
	{
		echo '<div class="mesaje">';
		echo '<a href="reply.php?id='.$row['id'].'&to='.$row['from'].'">';
		echo '<div class="inmesaje">'.$row['subiect'].'</div><div class="inmesaje">FROM:'.$row['from'].'</div><div class="inmesaje">Data:'.$row['data'].'</div></a>';
		echo '</div>';
	}
	$conn->close();
}

function sendpm()
{
	$conn=connect();
	$from=$_SESSION['user'];
	$to=$_POST['to'];
	$mesage=$_POST['mesaj'];
	$subiect=$_POST['subiect'];
	$data=date("Y/m/d");
	$sql = "INSERT INTO `pm` (`from`,`to`,mesaj,subiect,data)
	VALUES ('$from','$to','$mesage','$subiect','$data')"; 
	if ($conn->query($sql)===FALSE)
	{
		echo 'Error: '. $conn->error;
	}
	$conn->close();
}

?>