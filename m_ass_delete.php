<?php

//set this file as parent
define( 'MAIN', 1 );

require('db_con.php');	//require connection

//start session
session_name( 'bbmdsession' );
session_start();
$FN 	= $_SESSION['fullname'];
$UT_ID 	= $_SESSION['access'];
$UID	= $_SESSION['session_user_id'];
$UT_N 	= $_SESSION['access_type'];
$PIC	= $_SESSION['prof_pic'];


	$getID	= $_GET['id'];	//get the value of the id sent
	
	/**/
	$query	= " DELETE FROM assignment WHERE id = '$getID' ";
	$result	= mysql_query($query)
	or die(mysql_error());
	
	//LOG THIS EVENT
	$time_in = date("H:i:sa");							
	$cur_date = date("Y-m-d");
	$cur_timestamp = $cur_date." ".$time_in;	//concatinate time + current date
	
	$query = "	INSERT INTO events SET E_TS = '$cur_timestamp', UID = '$UID', E_D = '[ ".$FN." ] Delete lecture: [ ".$getID." ]'	";
	mysql_query($query)
	or die(mysql_error());
	
	$Action = "Delete";
	

	header("location: m_assign_details.php?action=$Action&&ts=$getID");


?>