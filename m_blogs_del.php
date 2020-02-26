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


	//get value
	$getBlogID	= $_GET['blogID'];	//blogID
	
	#WE WILL NEED TO GET THE LINK OF PICTURE TO BE ABLE TO DELETE IT TOGETHER WITH THE BLOG IT SELF
	
	//GET THE PATH/LINK OF THIS BLOG WHOS ID IS EQUAL TO THE SELECTED BLOG
	$query	= " SELECT LINK FROM blogs WHERE id = '".$getBlogID."' ";
	$result = mysql_query($query);
	$row	= mysql_fetch_array($result);
	//Initialize local variable
	$r_LINK = $row['LINK'];		//LINK or PATH of the picture * this will be use to delete the existing picture and be replace by the new upload

	#DELETE QUERY
	mysql_query(" DELETE FROM blogs WHERE id = '".$getBlogID."' ")
	or die(mysql_error());
	
	
	if($r_LINK == '')		
	{
		#there will be no execution of unlink
	}
	
	//else if theres picture attached then
	else
	{
		unlink($r_LINK);		//will remove the current picture attachment in the folder and in the database
	}
	
	//LOG THIS EVENT
	$time_in = date("H:i:s",time() + (960 * 120));							
	$cur_date = date("Y-m-d");
	$cur_timestamp = $cur_date." ".$time_in;	//concatinate time + current date
	
	$query = "	INSERT INTO events SET E_TS = '$cur_timestamp', UID = '$UID', E_D = '[ ".$FN." ] Deleted Blog: [ ".$getBlogID." ]'	";
	mysql_query($query)
	or die(mysql_error());
	

	#THEN WE SEND THIS BACK TO PREVIUS PAGE
	header("location:m_blogs.php?blogID=$getBlogID");

?>