<?php

//set that this location cant be access directly
defined( 'MAIN' ) or die( 'Direct access to this location is not allowed!' );
require ('db_conf.php');

//IF NOT SAME CONFIGURATION THEN FORCE THE PAGE TO BE OFFLINE
if(!($con = @mysql_connect( $host, $user, $password)))
{
	require('header.php');
	//require('offline.php');
	exit();
}

//IF NOT SAME DATABASE NAME THEN FORCE THE PAGE TO BE OFFLINE
if(!(@mysql_select_db($dbname)))
{
	require('header.php');
	//require('offline.php');
	exit();
}







//////////////////////////////////// FUNCTIONS SECTION BELOW ////////////////////////////////

//logs a page view to the current page
function log_hit()
{
	//echo $_SERVER['SCRIPT_NAME'];
	$page = mysql_real_escape_string(basename($_SERVER['SCRIPT_NAME']));//will cut the whole path rather just get the final slash value
	//echo $page;//echo value
	
	$sql = "INSERT INTO `hits` (`page_name`, `hits`)
			VALUES ('{$page}', 1)
			ON DUPLICATE KEY UPDATE `hits` = `hits` + 1";
	mysql_query($sql)
	or die(mysql_error());
}

//get the hits from the database
function get_hits()
{
	$query = mysql_query("SELECT page_name, hits FROM hits");
	$hits = array();
	while(($row = mysql_fetch_assoc($query)) !== false)
	{
		$hits[] = $row;
	}
	
	return $hits;
}


//getting the elapsed time of the posted record
function time_elapsed($secs)
{
	$bit	= array(
					
			'y'		=> $secs / 31556926 % 12,
			'w'		=> $secs / 604800 % 52,
			'd'		=> $secs / 86400 % 7,
			'h'		=> $secs / 3600 % 24,
			'm'		=> $secs / 60 % 60,
			's'		=> $secs % 60
			
			);
	
	foreach($bit as $k => $v)
		if($v > 0) $ret[] = $v . $k;

	//use the magic @ sign to skip the error part \m/ hell yeah!
	return @join(' ', $ret);
}

#Countdown function
$target	= mktime(20, 0, 0, 9, 25, 2013);	
$today	= time();
$diff	= ($target - $today);
$day	= (int) ($diff/86400);

#echo "My b-day will begin in '".$day."' days";


// Event Logging
/*function log($UID,$FN,$page){
	$query = "	INSERT INTO events SET E_TS = Now(), UID = '$UID', E_D = '[ ".$FN." ] At page: [ ".$page." ]'	";
	mysql_query($query)
	or die(mysql_error());
}*/

?>