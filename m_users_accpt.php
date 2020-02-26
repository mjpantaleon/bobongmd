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

	$query		= " SELECT UN FROM user_details WHERE UID = '$getUID' ";
	$result		= mysql_query($query);
	$row		= mysql_fetch_array($result);
	$r_UN		= $row['UN'];		//User Name
	$r_FN		= $row['FN'];		//Full Name

	//ACCEPT THE USER WHOS USER ID IS EQUAL TO THE VALUE OF  VARIABLE '$UID'
	//We change the status of the user selected so that he/she can access the system
	$query 	= " UPDATE user_details SET ST = '1' WHERE UID = '$getUID' ";
	$result	= mysql_query($query)
	or die(mysql_error());
	//$row	= mysql_fetch_array($result);
	
	//We change the status of the user selected so that he/she can access the system
	$query2		= " UPDATE users SET ST = '1' WHERE UID = '$getUID' ";
	$result2	= mysql_query($query2)
	or die(mysql_error());
	//$row2	= mysql_fetch_array($result2);
	
	
	//SEND first message to this user
	$welcomeMsg	= "<p>Welcome! Youre account is now active. I highly recomend that you update your profile as soon as you read this message. 
					And also you can now send messages to me, download lectures and others stuffs. ENJOY! </p>";
	//setting up the date
	$today	= date("F j, Y");	//string/Int format
	$hr_s 	= date("H:i:sa");	//12hrs format
					
	$query3		=  " INSERT INTO `msgs` 
					 SET 
					 MSG = '".$welcomeMsg."', 
					 msgFrom = '".$UID."', 
					 msgTo	= '".$getUID."',
					 dateSent = '".$today."',
					 timeSent = '".$hr_s."',
					 MST = '1'
					 ";
	$result3 	= mysql_query($query3)
	or die(mysql_error());
	
	
	
	//LOG THIS EVENT
	$time_in = date("H:i:sa");							
	$cur_date = date("Y-m-d");
	$cur_timestamp = $cur_date." ".$time_in;	//concatinate time + current date
	
	$query = "	INSERT INTO events SET E_TS = '$cur_timestamp', UID = '$UID', E_D = '[ ".$FN." ] Accepted User: [ ".$getUID." ]'	";
	mysql_query($query)
	or die(mysql_error());
	
	$Action = "Accept";
	
	//then redirect to the admin users page
	/*
	echo "<script>document.location.href='m_users.php?mes=$Message';</script>\n";
	exit();*/
	
	header("location: m_user_details.php?action=$Action&&user=$getUID");
?>