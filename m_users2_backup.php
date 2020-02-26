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

//get the user id posted in the page
//$getUID		= $_GET['uid'];
$Action		= $_GET['action'];		//get value of message
$getUID		= $_GET['uid'];			// get user id

	
?>

<?php include 'm_header.php'; ?>
<form action="#" method="post">


<td width="75%" valign="top" align="left">
	<!-- BREAD CRUMBS / SEARCH TOOL -->
	<div class="left" style="height:70px; background-color: #9C6;">
        <div class="left" style="border-bottom: 1px solid #CCC; height:68px; margin-right: 5px;">
        <span class="okw"><img src="images/icons/Users.png" width="20" height="20" style="margin-top: 5px;" />&nbsp;USERS</span>
            <div class="left" style="margin-left: 25px; margin-top: 10px;">Search by:
                <span class="left" style="margin-left: 5px; margin-top: 10px;">
                  <input type="text" name="txtSearch" onKeyUp="showRec(this.value)" class="SearchBox" placeholder="User Name" />
                </span> 
            </div>
        </div>
    </div>
    <!-- BREAD CRUMBS / SEARCH TOOL -->
    
    
    <!-- WILL DISPLAY THE CONTENT-->
    <div class="center" style="margin-top: 15px; margin-right: 5px; width: 97%;">
    
    	 <div class="left" style=" border-top-left-radius: 0.3em; border-top-right-radius: 0.3em; ">
             <div class="header_arrow">
                <span class="left">
                    <img src="images/icons/question.png" width="20" height="20" style="margin-top: 2px;" />&nbsp;Friends or Foes</span><span class="arrow">
                </span>
             </div>
         </div>
         
         <div class="center" style="margin-top: 15px; margin-bottom: 15px;">
			<!-- MESSAGE HERE -->
         </div>
         
         <!-- LOAD THE MESSAGE BOX HERE -->
         <div id="showLightbox"></div>
         <!-- LOAD THE MESSAGE BOX HERE -->
        
   		 <!-- WILL DISPLAY THE DETAILS ABOUT THE USER -->
         <div id="txtHint" class="left" style=" margin-top: 15px; margin-bottom: 15px;">
         
        <table border="0" cellspacing="0" cellpadding="0">
        <?php
        
        //DISPLAY USER PICTURES AND DETAILS SET-UP
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
										<a href='m_users_v.php?uid=$e_UID'>
											<img src='".$e_PIC."' width='95' height='95' style='border: 1px solid #999;' title='View Details' />
										</a>
									</div>
								</td>
								<td valign='top' align='left' style='border-bottom: 1px solid #CCC;'>
									<div class='left' style=' margin-top: 15px;'><span class='italic3'>Full Name:</span></div>
									<div class='left' style=' margin-top: 5px;'><span class='italic3'>User Name:</span></div>
									
									<div class='left' style=' margin-top: 5px;'><span class='italic3'>Relation:</span></div>
									<div class='left' style=' margin-top: 5px; margin-bottom: 15px;'><span class='italic3'>&nbsp;</span></div>
								</td>
								<td valign='top' align='left' style='border-bottom: 1px solid #CCC;'>
									<div class='left' style=' margin-top: 15px;'><span class='s_normalb'>".$e_FN."</span></div>
									<div class='left' style=' margin-top: 5px;'><span class='s_normalb'>".$e_UN."</span></div>
									
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
										<a href='m_users_v.php?uid=$e_UID'>
											<img src='".$e_PIC."' width='95' height='95' style='border: 1px solid #999;' title='View Details' />
										</a>
									</div>
								</td>
								<td valign='top' align='left' style='border-bottom: 1px solid #CCC;'>
									<div class='left' style=' margin-top: 15px;'><span class='italic3'>Full Name:</span></div>
									<div class='left' style=' margin-top: 5px;'><span class='italic3'>User Name:</span></div>
									
									<div class='left' style=' margin-top: 5px;'><span class='italic3'>Relation:</span></div>
								</td>
								<td valign='top' align='left' style='border-bottom: 1px solid #CCC;'>
									<div class='left' style=' margin-top: 15px;'><span class='s_normal'>".$e_FN."</span></div>
									<div class='left' style=' margin-top: 5px;'><span class='s_normal'>".$e_UN."</span></div>
									
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
										<a href='m_users_v.php?uid=$e_UID'>
											<img src='".$e_PIC."' width='95' height='95' style='border: 1px solid #999;' title='View Details' />
										</a>
									</div>
								</td>
								<td valign='top' align='left' style='border-bottom: 1px solid #CCC;'>
									<div class='left' style=' margin-top: 15px;'><span class='italic3'>Full Name:</span></div>
									<div class='left' style=' margin-top: 5px;'><span class='italic3'>User Name:</span></div>
									
									<div class='left' style=' margin-top: 5px;'><span class='italic3'>Relation:</span></div>
								</td>
								<td valign='top' align='left' style='border-bottom: 1px solid #CCC;'>
									<div class='left' style=' margin-top: 15px;'><span class='s_normalb'>".$e_FN."</span></div>
									<div class='left' style=' margin-top: 5px;'><span class='s_normalb'>".$e_UN."</span></div>
									
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
										<a href='m_users_v.php?uid=$e_UID'>
											<img src='".$e_PIC."' width='95' height='95' style='border: 1px solid #999;' title='View Details' />
										</a>
									</div>
								</td>
								<td valign='top' align='left' style='border-bottom: 1px solid #CCC;'>
									<div class='left' style=' margin-top: 15px;'><span class='italic3'>Full Name:</span></div>
									<div class='left' style=' margin-top: 5px;'><span class='italic3'>User Name:</span></div>
									
									<div class='left' style=' margin-top: 5px;'><span class='italic3'>Relation:</span></div>
								</td>
								<td valign='top' align='left' style='border-bottom: 1px solid #CCC;'>
									<div class='left' style=' margin-top: 15px;'><span class='s_normal'>".$e_FN."</span></div>
									<div class='left' style=' margin-top: 5px;'><span class='s_normal'>".$e_UN."</span></div>
									
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
        
        </table>

		  
         </div>
         <!-- WILL DISPLAY THE DETAILS ABOUT THE USER -->
    </div>
    
    <div class="center" style="margin-top: 15px; margin-bottom: 15px; "><?php echo $paginationDisplay; ?></div>
    
    <!-- WILL DISPLAY THE CONTENT-->
