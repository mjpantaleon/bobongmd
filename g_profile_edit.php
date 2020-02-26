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


	//set POST variable
	$e_FN	= trim(mysql_real_escape_string($_POST['txtFN']));		//Full name
	$e_EM	= trim(mysql_real_escape_string($_POST['txtEM']));		//Email
	$e_AD	= trim(mysql_real_escape_string($_POST['txtAD']));		//Address
	$e_CN	= trim($_POST['txtCN']);								//Contact number
	//IF STUDENT ONLY!!!!!
	$e_SUBJ	= $_POST['cmbSUBJ'];									//Subject
	$e_SECS	= trim(mysql_real_escape_string($_POST['txtSECS']));	//Section
	$e_SEMS	= $_POST['cmbSEMS'];									//Semester
	$e_SY	= $_POST['cmbSY'];										//School year
	
	$Valid	= true;													//boolean condition
	
	//if update button has been clicked then
	if( isset( $_POST['cmdUpdate']))
	{
		
		#### IF THIS IS A STUDENT THEN #####################################################
		if(isset($_POST['cmbSUBJ']))
		{
			//update table user details
			mysql_query(" 	UPDATE user_details SET
							FN 		= '".$e_FN."', 
							EM		= '".$e_EM."',
							CN 		= '".$e_CN."',
							AD		= '".$e_AD."',
							SUBJ	= '".$e_SUBJ."',
							SECS	= '".$e_SECS."',
							SEMS	= '".$e_SEMS."',
							SY		= '".$e_SY."'
							WHERE UID = '".$UID."'
						")
			or die(mysql_error());
			
			//update users table
			mysql_query(" 	UPDATE users
							SET FN = '".$e_FN."'
							WHERE UID = '".$UID."'
						")
			or die(mysql_error());
			
			
			//log event
			$time_in = date("H:i:sa");							
			$cur_date = date("Y-m-d");
			$cur_timestamp = $cur_date." ".$time_in;	//concatinate time + current date
			
			$query = "	INSERT INTO events SET E_TS = '$cur_timestamp', UID = '".$UID."', E_D = '[ ".$FN." ] Update Profile'	";
			mysql_query($query)
			or die(mysql_error());
			
			//unset variables
			unset($_POST['txtFN']);		//Full name
			unset($_POST['txtEM']);		//Email
			unset($_POST['txtCN']);		//Address
			unset($_POST['txtAD']);		//Contact number
			unset($_POST['cmbSUBJ']);	//Subject
			unset($_POST['txtSECS']);	//Section
			unset($_POST['cmbSEMS']);	//Semester
			unset($_POST['cmbSY']);		//School year
			
			
			//clear post variables
			$e_FN	= "";		//Full name
			$e_EM	= "";		//Email
			$e_AD	= "";		//Address
			$e_CN	= "";		//Contact number
			$e_SUBJ	= "";		//Subject
			$e_SECS	= "";		//Section
			$e_SEMS	= "";		//Semester
			$e_SY	="";		//School year
			
			$Message	= "
			<div style=' border: 1px solid #06C; box-shadow: inset 0 1px 5px #06C; padding: 5px 0; margin: 15px 25px;'>
				<span class='okb'><img src='images/icons/check.jpg' width='20' height='20' />&nbsp;Your student profile has been successfully updated.</span>
			</div>
			";
		}
		### IF THIS IS A STUDENT THEN #####################################################
		
		
		### IF NOT A STUDENT THEN !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
		else
		{
			//update table user details
			mysql_query(" 	UPDATE user_details 
							SET FN = '".$e_FN."', 
							EM	= '".$e_EM."',
							CN 	= '".$e_CN."',
							AD	= '".$e_AD."'
							WHERE UID = '".$UID."'
						")
			or die(mysql_error());
			
			//update users table
			mysql_query(" 	UPDATE users
							SET FN = '".$e_FN."'
							WHERE UID = '".$UID."'
						")
			or die(mysql_error());
			
			
			//log event
			$time_in = date("H:i:sa");							
			$cur_date = date("Y-m-d");
			$cur_timestamp = $cur_date." ".$time_in;	//concatinate time + current date
			
			$query = "	INSERT INTO events SET E_TS = '$cur_timestamp', UID = '".$UID."', E_D = '[ ".$FN." ] Update Profile'	";
			mysql_query($query)
			or die(mysql_error());
			
			//unset variables
			unset($_POST['txtFN']);		//Full name
			unset($_POST['txtEM']);		//Email
			unset($_POST['txtCN']);		//Address
			unset($_POST['txtAD']);		//Contact number
			
			//clear post variables
			$e_FN	= "";		//Full name
			$e_EM	= "";		//Email
			$e_AD	= "";		//Address
			$e_CN	= "";		//Contact number
		
			$Message	= "
			<div style=' border: 1px solid #06C; box-shadow: inset 0 1px 5px #06C; padding: 5px 0; margin: 15px 25px;'>
				<span class='okb'><img src='images/icons/check.jpg' width='20' height='20' />&nbsp;Profile has been successfully Updated.</span>
			</div>
			";
		}
		### IF NOT A STUDENT THEN !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
	}
	//if update button has been clicked then
	
	
	
	
	//IF USER CHANGE HIS/HER PROFILE PICK
	//when a user select a new file atachment
	if( isset( $_FILES['fileProfPick']))
	{
		
		//select of this user has a profile picture
		$query	= " SELECT PIC FROM user_details WHERE UID = '".$UID."' ";
		$result	= mysql_query($query)
		or die(mysql_error());
		$row	= mysql_fetch_array($result);
		
		$r_PIC	= $row['PIC'];		//profile picture of this user

		//CHECK FOR ERRORS
		//if no errors found then
		if($_FILES['fileProfPick']['error'] <= 0)
		{
			$fileAtt		= $_POST['fileProfPick'];
				
			$fileAttName	= $_FILES['fileProfPick']['name'];
			$fileAttTemp	= $_FILES['fileProfPick']['tmp_name'];
			$fileAttPath 	= "images/user_picks/".$fileAttName;
			
			//Must first remove old fiela attachement then
			//check first if the theres a profile pick
			
			//IF USER HAS NO PROFILE PICK THEN
			if($r_PIC == '')
			{

				//UPDATE NEW PROFILE PICK
				mysql_query(" UPDATE user_details SET PIC = '".$fileAttPath."' WHERE UID = '".$UID."' ")
				or die(mysql_error());
				move_uploaded_file($fileAttTemp, $fileAttPath);	//code structure for moving this file
			
			
				//log this event
				$time_in = date("H:i:sa");							
				$cur_date = date("Y-m-d");
				$cur_timestamp = $cur_date." ".$time_in;	//concatinate time + current date
				
				$query = "	INSERT INTO events SET E_TS = '$cur_timestamp', UID = '$UID', E_D = '[ ".$FN." ] Added profile picture'	";
				mysql_query($query)
				or die(mysql_error());
				
				//then prompt message
				$Message	= "
				
				<div style=' border: 1px solid #06C; box-shadow: inset 0 1px 5px #06C; padding: 5px 0; margin: 15px 25px;'>
					<span class='okb'><img src='images/icons/check.jpg' width='20' height='20' />&nbsp;New Profile has been added.</span>
				</div>
				
				";
			}
			//IF USER HAS NO PROFILE PICK THEN
			
			//ELSE IF USER HAS PROFILE PICK THEN
			else
			{
				//remove first the old one
				$Old_File	= $r_PIC;
				unlink($Old_File);		//removing old files in the folder
				
				
				//UPDATE NEW PROFILE PICK
				mysql_query(" UPDATE user_details SET PIC = '".$fileAttPath."' WHERE UID = '".$UID."' ")
				or die(mysql_error());
				move_uploaded_file($fileAttTemp, $fileAttPath);	//code structure for moving this file
			
			
				//log this event
				$time_in = date("H:i:sa");							
				$cur_date = date("Y-m-d");
				$cur_timestamp = $cur_date." ".$time_in;	//concatinate time + current date
				
				$query = "	INSERT INTO events SET E_TS = '$cur_timestamp', UID = '$UID', E_D = '[ ".$FN." ] Update profile picture'	";
				mysql_query($query)
				or die(mysql_error());
				
				//then prompt message
				$Message	= "
				
				<div style=' border: 1px solid #06C; box-shadow: inset 0 1px 5px #06C; padding: 5px 0; margin: 15px 25px;'>
					<span class='okb'><img src='images/icons/check.jpg' width='20' height='20' />&nbsp;Updated a new Profile picture.</span>
				</div>
				
				";
				
			}	
			//ELSE IF USER HAS PROFILE PICK THEN
			
		}
		//CHECK FOR ERRORS
		
		else
			//then prompt message
			$Message	= "
			
			<div style=' border: 1px solid #F00; box-shadow: inset 0 1px 5px #F00; padding: 5px 0; margin: 15px 25px;'>
				<span class='error'><img src='images/icons/x.png' width='20' height='20' />&nbsp;Oppss! having an error during uploading.</span>
			</div>
			
			";
	}
	//IF USER CHANGE HIS/HER PROFILE PICK
?>

<?php include 'g_header.php'; ?>
<form action="#" method="post">


<td width="75%" valign="top" align="left">
	<!-- BREAD CRUMBS / SEARCH TOOL -->
	<div class="left" style="height:70px; background-color: #9C6;">
        <div class="left" style="border-bottom: 1px solid #CCC; height:68px; margin-right: 5px;">
        <span class="okw"><img src="images/icons/toolbar-icons/user.png" width="20" height="20" style="margin-top: 5px;" />&nbsp;EDIT PROFILE</span>
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
                    <img src="images/icons/toolbar-icons/tools.png" width="20" height="20" style="margin-top: 2px;" />&nbsp;Edit Profile</span>
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
         
         <!-- LOAD THE MESSAGE BOX HERE -->
         <div id="showLightbox"></div>
         <!-- LOAD THE MESSAGE BOX HERE -->
        
   		 <!-- WILL DISPLAY THE DETAILS ABOUT THE USER -->
         <div id="txtHint" class="left" style=" margin: 0 25px 15px;">
         <?php
			 //GETTING ALL THE INFORMATION OF THIS USER
			$query	= " SELECT * FROM user_details WHERE UID = '".$UID."' ";
			$result	= mysql_query($query);
			$row	= mysql_fetch_array($result);
			//declaire variables
			$e_UN	= $row['UN'];	//User name
			$e_FN	= $row['FN'];	//Full name
			$e_EM	= $row['EM'];	//Email
			$e_CN	= $row['CN'];	//Contact Nummber
			$e_AD	= $row['AD'];	//Address
			$e_REL	= $row['REL'];	//Relation
			$e_PIC	= $row['PIC'];	//Picture
			//FOR STUDENTS ONLY
			$e_SUBJ	= $row['SUBJ'];
			$e_SECS	= $row['SECS'];
			$e_SEMS	= $row['SEMS'];
			$e_SY	= $row['SY'];
			
			
			//change format of profile picture of user
			if($e_PIC != '')
				$e_PIC = $e_PIC;
			else
				$e_PIC = "images/icons/default_user.jpg";

		 ?>
         	<!-- YOU TABLE HERE -->
            <div class="profWrapper2">
            	<div class="profFN">
                	<div class="left">
                    	<input type="text" class="InputBox5" name="txtFN" value="<?php echo $e_FN; ?>" required />
                    </div>
                </div>
                
                <div class="profPIC">
                	<div class="center">
                        <img style="cursor: pointer;" onclick="showProf()" src="<?php echo $e_PIC; ?>" width="140" 
                        height="140" alt="Prof1" title="Change profile picture" />
                    </div>
                </div>
            </div>
            
            <div class="aboutWrapper">
            	<div class="left">ABOUT</div>
            </div>
            
            <div class="aboutInfos">
            	<div class="left"><img src="images/icons/toolbar-icons/mail-diable.png" width="16" height="16" />&nbsp;
					<span class="s_normal">Email address</span>&nbsp;
					<span style="margin-left: 5px;"><input type="email" name="txtEM" class="InputBox6" value="<?php echo $e_EM; ?>" required /></span>
				</div>
            </div>
            
            <div class="aboutInfos">
            	<div class="left"><img src="images/icons/toolbar-icons/home.png" width="16" height="16" />&nbsp;
					<span class="s_normal">Lives at</span>&nbsp;
					<span style="margin-left: 35px;"><input type="text" name="txtAD" class="InputBox6" value="<?php echo $e_AD; ?>" required /></span>
				</div>
            </div>
            
            <div class="aboutInfos">
            	<div class="left"><img src="images/icons/toolbar-icons/help.png" width="16" height="16" />&nbsp;
				<span class="s_normal">Doc bobong's</span>&nbsp;<?php echo $e_REL; ?></div>
            </div>
            
            <div class="aboutInfos">
            	<div class="left"><img src="images/icons/toolbar-icons/link.png" width="16" height="16" />&nbsp;
					<span class="s_normal">Contact me</span>&nbsp;
					<span style="margin-left: 12px;"><input type="text" name="txtCN" class="InputBox6" value="<?php echo $e_CN; ?>" required /></span>
				</div>
            </div>
			
			<?php
			##################!!!!!!!!! DIDPLAY THIS ONLY IF THE USER IS STUDENT !!!!!!!!!!!!!#####################
			
			if($e_REL == 'Student')
			{
				$divStudDetails = "
				
				<div class='aboutInfos' style='background:#F1F1F1;'>
					<div class='left'>STUDENT INFORMATION</div>
				</div>
					
					
				<div class='aboutInfos'>
					<div class='left'><img src='images/icons/writing-4.png' width='16' height='16' />&nbsp;
						<span class='s_normal'>Subject</span>&nbsp;
						<span style='margin-left: 32px;'>
							<select name='cmbSUBJ'>
								<option value='LabDx'>LabDx</option>
								<option value='ClinDx'>ClinDx</option>
								<option value='PathoA'>PathoA</option>
								<option value='PathoB'>PathoB</option>
								<option value='HistoTech'>HistoTech</option>
								<option value='PT Patho'>PT Patho</option>
							</select>
							&nbsp;".$e_SUBJ."
						</span>
					</div>
				</div>
				
				<div class='aboutInfos'>
					<div class='left'><img src='images/icons/writing-4.png' width='16' height='16' />&nbsp;
						<span class='s_normal'>Section</span>&nbsp;
						<span style='margin-left: 32px;'>
							<input type='text' name='txtSECS' class='InputBox6' value='".$e_SECS."' required placeholder='Enter your section...' />
						</span>
					</div>
				</div>
				
				<div class='aboutInfos'>
					<div class='left'><img src='images/icons/writing-4.png' width='16' height='16' />&nbsp;
						<span class='s_normal'>Semester</span>&nbsp;
						<span style='margin-left: 22px;'>
							<select name='cmbSEMS'>
								<option value='1st Sem'>1st Sem</option>
								<option value='2nd Sem'>2nd Sem</option>
							</select>
							&nbsp;".$e_SEMS."
						</span>
					</div>
				</div>
				
				
				<div class='aboutInfos'>
					<div class='left'><img src='images/icons/writing-4.png' width='16' height='16' />&nbsp;
						<span class='s_normal'>School Year</span>&nbsp;
						<span style='margin-left: 5px;'>
							<select name='cmbSY'>
								<option value='2010-2011'>2010-2011</option>
								<option value='2011-2012'>2011-2012</option>
								<option value='2012-2013'>2012-2013</option>
								<option value='2013-2014'>2013-2014</option>
								<option value='2014-2015'>2014-2015</option>
								<option value='2015-2016'>2015-2016</option>
								<option value='2016-2017'>2016-2017</option>
								<option value='2017-2018'>2017-2018</option>
								<option value='2018-2019'>2018-2019</option>
								<option value='2019-2020'>2019-2020</option>
							</select>
							&nbsp;".$e_SY."
						</span>
					</div>
				</div>
				";
				
				echo $divStudDetails;
			}
			
			
			else
				####!!!!! DO NOT DISPLAY THIS PART !!!!!####
			/*
			
			*/
			##################!!!!!!!!! DIDPLAY THIS ONLY IF THE USER IS STUDENT !!!!!!!!!!!!!#####################
			?>
			
			<div>
            	<div class="left" style="margin: 15px 0 15px 15px;">
            	  	<input type="submit" name="cmdUpdate" value="Update Profile" />
            	</div>
            </div>
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
			if($e_PIC == '')
				//we set the defaul profile pick 
				echo ' <img src="images/icons/default_user.jpg" height="50" width="50" style="border: 1px solid #999;" /> ';
			
			//else if profile pick is NOT empty then
			else
				//we display the users profile pick
				echo ' <img src="'.$e_PIC.'" height="50" width="50" style="border: 1px solid #999;" /> ';
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