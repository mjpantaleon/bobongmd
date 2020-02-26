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

//Default variables
$Message	= "";
$Count 		= 0;

?>

<?php include 'm_header.php'; ?>


<td width="75%" valign="top" align="left">
	<div class="left" style="height:70px; background-color: #9C6;">
    
        <div class="left" style="border-bottom: 1px solid #CCC; height:68px; margin-right: 5px;">
            <span class="okw">
            <img src="images/icons/Email-icon.png" width="20" height="20" style="margin-top: 5px;" />&nbsp;MESSAGES</span>
            
            <div class="left" style="margin-left: 25px; margin-top: 10px;">&nbsp; </div>
    	</div>  
         
        <!-- DIV HOLDING THE BANNER AND THE CONTENT -->
        <div class="center" style="margin-top: 15px; margin-bottom: 25px; margin-right: 5px; width: 97%;">
        
        	<!-- BANNER -->	
            <div class="left" style=" border-top-left-radius: 0.3em; border-top-right-radius: 0.3em;">
                <div class="header_arrow_h">
                    <span class="left">
                        <img src="images/icons/message.png" width="20" height="20" style="margin-top: 2px;" />&nbsp;Recent Messages</span><span class="arrow">
                    </span>
                </div>
            </div>
        	<!-- BANNER --> 
            
            <div class="center" style="margin-top: 15px; margin-bottom: 15px;">&nbsp;
                <table width="100%" border="0" cellspacing="0" cellpadding="0">
                  <tr>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                  </tr>
                  <tr>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                  </tr>
                  <tr>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                  </tr>
                </table>
            </div>
            
            <!-- LOAD THE MESSAGE BOX HERE -->
            <div id="showLightbox"></div>
            <!-- LOAD THE MESSAGE BOX HERE -->
                
            <!-- WILL DISPLAY THE DETAILS ABOUT THE USER -->
         	<div id="txtHint" class="left" style=" margin-top: 15px; margin-bottom: 15px;">
            
                <table width="100%" border="0" cellspacing="0" cellpadding="0">
                <?php
                
                //if the user is clicked then
                if( isset( $_GET['user']))
                {
                    $getUserName	= $_GET['user'];	//User Name of the clicked user
                    
                    //then we have to get the USER ID and USER NAME of this user
                    $query	= " SELECT UID 
                                FROM user_details
                                WHERE UN = '".$getUserName."' ";
                    $result	= mysql_query($query);
                    $row	= mysql_fetch_array($result);
                    $r_UID	= $row['UID'];			//USER ID of the USER NAME we clicked
                    $r_UN	= $row['UN'];			//USER NAME of the USER NAME we clicked
                    
                    //then we want to display the message sent to this user and other details
                    $query	= " SELECT *
                                FROM msgs
                                LEFT JOIN user_details
                                ON msgs.sentTo = user_details.UID 
                                WHERE sentTo = '".$r_UID."'  
                                ORDER BY TS DESC ";			
                    $result	= mysql_query($query);
                    while($row	= mysql_fetch_array($result))
                    {
                        //get row on the user details table
                        $e_PIC	= $row['PIC'];		//user details: profile PIC
                        $e_FN	= $row['FN'];		//Full Name
                        
                        //get rows on msgs table
                        $e_MSG	= $row['MSG'];		//Message
                        $timeSent = $row['timeSent'];	//time Sent
                        $dateSent = $row['dateSent'];	//date sent
                        
                        $RPLY	= $row['RPLY'];			//reply
                        $sentTo = $row['senTo'];
                        $timeRply = $row['timeRply'];
                        $dateRply = $row['dateRply'];
						
						$table = "
							<tr>
								<td width='5%' rowspan='2' valign='top' align='left'>
									<div class='right' style='margin:3px;'>
										<img src='".$PIC."' height='40' width='40' style='border:1px solid #CCC;' />
									</div>
								</td>
								<td width='20%' valign='top' align='left'>&nbsp;<a href='#'>".$FN."</a></td>
								<td width='60%' valign='top' align='left'>&nbsp;</td>
								<td width='15%'>&nbsp;<span class='s_normal'>".$timeSent."</span></td>
							</tr>
							
							<tr>
								<td colspan='2' align='left' valign='top'>&nbsp;<span class='s_normal'> >>> ".$e_MSG."</span></td>
								<td width='15%'>&nbsp;<span class='s_normal'>".$dateSent."</span></td>
							</tr>
							
							<tr>
								<td rowspan='2'>
									<div class='right' style='margin:3px;'>
										<img src='".$e_PIC."' height='40' width='40' style='border:1px solid #CCC;' />
									</div>
								</td>
								<td width='20%' valign='top' align='left'>&nbsp;<a href='#'>".$e_FN."</a></td>
								<td width='60%' valign='top' align='left'>&nbsp;</td>
								<td width='15%'>&nbsp;<span class='s_normal'>".$timeRply."</span></td>
							</tr>
						
						";
					if($RPLY != '')
					{
						
						$table.="
						
							<tr>
								<td width='20%' valign='top' align='left'>&nbsp;<span class='s_normal'> <<< ".$RPLY."</span></td>
								<td width='60%' valign='top' align='left'>&nbsp;</td>
								<td>&nbsp;<span class='s_normal'>".$dateRply."</span></td>
							</tr>
							
							
						";
					}
					
					else
					{
						$table.="
						
							<tr>
								<td width='20%' valign='top' align='left'>&nbsp;<span class='s_normal'><<< -No reply-</span></td>
								<td width='60%' valign='top' align='left'>&nbsp;</td>
								<td>&nbsp;<span class='s_normal'> ...</span></td>
							</tr>
							
							
						";
					}
						
						echo $table;
                    }
                    //end while
                    
                }
                
                else
                    echo "<br><br><div class='center'><span class='s_normal'>No Conversations available</span></div>";
                ?>
                
                  
             	</table>   
             </div>
         <!-- WILL DISPLAY THE DETAILS ABOUT THE USER -->
    </div>
    
    <div class="center" style="margin-top: 15px; margin-bottom: 15px; "><?php echo $paginationDisplay; ?></div>
    
    <!-- WILL DISPLAY THE CONTENT-->
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
    	<div style="margin-top: 5px; color:#9C3;"><img src="images/icons/InfoIcon.png" width="16" height="16" />&nbsp;Recent Contacted Persons</div>
    </div> 
    
    <!-- SEARCH BOX -->
    <div class="left" style="margin: 15px;"><span class="s_normalb">Search by:</span>
    <input class="searchbox_mini" type="text" name="txtSearch" placeholder="Full name" /></div>
    <!-- SEARCH BOX -->
    
    
    <!-- DISPLAY ALL RECENTLY CONTACTED PERSON -->
    <div class="center" style=" margin-top: 4px; margin-bottom: 25px; margin-left: 4px; margin-right: 4px; border-radius: 0.3em;">
	
	<?php
	//get details of user in 2 tables NO DUPLICATE USER MUST BE DISPLAY
	/*
	
	this query doent display the recent user recipient
	it onyl diplay a distinct user -_- lame
	
	 SELECT DISTINCT msgs.STO, msgs.*, user_details.*  
				FROM msgs
				LEFT JOIN user_details
				ON msgs.STO = user_details.UID
				GROUP BY UN
				ORDER BY TS DESC
	*/
	
	$query	= " SELECT DISTINCT(msgs.sentTo), msgs.*, user_details.*  
				FROM msgs
				LEFT JOIN user_details
				ON msgs.sentTo = user_details.UID
				ORDER BY TS DESC ";

	$result	= mysql_query($query);

	//loop how many records are there in the table
	while($row = mysql_fetch_array($result))
	{											
		$STO	= $row['sentTo'];	//userid of the recipient of the message
		$DT		= substr($row['dateSent'],0 ,5);	//date message received in String format
		$MSG	= substr($row['MSG'],0 ,15);	//message recieved, trim the number of characters(15) to be displayed
		
		$e_UN	= $row['UN'];	//user name
		$e_PIC	= $row['PIC'];	//profile picture of the user
		$e_FN	= $row['FN'];	//full name of the user
		$Count++;
		//we create a div the will hold the result
		/**/
		echo"
		
		<table width='100%' border='0' cellspacing='0' cellpadding='0'>
		
		<tr>
			<td rowspan='2' class='left' style='border-bottom:1px solid #CCC; background-color: #fff; padding-bottom: 2px; padding-top: 5px;'>
				<a href='m_msgs.php?user=$e_UN'><img src='".$e_PIC."' width='40' height='40' style='border: 1px solid #999;' title='Click me' /></a>
            </td>
			<td class='left' style='background-color: #fff;padding-bottom: 2px; padding-top: 5px;'>".$e_FN."</td>
			
			<td valign='top' align='left' rowspan='2' class='left' style='border-bottom:1px solid #CCC; background-color: #fff; padding-bottom: 2px; 
			padding-top: 5px;'>
				<span class='s_normal'>".$DT."</span>
            </td>
        </tr>
		
		<tr>
		  <td class='left' style='border-bottom:1px solid #CCC; padding-bottom: 2px; padding-top:5px;'><span class='s_normal'>".$MSG."</span></td>
		</tr>
		
		</table>
		";
		
		//we create a div the will hold the result
	}
	//end while
	?>
   
    </div>
    
    <!-- DISPLAY ALL RECENTLY CONTACTED PERSON -->
    <!-- Display Updates thats happening in the system -->
    
    <!-- pagination -->
    <div class="center" style="margin-top: 15px; margin-bottom: 15px; margin-left: 4px; margin-right: 4px; "><?php echo $paginationDisplay; ?></div>
    <!-- pagination -->
</td>


<?php include 'footer.php'; ?>