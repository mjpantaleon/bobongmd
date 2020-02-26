<?php

/** Set flag that this is a parent file */
define( "MAIN", 1 );


//require connection
require('db_con.php');

//gets whatever value inputed in the search box
$Q = $_GET['q'];


//if search box has a value then
if($Q)
{
	//echo the top part of the table
	$result=" <table border='0' cellspacing='0' cellpadding='0'> ";
		
	//set limit
	# $Limit2 	= 2;	//number of columns
	# $Count		= 0;	//default value is 0	
		
	//We select the user WHOSE user type id is equal to 2 and whatever value inputed in the search box THEN order is by user id Descending			
	#$query		= " SELECT * FROM user_details WHERE UT_ID = '2' AND UN LIKE '%$Q%' ORDER BY UID DESC ";
	#$result2	= mysql_query($query);
	 $per_page	= 2;
			
		$Limit	= 2;	//number of columns or records to display per row
		$Count	= 0;	//zero default value
		
		$query	= mysql_query(" SELECT COUNT(`UID`) FROM `user_details` WHERE UID != '".$UID."' AND UT_ID != '1' ")		//this only serves as a counter
		or die(mysql_error());
		$pages	= ceil(mysql_result($query, 0) / $per_page);				//use ciel to get the round of the higher value
				
		$page	= ( isset( $_GET['page']))  ? (int)$_GET['page'] : 1;
		$start	= ($page - 1) * $per_page;
		
		//DISPLAY ALL THE USERS IN THE SYSTEM WHOS STATUS IS ACTIVE AND NOT THE USER CURRENTLY LOGGED IN
		/* DO NOT FORGET THE START.", ".per_page  IMPORTANT */
		$query	= " SELECT *
					FROM `user_details`
					WHERE UT_ID != '1' AND FN LIKE '%$Q%'
					ORDER BY UID DESC LIMIT ".$start.", ".$per_page."
				  ";
		$result	= mysql_query($query)
		or die(mysql_error());
		while($row = mysql_fetch_array($result))
		{
			//declare variables
			$e_UID	= $row['UID'];		//user id
			$e_UN 	= $row['UN'];		//user name
			$e_FN	= $row['FN'];		//Full name
			$e_EM	= $row['EM'];		//Email
			$e_CN	= $row['CN'];		//Contat #
			$e_AD	= $row['AD'];		//Address
			$e_REL	= $row['REL'];		//Relation
			$e_ST	= $row['ST'];		//Status
			$e_PIC	= $row['PIC'];		//Picture
			
			
			//if COunt(0) is less then limit(2)
			
			if($Count < $Limit)
			{
				//if count is equal to 0 then
				if($Count == 0)
				{
					//we echo the opening row
					$divUsers="
					<tr>
					";
				}
				
				//then we echo the column inside the row
				$divUsers.="
				<td>
					<div class='userRow' style=' margin: 25px 15px; box-shadow: 0 1px 3px #FFF; padding: 5px;'>
						<div class='colWrap_left'>
							<div class='picWrap'>
								<a href='m_user_details.php?user=".$e_UID."' title='View Profile' alt='View Profile'>
									<img src='".$e_PIC."' width='95px' height='95px' />
								</a>
							</div>
							
							<div class='UserFNwrap'>
								<div class='userFN'>
									<a href='m_user_details.php?user=".$e_UID."' title='View Profile' alt='View Profile'>
										<h5>".$e_FN."</h5>
									</a>
								</div>
							</div>
							
							<div class='msgIconWrap'>
								<span class='s_normal'>".$e_REL."</span>
							</div>
						</div>
					</div>
				</td>
				";
			}
			//if COunt(0) is less then limit(2)
			
			
			//else
			else
			{
				$Count = 0;	//set the value of count into 0
				
				//close the 1st row then open another opening row
				$divUsers.="
				</tr>
				
				<tr>
					<td>
						<div class='userRow' style=' margin: 10px 5px; box-shadow: 0 1px 3px #FFF; padding: 5px;'>
							<div class='colWrap_left'>
								<div class='picWrap'>
									<a href='m_user_details.php?user=".$e_UID."' title='View Profile' alt='View Profile'>
										<img src='".$e_PIC."' width='95px' height='95px' />
									</a>
								</div>
								
								<div class='UserFNwrap'>
									<div class='userFN'>
										<a href='m_user_details.php?user=".$e_UID."' title='View Profile' alt='View Profile'>
											<h5>".$e_FN."</h5>
										</a>
									</div>
								</div>
								
								<div class='msgIconWrap'>
									&nbsp;
								</div>
							</div>
						</div>
					</td>
					
				";
			}
			//else
			$Count++;	//increment value of count
			
			
		}
		//end while
		/*
		<img src='images/icons/toolbar-icons/mail.png' width='20' height='20' />
		<input type='button' class='button3' value='Send Message' title='Send Message' />
		*/
		
				//then we close the row
				$divUsers.="
				</tr>
				";
		
		

 
			//We select the user WHOSE user type id is equal to 2 and whatever value inputed in the search box THEN order is by user id Descending

	//echo lower part of the table
	$divUsers.="
	</table> 
	
	";
	
	
	/*$result.="<div class='center' style='margin-top: 15px;'><?php echo $paginationDisplay; ?></div>";*/
	
	//then we echo the result
	echo $divUsers;	//echo result
}
//if search box has a value then

?>