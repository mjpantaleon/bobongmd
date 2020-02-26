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


$Message	= "";
$Count 		= 0;

	 //GETTING ALL THE INFORMATION OF THIS USER
	$query	= " SELECT * FROM user_details WHERE UID = '".$UID."' ";
	$result	= mysql_query($query);
	$row	= mysql_fetch_array($result);
	//declaire variables
	$r_UN	= $row['UN'];	//User name
	$r_FN	= $row['FN'];	//Full name
	$r_EM	= $row['EM'];	//Email
	$r_CN	= $row['CN'];	//Contact Number
	$r_AD	= $row['AD'];	//Address
	$r_REL	= $row['REL'];	//Realtion
	$r_PIC	= $row['PIC'];	//Picture
	
	
	###########################################################################################################################
	############################# ADDING A NEW GROUP ############################################################
	if(isset($_POST['txtGN']))
	{
		#CREATE LOCAL VARIABLES
		$GN		= mysql_real_escape_string($_POST['txtGN']);
		$DESK	= mysql_real_escape_string($_POST['txtDESK']);
		$ST		= 0;
		
		
		#setting up the date
		$today	= date("F j, Y");	//string/Int format
		$hr_s 	= date("H:i:sa");	//12hrs format
		$cur_Y 	= date('Y');			//get the current year
		
		
		#id format
		$limit 	= 4;		//set the limit into 4
		$sql	= "SELECT * FROM `groups` ORDER BY `group_id` DESC LIMIT 0,1";	//query serves as our counter
		$result = mysql_query($sql);										//then its passed down to the varibale $result
		$row 	= mysql_fetch_array($result);									//so that we could access row 'UID' from the table
			$last_id = $row['group_id'];
			$last_dgt = substr($last_id, -4);								
			
			$id 			= str_pad((int) $last_dgt,$limit,"0",STR_PAD_LEFT);
			$id				= $id + 1;										//increment by 1
			$id 			= str_pad((int) $id,$limit,"0",STR_PAD_LEFT);
			$group_id		= "BBMDGP".$cur_Y."-".$id;						//BBDM2013-0001
		#id format
		
		
		# !!!!! MUST CHECK FIRST FOR DUPLICATIONS !!!!!!!
		if(mysql_num_rows(mysql_query(" SELECT `GN` FROM `groups` WHERE `GN` = '".$GN."' ")) > 0)
		{
			$Message	= "<span class='error'><img src='images/icons/x.png' width='20' height='20'>Ooppps! Group name already exists!</span>";
		}
		# !!!!! MUST CHECK FIRST FOR DUPLICATIONS !!!!!!!
		
		# IF NO DUPLICATIONS FOUND THEN
		else
		{
			#INSERT INTO TABLE
			$query	= " INSERT INTO `groups` SET
						`group_id`	= '".$group_id."',
						`GN`		= '".$GN."',
						`DESK`		= '".$DESK."',
						`dateM`		= '".$today."',
						`timeM`		= '".$hr_s."',
						`UID`		= '".$UID."',
						`BY`		= '".$FN."',
						`ST`		= '".$ST."'	
			 ";
			 mysql_query($query)
			 or die(mysql_error());
			 
			 #LOG THIS EVENT
			$time_in = date("H:i:sa");							
			$cur_date = date("Y-m-d");
			$cur_timestamp = $cur_date." ".$time_in;
			$query = "	INSERT INTO events SET E_TS = '$cur_timestamp', UID = '$UID', E_D = 'New group has been made with ID:[ ".$group_id." ] '	";
			mysql_query($query)
			or die(mysql_error());
			
			
			#UNSET FIELDS
			unset($_POST['txtGN']);
			unset($_POST['txtDESK']);
			
			
			#PROMT MESSAGE
			$Message	= "<span class='okb'><img src='images/icons/check.jpg' width='20' height='20'>New Group has been added.</span>";
		}
		# IF NO DUPLICATIONS FOUND THEN
		
	}
	############################# ADDING A NEW GROUP ############################################################
	###########################################################################################################################
	
?>



<?php include 'g_header.php'; ?>

<!-- -->


