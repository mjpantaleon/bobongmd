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
	$e_FN	= trim($_POST['txtFN']);		//Full name
	$e_EM	= trim($_POST['txtEM']);		//Email
	$e_AD	= trim($_POST['txtAD']);		//Address
	$e_CN	= trim($_POST['txtCN']);		//Contact number
	$Valid	= true;					//boolean condition
	
	//if update button has been clicked then
	if( isset( $_POST['cmdUpdate']))
	{
		
		if($Valid == true)
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
			
			
			//then prompt message
			$Message	= "
			
			<div style=' border: 1px solid #06C; box-shadow: inset 0 1px 5px #06C; padding: 5px 0; margin: 15px 25px;'>
				<span class='okb'><img src='images/icons/check.jpg' width='20' height='20' />&nbsp;Profile has been successfully Updated.</span>
			</div>
			
			";
		}
		
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

<?php include 'm_header.php'; ?>
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
         
         <!-- LOAD MESSAGES POPUP -->
         <div id="showSentByHere"></div>
         <!-- LOAD MESSAGES POPUP -->
         
         
        
   		 <!-- WILL DISPLAY THE DETAILS ABOUT THE USER -->
         <div id="txtHint" class="left" style=" margin-top: 15px; margin-bottom: 15px;">
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
			$e_REL	= $row['REL'];	//Realtion
			$e_PIC	= $row['PIC'];	//Picture
			
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
                        <img style="cursor: pointer;" onclick="showProfBox()" src="<?php echo $e_PIC; ?>" width="140" 
                        height="140" alt="Prof1" title="Change profile picture" />
                    </div>
                </div>
            </div>
            
            <div class="aboutWrapper">
            	<div class="left">ABOUT</div>
            </div>
            
            <div class="aboutInfos">
            	<div class="left"><img src="images/icons/toolbar-icons/mail-diable.png" width="16" height="16" />&nbsp;
				<span class="s_normal">Email address</span>&nbsp;<input type="email" name="txtEM" class="InputBox6" value="<?php echo $e_EM; ?>" required /></div>
            </div>
            
            <div class="aboutInfos">
            	<div class="left"><img src="images/icons/toolbar-icons/home.png" width="16" height="16" />&nbsp;
				<span class="s_normal">Lives at</span>&nbsp;<input type="text" name="txtAD" class="InputBox6" value="<?php echo $e_AD; ?>" required /></div>
            </div>
            
            <div class="aboutInfos">
            	<div class="left"><img src="images/icons/toolbar-icons/help.png" width="16" height="16" />&nbsp;
				<span class="s_normal">Doc bobong's</span>&nbsp;<?php echo $e_REL; ?></div>
            </div>
            
             <div class="aboutInfos">
            	<div class="left"><img src="images/icons/toolbar-icons/link.png" width="16" height="16" />&nbsp;
				<span class="s_normal">Contact me</span>&nbsp;<input type="text" name="txtCN" class="InputBox6" value="<?php echo $e_CN; ?>" required /></div>
            </div>
            
            <div class="profWrapper2">
            	<div class="right" style="margin: -75px 5px 15px;">
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
	//declaire variables
	$r_UN	= $row['UN'];	//User name
	$r_FN	= $row['FN'];	//Full name
	$r_EM	= $row['EM'];	//Email
	$r_CN	= $row['CN'];	//Contact Nummber
	$r_AD	= $row['AD'];	//Address
	$r_REL	= $row['REL'];	//Realtion
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
    <!-- Display Updates thats happening in the system -->
</td>
<!-- RIGHT NAV -->


</form>
<?php include 'footer.php'; ?>