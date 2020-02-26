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


?>

<?php include 'g_header.php'; ?>
<form action="#" method="post">

<td width="75%" valign="top" align="left">
<!-- BREAD CRUMBS / SEARCH TOOL -->
	<div class="left" style="height:70px; background-color: #9C6;">
        <div class="left" style="border-bottom: 1px solid #CCC; height:68px; margin-right: 5px;">
        <span class="okw"><img src="images/icons/Users.png" width="20" height="20" style="margin-top: 5px;" />&nbsp;USERS</span>
            <div class="left" style="margin-left: 25px; margin-top: 10px;">Search by:
                <span class="left" style="margin-left: 5px; margin-top: 10px;">
                	<input class="SearchBox" type="text" name="txtSearch" onclick="" placeholder="Full Name" />
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
                    <img src="images/icons/toolbar-icons/comment.png" width="20" height="20" style="margin-top: 2px;" />&nbsp;Registered Users</span>
                    <span class="arrow">
                </span>
             </div>
         </div>
         
         <div class="center" style="margin-top: 15px; margin-bottom: 15px;">
                <!-- MESSAGE HERE -->		
         </div>
         
          <!-- LOAD THE MESSAGE BOX HERE -->
         <div id="showSentByHere"></div>
         <!-- LOAD THE MESSAGE BOX HERE -->
         
        
   		 <!-- WILL DISPLAY THE DETAILS ABOUT THE USER -->
         <div id="txtHint" class="left" style=" margin-top: 15px; margin-bottom: 35px; margin-left: 5px; margin-right:15px;">
          	<table width="100%" border="0" cellspacing="0" cellpadding="0">
            <?php
			
			$per_page	= 10;
			
			$Limit	= 2;	//number of columns or records to display per row
			$Count	= 0;	//zero default value
			
			$query	= mysql_query(" SELECT COUNT(`UID`) FROM `user_details` WHERE ST = '1' AND UID != '".$UID."' ")		//this only serves as a counter
			or die(mysql_error());
			$pages	= ceil(mysql_result($query, 0) / $per_page);				//use ciel to get the round of the higher value
					
			$page	= ( isset( $_GET['page']))  ? (int)$_GET['page'] : 1;
			$start	= ($page - 1) * $per_page;
			
            //DISPLAY ALL THE USERS IN THE SYSTEM WHOS STATUS IS ACTIVE AND NOT THE USER CURRENTLY LOGGED IN
			/* DO NOT FORGET THE START.", ".per_page  IMPORTANT */
			$query	= " SELECT *
						FROM `user_details`
						WHERE ST = '1' AND UID != '".$UID."'
						ORDER BY UID DESC LIMIT ".$start.", ".$per_page."
					  ";
			$result	= mysql_query($query)
			or die(mysql_error());
			while($row = mysql_fetch_array($result))
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
				
				//change format of profile picture of user
				if($e_PIC != '')
					$e_PIC = $e_PIC;
				else
					$e_PIC = "images/icons/default_user.jpg";
		
				
				//if COunt(0) is less then limit(2)
				
				if($Count < $Limit)
				{
					//if count is equal to 0 then
					if($Count == 0)
					{
						//we echo the opening row
						$divUsers="
						<tr>
						";
					}
					
					//then we echo the column inside the row
					$divUsers.="
					<td>
						<div class='userRow' style=' margin: 25px 15px; box-shadow: 0 1px 3px #FFF; padding: 5px;'>
							<div class='colWrap_left'>
								<div class='picWrap'>
									<a href='g_user_details.php?user=".$e_UN."' title='View Profile' alt='View Profile'>
										<img src='".$e_PIC."' width='95px' height='95px' />
									</a>
								</div>
								
								<div class='UserFNwrap'>
									<div class='userFN'>
										<a href='g_user_details.php?user=".$e_UN."' title='View Profile' alt='View Profile'>
											<h5>".$e_FN."</h5>
										</a>
									</div>
								</div>
								
								<div class='msgIconWrap'>
									<span class='s_normal'>".$e_REL."</span>
								</div>
							</div>
						</div>
					</td>
					";
				}
				//if COunt(0) is less then limit(2)
				
				
				//else
				else
				{
					$Count = 0;	//set the value of count into 0
					
					//close the 1st row then open another opening row
					$divUsers.="
					</tr>
					
					<tr>
						<td>
							<div class='userRow' style=' margin: 20px 5px; box-shadow: 0 1px 3px #FFF; padding: 5px;'>
								<div class='colWrap_left'>
									<div class='picWrap'>
										<a href='g_user_details.php?user=".$e_UN."' title='View Profile' alt='View Profile'>
											<img src='".$e_PIC."' width='95px' height='95px' />
										</a>
									</div>
									
									<div class='UserFNwrap'>
										<div class='userFN'>
											<a href='g_user_details.php?user=".$e_UN."' title='View Profile' alt='View Profile'>
												<h5>".$e_FN."</h5>
											</a>
										</div>
									</div>
									
									<div class='msgIconWrap'>
										<span class='s_normal'>".$e_REL."</span>
									</div>
								</div>
							</div>
						</td>
						
					";
				}
				//else
				$Count++;	//increment value of count
				
				
			}
			//end while
			/*
			<img src='images/icons/toolbar-icons/mail.png' width='20' height='20' />
			<input type='button' class='button3' value='Send Message' title='Send Message' />
			*/
			
					//then we close the row
				   	$divUsers.="
					</tr>
					";
			
			echo $divUsers;	//echo result
            ?>
            
		</table>
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

    <!-- Display Updates thats happening in the system -->
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
    
    <!-- DISPLAY ALL RECENTLY CONTACTED PERSON -->
    
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
    <!-- DIV FOR DAILY UPDATES -->
    
    <!-- Display Updates thats happening in the system -->
    
    <!-- pagination -->
    <div class="center" style="margin-top: 15px; margin-bottom: 15px; margin-left: 4px; margin-right: 4px; "><?php echo $paginationDisplay; ?></div>
    <!-- pagination -->
</td>

</form>
<?php include 'footer.php'; ?>