<!-- \\\\\\\\\\\\\\\\\\\\CONTENT PART//////////////////////// -->
<td width="75%" valign="top" align="left">
	<div class="left" style="height:70px; background-color: #9C6;">
        <div class="left" style="border-bottom: 1px solid #CCC; height:68px; margin-right: 5px;">
            <span class="okw">
            <img src="images/icons/Users.png" width="20" height="20" style="margin-top: 5px;" />&nbsp;GROUPS</span>
            <div class="left" style="margin-left: 25px; margin-top: 10px;">&nbsp; </div>
        </div>
    </div>
	
    <!-- WILL DISPLAY THE CONTENT-->
    <div class="center" style="margin-top: 15px; margin-right: 5px; width: 97%;">
    
    	 <div class="left" style=" border-top-left-radius: 0.3em; border-top-right-radius: 0.3em; ">
             <div class="header_arrow">
                <span class="left">
                    <img src="images/icons/Users.png" height="20" style="margin-top: 2px;" />&nbsp;Most recent Groups</span><span class="arrow">
                </span>
             </div>
         </div>
         
         <div class="center" style="margin-top: 15px; margin-bottom: 15px;">
			<?php echo $Message; ?>
         </div>
         
        
   		 <!-- WILL DISPLAY THE DETAILS ABOUT THE USER -->
         <div id="txtHint" class="left" style=" margin-top: 15px; margin-bottom: 15px;">
           	<!-- YOU TABLE HERE -->
			<table>
				<tr>
					<td>
					<?php
					#GET ALL DETAILS OF THE RECORDS IN THE TABLE `GROUPS`
					
					# **** should change the ST from 0 to 1 ****
					$query		= "	SELECT * FROM `groups` WHERE `ST` = '0' ORDER BY `group_id` DESC ";
					$result		= mysql_query($query);
					#LOOP ON THE TABLE TO FETCH ALL THE RECORDS
					while($row	= mysql_fetch_array($result))
					{
						#CREATE LOCAL VARIABLES
						$r_group_id	= $row['group_id'];	#group id
						$r_GN		= $row['GN'];		#group name
						$r_DESK		= $row['DESK'];		#description of the group
						$r_dateM	= $row['dateM'];	#date made
						#$r_timeM	= $row['timeM'];	#time made
						$r_MMBRS	= $row['MMBRS'];	#number of members
						$r_UID		= $row['UID'];		#user ID
						$r_BY		= $row['BY'];		#full name of the admin of this group
						
						
						$divGroup ="
						
						<div style=' margin: 20px 25px; '>
							<div class='ribbon'>
								<a href='g_group_info.php?group=$r_group_id' title='View details about this group'><h3>" .$r_GN. "</h3></a>
								
								<div style=' padding: 10px; margin: 5px 0 0;'>
									<p>" .nl2br($r_DESK). "</p>
								</div>
								
								<div style=' color: #000; border-top: 1px dashed #CCC;'>
									<span class='s_normalb'>Date created: " .$r_dateM. "</span>
									<span class='s_normalb' style=' float: right;'>Members (".$r_MMBRS.")</span>
								</div>
								
								<div style=' color: #000;'>
									<span class='s_normal'>Admin: ".$r_BY."</span> 
									<span class='s_normal' style=' float: right;'></span>
								</div>
								
							</div>
						</div>
						
						";
						
						echo $divGroup; #display result
					}
					#END WHILE
					
					#GET ALL DETAILS OF THE RECORDS IN THE TABLE `GROUPS`
					?>
					
		
					</td>
				</tr>
			</table>
        	<!-- YOU TABLE HERE -->
         </div>
         <!-- WILL DISPLAY THE DETAILS ABOUT THE USER -->
    </div>
   
    
    <div class="center" style="margin-top: 15px;"><?php echo $paginationDisplay; ?></div>

	<!-- CONTENT -->
</td>
<!-- \\\\\\\\\\\\\\\\\\\\CONTENT PART//////////////////////// -->



