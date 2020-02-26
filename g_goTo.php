<?php
/** Set flag that this is a parent file */
define( "MAIN", 1 );


//require connection to the database
require('db_con.php');
log_hit();

//gets the page name
$page = mysql_real_escape_string(basename($_SERVER['SCRIPT_NAME']));


//start session
session_name( 'bbmdsession' );
session_start();
$FN 	= $_SESSION['fullname'];
$UT_ID 	= $_SESSION['access'];
$UID 	= $_SESSION['session_user_id'];
$UT_N 	= $_SESSION['access_type'];
$PIC	= $_SESSION['prof_pic'];
$UN		= $_SESSION['session_username'];


//LOG THIS EVENT
$time_in = date("H:i:sa");							
$cur_date = date("Y-m-d");
$cur_timestamp = $cur_date." ".$time_in;	//concatinate time + current date

$query = "	INSERT INTO events SET E_TS = '$cur_timestamp', UID = '$UID', E_D = '[ ".$FN." ] At page: [ ".$page." ]'	";
mysql_query($query)
or die(mysql_error());


	#GET SUBJECT FROM NOTIFICATION
	$SUBJ		= $_GET['subj'];
	$notify_id	= $_GET['ref'];
	
	#MUST REDIRECT TO EACH RESPECTIVE LOCATION
	if($SUBJ == 'Blog')
	{
		#LOG THIS IN THE `notify_hits` TABLE
		$query	= " INSERT INTO `notify_hits` SET
					UID			= '".$UID."',
					notify_id	= '".$notify_id."'
		";
		mysql_query($query)
		or die(mysql_error());
		
		
		#REDIRECT TO THIS PAGE
		echo "<script>document.location.href='g_blogs.php';</script>\n";
		exit();	
	}
	
	
	


?>