</td>





<!-- RIGHT NAV -->
<td width="25%" valign="top" align="center" style="border-left:1px solid #999;">
	<!-- DISPLAY PICTURE OF USER THAT LOGGED IN AND DETAILS -->
	<div class="right" style="height: 68px;  background-color: #9C6; border-top-right-radius: 0.3em;">
    	<table width="100%" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td>
            <div style=" margin-top: 5px; margin-left: 25px;">
             <?php
			//set default profile pick if user doesnt have profile pick
			//if profile picks is equal to empty then
			if($PIC == '')
				//we set the defaul profile pick 
				echo ' <img src="images/icons/default_user.jpg" height="50" width="50" style="border: 1px solid #999;" /> ';
			
			//else if profile pick is NOT empty then
			else
				//we display the users profile pick
				echo ' <img src="'.$PIC.'" height="50" width="50" style="border: 1px solid #999;" /> ';
			//set default profile pick if user doesnt have profile pick
			?>
            </div>
            </td>
            <td class="a"><div style=" margin-top: 5px;"><span class="okb"><?php echo $FN.'<br> '.$UT_N.'<br>'; ?><a href="#">Change Password</a></span></div></td>
          </tr>
        </table>	
    </div>
    <!-- DISPLAY PICTURE OF USER THAT LOGGED IN AND DETAILS -->
   <div class="left" style=" margin-top: 1px; margin-left: 2px; margin-right: 2px; border-top: 1px solid #9C6;">
    	<div style="margin-top: 5px; color:#9C3;"></div>
    </div> 
    
     <div style="margin-top: 15px; margin-left: 5px; margin-right: 5px;">
        	<div class="left" style=" height: 25px; border: 1px solid #CCC; border-top-left-radius: 0.3em; border-top-right-radius: 0.3em; 
            background-color:#b6e56d">
            	<img src="images/icons/toolbar-icons/user.png" width="16" height="16" />&nbsp;User Utilities
            </div>
        	<div class="left" style="padding-top: 5px; padding-bottom: 5px; 
            border-left:1px solid #CCC;  border-right:1px solid #CCC; border-bottom: 1px solid #CCC;">
            	<img src="images/icons/toolbar-icons/tools.png" width="20" height="20" />
            	<a href="#" class="button">User Rights</a>
            </div>
            <div class="left" style="padding-top: 5px; padding-bottom: 5px; 
            border-left:1px solid #CCC;  border-right:1px solid #CCC; border-bottom: 1px solid #CCC;">
            	<img src="images/icons/toolbar-icons/settings.png" width="20" height="20" />
            	<a href="#" class="button">Add Admin</a>
            </div>
     </div>
</td>
<!-- RIGHT NAV -->


</form>
<?php include 'footer.php'; ?>