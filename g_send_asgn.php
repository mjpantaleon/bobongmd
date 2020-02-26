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


//default values
$Count 		= 0;
$Mesage		= "";


	//IF Submit button is clicked or detected then
	//POST VARIBLES
	$SUB	= trim($_POST['txtSubj']);	//subject
	$LINK	= trim($_POST['file1']);	//file attachment
	$REM	= trim($_POST['txtNote']);	//Note(Optional)
	$Valid	= true;
	
	
	if( isset( $_POST['cmdSumit']))
	{
		//check for empty fields
		
		
		//if valid then insert into assignments
		if($Valid == true)
		{
			//setting up the date
			$today	= date("F j, Y");	//string/Int format
			$hr_s 	= date("H:i:sa");	//12hrs format
			
			//check for errors during upload
			if($_FILES['file1']['error'] <= 0)
			{
				//File upload attributes
				$FILE	= $_FILES['file1']['name'];
				$Ftemp	= $_FILES['file1']['tmp_name'];
				$Path 	= "assignments/".$FILE;
				
				//if no errors found insert this assignment
				mysql_query(" INSERT INTO `assignment`
							SET 
							UID = '".$UID."' ,
							SUB	= '".$SUB."' ,
							LINK = '".$Path."' ,
							dateSent = '".$today."',
							timeSent = '".$hr_s."',
							REM = '".$REM."' ,
							A_ST = '1'
						   ")
				or die(mysql_error());
				move_uploaded_file($Ftemp, $Path);	//code structure for moving this file
				
				
				//log this event
				$time_in = date("H:i:sa");							
				$cur_date = date("Y-m-d");
				$cur_timestamp = $cur_date." ".$time_in;	//concatinate time + current date
				
				$query = "	INSERT INTO events SET E_TS = '$cur_timestamp', UID = '$UID', E_D = '[ ".$FN." ] Sends Assignment'	";
				mysql_query($query)
				or die(mysql_error());
				
				
				//unset post variables
				unset($_POST['txtSubj']);	//subject
				unset($_POST['file1']);		//file attachment
				unset($_POST['txtNote']);	//Note(Optional)
			
				//clear all fields
				$SUB	= "";	//subject
				$LINK	= "";	//file attachment
				$REM	= "";	//Note(Optional)
	
	
				$Message	= "
			
				<div style=' border: 1px solid #06C; box-shadow: inset 0 1px 5px #06C; padding: 5px 0;'>
					<span class='okb'><img src='images/icons/check.jpg' width='20' height='20' />&nbsp;Assignment has been submitted.</span>
				</div>
				
				";
				
				
			}
			//check for errors during upload
			
			else
				$Message = "<span class='error'>Oppss! Something went wrng during the upload.</span>";
			
			
		}
		//if valid then insert into assignments
		
	}
	//IF Submit button is clicked or detected then
?>

<?php include 'g_header.php'; ?>
<form action="#" method="post" enctype="multipart/form-data">


<td width="75%" valign="top" align="left">
	<!-- BREAD CRUMBS / SEARCH TOOL -->
	<div class="left" style="height:70px; background-color: #9C6;">
        <div class="left" style="border-bottom: 1px solid #CCC; height:68px; margin-right: 5px;">
        <span class="okw"><img src="images/icons/toolbar-icons/book.png" width="20" height="20" style="margin-top: 5px;" />&nbsp;SEND ASSIGNMENT</span>
            <div class="left" style="margin-left: 25px; margin-top: 10px;"><!--Search by:
                <span class="left" style="margin-left: 5px; margin-top: 10px;">
                  <input type="text" name="txtSearch" onKeyUp="showSomething(this.value)" class="SearchBox" placeholder="User Name" />
                </span>-->
                <span class="error">*</span> Please provide all the necessary fields needed. 
            </div>
        </div>
    </div>
    <!-- BREAD CRUMBS / SEARCH TOOL -->
    
    
    <!-- WILL DISPLAY THE CONTENT-->
    <div class="center" style="margin-top: 15px; margin-right: 5px; width: 97%;">
    
    	 <div class="left" style=" border-top-left-radius: 0.3em; border-top-right-radius: 0.3em; ">
             <div class="header_arrow">
                <span class="left">
                    <img src="images/icons/toolbar-icons/link.png" width="20" height="20" style="margin-top: 2px;" />&nbsp;
                    New Assigment</span><span class="arrow">
                </span>
             </div>
         </div>
         
         <div class="center" style="margin: 8px;">
			<!-- PROMPT MESSAGE HERE -->&nbsp;<?php echo $Message; ?>
         </div>
         
         <!-- LOAD THE MESSAGE BOX HERE -->
         <div id="showLightbox"></div>
         <!-- LOAD THE MESSAGE BOX HERE -->
         
          <!-- LOAD THE MESSAGE BOX HERE -->
         <div id="showSentByHere"></div>
         <!-- LOAD THE MESSAGE BOX HERE -->
         
        
   		 <!-- WILL DISPLAY THE DETAILS ABOUT THE USER -->
         <div id="txtHint" class="left" style=" margin-top: 15px; margin-bottom: 15px;">
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
         	<!-- YOU TABLE HERE -->
             <table width="100%" border="0" cellspacing="0" cellpadding="0">
             	<tr>
                	<td>
                    <div class="assignWrap">
                    	<div style=" width: 105px; margin: -15px 0 0 -1px; border:1px solid #FFF; background-color: #FFF;">Assignment Info</div>
                        
                    	<div class="left">
                       	  <span class="caption">Subject:</span>
                       	  <input type="text" class="InputBox4" name="txtSubj" placeholder="Enter Subject here..." value="<?php echo $SUB; ?>" required />
                          &nbsp;<span class="error">*</span>
                    	</div>
                        <div class="left">
                        	<span class="s_normal" style="margin: 5px 3px 3px 92px">Example: 'Assignment on Hygiene and Safety'</span>
                        </div>
                        
                        <div class="cleaner">&nbsp;</div>
                        
                        <div class="left">
                        	<span style="margin:0 5px; padding-right: 5px;">Attach File:</span>
                            <input type="file" class="file" name="file1" id="file1" required />&nbsp;<span class="error">*</span>
                        </div>
                        <div class="left">
                        	<span class="s_normal" style="margin: 5px 3px 3px 92px">MSword, Excel, Power point and PDF attachment format is accepted</span>
                        </div>
                        
                        
                        <div class="left">
                       	  <span style="margin:0 5px; padding-right: 45px;">Note:</span>
                       	  <input type="text" class="InputBox4" name="txtNote" placeholder="Enter note here..." value="<?php echo $REM; ?>" />
                    	</div>
                        <div class="left">
                        	<span class="s_normal" style="margin: 5px 3px 3px 95px">Leave a note(Optional)</span>
                        </div>
                    </div>
                    
                    <div class="left"><input type="submit" name="cmdSumit" value="Submit Assignment" /></div>
                    </td>
                </tr>
             </table>
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