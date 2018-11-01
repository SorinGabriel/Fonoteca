<?php

	//CRON JOB STATUS CHECK (verifica daca userii mai sunt on )
	include 'engine.php';
	$conn=connect();
	$sql="SELECT * FROM `users`";
	$results=$conn->query($sql);
	while ($row=$results->fetch_assoc())
	{
		$t=time();
		if ($t-$row['lastactivity']>300 && $row['status']=="On")
		{
			$id=$row['id'];
			$sql = "UPDATE `users` SET status='Off' WHERE id='$id'"; 
			if ($conn->query($sql) === FALSE) {
			 echo 'Error: '. $conn->error;
			}
		}
	}
	echo "SUCCES".time();
	$conn->close();

?>