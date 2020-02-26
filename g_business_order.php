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


	#|||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
	#GETTING THE ITEM DETAILS BY GETTING THE ITEM ID|||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
	$Item_id	= $_GET['item'];
	
	$query		= ' SELECT * FROM `business` WHERE `item_id` = "'.$Item_id.'" ';
	$result		= mysql_query($query);
	$row		= mysql_fetch_array($result);
	#INITIATE LOCAL VARIABLES
	$IN		= $row['IN'];			#Item Name
	$IMG	= $row['IMG'];			#Image
	$DESK	= $row['DESK'];			#Description
	$PRCE	= $row['PRCE'];			#Price
	$ST		= $row['ST'];			#Status
	
	#Configure Status Display into string
	if($ST == 1)
		$ST = "Available";
	else
		$ST = "Not Available";
	#GETTING THE ITEM DETAILS BY GETTING THE ITEM ID|||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
	#|||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||	
	
	
	
	
	
	#|||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
	#IF BUTTON ORDER HAS BEEN CLICKED OR DETECTED THEN||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
	if(isset($_POST['cmdSubmit']))
	{
		#POST FIELDS||||||||||||||||||||||||||
		$QNT	= $_POST['cmbQnt'];
		$TTL	= $PRCE * $QNT;
	
		#setting up the date
		$today	= date("F j, Y");	//string/Int format
		$hr_s 	= date("H:i:sa");	//12hrs format
	
		#IF QUANTITY IS NOT EMPTY THEN
		if($QNT != '')
		{
			#ADD ORDER IN THE ORDERS TABLE
			$query		= "	INSERT INTO `business_orders` SET
							`item_id`	= '".$Item_id."',
							`IN`		= '".$IN."',
							`UID`		= '".$UID."',
							`FN`		= '".$FN."',
							`PRCE`		= '".$PRCE."',
							`QNT`		= '".$QNT."',
							`SUBTTL`	= '".$TTL."',
							`dateSnt`	= '".$today."',
							`timeSnt`	= '".$hr_s ."',
							`O_ST`		= '1'
			";
			mysql_query($query)
			or die(mysql_error());
			#echo number_format($TTL, 2)." = (".number_format($PRCE, 2)." * ".$QNT.")";
			
			
			#LOG THIS EVENT
			$time_in = date("H:i:sa");							
			$cur_date = date("Y-m-d");
			$cur_timestamp = $cur_date." ".$time_in;	//concatinate time + current date
			
			$query = "	INSERT INTO events SET E_TS = '$cur_timestamp', UID = '$UID', E_D = '[ ".$FN." ] Ordered item: [ ".$Item_id." ]'	";
			mysql_query($query)
			or die(mysql_error());
			
		
			#PROMPT SUCCESS MESSAGE
			$Message	= "<div class='center' style='padding: 3px;'>
								<img src='images/icons/check.jpg' height='18' width='18'>&nbsp;<span class='okb'>Your order has been sent.</span>
							</div>";					
		}
		#IF QUANTITY IS NOT EMPTY THEN
		
	}
	#IF BUTTON ORDER HAS BEEN CLICKED OR DETECTED THEN||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
	#|||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
	
	
	
	#|||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
	#IF TXTCOMMENT HAS BEEN DETECTED THEN|||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
	if(isset($_POST['txtComment']))
	{
		#POST FIELD
		$CMNT	= trim($_POST['txtComment']);
		
		#setting up the date
		$today	= date("F j, Y");	//string/Int format
		$hr_s 	= date("H:i:sa");	//12hrs format
		
		#GET THE TOTAL NUMBER OF COMMENTS ON THIS ITEM
		$query	= '	SELECT `CMNTS` FROM `business` WHERE `item_id` = "'.$Item_id.'" ';
		$result	= mysql_query($query);
		$row	= mysql_fetch_array($result);
		#LOCAL VARIABLE
		$comments		= $row['CMNTS'];
		$increment		= 1;
		$TotalComnts	= $comments + $increment;
		
		
		#IF COMMENT IS NOT EMPTY THEN
		if($CMNT != '')
		{
			#ADD COMMENT TO THE COMMENTS TABLE
			$query		= "	INSERT INTO `business_comments` SET
							`item_id`	= '".$Item_id."',
							`UID`		= '".$UID."',
							`UT_ID`		= '".$UT_ID."',
							`FN`		= '".$FN."',
							`CMNT`		= '".$CMNT."',
							`dateSnt`	= '".$today."',
							`timeSnt`	= '".$hr_s."',
							`ST`		= '1'
			";
			mysql_query($query)
			or die(mysql_error());
			
			#UPDATE TOTAL NUMBER OF COMMENTS IN THIS ITEM
			mysql_query("	UPDATE `business` SET
							`CMNTS` = '".$TotalComnts."'
							WHERE `item_id` = '".$Item_id."'
			")
			or die(mysql_error());
			
			
			#LOG THIS EVENT
			$time_in = date("H:i:sa");							
			$cur_date = date("Y-m-d");
			$cur_timestamp = $cur_date." ".$time_in;	//concatinate time + current date
			
			$query = "	INSERT INTO events SET E_TS = '$cur_timestamp', UID = '$UID', E_D = '[ ".$FN." ] commented on item: [ ".$Item_id." ]'	";
			mysql_query($query)
			or die(mysql_error());
			
			
			#PROMPT SUCCESS MESSAGE
			$Message	= "<div class='center' style='padding: 3px;'>
								<img src='images/icons/check.jpg' height='18' width='18'>&nbsp;<span class='okb'>Your comment has been added.</span>
							</div>";			
		}
		#IF COMMENT IS NOT EMPTY THEN
	}
	#IF TXTCOMMENT HAS BEEN DETECTED THEN|||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
	#|||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
	
	
	
?>

<?php include 'g_header.php'; ?>
<form action="#" method="post" enctype="multipart/form-data">


<td width="75%" valign="top" align="left">
	<!-- BREAD CRUMBS / SEARCH TOOL -->
	<div class="left" style="height:70px; background-color: #9C6;">
        <div class="left" style="border-bottom: 1px solid #CCC; height:68px; margin-right: 5px;">
        <span class="okw"><img src="images/icons/toolbar-icons/cart2.png" width="20" height="20" style="margin-top: 5px;" />&nbsp;BUSINESS</span>
            <div class="left" style="margin-left: 25px; margin-top: 10px;">Search by:
                <span class="left" style="margin-left: 5px; margin-top: 10px;">
                  <input type="text" name="txtSearch" onKeyUp="showSomething(this.value)" class="SearchBox" placeholder="Item Name" />
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
                    <img src="images/icons/toolbar-icons/link.png" width="20" height="20" style="margin-top: 2px;" />&nbsp;Exclusive Items for you
                </span>
                <span class="arrow"></span>
             </div>
         </div>
         
         <div class="center" style="margin-top: 15px; margin-bottom: 15px;">
			<?php echo $Message; #Echo message here ?>
         </div>
        
        
   		 <!-- WILL DISPLAY THE DETAILS ABOUT THE USER -->
         <div id="txtHint" class="left" style=" margin-top: 15px; margin-bottom: 15px;" >
		  <?php
		 
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
		 
		 ?>
		<!-- YOU TABLE HERE -->
		<table border="0" cellspacing="0" cellpadding="0">
		
		<tr>
			<td>
			
				<div class='item_wrap' style=' border: 1px solid #CCC; box-shadow: 0 0 5px #CCC; background: #FFC;  margin: 15px 25px 15px; width: 500px;'>
					<div class='item_name' style=' padding: 15px 5px; color:#900; border-bottom: 1px dashed #CCC; margin: 0 3px 3px;'>
						<?php echo $IN; ?>
					</div>
					
					<div class='img_holder' style='border: 1px solid #CCC; width: 185px; height: 265px; padding: 2px; 
					margin: 0 5px 5px; background: #FFF;'>
					
						<img src='<?php echo $IMG; ?>' width='185px' height='265px' />
						<div class='details_holder' style='margin: -268px 0 0 210px; width: 280px;'>
						
							<div class'desc' style='padding: 15px 15px 15px 0; height: 110px;  margin: 0 5px 5px; border-bottom: 1px dashed #CCC;'>
								<span class='s_normalb'><?php echo $DESK; ?></span>
							</div>
							
							<div class='price' style='color:#03F; padding: 5px 5px 5px 0;'>
								<span class='s_normalb'>Selling Price:</span>&nbsp;<?php echo number_format($PRCE, 2); ?> php
							</div>
							
							<div class='price' style='color:#03F; padding: 5px 5px 5px 0;'>
								<span class="s_normalb">Status:</span>&nbsp;<span style="color:#0033FF"><?php echo $ST; ?></span>
							</div>
							
							<div class="Qnt" style=" padding: 5px 5px 5px 0;">
									<span class="s_normalb">Quantity:</span>
									<select name="cmbQnt">
										<option value="">--</option>
										<option value="1">1</option>
										<option value="2">2</option>
										<option value="3">3</option>
										<option value="4">4</option>
										<option value="5">5</option>
										<option value="6">6</option>
										<option value="7">7</option>
										<option value="8">8</option>
										<option value="9">9</option>
										<option value="10">10</option>
									</select>
							</div>
							
							<div class="submitOK" style=" padding: 5px 5px 5px 0;">
									<input class="Update_btn" type="submit" name="cmdSubmit" value="ORDER">
							</div>
							
						</div>
						
					</div>
					
				</div>
				
				<div class="left">
					<div class="blogCommBox">
						<h5 class="gray">Comment</h5>
						<input type="text" class="commentBox" name="txtComment" placeholder="Write a comment..." />
					</div>
				</div>
				
				<div class="blogCommBox">
						<h5 class="gray2">Recent Comments</h5>
						<?php
						#SELECT COMMENTS FROM COMMENTS TABLE
						$query	= "	SELECT * FROM `business_comments` WHERE `item_id` = '".$Item_id."' AND `ST` = '1' ORDER BY `bus_com_id` DESC";
						$result	= mysql_query($query)
						or die(mysql_error());
						while($row = mysql_fetch_array($result))
						{
							#INITIATE LOCAL VARIABLES
							$r_UID	 	= $row['UID'];
							$r_UT_ID 	= $row['UT_ID'];
							$r_FN	 	= $row['FN'];
							$r_CMNT	 	= $row['CMNT'];
							$r_dateSnt	= $row['dateSnt'];
							$r_timeSnt	= $row['timeSnt'];
							
							#WE WILL FILTER THE DISPLAY BY THE TYPE OF USER LEVEL
							
							if($r_UT_ID == 1)
							{
								$div ="
								<div style=' float: right; width: 420px; border:1px dashed #CCC; margin: 25px 15px 15px; background-color:#FFFFCC; 
								padding: 3px 10px 3px 3px; box-shadow: 0 1px 3px #999;'>
									<div>
										<div class=' left'>
											<h5>".$r_FN."</h5>
										</div>
									</div>
									
									<div style=' margin: 3px 5px'>
										<p><span class='s_normal'>".$r_CMNT."</span></p>
									</div>
									
									<div style=' margin: 0 0 0 85px;'>
										<div class='right'><span class='s_normal'>".substr($r_dateSnt, 0,24)." - ".substr($r_timeSnt, 0 , 10)."</span></div>
									</div>
								</div>
								";
							}
							
							else
							{
								$div ="
								<div style=' float: left; width: 420px; border:1px dashed #CCC; margin: 25px 15px 15px; background-color:#FFF; 
								padding: 3px 10px 3px 3px; box-shadow: 0 1px 3px #999;'>
									<div>
										<div class=' left'>
											<h5>".$r_FN."</h5>
										</div>
									</div>
									
									<div style=' margin: 3px 5px'>
										<p><span class='s_normal'>".$r_CMNT."</span></p>
									</div>
									
									<div style=' margin: 0 0 0 85px;'>
										<div class='right'><span class='s_normal'>".substr($r_dateSnt, 0,24)." - ".substr($r_timeSnt, 0 , 10)."</span></div>
									</div>
								</div>
								";
							}
							echo $div;
							#WE WILL FILTER THE DISPLAY BY THE TYPE OF USER LEVEL
						
						}
						#END WHILE
						?>
						
									 
						
				</div>		
			
			</td>
		</tr>
		
		</table>
		
		<!-- YOU TABLE HERE -->
         </div>
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
</td>
<!-- RIGHT NAV -->


</form>
<?php include 'footer.php'; ?>