<!-- \\\\\\\\\\\\\\\\\\\\RIGHT NAV//////////////////////// -->
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
            <img src="images/icons/toolbar-icons/help.png"  width="20" height="20" />&nbsp;What do you want to do?            
        </div>
        
         <!--<div class="left" style="padding-top: 5px; padding-bottom: 5px; 
        border-left:1px solid #CCC;  border-right:1px solid #CCC; border-bottom: 1px solid #CCC;">
            <div class="left">
                <img src="images/icons/toolbar-icons/mail.png" width="20" height="20" />&nbsp;
                <a class="button" href="g_send_msg.php" title="Send Message">Send Mesage</a>  
            </div>
        </div>-->
        
         <div class="left" style="padding-top: 5px; padding-bottom: 5px; 
        border-left:1px solid #CCC;  border-right:1px solid #CCC; border-bottom: 1px solid #CCC;">
            <div class="left">
            <img src="images/icons/toolbar-icons/book.png" width="20" height="20" />&nbsp;
            <a class="button" href="g_send_asgn.php" title="Send Message">Send Assignment</a>  
            </div>
        </div>
        
       <?php
	   if($r_PIC != '')
	   {
		   echo "
		    <div class='left' style='padding-top: 5px; padding-bottom: 5px; 
			border-left:1px solid #CCC;  border-right:1px solid #CCC; border-bottom: 1px solid #CCC;'>
				<div class='left'>
				<img src='images/icons/toolbar-icons/user.png' width='20' height='20' />&nbsp;
				<a class='button' href='g_profile_edit.php' title='Edit Profile'>Edit Profile</a>  
				</div>
			</div>
			";
	   }
	   
	   else
	   {
		   echo "
		    <div class='left' style='padding-top: 5px; padding-bottom: 5px; 
			border-left:1px solid #CCC;  border-right:1px solid #CCC; border-bottom: 1px solid #CCC;'>
				<div class='left'>
				<img src='images/icons/toolbar-icons/user_alert.png' width='20' height='20' />&nbsp;
				<a class='button' href='g_profile_edit.php' title='Edit Profile'>Edit Profile</a>  
				</div>
			</div>
			";
	   }
	   
	   ?>
      
    </div>
    
    <!-- DIV FOR DAILY UPDATES -->
    <div style="margin-top: 15px; margin-left: 5px; margin-right: 5px; margin-bottom: 15px;">
        <div class="left" style=" height: 25px; border: 1px solid #CCC; border-top-left-radius: 0.3em; border-top-right-radius: 0.3em; 
        background-color:#b6e56d">
            <img src="images/icons/warning_48.png"  width="20" height="20" />&nbsp;Manage Group           
        </div>
        
        <!-- DIV FOR NEW MESSAGES -->
        <div class='left' style='padding-top: 5px; padding-bottom: 5px; 
            border-left:1px solid #CCC;  border-right:1px solid #CCC; border-bottom: 1px solid #CCC;'>
			<div class='left'>
			 <img src="images/icons/Users.png" width='20' height='20' />&nbsp;
			<input type='button' class='button' onclick='showGroup()' value='Add New' title="Add New Group" />
			</div>
		</div>
			
		<div class='left' style='padding-top: 5px; padding-bottom: 5px; 
            border-left:1px solid #CCC;  border-right:1px solid #CCC; border-bottom: 1px solid #CCC;'>	
			<div class='left'>
			 <img src="images/icons/toolbar-icons/tools.png" width='20' height='20' />&nbsp;
			<a href='' type='button' class='button'>Edit my Group</a>
			</div>
		</div>
        <!-- DIV FOR NEW MESSAGES -->
        
        
    </div>
    <!-- DIV FOR DAILY UPDATES -->
	
	
	<!-- DIV FOR BULLETIN BOARD -->
	<!-- <div style="margin-top: 15px; margin-left: 5px; margin-right: 5px; margin-bottom: 15px;">
        <div class="left" style=" height: 25px; border: 1px solid #CCC; border-top-left-radius: 0.3em; border-top-right-radius: 0.3em; 
        background-color:#b6e56d">
            <img src="images/icons/Info-icon.png"  width="20" height="20" />&nbsp;Bulletin Board           
        </div>
		
		<div class='left' style='padding: 5px 10px;
		border-left:1px solid #CCC;  border-right:1px solid #CCC; border-bottom: 1px solid #CCC;'>
			<script type="text/javascript">new pausescroller(pausecontent2, "pscroller2", "someclass", 2000)</script>	
		</div>
	</div>  -->
	<!-- DIV FOR BULLETIN BOARD -->
	
    <!-- Display Updates thats happening in the system -->
</td>

<!-- \\\\\\\\\\\\\\\\\\\\RIGHT NAV//////////////////////// -->

<?php include 'footer.php'; ?>