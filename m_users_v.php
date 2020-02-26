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


//GET VARIABLES
$getUID		= $_GET['uid'];		//user id of the sender
$Msg		= $_POST['txtMsg']; //whatever id the content og the text area message


//if message is detected then
if( isset( $_POST['txtMsg']))
{
	//check if the message is NOT empty then
	//if message is NOT empty then
	if($Msg != '')
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
		$query		= mysql_query(" INSERT INTO msgs SET id = '$MSGID', MSG = '$Msg', 
								  	msgFrom = '$UID', msgTo = '$getUID', dateSent = '$today', timeSent = '$hr_s ', MST = '1' ")
		or die(mysql_error());
		
		/*$query		= mysql_query(" INSERT INTO r_msgs SET id = '$MSGID', sentBy = '$UID', sentTo = '$getUID'
								  	, dateSent = '$today', timeSent = '$hr_s ', RplyST = '0' ")
		or die(mysql_error());*/
		
		//LOG THIS EVENT
		$time_in = date("H:i:sa");		//12hrs format						
		$cur_date = date("Y-m-d");		//Y m(int) d(int)
		$cur_timestamp = $cur_date." ".$time_in;
		
		$query = "	INSERT INTO events SET E_TS = '$cur_timestamp', UID = '$UID', E_D = 'Send message to ID:[ ".$getUID." ]'	";
		mysql_query($query)
		or die(mysql_error());
		
		//prompt message
		$Message 	= "<span class='okb'>
		<img src='images/icons/email-validated.png' height='20' width='20' />&nbsp;Message has been sent.</span>";
		
		//unset post fields
		unset( $_POST['txtMsg']);
	}
	//else if message is empty then	
	else
		//prompt user that the message is empty
		$Message = "<span class='error'>
		<img src='images/icons/email-alert.png' height='20' width='20' />&nbsp;Message field is EMPTY.</span>";
}


?>

<?php include 'm_header.php'; ?>

