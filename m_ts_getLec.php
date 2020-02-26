<?php
/** Set flag that this is a parent file */
define( "MAIN", 1 );


//require connection
require('db_con.php');

//gets whatever value inputed in the search box
$Q = $_GET['q'];


if($Q)
{
	//echo the top part of the table
	$table=" <table border='0' cellspacing='0' cellpadding='0'> ";
	
	$Limit	= 3;	//number of columns or records to display per row
	$Count	= 0;	//zero default value

	$query		= " SELECT *
					FROM teachersec
					WHERE TIT LIKE '%$Q%'
					ORDER BY id DESC ";
	 $result	= mysql_query($query)
	 or die(mysql_error());
	 
	 $r_CatPic_x	= "images/icons/teacherSec/locked_item.png";
					
	while($row	= mysql_fetch_array($result))
	{
		//set variables
		$r_id		= $row['id'];		//record id
		$r_Title	= $row['TIT'];		//Title
		$r_DESC		= $row['DESK'];		//Description
		$r_CAT		= $row['CAT'];		//Category
		$r_CatPic	= $row['CatPic'];	//Category picture
		$r_LINK		= $row['LINK'];		//File link
		$r_TS		= $row['TS'];		//time stamp
		$r_ST		= $row['ST'];		//disable flag

		
		//getting the elapsed time of this post
		$now	= time();
		$past	= strtotime($r_TS);
		
		
		//change format of the category(int) into string
		if($r_CAT == 1)
			$r_CAT = "PDF";
		
		else
			$r_CAT = "Presentation";
			
		
		//create a statement that will check if the count is equal to the limit set
		//if count(0) is less then limit then
		if($Count < $Limit)
		{
			//if count is zero then
			if($Count == 0)
			{
				//echo the opening row
				$table.=" 
					<tr> 
				";
			}
			
			$table.="
			
				<td>
					<div style='margin: 15px; border:1px solid #CCC; width: 180px; background-color:#F5F5F5;'>
					
					";
					
					//change this button if the status of this item is 0 or 1
					//if status is 1 then
					if($r_ST != 0)
					{
						$table.="
						
						<div class='center' style='border: 1px solid #F5F5F5; background-color:#FFF;'>
							<a href='m_ts_edit.php?id=".$r_id."'>
							<img src='".$r_CatPic."' width='176' height='176' style='border:1px solid #CCC;' /></a>
						</div>
						
						";
					}
					
					//else if status is 0 then
					else
					{
						$table.="
						
						<div class='center' style='border: 1px solid #F5F5F5; background-color:#FFF;'>
							<a href='m_ts_edit.php?id=".$r_id."'>
							<img src='".$r_CatPic_x."' width='176' height='176' style='border:1px solid #CCC;' /></a>
						</div>
						
						";
					}
					
					$table.="
					
						<div class='left' style=''><span class='s_normalb'>".$r_Title."</span></div>
						<div class='left' style=' height:40px'><span class='s_normal'>".substr($r_DESC, 0, 35)."...</span></div>
						<div class='left' style=''>
							<span class='s_normal'>".$r_CAT."</span>
							<span style='float: right'><a class='dloadBtn' href='".$r_LINK."' title='Download this lecture'>Download</a></span>
						</div>
						<div class='right'><span class='ss_normal'>".substr(time_elapsed($now-$past), 0, 15)." ago</span></div>
					</div>
				</td>
			
			";
		}
		//if count(0) is less then limit then
		
		//if count(0) id equal to limit(3) then
		else
		{
			$Count = 0;	//set the value to zero
			
			$table.="</tr>
			<tr>
			
				<td>
					<div style='margin: 15px; border:1px solid #CCC; width: 180px; background-color:#F5F5F5;'>
					
					";
					
					if($r_ST != 0)
					{
						$table.="
					
						<div class='center' style='border: 1px solid #F5F5F5; background-color:#FFF;'>
							<a href='m_ts_edit.php?id=".$r_id."'>
							<img src='".$r_CatPic."' width='176' height='176' style='border:1px solid #CCC;' /></a>
						</div>
						
						";
					}
					
					else
					{
						$table.="
						
						<div class='center' style='border: 1px solid #F5F5F5; background-color:#FFF;'>
							<a href='m_ts_edit.php?id=".$r_id."'>
							<img src='".$r_CatPic_x."' width='176' height='176' style='border:1px solid #CCC;' /></a>
						</div>
						
						";
					}
					
					$table.="
					
						<div class='left' style=''><span class='s_normalb'>".$r_Title."</span></div>
						<div class='left' style=' height:40px'><span class='s_normal'>".substr($r_DESC, 0, 35)."...</span></div>
						<div class='left' style=''>
							<span class='s_normal'>".$r_CAT."</span>
							<span style='float: right'><a class='dloadBtn' href='".$r_LINK."' title='Download this lecture'>Download</a></span>
						</div>
						<div class='right'><span class='ss_normal'>".substr(time_elapsed($now-$past), 0, 15)." ago</span></div>
					</div>
				</td>
			
			";
			
		}
		//if count(0) id equal to limit(3) then
		
		$Count++;	//increment the value of count every time it loops
		
		//create a statement that will check if the count is equal to the limit set

	}
	
	//we close the 1st row with 3 columns
	$table.="
		</tr>
	";
	
	
	$table.="
		</table>
	";
	//end while
	echo $table;	//echo the result table
}
//end if Q
?>