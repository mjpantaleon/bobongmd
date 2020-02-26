<?php

/** Set flag that this is a parent file */
define( "MAIN", 1 );

//require connection to the database
require('db_con.php');

if(!isset($_REQUEST['fn'])){
	exit("DATA NOT PROVIDED!");
}else{
	if(!function_exists($_REQUEST['fn'])){
		exit("FUNCTION NOT DEFINED!");
	}else{
		exit($_REQUEST['fn']());
	}
}

function get_events(){
	$query 		= " SELECT `e`.`UID`,	 ud.`FN`, `e`.`E_D`,`e`.`E_TS`, ud.`REL` 
					FROM `events` e
					LEFT JOIN `user_details` ud ON e.`UID` =  ud.`UID`
					ORDER BY `E_ID` ASC ";

	$result 	= mysql_query($query) or die(mysql_error());

	$list = [];

	while($row = mysql_fetch_array($result)){
		$row['3'] = date("F d, Y H:i:s A",strtotime($row['3'])); #change date format
		$list[] = $row;
	}

	$response = [
		'data' => $list
	];
	exit(json_encode($response));
}


function explainEventDescription(){
	$description = "[ Melissa Rae M Caparros ] At page: [ g_teacherSec.php ]";
	if(substr_count('User login', $description) > 0){
		return $description;
	}


	
	// Slice Part 1
	$strpos1 = strpos($description, '[');
	$strpos2 = strpos($description,']') + 1;
	$part1 = substr($description, $strpos1,$strpos2-$strpos1);

	$description = str_replace($part1, '', $description);

	$part1 = str_replace('[ ', '', $part1);
	$part1 = str_replace(' ]', '', $part1);
	$part1 = trim($part1);

	// Slice Part 3
	$strpos1 = strpos($description, '[');
	$strpos2 = strpos($description,']') + 1;
	
	$part3 = substr($description, $strpos1,$strpos2-$strpos1);
	$description = str_replace($part3, '', $description);

	$part3 = str_replace('[ ', '', $part3);
	$part3 = str_replace(' ]', '', $part3);	
	$part3 = trim($part3);
	// Slice Part 2
	$part2 = trim($description);

	$action = null;

	switch(strtoupper($part2)){
		case 'AT PAGE:':
			$action = 'visit the page';
		break;
	}

	switch($part3){
		case 'g_teacherSec.php':
			$part3 = "of Teacher's Lectures";
		break;
	}

	$real_description = $part1.' '.$action.' '.$part3;

	exit($real_description);
	
}

?>