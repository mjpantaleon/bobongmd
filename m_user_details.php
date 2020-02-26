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
$UID 	= $_SESSION['session_user_id'];
$UT_N 	= $_SESSION['access_type'];
$PIC	= $_SESSION['prof_pic'];
$UN		= $_SESSION['session_username'];


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

//Default variables
$Message	= "";
$Count 		= 0;


	//get the value of username
	$getUID	= $_GET['user'];
	
	//GETTING ALL THE INFORMATION OF THIS USER
	$query	= " SELECT * FROM user_details WHERE UID = '".$getUID."' ";
	$result	= mysql_query($query);
	$row	= mysql_fetch_array($result);
	//declaire variables
	$r_UID	= $row['UID'];	//id of this user
	$r_UN	= $row['UN'];	//User name
	$r_FN	= $row['FN'];	//Full name
	$r_EM	= $row['EM'];	//Email
	$r_CN	= $row['CN'];	//Contact Nummber
	$r_AD	= $row['AD'];	//Address
	$r_REL	= $row['REL'];	//Realtion
	$r_PIC	= $row['PIC'];	//Picture
	$r_ST	= $row['ST'];	//Status
	//FOR STUDENTS ONLY
	$r_SUBJ	= $row['SUBJ'];
	$r_SECS	= $row['SECS'];
	$r_SEMS	= $row['SEMS'];
	$r_SY	= $row['SY'];
	
	//change format of profile picture of user
	if($r_PIC != '')
		$r_PIC = $r_PIC;
	else
		$r_PIC = "images/icons/default_user.jpg";
	
	//IF USER SEND MESSAGE A USER
	if( isset( $_POST['txtMessage']))
	{
		$Message	= trim(mysql_real_escape_string($_POST['txtMessage']));
		
		if($Message != '')
		{
			//$cur_DT = date('Y-m-d');	//get the current year-month-day
			$cur_Y 	= date('Y');		//get the current year
			
			//id format
			$limit = 4;		//set the limit into 4
			$sql = "SELECT * FROM msgs ORDER BY id DESC LIMIT 0,1";	//query serves as our counter
			$result = mysql_query($sql);										//then its passed down to the varibale $result
			$row = mysql_fetch_array($result);									//so that we could access row 'UID' from the table
			
				$last_id = $row['id'];
				$last_dgt = substr($last_id, -4);								
				
				$id 			= str_pad((int) $last_dgt,$limit,"0",STR_PAD_LEFT);
				$id				= $id + 1;										//increment by 1
				$id 			= str_pad((int) $id,$limit,"0",STR_PAD_LEFT);
				$MSGID			= "MSG".$cur_Y."-".$id;						//BBDM2013-0001
			//id format
			
			//setting up the date
			$today	= date("F j, Y");	//string/Int format
			$hr_s 	= date("H:i:sa");	//12hrs format
		
			//insert message in the database
			$query		= mysql_query(" INSERT INTO msgs SET id = '".$MSGID."', MSG = '".$Message."', 
										msgFrom = '".$UID."', msgTo = '".$r_UID."', dateSent = '".$today."', timeSent = '".$hr_s ."', MST = '1' ")
			or die(mysql_error());
			
			
			//LOG THIS EVENT
			$time_in = date("H:i:sa");		//12hrs format						
			$cur_date = date("Y-m-d");		//Y m(int) d(int)
			$cur_timestamp = $cur_date." ".$time_in;
			
			$query = "	INSERT INTO events SET E_TS = '".$cur_timestamp."', UID = '".$UID."', E_D = 'Send message to ID:[ ".$r_UID." ]'	";
			mysql_query($query)
			or die(mysql_error());
			//then prompt message
			$Message	= "
			
			<div style=' border: 1px solid #06C; box-shadow: inset 0 1px 5px #06C; padding: 5px 0; margin: 15px 25px;'>
				<span class='okb'><img src='images/icons/check.jpg' width='20' height='20' />&nbsp;Message has been sent.</span>
			</div>
			
			";
		}
		
		
		else
			//then prompt message
			$Message	= "
			
			<div style=' border: 1px solid #F00; box-shadow: inset 0 1px 5px #F00; padding: 5px 0; margin: 15px 25px;'>
				<span class='error'><img src='images/icons/x.png' width='20' height='20' />&nbsp;Oppss! An empty message is not accepted.</span>
			</div>
			
			";
	}
	
	
	//IF A USER HAS BEEN ACCEPTED THEN
	$Action = $_GET['action'];
	
	if($Action == 'Accept')
		//post accept message
		$Message	= "
			
			<div style=' border: 1px solid #06C; box-shadow: inset 0 1px 5px #06C; padding: 5px 0; margin: 15px 25px;'>
				<span class='okb'><img src='images/icons/check.jpg' width='20' height='20' />
					".$r_FN."'s account is now active.
				</span>
			</div>
			
			";
	elseif($Action == 'Delete')
		//post delete message
		$Message	= "
			
			<div style=' border: 1px solid #F00; box-shadow: inset 0 1px 5px #F00; padding: 5px 0; margin: 15px 25px;'>
				<span class='error'><img src='images/icons/x.png' width='20' height='20' />
				User has been deleted and will not be able to use his/her account.
				</span>
			</div>
			
			";
	else
		//do nothing
?>

<?php include 'm_header.php'; ?>
<form action="#" method="post">

<td width="75%" valign="top" align="left">
<!-- BREAD CRUMBS / SEARCH TOOL -->
	<div class="left" style="height:70px; background-color: #9C6;">
        <div class="left" style="border-bottom: 1px solid #CCC; height:68px; margin-right: 5px;">
        <span class="okw"><img src="images/icons/toolbar-icons/user.png" width="20" height="20" style="margin-top: 5px;" />&nbsp;VIEW USER</span>
            <div class="left" style="margin-left: 25px; margin-top: 10px;"><!--Search by:
                <span class="left" style="margin-left: 5px; margin-top: 10px;">
                	<input class="SearchBox" type="text" name="txtSearch" onclick="" placeholder="Full Name" />
                </span> -->
                <span class="error">*</span> You can send message to this user
            </div>
        </div>
    </div>
    <!-- BREAD CRUMBS / SEARCH TOOL -->
    
    
    <!-- WILL DISPLAY THE CONTENT-->
    <div class="center" style="margin-top: 15px; margin-right: 5px; width: 97%;">
    
    	 <div class="left" style=" border-top-left-radius: 0.3em; border-top-right-radius: 0.3em; ">
             <div class="header_arrow">
                <span class="left">
                    <a href="m_users.php" title="Return to user">
                    	<img src="images/icons/toolbar-icons/search.png" width="20" height="20" style="margin-top: 2px;" />
                    </a>
                    View user details
                </span>
                <span class="arrow">
                </span>
             </div>
         </div>
         
         <!-- LOAD THE MESSAGE BOX HERE -->
         <div id="showUserReg"></div>
         <!-- LOAD THE MESSAGE BOX HERE -->
         
         <div class="center" style="margin-top: 15px; margin-bottom: 15px;">
                <div><span class="center"><?php echo $Message; ?></span></div>		
         </div>
        
   		 <!-- WILL DISPLAY THE DETAILS ABOUT THE USER -->
         <div id="txtHint" class="left" style=" margin-top: 15px; margin-bottom: 15px;">

            <!-- CHANGE DISPLAY BASED ON USER STATUS -->
            <?php
			//IF USER STATUS IS 1 (Active) THEN
			
			//if the user is already active then
			if($r_ST == 1)
			{
			
				//We dont show the div if we have deleted this users
				if($Action == 'Delete')
				{
					//Do not show any div just the error message
				}
				//We dont show the div if we have deleted this users
				
				
				//else show the div 
				else
				{
					//admin can only do is Write him/her a message and delete him/her if necessary
				$divAction="
				
				 <div class='profWrapper'>
					<div class='profFN'>
						<div class='left'><h3>".$r_FN."</h3></div>
					</div>
					
					<div class='profPIC'>
						<div class='center'><img src='".$r_PIC."' width='140' height='140' /></div>
					</div>
				</div>
				
				<div class='aboutWrapper'>
					<div class='left'>ABOUT</div>
				</div>
				
				<div class='aboutInfos'>
					<div class='left'><img src='images/icons/toolbar-icons/mail-diable.png' width='16' height='16' />&nbsp;
					<span class='s_normal'>Email address</span>&nbsp;<span  style=' margin-left: 5px;'>".$r_EM."</span></div>
				</div>
				
				<div class='aboutInfos'>
					<div class='left'><img src='images/icons/toolbar-icons/home.png' width='16' height='16' />&nbsp;
					<span class='s_normal'>Lives at</span>&nbsp;<span  style=' margin-left: 35px;'>".$r_AD."</span></div>
				</div>
				
				<div class='aboutInfos'>
					<div class='left'><img src='images/icons/toolbar-icons/help.png' width='16' height='16' />&nbsp;
					<span class='s_normal'>Doc bobong's</span>&nbsp;<span  style=' margin-left: 2px;'>".$r_REL."</span></div>
				</div>
				
				 <div class='aboutInfos'>
					<div class='left'><img src='images/icons/toolbar-icons/link.png' width='16' height='16' />&nbsp;
					<span class='s_normal'>Contact me</span>&nbsp;<span  style=' margin-left: 12px;'>".$r_CN."</span></div>
				</div>
				";
				
				if($r_REL == 'Student')
				{
				
					$divAction.="
					
					<div class='aboutInfos' style='background:#F1F1F1;'>
						<div class='left'>STUDENT INFORMATION</div>
					</div>
					
					<div class='aboutInfos'>
						<div class='left'><img src='images/icons/writing-4.png' width='16' height='16' />&nbsp;
						";
					
					#IF STUDENT SUBJECT IS EMPTY OR NULL THEN
					if($r_SUBJ == '')
					{
						#DISPLAY UPDATE MESSAGE
						$divAction.="
						<span class='s_normal'>Subject</span>&nbsp;<span class='error' style='margin-left: 35px;'>Need to update this part!</span></div>
						";
					}
					#IF STUDENT SUBJECT IS EMPTY OR NULL THEN
					
					#IF STUDENT SUBJECT HAS VALUE THEN
					else
					{
						#DISPLAY VALUE
						$divAction.="
						<span class='s_normal'>Subject</span>&nbsp;<span style='margin-left: 35px;'>".$r_SUBJ."</span></div>
						";
						
					}
					#IF STUDENT SUBJECT HAS VALUE THEN
						
					$divAction.="
					</div>
					
					<div class='aboutInfos'>
						<div class='left'><img src='images/icons/writing-4.png' width='16' height='16' />&nbsp;
					";
					
					#IF STUDENT SECTION IS EMPTY OR NULL THEN
					if($r_SECS == '')
					{
						#DISPLAY UPDATE MESSAGE
						$divAction.="
						<span class='s_normal'>Section</span>&nbsp;<span class='error' style='margin-left: 35px;'>Need to update this part!</span></div>
						";
					}
					#IF STUDENT SECTION IS EMPTY OR NULL THEN
					
					#IF STUDENT SECTION HAS VALUE THEN
					else
					{
						#DISPLAY VALUE
						$divAction.="
						<span class='s_normal'>Section</span>&nbsp;<span style='margin-left: 35px;'>".$r_SECS."</span></div>
						";
					}
					#IF STUDENT SECTION HAS VALUE THEN
					
					$divAction.="
					</div>
					
					<div class='aboutInfos'>
						<div class='left'><img src='images/icons/writing-4.png' width='16' height='16' />&nbsp;
					";
					
					#IF STUDENT SEMESTER IS EMPTY OR NULL THEN
					if($r_SEMS == '')
					{
						#DISPLAY UPDATE MESSAGE
						$divAction.="
						<span class='s_normal'>Semester</span>&nbsp;<span class='error' style='margin-left: 25px;'>Need to update this part!</span></div>
						";
					}
					#IF STUDENT SEMESTER IS EMPTY OR NULL THEN
					
					#IF STUDENT SEMESTER HAS VALUE THEN
					else
					{
						#DISPLAY VALUE
						$divAction.="
						<span class='s_normal'>Semester</span>&nbsp;<span style='margin-left: 25px;'>".$r_SEMS."</span></div>
						";
					}
					#IF STUDENT SEMESTER HAS VALUE THEN
						
					$divAction.="
					</div>
					
					<div class='aboutInfos'>
						<div class='left'><img src='images/icons/writing-4.png' width='16' height='16' />&nbsp;
					";
					
					#IF STUDENT SCHOOL YEAR IS EMPTY OR NULL THEN
					if($r_SY == '')
					{
						#DISPLAY VALUE
						$divAction.="
						<span class='s_normal'>School Year</span>&nbsp;<span class='error' style='margin-left: 10px;'>Need to update this part!</span></div>
						";
					}
					#IF STUDENT SCHOOL YEAR IS EMPTY OR NULL THEN
					
					#IF STUDENT SCHOOL YEAR HAS VALUE THEN
					else
					{
						#DISPLAY VALUE
						$divAction.="
						<span class='s_normal'>School Year</span>&nbsp;<span style='margin-left: 10px;'>".$r_SY."</span></div>
						";
					}
					#IF STUDENT SCHOOL YEAR HAS VALUE THEN
				}
				
				$divAction.="
				</div>
				
				<div class='profWrapper'>
					<div class='right' style='margin: -15px 5px 15px;'>
						<img style='cursor: pointer;' src='images/icons/toolbar-icons/mail.png' 
						width='25' height='25' onclick='showInputBox()' title='Write Message' /> &nbsp;
											
						<img style='cursor: pointer;' onclick='deleteUser()' 
						src='images/icons/toolbar-icons/rubbish-bin.png' width='25' height='25' title='Delete this user' />
					   
					</div>
				</div>
				
				";
				}
				//else show the div
			}
			//if the user is already active then
			
			//else if if user is not yet active then
			else
			{
				//We dont show the div if we have deleted this users
				if($Action == 'Delete')
				{
					//Do not show any div just the error message
				}
				//We dont show the div if we have deleted this users
				
				
				//else show the div 
				else
				{
					//admin can only do is Write him/her a message and delete him/her if necessary
				$divAction="
				
				 <div class='profWrapper'>
					<div class='profFN'>
						<div class='left'><h3>".$r_FN."</h3></div>
					</div>
					
					<div class='profPIC'>
						<div class='center'><img src='".$r_PIC."' width='140' height='140' /></div>
					</div>
				</div>
				
				<div class='aboutWrapper'>
					<div class='left'>ABOUT</div>
				</div>
				
				<div class='aboutInfos'>
					<div class='left'><img src='images/icons/toolbar-icons/mail-diable.png' width='16' height='16' />&nbsp;
					<span class='s_normal'>Email address</span>&nbsp;<span  style=' margin-left: 5px;'>".$r_EM."</span></div>
				</div>
				
				<div class='aboutInfos'>
					<div class='left'><img src='images/icons/toolbar-icons/home.png' width='16' height='16' />&nbsp;
					<span class='s_normal'>Lives at</span>&nbsp;<span  style=' margin-left: 35px;'>".$r_AD."</span></div>
				</div>
				
				<div class='aboutInfos'>
					<div class='left'><img src='images/icons/toolbar-icons/help.png' width='16' height='16' />&nbsp;
					<span class='s_normal'>Doc bobong's</span>&nbsp;<span  style=' margin-left: 2px;'>".$r_REL."</span></div>
				</div>
				
				 <div class='aboutInfos'>
					<div class='left'><img src='images/icons/toolbar-icons/link.png' width='16' height='16' />&nbsp;
					<span class='s_normal'>Contact me</span>&nbsp;<span  style=' margin-left: 12px;'>".$r_CN."</span></div>
				</div>
				";
				
				
				if($r_REL == 'Student')
				{
				
					$divAction.="
					
					<div class='aboutInfos' style='background:#F1F1F1;'>
						<div class='left'>STUDENT INFORMATION</div>
					</div>
					
					<div class='aboutInfos'>
						<div class='left'><img src='images/icons/writing-4.png' width='16' height='16' />&nbsp;
						";
					
					#IF STUDENT SUBJECT IS EMPTY OR NULL THEN
					if($r_SUBJ == '')
					{
						#DISPLAY UPDATE MESSAGE
						$divAction.="
						<span class='s_normal'>Subject</span>&nbsp;<span class='error' style='margin-left: 35px;'>Need to update this part!</span></div>
						";
					}
					#IF STUDENT SUBJECT IS EMPTY OR NULL THEN
					
					#IF STUDENT SUBJECT HAS VALUE THEN
					else
					{
						#DISPLAY VALUE
						$divAction.="
						<span class='s_normal'>Subject</span>&nbsp;<span style='margin-left: 35px;'>".$r_SUBJ."</span></div>
						";
						
					}
					#IF STUDENT SUBJECT HAS VALUE THEN
						
					$divAction.="
					</div>
					
					<div class='aboutInfos'>
						<div class='left'><img src='images/icons/writing-4.png' width='16' height='16' />&nbsp;
					";
					
					#IF STUDENT SECTION IS EMPTY OR NULL THEN
					if($r_SECS == '')
					{
						#DISPLAY UPDATE MESSAGE
						$divAction.="
						<span class='s_normal'>Section</span>&nbsp;<span class='error' style='margin-left: 35px;'>Need to update this part!</span></div>
						";
					}
					#IF STUDENT SECTION IS EMPTY OR NULL THEN
					
					#IF STUDENT SECTION HAS VALUE THEN
					else
					{
						#DISPLAY VALUE
						$divAction.="
						<span class='s_normal'>Section</span>&nbsp;<span style='margin-left: 35px;'>".$r_SECS."</span></div>
						";
					}
					#IF STUDENT SECTION HAS VALUE THEN
					
					$divAction.="
					</div>
					
					<div class='aboutInfos'>
						<div class='left'><img src='images/icons/writing-4.png' width='16' height='16' />&nbsp;
					";
					
					#IF STUDENT SEMESTER IS EMPTY OR NULL THEN
					if($r_SEMS == '')
					{
						#DISPLAY UPDATE MESSAGE
						$divAction.="
						<span class='s_normal'>Semester</span>&nbsp;<span class='error' style='margin-left: 25px;'>Need to update this part!</span></div>
						";
					}
					#IF STUDENT SEMESTER IS EMPTY OR NULL THEN
					
					#IF STUDENT SEMESTER HAS VALUE THEN
					else
					{
						#DISPLAY VALUE
						$divAction.="
						<span class='s_normal'>Semester</span>&nbsp;<span style='margin-left: 25px;'>".$r_SEMS."</span></div>
						";
					}
					#IF STUDENT SEMESTER HAS VALUE THEN
						
					$divAction.="
					</div>
					
					<div class='aboutInfos'>
						<div class='left'><img src='images/icons/writing-4.png' width='16' height='16' />&nbsp;
					";
					
					#IF STUDENT SCHOOL YEAR IS EMPTY OR NULL THEN
					if($r_SY == '')
					{
						#DISPLAY VALUE
						$divAction.="
						<span class='s_normal'>School Year</span>&nbsp;<span class='error' style='margin-left: 10px;'>Need to update this part!</span></div>
						";
					}
					#IF STUDENT SCHOOL YEAR IS EMPTY OR NULL THEN
					
					#IF STUDENT SCHOOL YEAR HAS VALUE THEN
					else
					{
						#DISPLAY VALUE
						$divAction.="
						<span class='s_normal'>School Year</span>&nbsp;<span style='margin-left: 10px;'>".$r_SY."</span></div>
						";
					}
					#IF STUDENT SCHOOL YEAR HAS VALUE THEN
				}
				
				$divAction.="
				</div>
					
				 <div class='profWrapper'>
					<div class='right' style='margin: -15px 5px 15px;'>
						<img style='cursor: pointer;' src='images/icons/toolbar-icons/user-accept.png' 
						width='25' height='25' onclick='acceptUser()' title='Accept this user' /> &nbsp;
											
						<img style='cursor: pointer;' onclick='deleteUser()' 
						src='images/icons/toolbar-icons/rubbish-bin.png' width='25' height='25' title='Delete this user' />
					   
					</div>
				</div>
				
				";
				}
				//else show the div
				
			}
			//else if if user is not yet active then
			echo $divAction;	//echo result div
			?>
            
            <!-- CHANGE DISPLAY BASED ON USER STATUS -->
            
            
            <!-- LOAD THE MESSAGE BOX HERE -->
             <div id="showMessagebox"></div>
             <!-- LOAD THE MESSAGE BOX HERE -->
             
             

        </div><!-- end div txtHint -->
        
         <!-- WILL DISPLAY THE DETAILS ABOUT THE USER -->
    </div>
    
    <!-- pagination -->
    <div class="pagination" style="margin: 2px 15px 15px;">
		<?php 
		if($pages >= 1 && $page <= $pages)
		{
			for ($x = 1; $x <= $pages; $x++)
			{
				echo ($x == $page) ? '<b><a id="onLink" href="?page='.$x.'">'.$x.'</a></b> ' :  '<a href="?page='.$x.'">'.$x.'</a> ';
			}
		} 
		?>
    </div>
    <!-- pagination -->	
</td>

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
    
    
    
    <!-- Display Updates thats happening in the system -->
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
    <!-- DIV FOR DAILY UPDATES -->
    
    <!-- Display Updates thats happening in the system -->
    
    <!-- pagination -->
    <div class="center" style="margin-top: 15px; margin-bottom: 15px; margin-left: 4px; margin-right: 4px; "><?php echo $paginationDisplay; ?></div>
    <!-- pagination -->
</td>

</form>
<?php include 'footer.php'; ?>