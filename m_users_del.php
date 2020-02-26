<?php

//set this file as parent
define( 'MAIN', 1 );

//require connection
require('db_con.php');

//start session
session_name( 'bbmdsession' );
session_start();
$FN 	= $_SESSION['fullname'];
$UT_ID 	= $_SESSION['access'];
$UID	= $_SESSION['session_user_id'];
$UT_N 	= $_SESSION['access_type'];
$PIC	= $_SESSION['prof_pic'];

//get the value of variable 'uid' then place it on a variable '$UID'
$getUID = $_GET['uid'];


	//DELETE THE USER WHOS USER ID IS EQUAL TO THE VALUE OF  VARIABLE '$UID'
	//execute query here
	$query 	= " DELETE FROM user_details WHERE UID = '$getUID' ";
	$result	= mysql_query($query)
	or die(mysql_error());
	//$row	= mysql_fetch_array($result);
	
	$query	= " DELETE FROM users WHERE UID = '$getUID' ";
	$result	= mysql_query($query)
	or die(mysql_error());
	//$row	= mysql_fetch_array($result);
	
	
	//LOG THIS EVENT
	$time_in = date("H:i:s",time() + (960 * 120));							
	$cur_date = date("Y-m-d");
	$cur_timestamp = $cur_date." ".$time_in;	//concatinate time + current date
	
	$query = "	INSERT INTO events SET E_TS = '$cur_timestamp', UID = '$UID', E_D = '[ ".$FN." ] Deleted User: [ ".$getUID." ]'	";
	mysql_query($query)
	or die(mysql_error());
	
	$Action = "Delete";
	
	
	//then redirect to the admin users page
	/*echo "<script>document.location.href='m_users.php';</script>\n";
	exit();*/
	
	header("location: m_user_details.php?action=$Action&&user=$getUID");
?>