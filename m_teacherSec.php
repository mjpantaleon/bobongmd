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

//get the values in the lightbox
if( isset( $_POST['txtTitle']))
{
	//initiate varibles of the field
	$Title	= trim($_POST['txtTitle']);
	$DESC	= trim($_POST['txtDesc']);
	$CAT	= trim($_POST['cmbCat']);
	$FILE1	= $_POST['file1'];
	$Valid	= true;
	
	//check for empty fields
	if($Title == '')
		$Valid	= false;
		
	elseif($DESC == '')
		$Valid	= false;
	
	elseif($CAT == '')
		$Valid	= false;
	
	//if all fields are OK then
	if($Valid == true)
	{
		//$cur_DT = date('Y-m-d');	//get the current year-month-day
		$cur_Y 	= date('Y');		//get the current year
		
		//format category picture
		if($CAT == 1)
			$CatPic	= "images/icons/teacherSec/PDF.png";	//get the PDF pic
		else
			$CatPic	= "images/icons/teacherSec/ppt.png";		//get the power point pic
		
		//id format
		$limit = 4;		//set the limit into 4
		$sql = "SELECT * FROM teachersec ORDER BY id DESC LIMIT 0,1";	//query serves as our counter
		$result = mysql_query($sql);										//then its passed down to the varibale $result
		$row = mysql_fetch_array($result);									//so that we could access row 'UID' from the table
		
			$last_id = $row['id'];
			$last_dgt = substr($last_id, -4);								
			
			$id 			= str_pad((int) $last_dgt,$limit,"0",STR_PAD_LEFT);
			$id				= $id + 1;										//increment by 1
			$id 			= str_pad((int) $id,$limit,"0",STR_PAD_LEFT);
			$LECTID			= "LECT".$cur_Y."-".$id;						//LECT2013-0001
		//id format
		
		
		//check fro duplicated titles
		/**/
		if( mysql_num_rows(mysql_query(" SELECT TIT FROM teachersec WHERE TIT = '$Title' ")) > 0)
		{
			$Message = " <img src='images/icons/x.png' width='20' height='20'>&nbsp;<span class='error'>This lecture already exist! Please add another lecture. ";
		}
		//check fro duplicated titles
		
		//else if there are no duplicate titles then
		else
		{
		
			//check first if the file has no error
			if($_FILES['file1']['error'] <= 0)
			{
				//File upload attributes
				$FILE	= $_FILES['file1']['name'];
				$Ftemp	= $_FILES['file1']['tmp_name'];
				$Path 	= "lectures/".$FILE;					//path to upload / the name of the file
				
				mysql_query(" 	INSERT INTO teachersec 
								SET 
								id		= '$LECTID',
								TIT 	= '$Title', 
								DESK 	= '$DESC', 
								CAT 	= '$CAT', 
								CatPic	= '$CatPic',
								LINK 	= '$Path',
								ST		= '1'
							")
				or die(mysql_error());
				move_uploaded_file($Ftemp,"lectures/".$FILE);	//code structure for moving this file
				
				
				//log this event
				$time_in = date("H:i:sa");							
				$cur_date = date("Y-m-d");
				$cur_timestamp = $cur_date." ".$time_in;	//concatinate time + current date
				
				$query = "	INSERT INTO events SET E_TS = '$cur_timestamp', UID = '$UID', E_D = '[ ".$FN." ] Added new lecture: [ ".$LECTID." ]'	";
				mysql_query($query)
				or die(mysql_error());
				
				//unset post variables
				unset($_POST['txtTitle']);
				unset($_POST['txtDesc']);
				unset($_POST['cmbCat']);
				unset($_FILES['file1']['name']);
				
				$Message = "<span class='okb'><img src='images/icons/check.jpg' width='20' height='20'>New Lecture has been added.</span>";
			}
			
			else
				$Message = "Having problems regarding the file upload...";
		}
		//if there are no duplicate titles
		
	}
	//if all fields are OK then
	//$Message = $Path;
}
?>

<?php include 'm_header.php'; ?>
<form action="#" method="post" enctype="multipart/form-data">


<td width="75%" valign="top" align="left">
	<!-- BREAD CRUMBS / SEARCH TOOL -->
	<div class="left" style="height:70px; background-color: #9C6;">
        <div class="left" style="border-bottom: 1px solid #CCC; height:68px; margin-right: 5px;">
        <span class="okw"><img src="images/icons/teacher.png" width="20" height="20" style="margin-top: 5px;" />&nbsp;TEACHER'S SECTION</span>
            <div class="left" style="margin-left: 25px; margin-top: 10px;">Search by:
                <span class="left" style="margin-left: 5px; margin-top: 10px;">
                  <input type="text" name="txtSearch" onKeyUp="showLec(this.value)" class="SearchBox" placeholder="Title" />
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
                    <img src="images/icons/desktop.png" width="20" height="20" style="margin-top: 2px;" />&nbsp;Recent Lectures</span>
                    <span class="arrow">
                </span>
             </div>
         </div>
         
         <div class="center" style="margin-top: 8px; margin-bottom: 15px;">
			<!-- PROMPT MESSAGE HERE -->&nbsp;<?php echo $Message; ?>
         </div>
         
        
         
         <!-- LOAD MESSAGES POPUP -->
         <div id="showSentByHere"></div>
         <!-- LOAD MESSAGES POPUP -->
         
          <!-- LOAD ASSIGNMENT POPUP -->
         <div id="showAssign"></div>
         <!-- LOAD ASSIGNMENT POPUP -->
         
          <!-- LOAD THE MESSAGE BOX HERE -->
         <div id="showUserReg"></div>
         <!-- LOAD THE MESSAGE BOX HERE -->
         
         
        
   		 <!-- CONTENT DIV -->
         <div id="txtHint" class="left" style=" margin-top: 15px; margin-bottom: 15px;">
         	<!-- YOUR TABLE HERE -->
            <table width="100%" border="0" cellspacing="0" cellpadding="0">
            <?php
			//I want to display 3 records per row
			
			$Limit	= 3;	//number of columns or records to display per row
			$Count	= 0;	//zero default value
			
			//select records from lectures table
			
			$query		= " SELECT *
							FROM teachersec
							ORDER BY id DESC ";
			 $result	= mysql_query($query)
			 or die(mysql_error());
			 
			 
			 //////////////////////////////////////////////////// PAGINATION SET UP ///////////////////////////////////////////////////
			$nr = mysql_num_rows($result);					//get the total number of rows in the table
			if(isset($_GET['pn']))							//get pn from url vars if it is present
			{
				$pn = preg_replace('#[^0-9]#i','',$_GET['pn']); //filters everything except for numbers	
			}
			
			else //if the pn URL value is NOT present force it to be value of page number 1
			{
				$pn = 1;
			}
			
			$itemPerPage = 9; //setting for how many item will be shown in every page
			
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
				$centerPages .= '&nbsp;&nbsp;<span class="pageNumActive"><a href="'.$_SERVER['PHP_SELF']."?pn=".$sub1.'">'.$sub1.'</a></span> &nbsp;';
				$centerPages .= '<span class="pageActiveNum">'.$pn.'</span> &nbsp;';
			}
			elseif($pn > 2 && $pn < ($lastPage - 1))
			{
				$centerPages .= '&nbsp;&nbsp;<span class="pageNumActive"><a href="'.$_SERVER['PHP_SELF']."?pn=".$sub2.'">'.$sub2.'</a></span> &nbsp;';
				$centerPages .= '<span class="pageNumActive"><a href="'.$_SERVER['PHP_SELF']."?pn=".$sub1.'">'.$sub1.'</a></span> &nbsp;';
				$centerPages .= '<span class="pageActiveNum">'.$pn.'</span> &nbsp;';
				$centerPages .= '<span class="pageNumActive"><a href="'.$_SERVER['PHP_SELF']."?pn=".$add1.'">'.$add1.'</a></span> &nbsp;';
				$centerPages .= '<span class="pageNumActive"><a href="'.$_SERVER['PHP_SELF']."?pn=".$add2.'">'.$add2.'</a></span> &nbsp;';
			}
			elseif($pn > 1 && $pn < ($lastPage))
			{
				$centerPages .= '&nbsp;&nbsp;<span class="pageNumActive"><a href="'.$_SERVER['PHP_SELF']."?pn=".$sub1.'">'.$sub1.'</a></span> &nbsp;';
				$centerPages .= '<span class="pageActiveNum">'.$pn.'</span> &nbsp;';
				$centerPages .= '<span class="pageNumActive"><a href="'. $_SERVER['PHP_SELF']."?pn=".$add1.'">'.$add1.'</a></span> &nbsp;';
			}
			/////////////////////////////////////////////////////////////////////////////////////////////////
			///////////////////////////////////////end creates a number to clicked between the next and the back
			
			
			//setting the limit range..the 2 values we place to choose a range of rows in the database in our query
			$limit = 'LIMIT ' .($pn - 1) * $itemPerPage.','.$itemPerPage; 
			//setting the limit range..the 2 values we place to choose a range of rows in the database in our query		
			
			
			
			$query2 	= " SELECT *
							FROM teachersec
							ORDER BY id DESC $limit ";	//limit included
			$result2	= @mysql_query($query2)
			or die(mysql_error());
			
			////////////////////////////////// If record of query2 is equal to 0 then DO NOT DISPLAY PAGINATION DISPLAY SETUP
			if($result2)
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
				
					//if status of lecture is 0 then
					$r_CatPic_x	= "images/icons/teacherSec/locked_item.png";
					
					while($row	= mysql_fetch_array($result2))
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
								$table =" 
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
							
							$table .="</tr>
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
					//end while
					echo $table;	//echo the result table
					
					
			 //DISPLAY USER PICTURES AND DETAILS SET-UP
			}
			////////////////////////////////// If record of query2 is equal to 0 then DO NOT DISPLAY PAGINATION DISPLAY SETUP
			?>
            
              
              </table>

        	<!-- YOUR TABLE HERE -->
         </div>
         <!-- CONTENT DIV -->
    </div>
    
    <div class="center" style="margin: 15px 10px 45px; "><?php echo $paginationDisplay; ?></div>
    
    <!-- WILL DISPLAY THE CONTENT-->
