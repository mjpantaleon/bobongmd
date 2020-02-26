<?php

 //set limit
 $Limit2 	= 2;	//number of columns
 $Count		= 0;	//default value is 0
 
 //we select the records in the table
 $query		= mysql_query(" SELECT * FROM user_details WHERE UT_ID = '2' ORDER BY UID DESC ")
 or die(mysql_error());
 
 
 //////////////////////////////////////////////////// PAGINATION SET UP ///////////////////////////////////////////////////
$nr = mysql_num_rows($query);					//get the total number of rows in the table
if(isset($_GET['pn']))							//get pn from url vars if it is present
{
	$pn = preg_replace('#[^0-9]#i','',$_GET['pn']); //filters everything except for numbers	
}

else //if the pn URL value is NOT present force it to be value of page number 1
{
	$pn = 1;
}

$itemPerPage = 6; //setting for how many item will be shown in every page

$lastPage = ceil($nr/$itemPerPage); //get the value of the last page in the last pagination set

//ensuring that the page is NOT less than 1 and NOT greater than the last page
if($pn < 1) //if page number is less than 1 then
{
	$pn = 1; //force it to be 1
}
elseif($pn > $lastPage) //if page is greater than the last page then
{
	$pn = $lastPage; //force it to be in the last page
}

///////////////////////////////////////creates a number to clicked between the next and the back
/////////////////////////////////////////////////////////////////////////////////////////////////
$centerPages = ""; //initialize the variable
$sub1 = $pn - 1;
$sub2 = $pn - 2;
$add1 = $pn + 1;
$add2 = $pn + 2;

if($pn == 1)
{
	$centerPages .= '<span class="pageActiveNum">'.$pn.'</span> &nbsp;';
	$centerPages .= '<span class="pageNumActive"><a href="'.$_SERVER['PHP_SELF']."?pn=".$add1.'">'.$add1.'</a></span> &nbsp;';
}
elseif($pn == $lastPage)
{
	$centerPages .= '<span class="pageNumActive"><a href="'.$_SERVER['PHP_SELF']."?pn=".$sub1.'">'.$sub1.'</a></span> &nbsp;';
	$centerPages .= '<span class="pageActiveNum">'.$pn.'</span> &nbsp;';
}
elseif($pn > 2 && $pn < ($lastPage - 1))
{
	$centerPages .= '<span class="pageNumActive"><a href="'.$_SERVER['PHP_SELF']."?pn=".$sub2.'">'.$sub2.'</a></span> &nbsp;';
	$centerPages .= '<span class="pageNumActive"><a href="'.$_SERVER['PHP_SELF']."?pn=".$sub1.'">'.$sub1.'</a></span> &nbsp;';
	$centerPages .= '<span class="pageActiveNum">'.$pn.'</span> &nbsp;';
	$centerPages .= '<span class="pageNumActive"><a href="'.$_SERVER['PHP_SELF']."?pn=".$add1.'">'.$add1.'</a></span> &nbsp;';
	$centerPages .= '<span class="pageNumActive"><a href="'.$_SERVER['PHP_SELF']."?pn=".$add2.'">'.$add2.'</a></span> &nbsp;';
}
elseif($pn > 1 && $pn < ($lastPage))
{
	$centerPages .= '<span class="pageNumActive"><a href="'.$_SERVER['PHP_SELF']."?pn=".$sub1.'">'.$sub1.'</a></span> &nbsp;';
	$centerPages .= '<span class="pageActiveNum">'.$pn.'</span> &nbsp;';
	$centerPages .= '<span class="pageNumActive"><a href="'. $_SERVER['PHP_SELF']."?pn=".$add1.'">'.$add1.'</a></span> &nbsp;';
}
/////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////end creates a number to clicked between the next and the back


//setting the limit range..the 2 values we place to choose a range of rows in the database in our query
$limit = 'LIMIT ' .($pn - 1) * $itemPerPage.','.$itemPerPage; 
//setting the limit range..the 2 values we place to choose a range of rows in the database in our query		



