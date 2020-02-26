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

	#ADDING A NEW ITEM SECTION||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
	if(isset($_POST['cmdAddNewFile']))
	{
		#CREATE LOCAL VARIABLES
		$IN		= trim($_POST['txtTitle']);		#Item Name
		$DESK	= trim($_POST['txtDesc']);		#Description
		$PRCE	= trim($_POST['txtPrice']);		#Price
		$Valid	= true;							#Validity
		
		
		#IF ALL FIELDS ARE CORRECT OR NOT EMPTY THEN
		if($Valid == true)
		{
			#$Message	= "<span class='okb'>Add New file button has been clicked.</span>";
			$cur_Y 	= date('Y');		#get the current year
			#id format
			$limit = 4;		//set the limit into 4
			$sql = "SELECT * FROM `business` ORDER BY `item_id` DESC LIMIT 0,1";	//query serves as our counter
			$result = mysql_query($sql);										//then its passed down to the varibale $result
			$row = mysql_fetch_array($result);									//so that we could access row 'UID' from the table
			
				$last_id = $row['item_id'];
				$last_dgt = substr($last_id, -4);								
				
				$id 			= str_pad((int) $last_dgt,$limit,"0",STR_PAD_LEFT);
				$id				= $id + 1;										//increment by 1
				$id 			= str_pad((int) $id,$limit,"0",STR_PAD_LEFT);
				$ITEMID			= "ITM".$cur_Y."-".$id;						//LECT2013-0001
			#id format
			
			#CHECK FIRST FOR DUPLICATION
			if(mysql_num_rows(mysql_query(" SELECT `IN` FROM `business` WHERE `IN` = '".$IN."' ")) > 0)
			{
				#SHOW ERROR MESSAGE
				$Message = " <img src='images/icons/x.png' width='20' height='20'>&nbsp;
				<span class='error'>This Item already exist! Please add another Item. ";
			}
			#CHECK FIRST FOR DUPLICATION
			
			#IF NO DUPLICATION FOUND THEN
			else
			{
				#CHECK IF THE FILE UPLOAD HAS NO ERROR
				if($_FILES['file1']['error'] <= 0)
				{
					//File upload attributes
					$FILE	= $_FILES['file1']['name'];
					$Ftemp	= $_FILES['file1']['tmp_name'];
					$Path 	= "bussiness/".$FILE;					//path to upload / the name of the file
					
					#INSERTING IN THE DATABASE
					mysql_query(" 	INSERT INTO `business` SET
									`item_id`	= '".$ITEMID."',
									`IN`		= '".$IN."',
									`DESK`		= '".$DESK."',
									`IMG`		= '".$Path."',
									`PRCE`		= '".$PRCE."',
									`ST`		= '1'
								")
					or die(mysql_error());
					move_uploaded_file($Ftemp,"bussiness/".$FILE);	//code structure for moving this file
					#INSERTING IN THE DATABASE
					
					#log this event
					$time_in = date("H:i:sa");							
					$cur_date = date("Y-m-d");
					$cur_timestamp = $cur_date." ".$time_in;	//concatinate time + current date
					
					$query = "	INSERT INTO events SET E_TS = '$cur_timestamp', UID = '$UID', E_D = '[ ".$FN." ] Added new Item: [ ".$IN." ]'	";
					mysql_query($query)
					or die(mysql_error());
					
					#UNSET FIELDS
					unset($_POST['txtTitle']);		#Item Name
					unset($_POST['txtDesc']);		#Description
					unset($_POST['txtPrice']);		#Price
					
					
					#PROMPT SUCCESS MESSAGE
					$Message	= "<img src='images/icons/checkgreen.png' width='20' height='20'>&nbsp;
						<span class='okb'>Item [".$IN."] has been added successfully.</span>";
				}
				
				else
					$Message = "<img src='images/icons/x.png' width='20' height='20'>&nbsp;
				<span class='error'>Oppps!. Having problems regarding the file upload.";
				#CHECK IF THE FILE UPLOAD HAS NO ERROR	
			}
			#IF NO DUPLICATION FOUND THEN
			
		}
		#IF ALL FIELDS ARE CORRECT OR NOT EMPTY THEN
	}
	#ADDING A NEW ITEM SECTION||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||

	
?>

<?php include 'm_header.php'; ?>
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
         	<!-- YOU TABLE HERE -->
            <table border="0" cellspacing="0" cellpadding="0">
            <?php
			$per_page	= 4;	#SET HOW MANY RECORDS WILL BE SHOW IN THE TABLE
			
			$Count		= 0; 	#SET DEFAULT AS ZERO
			
			#WE WILL QUERY TO GET THE NUMBER OF RECORDS IN THE TABLE
			$query		= mysql_query(" SELECT COUNT(`item_id`) FROM `business` WHERE `ST` = '1' ")
			or die(mysql_error());
			#WILL SET HOW MANY PAGES ARE THE BASED ON THE TOTAL RECORDS DIVIDED BY HOW MANY WILL BE SHOWN IN THE TABLE (6)
			$pages		= ceil(mysql_result($query, 0) / $per_page);
			#SETUP FOR THE PAGINATION DISPLAY
			$page		= ( isset( $_GET['page']))  ? (int)$_GET['page'] : 1;
			$start		= ($page - 1) * $per_page;
			
			#FETCHING THE RECORDS IN THE TABLE
			$query		= " SELECT * FROM `business` WHERE `ST` = '1'
							ORDER BY `item_id` DESC LIMIT ".$start.",".$per_page."
						";
			$result		= mysql_query($query)
			or die(mysql_error());
			while($row	= mysql_fetch_array($result))
			{
				#SET LOCAL VARIABLES
				$Count++;
				$id		= $row['item_id'];		#Item ID
				$IN		= $row['IN'];			#Item Name
				$IMG	= $row['IMG'];			#Image
				$DESK	= $row['DESK'];			#Description
				$PRCE	= $row['PRCE'];			#Price
				$ST		= $row['ST'];			#Status
				
				#DISPLAYING THE RECORD
				$table = "
				<tr>
					<td>
					
						<div class='item_wrap' style=' border: 1px solid #CCC; box-shadow: 0 0 5px #CCC; background: #FFC;  margin: 15px 25px 15px; width: 500px;'>
							<div class='item_name' style=' padding: 15px 5px; color:#900; border-bottom: 1px dashed #CCC; margin: 0 3px 3px;'>
								".$IN."
							</div>
							<div class='img_holder' style='border: 1px solid #CCC; width: 185px; height: 265px; padding: 2px; 
							margin: 0 5px 5px; background: #FFF;'>
								<a href='m_business_edit.php?itemID=".$id."' title='Edit this item'><img src='".$IMG."' width='185px' height='265px' /></a>
								<div class='details_holder' style='margin: -268px 0 0 210px; width: 280px;'>
								
									<div class'desc' style='padding: 15px 15px 15px 0;  margin: 0 5px 5px; border-bottom: 1px dashed #CCC;'>
										<span class='s_normalb'>".$DESK."</span>
									</div>
									
									<div class='price' style='color:#03F; padding: 15px 15px 15px 0;'>
										<span class='s_normalb'>Selling Price:</span> ".number_format($PRCE, 2)." php
									</div>
									
									<div class='flagMe'>
										<a href='m_business_comments.php?item=".$id."' title='Click this if you want to view comments on this Item'>
											View Comments >>>
										</a>
									</div>
								</div>
							</div>
						</div>
					 
					</td>
            	</tr>
				
				";
				
				echo $table;
			}
			#end while
			#FETCHING THE RECORDS IN THE TABLE
			
			?>
            
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


</form>
<?php include 'footer.php'; ?>