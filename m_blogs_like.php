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
$UID	= $_SESSION['session_user_id'];
$UT_N 	= $_SESSION['access_type'];
$PIC	= $_SESSION['prof_pic'];
$UN		= $_SESSION['session_username'];
/**/
//LOG THIS EVENT
$time_in = date("H:i:sa");							
$cur_date = date("Y-m-d");
$cur_timestamp = $cur_date." ".$time_in;	//concatinate time + current date

$query = "	INSERT INTO events SET E_TS = '$cur_timestamp', UID = '$UID', E_D = '[ ".$FN." ] At page: [ ".$page." ]'	";
mysql_query($query)
or die(mysql_error());



//if user dont have access then redirect then to unautorized page
if( ($UT_ID!=1) && ($UT_ID!=2) && ($UID =='') )
{
	echo "<script>document.location.href='unauthorized.php';</script>\n";
	exit();
}

$Count 		= 0;


#If like is been clicked then
if(isset($_GET['blogID']))
{
	$getBlogID	= $_GET['blogID'];
	$getUID		= $_GET['userID'];
	
	//Update blogs table
	
	if(mysql_num_rows(mysql_query(" SELECT userID,blogID FROM blog_hits WHERE userID = '".$getUID."' AND blogID = '".$getBlogID."' "))> 0)
	{
		header("location:m_blogs_details.php?blogID=$getBlogID&flag=1");
	}
	
	//check first if this user has already like this blog
	
	//if doenst liked this before then
	else
	{
		//Update blogs table
		$query	= "	SELECT LIKES
					FROM blogs
					WHERE
					id	= '".$getBlogID."'
				  ";
		$result	= mysql_query($query);
		$row	= mysql_fetch_array($result);
		//initialize local variable
		$likes	= $row['LIKES'];	//total likes in the table
		$increment	= 1;
		
		$totalLikes	= $likes + $increment;
		
		//then we update it to the blogs table
		mysql_query(" UPDATE blogs
					  SET
					  LIKES = '".$totalLikes."'
					  WHERE id = '".$getBlogID."'
					")
		or die(mysql_error());
		
		
		//LIKE
		$query	= " INSERT INTO
					blog_hits
					SET
					userID	= '".$UID."',
					blogID	= '".$getBlogID."'
				  ";
		mysql_query($query)
		or die(mysql_error());
		
		
		//log this event
		$time_in = date("H:i:sa");							
		$cur_date = date("Y-m-d");
		$cur_timestamp = $cur_date." ".$time_in;	//concatinate time + current date
		
		$query = "	INSERT INTO events SET E_TS = '$cur_timestamp', UID = '$UID', E_D = '[ ".$FN." ] likes blog: [ ".$getBlogID." ]'	";
		mysql_query($query)
		or die(mysql_error());
		
		header("location:m_blogs_details.php?blogID=$getBlogID&flag=1");
		
	}
	//if doenst liked this before then 
	

}
#If like is been clicked then

?>