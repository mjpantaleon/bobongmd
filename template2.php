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
?>

<?php include 'm_header.php'; ?>


<td width="75%" valign="top" align="left">
	<div class="left" style="height:70px; background-color: #9C6;">
        <div class="left" style="border-bottom: 1px solid #CCC; height:68px; margin-right: 5px;">
            <span class="okw">
            <img src="images/icons/home_icon.png" width="20" height="20" style="margin-top: 5px;" />&nbsp;HOME</span>
            
            <div class="left" style="margin-left: 25px; margin-top: 10px;">&nbsp; </div>
    	</div>  
         
        <!-- WILL DISPLAY THE CONTENT-->
        <div class="center" style="margin-top: 15px; margin-right: 5px; width: 97%;">
             <!-- BANNER -->	
             <div class="left" style=" border-top-left-radius: 0.3em; border-top-right-radius: 0.3em;">
                 <div class="header_arrow_h">
                    <span class="left">
                        <img src="images/icons/icon.png" width="20" height="20" style="margin-top: 2px;" />&nbsp;Welcome! Administrator</span><span class="arrow">
                    </span>
                 </div>
             </div>
             <!-- BANNER -->
             
             
        </div>
   </div>     
        <!--<div style="margin-top: 15px; margin-left: 25px;"><img src="images/icons/addblankrow.gif" width="20" height="20" />&nbsp;Update Status</div>
        <div style="margin-top: 3px; margin-left: 25px;">
        	<textarea name="txtStatus" rows="3" cols="60" title="Whats on your mind?" placeholder="Whats on your mind?"></textarea>
        </div>-->
        
        
        <!-- WILL DISPLAY THE CONTENT-->
    <!--
	<div class="left" style="margin-top: 15px; margin-left: 15px; margin-right: 15px; height:55px; border-bottom: 1px solid #CCC;">
    <span class="okg2">ADMIN HOME PAGE</span>
        <div class="left" style="margin-left: 25px; margin-top: 10px;">&nbsp; </div>
    </div>
    -->
</td>

<td width="25%" valign="top" align="center" style="border-left:1px solid #999;" class="a">
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
            <td><div style=" margin-top: 5px;"><span class="okb"><?php echo $FN.'<br> '.$UT_N.'<br>'; ?><a href="#">Change Password</a></span></div></td>
          </tr>
        </table>	
    </div>
    <!-- DISPLAY PICTURE OF USER THAT LOGGED IN AND DETAILS -->
    
    
    
    <!-- Display Updates thats happening in the system -->
    <div class="left" style=" margin-top: 1px;  margin-left: 2px; margin-right: 2px; border-top: 1px solid #9C6;">
    	<div style="margin-top: 5px; color:#9C3;"><img src="images/icons/InfoIcon.png" width="16" height="16" />&nbsp;Your Daily Updates</div>
    </div> 
    
    <div class="center" style=" margin-left: 2px; margin-right: 2px;">
			<?php 
             //we want to get the total number of users whos status is 0 or not yet active
             $query		= " SELECT COUNT(ST) AS ST FROM users WHERE ST = '0' ";
             $result	= mysql_query($query);
             $row		= mysql_fetch_array($result);
             
			 //we get the total count based on the 0 status and place it in a variable '$T_ST'
             $T_ST		= $row['ST'];	//will hold watever number is in the table
            ?>
            <!-- there should be an exclamation mark here!!! -->
            
            <?php
			//if there are users whos status is O then
			if($T_ST == true)
				//display an alert message to alert the admin
				echo '<div style="margin-top: 15px;" class="upd8ON">
						<img src="images/icons/alert-small.gif" width="16" height="16" />
						<a href="m_users.php">New User Registry</a> (<span class="error" style="font-size:12px">'.$T_ST.'</span>)
					  </div>
					 ';
					 
			//if theres are no user whos status is O then
			else
				//display a gray colored text + unclickable link
				echo '<div style="margin-top: 15px; color: #999;">New User Registry ('.$T_ST.')</div>';
			?>	
        
    </div>
    <!-- Display Updates thats happening in the system -->
</td>


<?php include 'footer.php'; ?>