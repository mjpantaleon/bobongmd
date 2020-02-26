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


	################################################################################################################################
	#IF USER ADDED NEW DOWNLOADABLES||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
	
	
	#$fileType	= array('pdf', 'ppt', 'pptx', 'xls', 'xlxs', 'doc', 'docx');
			
	#if(in_array($_FILES['fileUp1']['name'], $fileType))
	#{
		#PROMTP SUCCESS MESSAGE
		#$Message	= "<span class='okb'><img src='images/icons/check.jpg' width='20' height='20'>&nbsp;
		#Your file has been successfully uploaded.</span>";
	#}
	
	#else
		#ERROR MESSAGE
		#$Message = " <img src='images/icons/x.png' width='20' height='20'>&nbsp;<span class='error'>
		#ERROR! Format not supported.";
		
	
	#IF UPLOAD BUTTON HAS BEEN CLICKED OR DETECTED
	if(isset($_POST['cmdUploadD']))
	{
		#|||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
		#CHECK FOR ERRORS DURING UPLOAD IN FILE 1
		if($_FILES['fileUp1']['error'] <= 0)
		{
			#CREATE LOCAL VARIABLES
			$fileName	= $_FILES['fileUp1']['name'];
			$fileTemp	= $_FILES['fileUp1']['tmp_name'];
			$path		= 'downloadables/'.$fileName;
			$ST			= '1';
			
			#WE INSERT IT IN THE DATABASE
			mysql_query(" 	INSERT INTO `downloadables`
							SET
							TIT = '".$fileName."',
							LOC = '".$path."',
							ST	= '".$ST."'
						")
			or die(mysql_error());
			move_uploaded_file($fileTemp, $path);		//MOVE FILE TO THE DESTINATION FOLDER
			
			
			#LOG THIS EVENT
			$time_in = date("H:i:s",time() + (960 * 120));							
			$cur_date = date("Y-m-d");
			$cur_timestamp = $cur_date." ".$time_in;	//concatinate time + current date
			
			$query = "	INSERT INTO events SET E_TS = '$cur_timestamp', UID = '$UID', E_D = '[ ".$FN." ] Add file: [ ".$fileName." ]'	";
			mysql_query($query)
			or die(mysql_error());

			
			
			#PROMTP SUCCESS MESSAGE
			$Message	= "<span class='okb'><img src='images/icons/check.jpg' width='20' height='20'>&nbsp;
			Your file has been successfully uploaded.</span>";
			
		}
		#CHECK FOR ERRORS DURING UPLOAD IN FILE 1
		#|||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
		
		
		#|||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
		#CHECK FOR ERRORS DURING UPLOAD IN FILE 1
		if($_FILES['fileUp2']['error'] <= 0)
		{
			#CREATE LOCAL VARIABLES
			$fileName	= $_FILES['fileUp2']['name'];
			$fileTemp	= $_FILES['fileUp2']['tmp_name'];
			$path		= 'downloadables/'.$fileName;
			$ST			= '1';
			
			mysql_query(" 	INSERT INTO `downloadables`
							SET
							TIT = '".$fileName."',
							LOC = '".$path."',
							ST	= '".$ST."'
						")
			or die(mysql_error());
			move_uploaded_file($fileTemp, $path);		//MOVE FILE TO THE DESTINATION FOLDER
			
			#LOG THIS EVENT
			$time_in = date("H:i:s",time() + (960 * 120));							
			$cur_date = date("Y-m-d");
			$cur_timestamp = $cur_date." ".$time_in;	//concatinate time + current date
			
			$query = "	INSERT INTO events SET E_TS = '$cur_timestamp', UID = '$UID', E_D = '[ ".$FN." ] Add file: [ ".$fileName." ]'	";
			mysql_query($query)
			or die(mysql_error());
			
			
			#PROMTP SUCCESS MESSAGE
			$Message	= "<span class='okb'><img src='images/icons/check.jpg' width='20' height='20'>&nbsp;
			Your file has been successfully uploaded.</span>";
			
		}
		#CHECK FOR ERRORS DURING UPLOAD IN FILE 1
		#|||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
		
		
		#|||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
		#CHECK FOR ERRORS DURING UPLOAD IN FILE 1
		if($_FILES['fileUp3']['error'] <= 0)
		{
			#CREATE LOCAL VARIABLES
			$fileName	= $_FILES['fileUp3']['name'];
			$fileTemp	= $_FILES['fileUp3']['tmp_name'];
			$path		= 'downloadables/'.$fileName;
			$ST			= '1';
			
			mysql_query(" 	INSERT INTO `downloadables`
							SET
							TIT = '".$fileName."',
							LOC = '".$path."',
							ST	= '".$ST."'
						")
			or die(mysql_error());
			move_uploaded_file($fileTemp, $path);		//MOVE FILE TO THE DESTINATION FOLDER
			
			
			#LOG THIS EVENT
			$time_in = date("H:i:s",time() + (960 * 120));							
			$cur_date = date("Y-m-d");
			$cur_timestamp = $cur_date." ".$time_in;	//concatinate time + current date
			
			$query = "	INSERT INTO events SET E_TS = '$cur_timestamp', UID = '$UID', E_D = '[ ".$FN." ] Add file: [ ".$fileName." ]'	";
			mysql_query($query)
			or die(mysql_error());
			
			
			#PROMTP SUCCESS MESSAGE
			$Message	= "<span class='okb'><img src='images/icons/check.jpg' width='20' height='20'>&nbsp;
			Your file has been successfully uploaded.</span>";
			
		}
		#CHECK FOR ERRORS DURING UPLOAD IN FILE 1
		#|||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
		
		
		#|||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
		#CHECK FOR ERRORS DURING UPLOAD IN FILE 1
		if($_FILES['fileUp4']['error'] <= 0)
		{
			#CREATE LOCAL VARIABLES
			$fileName	= $_FILES['fileUp4']['name'];
			$fileTemp	= $_FILES['fileUp4']['tmp_name'];
			$path		= 'downloadables/'.$fileName;
			$ST			= '1';
			
			mysql_query(" 	INSERT INTO `downloadables`
							SET
							TIT = '".$fileName."',
							LOC = '".$path."',
							ST	= '".$ST."'
						")
			or die(mysql_error());
			move_uploaded_file($fileTemp, $path);		//MOVE FILE TO THE DESTINATION FOLDER
			
			
			#LOG THIS EVENT
			$time_in = date("H:i:s",time() + (960 * 120));							
			$cur_date = date("Y-m-d");
			$cur_timestamp = $cur_date." ".$time_in;	//concatinate time + current date
			
			$query = "	INSERT INTO events SET E_TS = '$cur_timestamp', UID = '$UID', E_D = '[ ".$FN." ] Add file: [ ".$fileName." ]'	";
			mysql_query($query)
			or die(mysql_error());
			
			
			#PROMTP SUCCESS MESSAGE
			$Message	= "<span class='okb'><img src='images/icons/check.jpg' width='20' height='20'>&nbsp;
			Your file has been successfully uploaded.</span>";
			
		}
		#CHECK FOR ERRORS DURING UPLOAD IN FILE 1
		#|||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
		
		
	}
	#IF UPLOAD BUTTON HAS BEEN CLICKED OR DETECTED
	
	
	#IF USER ADDED NEW DOWNLOADABLES||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
	################################################################################################################################
	
	
	################################################################################################################################
	#IF USER DELETED A FILE|||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
	$getAction	= $_GET['action'];
	
	if($getAction == 'Delete')
	{
		#ERROR MESSAGE
		$Message = " <img src='images/icons/x.png' width='20' height='20'>&nbsp;<span class='error'>
		Your file has been deleted. This action is cannot be undone.";
	}
	
	#IF USER DELETED A FILE|||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
	################################################################################################################################
?>

<?php include 'm_header.php'; ?>
<form action="#" method="post">


<td width="75%" valign="top" align="left">
	<!-- BREAD CRUMBS / SEARCH TOOL -->
	<div class="left" style="height:70px; background-color: #9C6;">
        <div class="left" style="border-bottom: 1px solid #CCC; height:68px; margin-right: 5px;">
        <span class="okw"><img src="images/icons/download_icon.png" width="20" height="20" style="margin-top: 5px;" />&nbsp;DOWNLOADABLES</span>
            <div class="left" style="margin-left: 25px; margin-top: 10px;"><!--Search by:
                <span class="left" style="margin-left: 5px; margin-top: 10px;">
                  <input type="text" name="txtSearch" onKeyUp="showSomething(this.value)" class="SearchBox" placeholder="Type" />
                </span> -->
                Everything here is FREE! 
            </div>
        </div>
    </div>
    <!-- BREAD CRUMBS / SEARCH TOOL -->
    
    
    <!-- WILL DISPLAY THE CONTENT-->
    <div class="center" style="margin-top: 15px; margin-right: 5px; width: 97%;">
    
    	 <div class="left" style=" border-top-left-radius: 0.3em; border-top-right-radius: 0.3em; ">
             <div class="header_arrow">
                <span class="left">
                    <img src="images/icons/toolbar-icons/downloads.png" width="20" height="20" style="margin-top: 2px;" />&nbsp;Downloadables
                </span>
                <span class="arrow">
                </span>
             </div>
         </div>
         
         <div class="center" style="margin-top: 15px; margin-bottom: 15px;">
			<!-- PROMPT MESSAGE HERE --><?php echo $Message; ?>
         </div>
         
         <!-- LOAD THE MESSAGE BOX HERE -->
         <div id="showLightbox"></div>
         <!-- LOAD THE MESSAGE BOX HERE -->
         
         <!-- LOAD BLOGS POP-UP HERE-->
         <div id="showBlogs"></div>
         <!-- LOAD BLOGS POP-UP HERE-->
         
                  
          <!-- LOAD ASSIGNMENT POPUP -->
         <div id="showAssign"></div>
         <!-- LOAD ASSIGNMENT POPUP -->
         
          <!-- LOAD THE MESSAGE BOX HERE -->
         <div id="showUserReg"></div>
         <!-- LOAD THE MESSAGE BOX HERE -->
         
        
   		 <!-- WILL DISPLAY THE DETAILS ABOUT THE USER -->
         <div id="txtHint" class="left" style=" margin-top: 15px; margin-bottom: 15px;">
         	<!-- YOU TABLE HERE -->
            
            <table border="0" cellspacing="0" cellpadding="0">
            <?php 
			$per_page	= 5;
			
			$Count	= 0;	//zero default value
			
			$query	= mysql_query(" SELECT COUNT(`id`) FROM `downloadables` WHERE ST = '1' ")		//this only serves as a counter
			or die(mysql_error());
			$pages	= ceil(mysql_result($query, 0) / $per_page);				//use ciel to get the round of the higher value
					
			$page	= ( isset( $_GET['page']))  ? (int)$_GET['page'] : 1;
			$start	= ($page - 1) * $per_page;
			
			
			$query 	= mysql_query(" SELECT *
									FROM `downloadables`
									WHERE ST = '1'
									ORDER BY id DESC LIMIT ".$start.", ".$per_page." ")
			or die(mysql_error());
			
			while($row	= mysql_fetch_array($query))
			{
				#SET LOCAL VARIABLES
				$Count++;
				$r_id		= $row['id'];	//file id
				$r_title	= $row['TIT'];	//file name
				$r_link		= $row['LOC'];	//location folder
				
				$table = "
				
				<tr>
                	<td>
                    <div class='dloadWrap'>
					<span style='float: right;'>
						<a href='m_dloads_del.php?fileID=".$r_id."'><img src='images/icons/x.png' width='20' height='20' title='Delete this file'></a>
					</span>
                    	<div style=' text-align: left; margin:5px; padding:3px; border-bottom: 1px dashed #666;'>
                        	<h5>".$r_title."</h5>
                        </div>
                        <div class='left'>
                        	<h5 class='dloadLink'><a href='".$r_link."' title='Download this file'>Download this file</a></h5>
                        </div>
                    </div>
                    </td>
               	</tr>
				
				";
				
				echo $table;
				
			}
			//end while
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
        
        <!-- DIV FOR BLOGS MANAGEMENT -->
        <div style="margin-top: 15px; margin-left: 5px; margin-right: 5px; margin-bottom: 15px;">
            <div class="left" style=" height: 25px; border: 1px solid #CCC; border-top-left-radius: 0.3em; border-top-right-radius: 0.3em; 
            background-color:#b6e56d">
                <img src="images/icons/toolbar-icons/settings.png"  width="20" height="20" />&nbsp;Manage Downloadables           
            </div>
            
            <div class='left' style='padding-top: 5px; padding-bottom: 5px; 
            border-left:1px solid #CCC;  border-right:1px solid #CCC; border-bottom: 1px solid #CCC;'>
                <div class='left'>
                 <img src="images/icons/toolbar-icons/downloads.png" width='20' height='20' />&nbsp;
                <input type='button' class='button' onclick='showDloads()' value='Add New' title="Add New File" />
                </div>
            </div>
        
        </div>

	</div> 

</td>
<!-- RIGHT NAV -->


</form>
<?php include 'footer.php'; ?>