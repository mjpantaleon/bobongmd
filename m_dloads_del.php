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


	#GET FILE ID
	$getFileID	= $_GET['fileID'];
	
	$query		= " SELECT LOC FROM `downloadables` WHERE id = '".$getFileID."' ";
	$result		= mysql_query($query);
	$row		= mysql_fetch_array($result);
	#SET LOCAL VARIABLES
	$r_link		= $row['LOC'];		//file location
	$Action		= 'Delete';
	
	
	#UNLIKE ATTACHMENT
	unlink($r_link);
	
	#DELETE QUERY
	$query = " DELETE FROM `downloadables` WHERE id = '$getFileID' ";
	$result	= mysql_query($query)
	or die(mysql_error());
	
	

	#LOG THIS EVENT
	$time_in = date("H:i:s",time() + (960 * 120));							
	$cur_date = date("Y-m-d");
	$cur_timestamp = $cur_date." ".$time_in;	//concatinate time + current date
	
	$query = "	INSERT INTO events SET E_TS = '$cur_timestamp', UID = '$UID', E_D = '[ ".$FN." ] Delete file: [ ".$getFileID." ]'	";
	mysql_query($query)
	or die(mysql_error());

	header("location: m_dloads.php?action=".$Action." ");
?>