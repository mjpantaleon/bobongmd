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

//default value
$Count 		= 0;


//-------------------------------------------------------------------------------------------------------------------------

//IN CASE THAT THE ADMIN ADD NEW LECTURE THEN
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
			$CatPic	= "images/icons/teacherSec/lecture.jpg";	//get the PDF pic
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
			if($_FILES['txtFile']['error'] <= 0)
			{
				//File upload attributes
				$FILE	= rawurlencode($_FILES['file1']['name']);
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
//get the values in the lightbox
//IN CASE THAT THE ADMIN ADD NEW LECTURE THEN

//-------------------------------------------------------------------------------------------------------------------------


	//get the value of action made in edit lecture page
	$Action	= $_GET['action'];	//lock or delete

	//get the id of lecture from lectures page
	$getID	=  $_GET['id'];	//lecture ID

	//get the name of the lecture based on the get id
	$query	= " SELECT * FROM teachersec WHERE id = '$getID' ";
	$result	= mysql_query($query);
	$row	= mysql_fetch_array($result);
	//initialize varible
	$e_Title	= $row['TIT'];	//title
	$e_DESC		= $row['DESK'];	//Decription
	$e_CAT		= $row['CAT'];	//Category
	$e_LINK		= $row['LINK'];	//Link
	
	
//-------------------------------------------------------------------------------------------------------------------------
if( isset( $_POST['cmdUpdate']))
{
	//inialize variables
	$newTitle	= $_POST['newTitle'];
	$newDesc	= $_POST['newDesc'];
	$Validthis	= true;
	
	//check if title and description is not empty
	if($newTitle == '')
		$Validthis	= false;
		
	elseif($newDesc	== '')
		$Validthis	= false;
		
	//fi all fields is OK then
	if($Validthis == true)
	{
		mysql_query(" UPDATE teachersec SET TIT = '$newTitle', DESK = '$newDesc' WHERE id = '$getID' ")
		or die(mysql_error());
		
		$Message	= "<span class='okb'>Successfully update changes.</span>";
	}
	//print_r($newDesc);
}




//IF USER CHANGE THE LINK AND CATEGORY OF THIS RECORD
//when a user select a new file atachme
if( isset( $_FILES['fileAtt']))
{
	//if no errors found then
	if($_FILES['fileAtt']['error'] <= 0)
	{
		$fileAtt		= $_POST['fileAtt'];
			
		$fileAttName	= $_FILES['fileAtt']['name'];
		$fileAttTemp	= $_FILES['fileAtt']['tmp_name'];
		$fileAttPath 	= "lectures/".$fileAttName;
		//$r_LINK	= $fileCatName;
		
		//check first if the file upload is still the same or not
		if( mysql_num_rows( mysql_query( " SELECT LINK FROM teachersec WHERE id = '$getID' AND LINK = '$fileAttPath' ")) > 0)
		{
			$Message	= "<span class='error'>This File Attachment already exist! Please select another file.</span>";
		}
		else
		{
			//Must first remove old fiela attachement then
			$Old_File	= $e_LINK;
			unlink($Old_File);		//removing old files in the folder
			
			//Update new file attachment
			mysql_query(" UPDATE teachersec SET LINK = '$fileAttPath' WHERE id = '$getID' ")
			or die(mysql_error());
			move_uploaded_file($fileAttTemp,"lectures/".$fileAttName);	//code structure for moving this file
			
			
			//log this event
			$time_in = date("H:i:sa");							
			$cur_date = date("Y-m-d");
			$cur_timestamp = $cur_date." ".$time_in;	//concatinate time + current date
			
			$query = "	INSERT INTO events SET E_TS = '$cur_timestamp', UID = '$UID', E_D = '[ ".$FN." ] update attachement of: [ ".$getID." ]'	";
			mysql_query($query)
			or die(mysql_error());
			
			
			$Message	= "<span class='okb'>New File has been Attached.</span>";
		}
	}
	//if no errors found then
}


//When a user select a new category
if( isset( $_POST['cmbCat2']))
{
	$catName	= $_POST['cmbCat2'];
	
	if( mysql_num_rows( mysql_query( " SELECT CAT FROM teachersec WHERE id = '$getID' AND CAT = '$catName' ")) > 0)
	{
		$Message	= "<span class='error'>This Category already exist! Please select another Category.</span>";
	}
	else
	{
		//change format of the category
		if($catName == 1)
			$CatPic2	= "images/icons/teacherSec/PDF.png";	//get the PDF pic
		else
			$CatPic2	= "images/icons/teacherSec/ppt.png";
		
		mysql_query(" UPDATE teachersec SET CAT = '$catName', CatPic = '$CatPic2' WHERE id = '$getID' ")
		or die(mysql_error());
		
		//log this event
		$time_in = date("H:i:sa");							
		$cur_date = date("Y-m-d");
		$cur_timestamp = $cur_date." ".$time_in;	//concatinate time + current date
		
		$query = "	INSERT INTO events SET E_TS = '$cur_timestamp', UID = '$UID', E_D = '[ ".$FN." ] update category of: [ ".$getID." ]'	";
		mysql_query($query)
		or die(mysql_error());
		
		$Message	= "<span class='okb'>New Category has been Attached.</span>";
	}
}

//IF THE UPDATE BUTTON HAS BEEN CLICKED OR DETECTED THEN
//POST Fields

?>

<?php include 'm_header.php'; ?>
<form action="#" method="post">


<td width="75%" valign="top" align="left">
	<!-- BREAD CRUMBS / SEARCH TOOL -->
	<div class="left" style="height:70px; background-color: #9C6;">
        <div class="left" style="border-bottom: 1px solid #CCC; height:68px; margin-right: 5px;">
        <span class="okw"><img src="images/icons/teacher.png" width="20" height="20" style="margin-top: 5px;" />&nbsp;TEACHER'S SECTION</span>
            <div class="left" style="margin-left: 25px; margin-top: 10px;">&nbsp;
                <!-- SEARCH BOX HERE/ NOTES/ -->
            </div>
        </div>
    </div>
    <!-- BREAD CRUMBS / SEARCH TOOL -->
    
    
    <!-- WILL DISPLAY THE CONTENT-->
    <div class="center" style="margin-top: 15px; margin-right: 5px; width: 97%;">
    
    	 <div class="left" style=" border-top-left-radius: 0.3em; border-top-right-radius: 0.3em; ">
             <div class="header_arrow">
                <span class="left">
                    <a href="m_teacherSec.php"><img src="images/icons/prev.png" width="20" height="20" style="margin-top: 2px;" title="Back" /></a>
                    &nbsp;Edit&nbsp;
                    <img src="images/icons/desktop.png" width="20" height="20" />
                    &nbsp;<?php echo $e_Title; ?>
                </span>
                    <span class="arrow"></span>
             </div>
         </div>
         
         <div class="center" style="margin-top: 8px; margin-bottom: 15px;">
			<!-- PROMPT MESSAGE HERE -->&nbsp;<?php echo $Message; ?>
         </div>
         
         <!--- LIGHTBOX HERE -->
         
         <!-- LOAD THE MESSAGE BOX HERE -->
         <div id="showLightbox"></div>
         <!-- LOAD THE MESSAGE BOX HERE -->
         
         <!-- LOAD MESSAGES POPUP -->
         <div id="showSentByHere"></div>
         <!-- LOAD MESSAGES POPUP -->
         
          <!-- LOAD ASSIGNMENT POPUP -->
         <div id="showAssign"></div>
         <!-- LOAD ASSIGNMENT POPUP -->
         
          <!-- LOAD THE MESSAGE BOX HERE -->
         <div id="showUserReg"></div>
         <!-- LOAD THE MESSAGE BOX HERE -->
         
         <!--- LIGHTBOX HERE -->
         
        
   		 
         <?php
		 //get the records from the table
		 $query		= " SELECT * FROM teachersec WHERE id = '".$getID."' ";
		 $result	= mysql_query($query);
		 $row		= mysql_fetch_array($result);
		 //declate variables
		 $r_Title	= $row['TIT'];	//title
		 $r_DESK	= $row['DESK'];	//description
		 $r_LINK	= $row['LINK'];	//link
		 $r_CAT		= $row['CAT'];	//category
		 $r_ST		= $row['ST'];	//status
		 
		 //change format of category into string
		 if($r_CAT == 1)
		 	$r_CAT	= "PDF";
		 else
		 	$r_CAT	= "Presentation";
		 ?>
         <!-- CONTENT DIV -->
         <div id="txtHint" class="left" style=" margin-top: 15px; margin-bottom: 45px;">
         	<!-- YOUR TABLE HERE -->
             <?php
				//if this record no longer exist then
				
				//if the user clicked delete then
				if($Action == 'Delete')
				{
					$divLink.="
					
					<div style='height:400px; width: 545px; border: 1px solid #F00; margin-left: 45px; box-shadow: inset 0 1px 25px #F00;'>
						<div style='margin-top:-11px; margin-left: 25px; background-color:#FFF; width: 105px;'>&nbsp;Lecture Details</div>
						
						<div class='left' style=' margin-left:25px;'>
							<div class='center' style='margin-top: 45px;'>
								<img src='images/icons/toolbar-icons/rubbish-bin.png' width='45' height='45'><br>
								Lecture <b>'".$e_Title."'</b> has been deleted.<br>
								This Action can not be undone.
							</div>
						</div>
						
						
					</div>	
					";
				}
				
				elseif($Action == 'Lock')
				{
					$divLink.="
					
					<div style='height:400px; width: 545px; border: 1px solid #F00; margin-left: 45px; box-shadow: inset 0 1px 25px #F00;'>
						<div style='margin-top:-11px; margin-left: 25px; background-color:#FFF; width: 105px;'>&nbsp;Lecture Details</div>
						
						<div class='left' style=' margin-left:25px;'>
							<div class='center' style='margin-top: 45px;'>
								<img src='images/icons/toolbar-icons/lock.png' width='45' height='45'><br>
								Lecture <b>'".$e_Title."'</b> has been locked<br>
								and will not be accessible by the users of this site.
							</div>
						</div>
						
						
					</div>	
					";
				}
				
				elseif($Action == 'Activate')
				{
					$divLink.="
					
					<div style='height:400px; width: 545px; border: 1px solid #9C3; margin-left: 45px; box-shadow: inset 0 1px 25px #9C3;'>
						<div style='margin-top:-11px; margin-left: 25px; background-color:#FFF; width: 105px;'>&nbsp;Lecture Details</div>
						
						<div class='left' style=' margin-left:25px;'>
							<div class='center' style='margin-top: 45px;'>
								<img src='images/icons/check.jpg' width='45' height='45'><br>
								Lecture <b>'".$e_Title."'</b> has been Activated,<br>
								Users will now have an access in this lecture.
							</div>
						</div>
						
						
					</div>	
					";
				}
				
				//else if user clicked lock/activate then
				else
				{
					$divLink.="
					
					<div style='height:400px; width: 545px; border: 1px solid #999; margin-left: 45px; box-shadow: 0 1px 5px #CCC;'>
						<div style='margin-top:-11px; margin-left: 25px; background-color:#FFF; width: 105px;'>&nbsp;Lecture Details</div>
			
						<div class='left' style=' margin-left:25px;'>
							<div class='s_normalb' style='margin-top: 15px;'>Title:</div>
							<input class='InputBox3' type='text' name='newTitle' value='".$r_Title."' required />
							<div class='s_normal'>Spaces, periods, hypens, and underscore is allowed except for punctuation.</div>
						</div>
						
						<div class='left' style=' margin-left:25px;'>
							<div class='s_normalb' style='margin-top: 15px;'>Description:</div>
							<input class='InputBox3' type='text' name='newDesc' value='".$r_DESK."' required />
							<div class='s_normal'>Spaces, periods, hypens, and underscore is allowed except for punctuation.</div>
						</div>
						
						<div class='left' style=' margin-left:25px;'>
							<div class='s_normalb' style='margin-top: 15px;'>Link:</div>
							<div id='showCatBoxHere'><a href='#'>".$r_LINK."</a>
							<img src='images/icons/writing-4.png' width='20' height='20' style='cursor:pointer' title='Change' onClick='showCatBox()' />
							</div>
							<div class='s_normal'>Click the pencil to change the file attachement</div>
						</div>
						
						<div class='left' style=' margin-left:25px;'>
							<div class='s_normalb' style='margin-top: 15px;'>Category:</div>
							<div id='showCatBoxHere2'>
								<select name='cmbCat'>
									<option value='".$r_CAT."'>".$r_CAT."</option>
								</select>
								<img src='images/icons/writing-4.png' width='20' height='20' style='cursor:pointer' title='Change' onClick='showCatBox2()'  />
							</div>
							<div class='s_normal'>Click the pencil to change the Category</div>
						</div>
						
						<div class='left' style='margin-left: 25px; margin-top:15px;'>
						<input class='Update_btn' type='submit' name='cmdUpdate' value='Update' />
						<span style='float: right; margin-right: 15px;'>
						
						
						";
						
						//If lecture status is 1 then
						if($r_ST != 0)
						{
							$divLink.="
							<img src='images/icons/toolbar-icons/lock.png' width='25' height='25' title='Lock' style='cursor: pointer;' 
							onClick='lockItem()' />&nbsp;
							";
						}
						
						else
						{
							$divLink.="
							<img src='images/icons/toolbar-icons/undo.png' width='25' height='25' title='Activate' style='cursor: pointer' 
							onclick='activateItem()' />&nbsp;
							";
						}
						
						$divLink.="
						
							
							<img src='images/icons/toolbar-icons/rubbish-bin.png' width='25' height='25' title='Delete' style='cursor: pointer;' 
							onClick='delLecture()' />
						</span>
						</div>
					   
					</div> 
						
						";
						
					
				}
				echo $divLink; //echo display table
				?>
        	<!-- YOUR TABLE HERE -->
         </div>
         <!-- CONTENT DIV -->
    </div>
    
    <div class="center" style="margin-top: 15px; margin-bottom: 15px; "><?php echo $paginationDisplay; ?></div>
    
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