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


	//GETTING ALL THE INFORMATION OF THIS USER
	$query	= " SELECT * FROM user_details WHERE UID = '".$UID."' ";
	$result	= mysql_query($query);
	$row	= mysql_fetch_array($result);
	//declaire variables
	$r_UN	= $row['UN'];	//User name
	$r_FN	= $row['FN'];	//Full name
	$r_EM	= $row['EM'];	//Email
	$r_CN	= $row['CN'];	//Contact Nummber
	$r_AD	= $row['AD'];	//Address
	$r_REL	= $row['REL'];	//Relation
	$r_PIC	= $row['PIC'];	//Picture
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
		
?>

<?php include 'g_header.php'; ?>
<form action="#" method="post">


<td width="75%" valign="top" align="left">
	<!-- BREAD CRUMBS / SEARCH TOOL -->
	<div class="left" style="height:70px; background-color: #9C6;">
        <div class="left" style="border-bottom: 1px solid #CCC; height:68px; margin-right: 5px;">
        <span class="okw"><img src="images/icons/toolbar-icons/user.png" width="20" height="20" style="margin-top: 5px;" />&nbsp;PROFILE</span>
            <div class="left" style="margin-left: 25px; margin-top: 10px;"><!--Search by:
                <span class="left" style="margin-left: 5px; margin-top: 10px;">
                  <input type="text" name="txtSearch" onKeyUp="showSomething(this.value)" class="SearchBox" placeholder="User Name" />
                </span> -->
            </div>
        </div>
    </div>
    <!-- BREAD CRUMBS / SEARCH TOOL -->
    
    
    <!-- WILL DISPLAY THE CONTENT-->
    <div class="center" style="margin-top: 15px; margin-right: 5px; width: 97%;">
    
    	 <div class="left" style=" border-top-left-radius: 0.3em; border-top-right-radius: 0.3em; ">
             <div class="header_arrow">
                <span class="left">
                    <img src="images/icons/toolbar-icons/forward.png"  width="20" height="20" style="margin-top: 2px;" />&nbsp;
                    My Profile</span>
                    <span class="arrow">
                </span>
             </div>
         </div>
         
         <div class="center" style="margin-top: 15px; margin-bottom: 15px;">
			<!-- PROMPT MESSAGE HERE -->&nbsp;<?php echo $Message; ?>
         </div>
         
          <!-- LOAD THE MESSAGE BOX HERE -->
         <div id="showSentByHere"></div>
         <!-- LOAD THE MESSAGE BOX HERE -->
         
        
   		 <!-- WILL DISPLAY THE DETAILS ABOUT THE USER -->
         <div id="txtHint" class="left" style=" margin: 0 25px 15px;">
         	<!-- YOU TABLE HERE -->
            
           	<div class="profWrapper">
            	<div class="profFN">
                	<div class="left"><h3><?php echo $r_FN; ?></h3></div>
                </div>
                
                <div class="profPIC">
                	<div class="center"><img src="<?php echo $r_PIC; ?>" width="140" height="140" /></div>
                </div>
            </div>
            
            <div class="aboutWrapper">
            	<div class="left">ABOUT</div>
            </div>
            
            <div class="aboutInfos">
            	<div class="left"><img src="images/icons/toolbar-icons/mail-diable.png" width="16" height="16" />&nbsp;
				<span class="s_normal">Email address</span>&nbsp;<span style="margin-left: 4px;"><?php echo $r_EM; ?></span></div>
            </div>
            
            <div class="aboutInfos">
            	<div class="left"><img src="images/icons/toolbar-icons/home.png" width="16" height="16" />&nbsp;
				<span class="s_normal">Lives at</span>&nbsp;<span style="margin-left: 35px;"><?php echo $r_AD; ?></span></div>
            </div>
            
            <div class="aboutInfos">
            	<div class="left"><img src="images/icons/toolbar-icons/help.png" width="16" height="16" />&nbsp;
				<span class="s_normal">Doc bobong's</span>&nbsp;<span style="margin-left: 3px;"><?php echo $r_REL; ?></span></div>
            </div>
            
            <div class="aboutInfos">
            	<div class="left"><img src="images/icons/toolbar-icons/link.png" width="16" height="16" />&nbsp;
				<span class="s_normal">Contact me</span>&nbsp;<span style="margin-left: 12px;"><?php echo $r_CN; ?></span></div>
            </div>
			
			
			<?php
			##################!!!!!!!!! DIDPLAY THIS ONLY IF THE USER IS STUDENT !!!!!!!!!!!!!#####################
			if($r_REL == 'Student')
			{
				$divStudentInfo ="
				
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
					$divStudentInfo .="
					<span class='s_normal'>Subject</span>&nbsp;<span class='error' style='margin-left: 35px;'>Need to update this part!</span></div>
					";
				}
				#IF STUDENT SUBJECT IS EMPTY OR NULL THEN
				
				#IF STUDENT SUBJECT HAS VALUE THEN
				else
				{
					#DISPLAY VALUE
					$divStudentInfo .="
					<span class='s_normal'>Subject</span>&nbsp;<span style='margin-left: 35px;'>".$r_SUBJ."</span></div>
					";
					
				}
				#IF STUDENT SUBJECT HAS VALUE THEN
					
				$divStudentInfo .="
				</div>
				
				<div class='aboutInfos'>
					<div class='left'><img src='images/icons/writing-4.png' width='16' height='16' />&nbsp;
				";
				
				#IF STUDENT SECTION IS EMPTY OR NULL THEN
				if($r_SECS == '')
				{
					#DISPLAY UPDATE MESSAGE
					$divStudentInfo .="
					<span class='s_normal'>Section</span>&nbsp;<span class='error' style='margin-left: 35px;'>Need to update this part!</span></div>
					";
				}
				#IF STUDENT SECTION IS EMPTY OR NULL THEN
				
				#IF STUDENT SECTION HAS VALUE THEN
				else
				{
					#DISPLAY VALUE
					$divStudentInfo .="
					<span class='s_normal'>Section</span>&nbsp;<span style='margin-left: 35px;'>".$r_SECS."</span></div>
					";
				}
				#IF STUDENT SECTION HAS VALUE THEN
				
				$divStudentInfo .="
				</div>
				
				<div class='aboutInfos'>
					<div class='left'><img src='images/icons/writing-4.png' width='16' height='16' />&nbsp;
				";
				
				#IF STUDENT SEMESTER IS EMPTY OR NULL THEN
				if($r_SEMS == '')
				{
					#DISPLAY UPDATE MESSAGE
					$divStudentInfo .="
					<span class='s_normal'>Semester</span>&nbsp;<span class='error' style='margin-left: 25px;'>Need to update this part!</span></div>
					";
				}
				#IF STUDENT SEMESTER IS EMPTY OR NULL THEN
				
				#IF STUDENT SEMESTER HAS VALUE THEN
				else
				{
					#DISPLAY VALUE
					$divStudentInfo .="
					<span class='s_normal'>Semester</span>&nbsp;<span style='margin-left: 25px;'>".$r_SEMS."</span></div>
					";
				}
				#IF STUDENT SEMESTER HAS VALUE THEN
					
				$divStudentInfo .="
				</div>
				
				<div class='aboutInfos'>
					<div class='left'><img src='images/icons/writing-4.png' width='16' height='16' />&nbsp;
				";
				
				#IF STUDENT SCHOOL YEAR IS EMPTY OR NULL THEN
				if($r_SY == '')
				{
					#DISPLAY VALUE
					$divStudentInfo .="
					<span class='s_normal'>School Year</span>&nbsp;<span class='error' style='margin-left: 10px;'>Need to update this part!</span></div>
					";
				}
				#IF STUDENT SCHOOL YEAR IS EMPTY OR NULL THEN
				
				#IF STUDENT SCHOOL YEAR HAS VALUE THEN
				else
				{
					#DISPLAY VALUE
					$divStudentInfo .="
					<span class='s_normal'>School Year</span>&nbsp;<span style='margin-left: 10px;'>".$r_SY."</span></div>
					";
				}
				#IF STUDENT SCHOOL YEAR HAS VALUE THEN
				
				$divStudentInfo .="
				</div>
				
				";
				
				echo $divStudentInfo;
			}
			else
				#!!!!!DON NOT DISPLAY THIS!!!!!
			##################!!!!!!!!! DIDPLAY THIS ONLY IF THE USER IS STUDENT !!!!!!!!!!!!!#####################
			?>
			
            
            <div class="">
            	<div class="left" style="margin: 15px 0 15px 15px;">
                	<a href="g_profile_edit.php" title="Edit Profile"><h5>Edit Profile</h5></a>
                </div>
            </div>
            
            
             <!-- LOAD THE MESSAGE BOX HERE -->
             <div id="showLightbox"></div>
             <!-- LOAD THE MESSAGE BOX HERE -->
            
        	<!-- YOU TABLE HERE -->
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
            <img src="images/icons/warning_48.png"  width="20" height="20" />&nbsp;Daily Updates            
        </div>
        
        
        
        <!-- DIV FOR NEW MESSAGES -->
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
				<input type='button' class='button' onclick='showSentBy()' value='Inbox' title='View Messages' />
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
       
        <!-- DIV FOR NEW MESSAGES -->
        
        
    </div>
    <!-- Display Updates thats happening in the system -->
</td>
<!-- RIGHT NAV -->


</form>
<?php include 'footer.php'; ?>