<!-- \\\\\\\\\\\\\\\\\\\\CONTENT PART//////////////////////// -->
<td width="75%" valign="top" align="left">
	<div class="left" style="height:70px; background-color: #9C6;">
        <div class="left" style="border-bottom: 1px solid #CCC; height:68px; margin-right: 5px;">
            <span class="okw">
            <img src="images/icons/images.png" width="20" height="20" style="margin-top: 5px;" />&nbsp;USER DETAILS</span>
            <div class="left" style="margin-left: 25px; margin-top: 10px;">&nbsp; </div>
        </div>
    </div>
    <!--
	<div class="left" style="margin-top: 15px; margin-left: 15px; margin-right: 15px; height:55px; border-bottom: 1px solid #CCC;">
    <span class="okg2">ADMIN HOME PAGE</span>
        <div class="left" style="margin-left: 25px; margin-top: 10px;">&nbsp; </div>
    </div>
    -->
    <?php

	//SELECT DETAILS OF USER WHOS ID IS EQUAL TO $getUID
	$query	= " SELECT * FROM user_details WHERE UID = '$getUID' ";
	$result	= mysql_query($query);
	$row	= mysql_fetch_array($result);
	
	$e_UID	= $row['UID'];		//user id
	$e_UN 	= $row['UN'];		//user name
	$e_FN	= $row['FN'];		//Full name
	$e_EM	= $row['EM'];		//Email
	$e_CN	= $row['CN'];		//Contat #
	$e_AD	= $row['AD'];		//Address
	$e_REL	= $row['REL'];		//Relation
	$e_ST	= $row['ST'];		//Status
	$e_PIC	= $row['PIC'];		//Picture
	
	//print_r($getUID);
	?>
    
    
    <!-- CONTENT -->
     <!-- WILL DISPLAY THE CONTENT-->
        <div class="center" style="margin-top: 15px; margin-right: 5px; width: 97%;">
             <!-- BANNER -->	
             <div class="left" style=" border-top-left-radius: 0.3em; border-top-right-radius: 0.3em;">
                 <div class="header_arrow_uv">
                    <span class="left">
                        <a href="m_users.php"><img src="images/icons/prev.png" width="20" height="20" style="margin-top: 2px;" title="go back" /></a>&nbsp;
						<?php echo $e_FN; ?></span>
                        <span class="arrow">
                    </span>
                 </div>
             </div>
             <!-- BANNER -->
             
              <?php 
				$Action		= $_GET['action'];		//get value of message
				$getUID		= $_GET['uid'];			// get user id
				
				
				$query		= " SELECT UN FROM user_details WHERE UID = '$getUID' ";
				$result		= mysql_query($query);
				$row		= mysql_fetch_array($result);
				$UN			= $row['UN'];			//User Name
				
					
				if($Action == "Accept")
					echo " 
					<div class='center' style='margin-top: 25px;'>
						<span class='okb'>User [ </span>".$UN." <span class='okb'>] with ID: [ </span>".$getUID." <span class='okb'>] is now Active.</span> 
					</div>
					";
					
				elseif($Action == "Delete")
					echo " 
					<div class='center'>
						<span class='error'>User [ ".$UN." ] with ID: [ ".$getUID." ] has been deleted.</span> 
					</div>	
					";
					
				else
					echo "";
			 
			 ?>
				 
             
             <div style="margin: 15px;">
             <table width="100%" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td width="25%" valign='top' align='left' style='border-bottom: 1px solid #CCC;'>
                    <div class='left' style='margin: 3px; margin-bottom: 15px; margin-top: 15px;'>
                        <img src='<?php echo $e_PIC; ?>' width='155' height='155' style='border: 1px solid #999;' />
                    </div>
                </td>
                <td width="15%" valign='top' align='left' style='border-bottom: 1px solid #CCC;'>
                    <div class='left' style=' margin-top: 15px;'><span class='italic3'>Full Name:</span></div>
                    <div class='left' style=' margin-top: 5px;'><span class='italic3'>User Name:</span></div>
                    <div class='left' style=' margin-top: 5px;'><span class='italic3'>Email:</span></div>
                    <div class='left' style=' margin-top: 5px;'><span class='italic3'>Contact #:</span></div>
                    <div class='left' style=' margin-top: 5px; height: 25px;'><span class='italic3'>Address:</span></div>
                    <div class='left' style=' margin-top: 5px;'><span class='italic3'>Relation:</span></div>
                    
                </td>
                <td width="25%" valign='top' align='left' style='border-bottom: 1px solid #CCC;'>
                <?php
				if($e_ST == 1)
				{
					echo"
					
					<div class='left' style=' margin-top: 15px;'><span class='s_normalb'>".$e_FN."</span></div>
                    <div class='left' style=' margin-top: 5px;'><span class='s_normalb'>".$e_UN."</span></div>
                    <div class='left' style=' margin-top: 5px;'><span class='s_normalb'>".$e_EM."</span></div>
                    <div class='left' style=' margin-top: 5px;'><span class='s_normalb'>".$e_CN."</span></div>
                    <div class='left' style=' margin-top: 5px; width: 150px; height: 25px;'>
                    <span class='s_normalb'>".$e_AD."</span></div>
                    <div class='left' style=' margin-top: 5px;'><span class='s_normalb'>".$e_REL."</span></div>
					
					";
				}
				
				else
				{
					echo "
					
					<div class='left' style=' margin-top: 15px;'><span class='s_normal'>".$e_FN."</span></div>
                    <div class='left' style=' margin-top: 5px;'><span class='s_normal'>".$e_UN."</span></div>
                    <div class='left' style=' margin-top: 5px;'><span class='s_normal'>".$e_EM."</span></div>
                    <div class='left' style=' margin-top: 5px;'><span class='s_normal'>".$e_CN."</span></div>
                    <div class='left' style=' margin-top: 5px; width: 150px; height: 25px;'>
                    <span class='s_normal'>".$e_AD."</span></div>
                    <div class='left' style=' margin-top: 5px;'><span class='s_normal'>".$e_REL."</span></div>
					
					";
				}
				?>
                   
                   
                </td>
                <td width="35%" valign='top' align='left' style='border-bottom: 1px solid #CCC;'>
                   <div class="arrow_box" style=' margin: 5px;'>
                   		<span class="empha" style="text-align:center;">What do you want to do with this user?</span>
                   </div>
                   
                   <?php
				   if($e_ST ==1)
				   {
					   echo "
					   <div class='center' style='margin-top:25px;'>
                        <img src='images/icons/x.png' width='30' height='30' title='Delete' onClick='deleteUser()'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        <img src='images/icons/emailwrite.jpg' width='30' height='30' title='Message' onclick='showMessageBox()'>
                   	   </div>
					   ";
				   }
				   else
				   {
					   echo "
					    <div class='center' style='margin-top:25px;'>
                        <img src='images/icons/check.jpg' width='30' height='30' title='Accept' onClick='acceptUser()'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        <img src='images/icons/x.png' width='30' height='30' title='Delete' onClick='deleteUser()'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                   	   </div>
					   ";
				   }
				   ?>
                   
                   
                   
                   <!-- LOAD THE MESSAGE BOX HERE -->
                   <div id="showLightbox" style="margin: 15px;"></div>
                   <!-- LOAD THE MESSAGE BOX HERE -->
                </td>
              </tr>
            
            </table>

             
             </div>
        </div>
    
    <!--<div class="center" style="margin-top: 15px;"><?php //echo $paginationDisplay; ?></div>-->
    
    <div class="center" style="margin-top: 15px; margin-bottom: 15px;"><?php echo $Message; ?></div>
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
    
    <!-- Display Updates thats happening in the system -->
    <div class="left" style=" margin-top: 1px; margin-left: 2px; margin-right: 2px; border-top: 1px solid #9C6;">
    	<div style="margin-top: 5px; color:#9C3;"></div>
    </div> 
    
     <div style="margin-top: 15px; margin-left: 5px; margin-right: 5px;">
        	<div class="left" style=" height: 25px; border: 1px solid #CCC; border-top-left-radius: 0.3em; border-top-right-radius: 0.3em; 
            background-color:#b6e56d">
            	<img src="images/icons/toolbar-icons/settings.png" width="16" height="16" />&nbsp;User Utilities
            </div>
        	<div class="left" style="padding-top: 5px; padding-bottom: 5px; 
            border-left:1px solid #CCC;  border-right:1px solid #CCC; border-bottom: 1px solid #CCC;">
            	<img src="images/icons/images (6).jpg" width="20" height="20" />
            	<a href="#" class="button">User Rights</a>
            </div>
            <div class="left" style="padding-top: 5px; padding-bottom: 5px; 
            border-left:1px solid #CCC;  border-right:1px solid #CCC; border-bottom: 1px solid #CCC;">
            	<img src="images/icons/images (6).jpg" width="20" height="20" />
            	<a href="#" class="button">Add Admin</a>
            </div>
     </div>
    <!-- Display Updates thats happening in the system -->
</td>
<!-- \\\\\\\\\\\\\\\\\\\\RIGHT NAV//////////////////////// -->

<?php include 'footer.php'; ?>