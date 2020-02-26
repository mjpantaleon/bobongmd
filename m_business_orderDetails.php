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
	#GETTING THE ORDER DETAILS BY GETTING THE ORDER ID|||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
	$Order	= $_GET['order'];
	
	#MUST CHANGE ITS STATUS SO THAT IT WILL FLAGGED AS VIEWED
	$query	= " UPDATE `business_orders` SET `O_ST` = '0' WHERE `bus_order_id` = '".$Order."' ";	
	mysql_query($query)
	or die(mysql_error());
	
	#GET ALL THE DETAILS ABOUT THIS ORDER
	$query	= " SELECT `business_orders`.*, `business`.IMG,DESK 
				FROM `business_orders`
				LEFT JOIN `business`
				ON `business_orders`.`item_id` = `business`.`item_id`
				WHERE `bus_order_id` = ".$Order."
	";
	$result	= mysql_query($query);
	$row	= mysql_fetch_array($result);
	#INITIATE LOCAL VARIABLES
	$r_IMG		= $row['IMG'];
	$r_DESK		= $row['DESK'];
	#orders table
	$r_ITM		= $row['item_id'];
	$r_IN		= $row['IN'];
	$r_UID	 	= $row['UID'];
	$r_FN2	 	= $row['FN'];
	$r_PRCE		= $row['PRCE'];
	$r_QNT		= $row['QNT'];
	$r_TTL		= $row['SUBTTL'];
	$r_dateSnt	= $row['dateSnt'];
	$r_timeSnt	= $row['timeSnt'];
	
	
	#GETTING THE ORDER DETAILS BY GETTING THE ORDER ID|||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
	#|||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
?>

<?php include 'm_header.php'; ?>



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
						<?php echo $r_FN2; ?>
					</div>
					
					<div class="order_details_holder" style=" height: 155px; border: 1px dashed #FFF; padding: 15px; margin: 20px 15px;">
						<div class="image_holder" style=" background: #FFF; border: 1px solid #CCC; width: 150px; height: 175px;">
							<img src="<?php echo $r_IMG; ?>" width="150px" height="175px" />
						</div>
						
						<div class="item_details" style=" margin: -175px 0 0 165px;">
							<div class="left" style="color:#900;">	
								<?php echo $r_IN; ?>
							</div>
							<div class="left" style=" padding: 5px; margin: 10px 0; border-top: 1px dashed #CCC; ">
								Ordered: <span class="okb">(<?php echo $r_QNT; ?>) x</span>
							</div>
							<div class="left" style=" padding: 5px; margin: 10px 0; border-top: 1px dashed #CCC; ">
								Unit Price: <span class="okb"><?php echo number_format($r_PRCE, 2); ?> php</span>
							</div>
							<div class="left" style=" padding: 5px; margin: 10px 0; border-top: 1px dashed #CCC; ">
								Date sent: <?php echo $r_dateSnt; ?>
							</div>
							<div class="left" style=" padding: 5px; margin: 10px 0; border-top: 1px dashed #CCC; ">
								Time sent: <?php echo $r_timeSnt; ?>
							</div>
						</div>
					</div>
					
					
					<div class='item_name' style=' padding: 15px 5px; color:#900; border-top: 1px dashed #CCC; margin: 0 3px 3px;'>
						SUB TOTAL:&nbsp; <span class="okb"><?php echo number_format($r_TTL, 2); ?> php</span>
					</div>
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
        
        <!-- DIV FOR BUSINESS MANAGEMENT -->
        <div style="margin-top: 15px; margin-left: 5px; margin-right: 5px; margin-bottom: 15px;">
            <div class="left" style=" height: 25px; border: 1px solid #CCC; border-top-left-radius: 0.3em; border-top-right-radius: 0.3em; 
            background-color:#b6e56d">
                <img src="images/icons/toolbar-icons/cart2.png"  width="20" height="20" />&nbsp;Manage Business           
            </div>
            
            <div class='left' style='padding-top: 5px; padding-bottom: 5px; 
            border-left:1px solid #CCC;  border-right:1px solid #CCC; border-bottom: 1px solid #CCC;'>
                <div class='left'>
                 <img src="images/icons/toolbar-icons/link.png" width='20' height='20' />&nbsp;
                 <input type='button' class='button' onclick='showBusiness()' value='Add New' title="Add New File" />
                </div>
            </div>
			
			
			<?php
			#GET THE NUMBER OF ORDERS IN THE ORDER TABLE
			$query	= "	SELECT COUNT(bus_order_id) AS orders FROM `business_orders` WHERE `O_ST` = '1' ";
			$result	= mysql_query($query);
			$row	= mysql_fetch_array($result);
			$orders	= $row['orders'];
			
			#IF THERE IS ORDER THEN
			if($orders)
				echo"
				<div class='left' style='padding-top: 5px; padding-bottom: 5px; 
				border-left:1px solid #CCC;  border-right:1px solid #CCC; border-bottom: 1px solid #CCC;'>
					<div class='left'>
					 <img src='images/icons/toolbar-icons/cart2.png' width='20' height='20' />&nbsp;
					 <input type='button' class='button' onclick='showOrders()' value='New Order/s' title='Add New File' />
					 (<span class='error' style='font-size:14px'>".$orders."</span>)
					</div>
				</div>
				";
			
			else
				echo"
				<div class='left' style='padding-top: 5px; padding-bottom: 5px; 
				border-left:1px solid #CCC;  border-right:1px solid #CCC; border-bottom: 1px solid #CCC;'>
					<div class='left'>
					 <img src='images/icons/toolbar-icons/cart2.png' width='20' height='20' />&nbsp;
					 <span class='s_normal'/>New Order/s</span>
					</div>
				</div>
				
				";
			?>
			
        
        </div>
        <!-- DIV FOR BUSINESS MANAGEMENT -->

	</div> 
</td>
<!-- RIGHT NAV -->



<?php include 'footer.php'; ?>