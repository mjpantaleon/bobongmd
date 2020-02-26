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
?>

<?php include 'g_header.php'; ?>
<form action="#" method="post">

<!-- \\\\\\\\\\\\\\\\\\\\CONTENT PART//////////////////////// -->
<td width="75%" valign="top" align="left">
	<div class="left" style="height:70px; background-color: #9C6;">
        <div class="left" style="border-bottom: 1px solid #CCC; height:68px; margin-right: 5px;">
            <span class="okw">
            <img src="images/icons/teacher.png" width="20" height="20" style="margin-top: 5px;" />&nbsp;TEACHER'S SECTION</span>
            <div class="left" style="margin-left: 25px; margin-top: 10px;">Search by:
                <span class="left" style="margin-left: 5px; margin-top: 10px;">
                  <input type="text" name="txtSearch" onKeyUp="showLecHome2(this.value)" class="SearchBox" placeholder="Title" />
                </span> 
            </div>
        </div>
    </div>
    
    
    
    <!-- WILL DISPLAY THE CONTENT-->
    <div class="center" style="margin-top: 15px; margin-right: 5px; width: 97%;">
    
    	 <div class="left" style=" border-top-left-radius: 0.3em; border-top-right-radius: 0.3em; ">
             <div class="header_arrow">
                <span class="left">
                    <img src="images/icons/toolbar-icons/page.png" width="20" height="20" style="margin-top: 2px;" />&nbsp;
                    Recent Lectures</span><span class="arrow">
                </span>
             </div>
         </div>
         
         <div class="center" style="margin-top: 15px; margin-bottom: 15px;">
			<!-- PROMPT MESSAGE HERE -->
         </div>
         
          <!-- LOAD THE MESSAGE BOX HERE -->
         <div id="showSentByHere"></div>
         <!-- LOAD THE MESSAGE BOX HERE -->
         
         <!-- LOAD THE MESSAGE BOX HERE -->
         <div id="showLightbox"></div>
         <!-- LOAD THE MESSAGE BOX HERE -->
        
   		 <!-- WILL DISPLAY THE DETAILS ABOUT THE USER -->
         <div id="txtHint" class="left" style=" margin-top: 15px; margin-bottom: 15px;">
         	<!-- YOU TABLE HERE -->
            <table width="100%" border="0" cellspacing="0" cellpadding="0">
           	<?php
			$per_page	= 9;
			
			$Limit	= 3;	//number of columns or records to display per row
			$Count	= 0;	//zero default value
			
			$query	= mysql_query(" SELECT COUNT(`id`) FROM `teachersec` WHERE ST = '1' ")		//this only serves as a counter
			or die(mysql_error());
			$pages	= ceil(mysql_result($query, 0) / $per_page);				//use ciel to get the round of the higher value
					
			$page	= ( isset( $_GET['page']))  ? (int)$_GET['page'] : 1;
			$start	= ($page - 1) * $per_page;
			
			
			$query2 	= mysql_query(" SELECT *
										FROM `teachersec`
										WHERE ST = '1'
										ORDER BY id DESC LIMIT ".$start.", ".$per_page." ")
			or die(mysql_error());
			
			while($row	= mysql_fetch_array($query2))
			{
				//set variables
				$r_id		= $row['id'];		//record id
				$r_Title	= $row['TIT'];		//Title
				$r_DESC		= $row['DESK'];		//Description
				$r_CAT		= $row['CAT'];		//Category
				$r_CatPic	= $row['CatPic'];	//Category picture
				$r_LINK		= $row['LINK'];		//File link
				$r_TS		= $row['TS'];		//time stamp
				$r_ST		= $row['ST'];		//disable flag
				//$r_LINK		= "a_ts_dload.php?id=".$r_id."";
				
				//getting the elapsed time of this post
				$now	= time();
				$past	= strtotime($r_TS);
				
				
				//change format of the category(int) into string
				if($r_CAT == 1)
					$r_CAT = "PDF";
				
				else
					$r_CAT = "Presentation";
					
				
				//create a statement that will check if the count is equal to the limit set
				//if count(0) is less then limit then
				if($Count < $Limit)
				{
					//if count is zero then
					if($Count == 0)
					{
						//echo the opening row
						$table.=" 
							<tr> 
						";
					}
					
					$table.="
					
						<td>
							<div style='margin: 15px; border:1px solid #CCC; width: 180px; background-color:#F5F5F5;'>
							
							";
							
							//change this button if the status of this item is 0 or 1
							//if status is 1 then
							if($r_ST != 0)
							{
								$table.="
								
								<div class='center' style='border: 1px solid #F5F5F5; background-color:#FFF;'>
									<a href='".$r_LINK."' title='Download this lecture'>
										<img src='".$r_CatPic."' width='176' height='176' style='border:1px solid #CCC;' />
									</a>
								</div>
								
								";
							}
							
							//else if status is 0 then
							else
							{
								$table.="
								
								<div class='center' style='border: 1px solid #F5F5F5; background-color:#FFF;'>
									<img src='".$r_CatPic_x."' width='176' height='176' style='border:1px solid #CCC;' />
								</div>
								
								";
							}
							
							$table.="
							
								<div class='left' style=''><span class='s_normalb'>".$r_Title."</span></div>
								<div class='left' style=' height:40px'><span class='s_normal'>".substr($r_DESC, 0 , 45)."...</span></div>
								<div class='left' style=''>
									<span class='s_normal'>".$r_CAT."</span>
									<span style='float: right'><a class='dloadBtn' href='".$r_LINK."' title='Download this lecture'>Download</a></span>
								</div>
								<div class='right'><span class='ss_normal'>".substr(time_elapsed($now-$past), 0, 15)." ago</span></div>
							</div>
						</td>
					
					";
				}
				//if count(0) is less then limit then
				
				//if count(0) id equal to limit(3) then
				else
				{
					$Count = 0;	//set the value to zero
					
					$table.="</tr>
					<tr>
					
						<td>
							<div style='margin: 15px; border:1px solid #CCC; width: 180px; background-color:#F5F5F5;'>
							
							";
							
							if($r_ST != 0)
							{
								$table.="
							
								<div class='center' style='border: 1px solid #F5F5F5; background-color:#FFF;'>
									<a href='".$r_LINK."' title='Download this lecture'>
										<img src='".$r_CatPic."' width='176' height='176' style='border:1px solid #CCC;' />
									</a>
								</div>
								
								";
							}
							
							else
							{
								$table.="
								
								<div class='center' style='border: 1px solid #F5F5F5; background-color:#FFF;'>
									<img src='".$r_CatPic_x."' width='176' height='176' style='border:1px solid #CCC;' />
								</div>
								
								";
							}
							
							$table.="
							
								<div class='left' style=''><span class='s_normalb'>".$r_Title."</span></div>
								<div class='left' style=' height:40px'><span class='s_normal'>".substr($r_DESC, 0 ,45)."...</span></div>
								<div class='left' style=''>
									<span class='s_normal'>".$r_CAT."</span>
									<span style='float: right'><a class='dloadBtn' href='".$r_LINK."' title='Download this lecture'>Download</a></span>
								</div>
								<div class='right'><span class='ss_normal'>".substr(time_elapsed($now-$past) ,0 , 15)." ago</span></div>
							</div>
						</td>
					
					";
					
				}
				//if count(0) id equal to limit(3) then
				
				$Count++;	//increment the value of count every time it loops
				
				//create a statement that will check if the count is equal to the limit set

			}
			
			//we close the 1st row with 3 columns
			$table.="
				</tr>
			";
			//end while
			echo $table;	//echo the result table
	
	
			?>
        	<!-- YOU TABLE HERE -->
            </table>
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

	<!-- CONTENT -->
</td>
<!-- \\\\\\\\\\\\\\\\\\\\CONTENT PART//////////////////////// -->



<!-- \\\\\\\\\\\\\\\\\\\\RIGHT NAV//////////////////////// -->
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
    <!-- Display Updates thats happening in the system -->
</td>
<!-- \\\\\\\\\\\\\\\\\\\\RIGHT NAV//////////////////////// -->
</form>
<?php include 'footer.php'; ?>