</td>





<!-- RIGHT NAV -->
<td width="25%" valign="top" align="center" style="border-left:1px solid #999;">
	<!-- DISPLAY PICTURE OF USER THAT LOGGED IN AND DETAILS -->
	<div class="right" style="height: 68px;  background-color: #9C6; border-top-right-radius: 0.3em;">
     <?php
	//GETTING ALL THE INFORMATION OF THIS USER
	$query	= " SELECT * FROM user_details WHERE UID = '".$UID."' ";
	$result	= mysql_query($query);
	$row	= mysql_fetch_array($result);
	//declaire variable
	$r_PIC	= $row['PIC'];	//Picture
	
	?>
    	<table width="100%" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td>
            <div style=" margin-top: 5px; margin-left: 25px;">
            <?php
			//set default profile pick if user doesnt have profile pick
			//if profile picks is equal to empty then
			if($r_PIC == '')
				//we set the defaul profile pick 
				echo ' <img src="images/icons/default_user.jpg" height="50" width="50" style="border: 1px solid #999;" /> ';
			
			//else if profile pick is NOT empty then
			else
				//we display the users profile pick
				echo ' <img src="'.$r_PIC.'" height="50" width="50" style="border: 1px solid #999;" /> ';
			//set default profile pick if user doesnt have profile pick
			?>
            </div>
            </td>
            <td class="a">
                <div style=" margin-top: 5px;">
                    <span class="okb"><?php echo $UN.'<br> '.$UT_N.'<br>'; ?>
                    	<a href="#">Edit Account</a>
                    </span>
                </div>
            </td>
          </tr>
        </table>	
    </div>
    <!-- DISPLAY PICTURE OF USER THAT LOGGED IN AND DETAILS -->
    
    <div class="left" style=" margin-top: 1px; margin-left: 2px; margin-right: 2px; border-top: 1px solid #9C6;">
    	<div style="margin-top: 5px; color:#9C3;"></div>
    </div> 
    
    <div style="margin-top: 15px; margin-left: 5px; margin-right: 5px; margin-bottom: 15px;">
        <div class="left" style=" height: 25px; border: 1px solid #CCC; border-top-left-radius: 0.3em; border-top-right-radius: 0.3em; 
        background-color:#b6e56d">
            <img src="images/icons/warning_48.png"  width="20" height="20" />&nbsp;Daily Updates            
        </div>
        
        
        <!-- DIV FOR NEW USER REGISTRY -->
		<?php 
        //we want to get the total number of users whos status is 0 or not yet active
        $query		= " SELECT COUNT(ST) AS ST FROM users WHERE ST = '0' ";
        $result		= mysql_query($query);
        $row		= mysql_fetch_array($result);
        //we get the total count based on the 0 status and place it in a variable '$T_ST'
        $T_ST		= $row['ST'];	//will hold watever number is in the table
       
        //if there are users whos status is O then
        if($T_ST)
        //display an alert message to alert the admin
        echo '
        <div class="left" style="padding-top: 5px; padding-bottom: 5px; 
        border-left:1px solid #CCC;  border-right:1px solid #CCC; border-bottom: 1px solid #CCC;">
                <div class="left">
                    <img src="images/icons/toolbar-icons/user_alert.png" width="20" height="20" />
                    <input type="button" class="button" onclick="showUserReg()" value="User Registry" title="View Users" />
					(<span class="error" style="font-size:14px">'.$T_ST.'</span>)
                </div>
        </div>
             ';
             
        //if theres are no user whos status is O then
        else
        //display a gray colored text + unclickable link
        echo '
        <div class="left" style="padding-top: 5px; padding-bottom: 5px; 
        border-left:1px solid #CCC;  border-right:1px solid #CCC; border-bottom: 1px solid #CCC;">
            <div class="left">
                <img src="images/icons/toolbar-icons/user.png" width="20" height="20" />&nbsp;
                <span class="s_normal">User Registry</span>
            </div>
        </div>
            ';
        ?>	
        <!-- DIV FOR NEW USER REGISTRY -->
        
        
        
        <!-- DIV FOR MESSAGES -->
         <?php
		//we have to check if theres a message whos status is 1 to this user
		// DONR FORGET THE ALIAS!!!!
		$query		= " SELECT COUNT(MST) AS MST FROM msgs WHERE msgTo= '".$UID."' AND MST = '1'  ";
		$result		= mysql_query($query);
		$row		= mysql_fetch_array($result);
		//variable fot the total result
		$T_MSG		= $row['MST'];
		
		//if theres a message to this user whos status is equal to 1 then
		if($T_MSG)
		
		echo "
		<div class='left' style='padding-top: 5px; padding-bottom: 5px; 
		border-left:1px solid #CCC;  border-right:1px solid #CCC; border-bottom: 1px solid #CCC;'>
			<div class='left'>
				<img src='images/icons/email-alert.png' width='20' height='20' />&nbsp;
				<input type='button' class='button' onclick='showSentBy2()' value='Inbox' title='View Messages' />
				(<span class='error' style='font-size:14px'>".$T_MSG."</span>)
			</div>
		</div>
		";
	
		
		//else if theres no message whos status is equal to 1 then
		else
		
		echo "
		<div class='left' style='padding-top: 5px; padding-bottom: 5px; 
		border-left:1px solid #CCC;  border-right:1px solid #CCC; border-bottom: 1px solid #CCC;'>
			<div class='left'>
				<img src='images/icons/Email-icon.png' width='20' height='20' />&nbsp;
				<span class='s_normal'/>Inbox</span>
			</div>
		</div>
		";

		
		
		?>
        <!-- DIV FOR MESSAGES -->
        
        
        <!-- DIV FOR ASSIGNMENTS -->
        <?php
		//were gona get the count of all records in the assignment table whos status is equal to 1
		$query		= " SELECT COUNT(id) AS id FROM assignment WHERE A_ST = '1' ";
		$result		= mysql_query($query);
		$row		= mysql_fetch_array($result);
		//variable fot the total result
		$T_ASGN		= $row['id'];	//Total assignment in the table
		
		//count of assignment having status 1 is NOT equal to 0 then
		if($T_ASGN)
		echo "
		
		<div class='left' style='padding-top: 5px; padding-bottom: 5px; 
        border-left:1px solid #CCC;  border-right:1px solid #CCC; border-bottom: 1px solid #CCC;'>
            <div class='left'>
                <img src='images/icons/toolbar-icons/folder-alert.png' width='20' height='20' />&nbsp;
                <input type='button' class='button' onclick='showAssign()' value='New Assignment' />
				(<span class='error' style='font-size:14px'>".$T_ASGN."</span>)
            </div>
        </div>
		
		";
		
		else
		echo"
		<div class='left' style='padding-top: 5px; padding-bottom: 5px; 
        border-left:1px solid #CCC;  border-right:1px solid #CCC; border-bottom: 1px solid #CCC;'>
            <div class='left'>
                <img src='images/icons/toolbar-icons/folder.png' width='20' height='20' />&nbsp;
                <span class='s_normal'/>New Assignment</span>
            </div>
        </div>
		
		";
		
		?>
        
    	<!-- DIV FOR ASSIGNMENTS -->

	</div> 

    
     <div style="margin-top: 15px; margin-left: 5px; margin-right: 5px;">
        	<div class="left" style=" height: 25px; border: 1px solid #CCC; border-top-left-radius: 0.3em; border-top-right-radius: 0.3em; 
            background-color:#b6e56d">
            	<img src="images/icons/toolbar-icons/settings.png" width="16" height="16" />&nbsp;Manage Lectures
            </div>
        	<div class="left" style="padding-top: 5px; padding-bottom: 5px; 
            border-left:1px solid #CCC;  border-right:1px solid #CCC; border-bottom: 1px solid #CCC;">
            	<img src="images/icons/images (6).jpg" width="20" height="20" />
            	<input class="button" type="button" onclick="showAddNew()" value="Add New" title="Add New" />
        	</div>
     </div>
     
     
     <div style="margin-top: 15px; margin-left: 5px; margin-right: 5px;">
        	<div class="left" style=" height: 25px; border: 1px solid #CCC; border-top-left-radius: 0.3em; border-top-right-radius: 0.3em; 
            background-color:#b6e56d">
            	<img src="images/icons/toolbar-icons/link.png" width="16" height="16" />&nbsp;Assigments
            </div>
        	<div class="left" style="padding-top: 5px; padding-bottom: 5px; 
            border-left:1px solid #CCC;  border-right:1px solid #CCC; border-bottom: 1px solid #CCC;">
            	<img src="images/icons/toolbar-icons/folder.png" width="20" height="20" />
            	<a href="m_assign.php" class="button">View</a>
            </div>
     </div>
    
</td>
<!-- RIGHT NAV -->


</form>
<?php include 'footer.php'; ?>