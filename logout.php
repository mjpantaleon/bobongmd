<?php

	/** Set flag that this is a parent file */
	define( "MAIN", 1 );

	session_name( 'bbmdsession' );
	session_start();

	$FN 	= $_SESSION['fullname'];
	$UT_ID 	= $_SESSION['access'];
	$UID 	= $_SESSION['session_user_id'];

	//require database connection
	require ("db_con.php");
	
	
	//log this activity to system events		
	$time_in = date("H:i:sa");							
	$cur_date = date("Y-m-d");
	$cur_timestamp = $cur_date." ".$time_in;
	$query = "	INSERT INTO events SET E_TS = '$cur_timestamp', UID = '0', E_D = 'User logout'	";
	mysql_query($query);

	session_destroy();
	
	//redirrect user in the index page
	echo "<script>document.location.href='index.php'</script>\n";
	exit();
?>