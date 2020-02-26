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
					WHERE ST = '1' AND TIT LIKE '%$Q%'
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
		//$r_LINK		= $row['LINK'];		//File link
		$r_TS		= $row['TS'];		//time stamp
		$r_ST		= $row['ST'];		//disable flag
		$r_LINK		= "a_ts_dload.php?id=".$r_id."";
		
		//getting the elapsed time of this post
		$now	= time();
		$past	= strtotime($r_TS);
		
		
		//change format of the category(int) into string
		if($r_CAT == 1)
			$r_CAT = "PDF";
		
		else
			$r_CAT = "Presentation";
			
		
		$table.="
				<tr>
            	<td valign='top' align='left'>
				
				<div class='LectureWrap2'>
                		<div style='float:left;'><img src='".$r_CatPic."' width='55' height='65' /></div>
                        <div style='padding-right: 15px;'><h5>".$r_Title."</h5></div>
                        <div style=''><span class='s_normal'><p style='padding-right: 15px;'>".substr($r_DESC, 0, 80)."...</p></span></div>
                        <div style=''>
							<span class='ss_normal'>".substr(time_elapsed($now-$past), 0, 15)."</span>
							<div style='float:right'><a href='".$r_LINK."' title='Download this file'>Download</a></div>
						</div>
                </div>
				
				    </td>
            	</tr>
				
				";

	}
	
	$table.="
		</table>
	";
	//end while
	echo $table;	//echo the result table
}
//end if Q
?>