$query2 	= mysql_query(" SELECT * FROM user_details WHERE UT_ID = '2' ORDER BY UID DESC $limit ")//limit included
or die(mysql_error());


////////////////////////////////// If record of query2 is equal to 0 then DO NOT DISPLAY PAGINATION DISPLAY SETUP
if($query2)
{
		 /////////////////////////////////////// PAGINATION DISPLAY SET UP /////////////////////////////////////////////
		$paginationDisplay = ""; //initialize the pagination output
			
		if($lastPage != "1")
		{
			//$paginationDisplay .= "Page <strong>".$pn."</strong> of ".$lastPage;
			
			if($pn != 1)
			{
				$previous = $pn - 1;
				$paginationDisplay .= '<span class="page_navs"><a href="'.$_SERVER['PHP_SELF']."?pn=".$previous.'">Back</a></span>';
			}
			
			$paginationDisplay .= "<span class='paginationNumbers'>".$centerPages."</span>";
			
			if($pn != $lastPage)
			{
				$nextPage = $pn + 1;
				$paginationDisplay .= '<span class="page_navs"><a href="'.$_SERVER['PHP_SELF']."?pn=".$nextPage.'">Next</a></span>';
			}
		}
		
		//then we gona do a looping function to get all the records
		 while( $row = mysql_fetch_array($query2))
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
			
			//change the INT value of Status into string
			if($e_ST == 1)
				$e_ST = "Active";
			else
				$e_ST = "For Approval";
			 
			 
			 //then we must create a statement that will check whether the columns is already reach the limit(2)
			 
			 //if count (0) is less then limit (2) then
			 if($Count < $Limit2)
			 {
				 //if count is 0 then
				 if($Count == 0)
				 {
					 //we create the 1st row
					echo "<tr>";
				 }
			 
				//check if the the Status of user is active then
				//if status of user is active 
				if($e_ST == "Active")
				{
					//then we create the columns inside the row
					 echo "
						<td valign='top' align='left' style='border-bottom: 1px solid #CCC;'>
							<div class='left' style='margin: 3px; margin-bottom: 15px; margin-top: 15px;'>
								<a href='m_users_v.php?uid=$e_UID'><img src='".$e_PIC."' width='95' height='95' style='border: 1px solid #999;' /></a>
							</div>
						</td>
						<td valign='top' align='left' style='border-bottom: 1px solid #CCC;'>
							<div class='left' style=' margin-top: 15px;'><span class='italic3'>Full Name:</span></div>
							<div class='left' style=' margin-top: 5px;'><span class='italic3'>User Name:</span></div>
							<div class='left' style=' margin-top: 5px;'><span class='italic3'>Email:</span></div>
							<div class='left' style=' margin-top: 5px;'><span class='italic3'>Contact #:</span></div>
							<div class='left' style=' margin-top: 5px; height: 25px;'><span class='italic3'>Address:</span></div>
							<div class='left' style=' margin-top: 5px;'><span class='italic3'>Relation:</span></div>
							<div class='left' style=' margin-top: 5px; margin-bottom: 15px;'><span class='italic3'>&nbsp;</span></div>
						</td>
						<td valign='top' align='left' style='border-bottom: 1px solid #CCC;'>
							<div class='left' style=' margin-top: 15px;'><span class='s_normalb'>".$e_FN."</span></div>
							<div class='left' style=' margin-top: 5px;'><span class='s_normalb'>".$e_UN."</span></div>
							<div class='left' style=' margin-top: 5px;'><span class='s_normalb'>".$e_EM."</span></div>
							<div class='left' style=' margin-top: 5px;'><span class='s_normalb'>".$e_CN."</span></div>
							<div class='left' style=' margin-top: 5px; width: 150px; height: 25px;'>
							<span class='s_normalb'>".$e_AD."</span></div>
							<div class='left' style=' margin-top: 5px;'><span class='s_normalb'>".$e_REL."</span></div>
							<div class='left' style=' margin-top: 5px; margin-bottom: 15px;'>
								<a href='m_users_del.php?uid=$e_UID'><img src='images/icons/x.png' width='20' height='20' title='Delete'></a>
								
							</div>
						</td>
					 ";
				}
				
				//else if status of user is NOT active 
				else
				{
					//then we create the columns inside the row
					 echo "
						<td valign='top' align='left' style='border-bottom: 1px solid #CCC;'>
							<div class='left' style='margin: 3px; margin-bottom: 15px; margin-top: 15px;'>
								<a href='m_users_v.php?uid=$e_UID'><img src='".$e_PIC."' width='95' height='95' style='border: 1px solid #999;' /></a>
							</div>
						</td>
						<td valign='top' align='left' style='border-bottom: 1px solid #CCC;'>
							<div class='left' style=' margin-top: 15px;'><span class='italic3'>Full Name:</span></div>
							<div class='left' style=' margin-top: 5px;'><span class='italic3'>User Name:</span></div>
							<div class='left' style=' margin-top: 5px;'><span class='italic3'>Email:</span></div>
							<div class='left' style=' margin-top: 5px;'><span class='italic3'>Contact #:</span></div>
							<div class='left' style=' margin-top: 5px; height: 25px;'><span class='italic3'>Address:</span></div>
							<div class='left' style=' margin-top: 5px;'><span class='italic3'>Relation:</span></div>
						</td>
						<td valign='top' align='left' style='border-bottom: 1px solid #CCC;'>
							<div class='left' style=' margin-top: 15px;'><span class='s_normal'>".$e_FN."</span></div>
							<div class='left' style=' margin-top: 5px;'><span class='s_normal'>".$e_UN."</span></div>
							<div class='left' style=' margin-top: 5px;'><span class='s_normal'>".$e_EM."</span></div>
							<div class='left' style=' margin-top: 5px;'><span class='s_normal'>".$e_CN."</span></div>
							<div class='left' style=' margin-top: 5px; width: 150px; height: 25px;'>
							<span class='s_normal'>".$e_AD."</span></div>
							<div class='left' style=' margin-top: 5px;'><span class='s_normal'>".$e_REL."</span></div>
							<div class='left' style=' margin-top: 5px; margin-bottom: 15px;'>
								<a href='m_users_accpt.php?uid=$e_UID'><img src='images/icons/check.jpg' width='20' height='20' title='Accept'></a>
								<a href='m_users_del.php?uid=$e_UID'><img src='images/icons/x.png' width='20' height='20' title='Delete'></a>
								
							</div>
						</td>
					 ";
				}
				//check if the the Status of user is active then
				
			  }
			  //if count (0) is less then limit (2) then
			  
			  //elseif count (0) is NOT less than limit (2) then
			  else
			  {
				  //the count value is back to 0
				  $Count = 0;
				  
				  //check if the the Status of user is active then
				  if($e_ST == "Active")
				  {
					  //then we close the the 1st row
					  echo "</tr>
					  <tr>
						<td valign='top' align='left' style='border-bottom: 1px solid #CCC;'>
							<div class='left' style='margin: 3px; margin-bottom: 15px; margin-top: 15px;'>
								<a href='m_users_v.php?uid=$e_UID'><img src='".$e_PIC."' width='95' height='95' style='border: 1px solid #999;' /></a>
							</div>
						</td>
						<td valign='top' align='left' style='border-bottom: 1px solid #CCC;'>
							<div class='left' style=' margin-top: 15px;'><span class='italic3'>Full Name:</span></div>
							<div class='left' style=' margin-top: 5px;'><span class='italic3'>User Name:</span></div>
							<div class='left' style=' margin-top: 5px;'><span class='italic3'>Email:</span></div>
							<div class='left' style=' margin-top: 5px;'><span class='italic3'>Contact #:</span></div>
							<div class='left' style=' margin-top: 5px; height: 25px;'><span class='italic3'>Address:</span></div>
							<div class='left' style=' margin-top: 5px;'><span class='italic3'>Relation:</span></div>
						</td>
						<td valign='top' align='left' style='border-bottom: 1px solid #CCC;'>
							<div class='left' style=' margin-top: 15px;'><span class='s_normalb'>".$e_FN."</span></div>
							<div class='left' style=' margin-top: 5px;'><span class='s_normalb'>".$e_UN."</span></div>
							<div class='left' style=' margin-top: 5px;'><span class='s_normalb'>".$e_EM."</span></div>
							<div class='left' style=' margin-top: 5px;'><span class='s_normalb'>".$e_CN."</span></div>
							<div class='left' style=' margin-top: 5px; width: 150px; height: 25px;'>
							<span class='s_normalb'>".$e_AD."</span></div>
							<div class='left' style=' margin-top: 5px;'><span class='s_normalb'>".$e_REL."</span></div>
							<div class='left' style=' margin-top: 5px; margin-bottom: 15px;'>
								<a href='m_users_del.php?uid=$e_UID'><img src='images/icons/x.png' width='20' height='20' title='Delete'></a>
								
							</div>
						</td>
					  ";
				  
				  }
				  //check if the the Status of user is active then
				  
				  //else if status of user is NOT active
				  else
				  {
					  //then we close the the 1st row
					  echo "</tr>
					  <tr>
						<td valign='top' align='left' style='border-bottom: 1px solid #CCC;'>
							<div class='left' style='margin: 3px; margin-bottom: 15px; margin-top: 15px;'>
								<a href='m_users_v.php?uid=$e_UID'><img src='".$e_PIC."' width='95' height='95' style='border: 1px solid #999;' /></a>
							</div>
						</td>
						<td valign='top' align='left' style='border-bottom: 1px solid #CCC;'>
							<div class='left' style=' margin-top: 15px;'><span class='italic3'>Full Name:</span></div>
							<div class='left' style=' margin-top: 5px;'><span class='italic3'>User Name:</span></div>
							<div class='left' style=' margin-top: 5px;'><span class='italic3'>Email:</span></div>
							<div class='left' style=' margin-top: 5px;'><span class='italic3'>Contact #:</span></div>
							<div class='left' style=' margin-top: 5px; height: 25px;'><span class='italic3'>Address:</span></div>
							<div class='left' style=' margin-top: 5px;'><span class='italic3'>Relation:</span></div>
						</td>
						<td valign='top' align='left' style='border-bottom: 1px solid #CCC;'>
							<div class='left' style=' margin-top: 15px;'><span class='s_normal'>".$e_FN."</span></div>
							<div class='left' style=' margin-top: 5px;'><span class='s_normal'>".$e_UN."</span></div>
							<div class='left' style=' margin-top: 5px;'><span class='s_normal'>".$e_EM."</span></div>
							<div class='left' style=' margin-top: 5px;'><span class='s_normal'>".$e_CN."</span></div>
							<div class='left' style=' margin-top: 5px; width: 150px; height: 25px;'>
							<span class='s_normal'>".$e_AD."</span></div>
							<div class='left' style=' margin-top: 5px;'><span class='s_normal'>".$e_REL."</span></div>
							<div class='left' style=' margin-top: 5px; margin-bottom: 15px;'>
								<a href='m_users_accpt.php?uid=$e_UID'><img src='images/icons/check.jpg' width='20' height='20' title='Accept'></a>
								<a href='m_users_del.php?uid=$e_UID'><img src='images/icons/x.png' width='20' height='20' title='Delete'></a>
								
							</div>
						</td>
					  ";
				  }
				  //else if status of user is NOT active
			  }
			  //increment value of count
			  $Count ++;
			 //then we must create a statement that will check whether the columns is already reach the limit(2)
		 }
		 //end while
		 
		 //echo the closing row
		 echo '</tr>';
 
 //DISPLAY USER PICTURES AND DETAILS SET-UP
}
////////////////////////////////// If record of query2 is equal to 0 then DO NOT DISPLAY PAGINATION DISPLAY SETUP

?>