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

	//then we have to get the USER ID and USER NAME of this user
	$getUserName	= $_GET['user'];	//User Name of the clicked user
	$getTS			= $_GET['ts'];		//timestamp of this message

	$query	= " SELECT UID 
				FROM user_details
				WHERE UN = '".$getUserName."' ";
	$result	= mysql_query($query);
	$row	= mysql_fetch_array($result);
	$r_UID	= $row['UID'];		//USER ID of the USER NAME we clicked
	$r_UN	= $row['UN'];		//USER NAME of the USER NAME we clicked
	$r_PIC	= $row['PIC'];		//user details: profile PIC
	$r_FN	= $row['FN'];		//Full Name
	//then we have to get the USER ID and USER NAME of this user
	
	//Change the message status so that the system would know that this message is already been viewd by the receiver
	mysql_query(" UPDATE msgs SET MST = '0' WHERE msgFrom = '".$r_UID."' AND TS = '".$getTS."' ")
	or die(mysql_error());
	
	

	//if the user has a reply then
	$Reply	= trim(mysql_real_escape_string($_POST['txtMSG']));		//holds any value of the reply box
	
	if( isset( $_POST['txtMSG']))
	{
		
		//if the has a value then
		if($Reply != '')
		{
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
			$query		= mysql_query(" INSERT INTO msgs SET id = '$MSGID', MSG = '$Reply', 
										msgFrom = '$UID', msgTo = '$r_UID', dateSent = '$today', timeSent = '$hr_s ', MST = '1' ")
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
				//$Message = "Good Reply";
		}
		//if the has a value then
			//$Message = "BAD Reply";
	}
	//if the user has a reply then	

?>

<?php include 'g_header.php'; ?>


<td width="75%" valign="top" align="left">
<!-- BREAD CRUMBS / SEARCH TOOL -->
	<div class="left" style="height:70px; background-color: #9C6;">
        <div class="left" style="border-bottom: 1px solid #CCC; height:68px; margin-right: 5px;">
        <span class="okw"><img src="images/icons/Email-icon.png" width="20" height="20" style="margin-top: 5px;" />&nbsp;MESSAGES</span>
            <div class="left" style="margin-left: 25px; margin-top: 10px;">
                <span class="left" style="margin-left: 5px; margin-top: 10px;">
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
                    <img src="images/icons/message.png" width="20" height="20" style="margin-top: 2px;" />&nbsp;Recent Messages</span><span class="arrow">
                </span>
             </div>
         </div>
         
         <div class="center" style="margin-top: 15px; margin-bottom: 15px; margin-left: 5px; margin-right:15px;">
         	<table width="100%" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td>
                <form action="#" method="post">
                <?php
				
				//if a user has been clicked then
				if( isset( $_GET['user']))
				{
					//we echo a textbox that will hold whatever reply of the user logged in
					echo "
					
					<div class='left' style='border-bottom: 1px solid #CCC;'>
					  <span class='left'>
						<input type='text' class='textArea' name='txtMSG' rows='3' cols='25' placeholder='Write a reply...' />
					  </span>
					</div>
					
					";
				}
				
				//else if NO user has been clicked then
				else
					//we simply promt that no conversations is available to the user
					 echo "<br><br><div class='center'><span class='s_normal'>No Conversations available</span></div>";
				?>
                </form>
                <span class="center"><?php echo $Message; ?></span>	
                </td>
              </tr>
            </table>
         </div>
        
   		 <!-- WILL DISPLAY THE DETAILS ABOUT THE USER -->
         <div id="txtHint" class="left" style=" margin-top: 15px; margin-bottom: 35px; margin-left: 5px; margin-right:15px; background-color:">
           
            <?php
            
            //if the user is clicked then
            if( isset( $_GET['user']))
            {
				/*
				SELECT *
				FROM msgs
				LEFT JOIN user_details
				ON msgs.sentTo = user_details.UID 
				WHERE sentTo = '".$r_UID."'  
				ORDER BY TS DESC
				*/
				
				//then we want to display the message sent to this user and other details
				$query	= " SELECT * 
							FROM  `msgs`
							LEFT JOIN user_details
							ON msgs.msgFrom = user_details.UID
							WHERE ( (msgFrom =  '$UID' AND msgTo =  '$r_UID') || ( msgFrom =  '$r_UID' AND msgTo =  '$UID' ))
							ORDER BY TS DESC ";			
				$result	= mysql_query($query);
				while($row	= mysql_fetch_array($result))
				{
					//get rows on msgs table
					$e_MSG	= $row['MSG'];		//Message
					$timeSent = $row['timeSent'];	//time Sent
					$dateSent = $row['dateSent'];	//date sent
					$sentBy	= $row['msgFrom'];
					$sentTo = $row['msgTo'];
					
					//---------------------------FROM TABLE user_details
					$e_UN	= $row['UN'];		//USER NAME of the USER NAME we clicked
					$e_PIC	= $row['PIC'];		//user details: profile PIC
					$e_FN	= $row['FN'];		//Full Name
					
					//change format of profile picture of user
					if($e_PIC != '')
						$e_PIC = $e_PIC;
					else
						$e_PIC = "images/icons/default_user.jpg";
		
					
					
					$table = "
					
					 <div class='left'>	
					 ";
					
					//if the full name of the user is the same with the user loggedin then
					if($e_FN == $FN)
					{
					 
					 $table.="
					 
						 <div style=' border:1px dashed #CCC; margin: 25px 15px 15px; background-color:#FF9; padding: 3px; 
						 box-shadow: 0 1px 3px #999;'>
					";
					
					}
					
					//else  display a div with lighter color
					else
					{
						
					$table.="
						 <div style=' border:1px dashed #CCC; margin: 25px 15px 15px; background-color:#FFC; padding: 3px; 
						 box-shadow: 0 1px 3px #999;'>
					";
					
					}
					
					$table.="
					
							<div style='border: 1px solid #999; background-color: #FFF; height:65px; width: 65px; margin: -17px 0 0 0;'>
								<div class='center'>
									<a href='g_user_details.php?user=".$e_UN."' title='View profile of ".$e_FN."'>
										<img src='".$e_PIC."' width='65px' height='65px'>
									</a>
								</div>
							</div>
							
							<div style=' margin: -55px 0 0 75px;'>
								<div class=' left'>
									<a href='g_user_details.php?user=".$e_UN."' title='View profile of ".$e_FN."'><h5>".$e_FN."</h5></a>
								</div>
								<div class='left' style='margin-left: 5px;'><span class='s_normal'>Said:</span></div>
							</div>
							<div style=' margin: 0 0 0 85px;'>
								<p>".$e_MSG."</p>
							</div>
							
							<div style=' margin: 0 0 0 85px;'>
								<div class='right'><span class='s_normal'>".substr($dateSent, 0, 7).' - '.substr($timeSent, 0 , 10)."</span></div>
							</div>
						</div>
						
					</div>
					
					";

					echo $table;
				}
				//end while
				
			}
			//end if
            ?>
            

         </div>
         <!-- WILL DISPLAY THE DETAILS ABOUT THE USER -->
    </div>
    
    <div class="center" style="margin-top: 15px; margin-bottom: 15px; "><?php echo $paginationDisplay; ?></div>
    
    <!-- WILL DISPLAY THE CONTENT-->	
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
    <div style="margin-top: 15px; margin-left: 5px; margin-right: 5px;">
    
        	<div class="left" style=" height: 25px; border: 1px solid #CCC; border-top-left-radius: 0.3em; border-top-right-radius: 0.3em; 
            background-color:#b6e56d">
            	<img src="images/icons/toolbar-icons/user.png" width="16" height="16" />&nbsp;Recent Contversations
            </div>
    
    		<div class="left" style="padding-top: 5px; padding-bottom: 5px; 
            border-left:1px solid #CCC;  border-right:1px solid #CCC; border-bottom: 1px solid #CCC;">
	
	<?php
	//get details of user in 2 tables NO DUPLICATE USER MUST BE DISPLAY
	/*
	
	this query doent display the recent user recipient
	it onyl diplay a distinct user -_- lame
	
	 SELECT DISTINCT msgs.STO, msgs.*, user_details.*  
				FROM msgs
				LEFT JOIN user_details
				ON msgs.STO = user_details.UID
				WHERE ST = '1' AND RplyST = '1'
				GROUP BY UN
				ORDER BY TS DESC
				
	SELECT msgs.*, user_details.*  
				FROM msgs
				LEFT JOIN user_details
				ON msgs.sentTo = user_details.UID
				ORDER BY TS DESC LIMIT 10
	*/
	
	//i need to get where the message is FROM and where message is TO
	$query2	= " SELECT *
				FROM msgs
				LEFT JOIN user_details
				ON msgs.msgFrom = user_details.UID
				WHERE msgFrom ='".$UID."' OR msgTo = '".$UID."'
				GROUP BY UID 
				ORDER BY TS DESC ";

	$result2	= mysql_query($query2);

	//loop how many records are there in the table
	while($row = mysql_fetch_array($result2))
	{
		$MSG		= substr($row['MSG'],0 ,15)."...";	//message recieved, trim the number of characters(15) to be displayed followed by ...
		$msgFrom 	= $row['msgFrom'];
		$msgTo		= $row['msgTo'];					//userid of the recipient of the message
		$DT			= substr($row['dateSent'],0 ,7);	//date message received in String format
		$TS			= $row['TS'];						//timestamp of this message
		
		//---------------------------FROM TABLE user_details
		$r_UN	= $row['UN'];		//USER NAME of the USER NAME we clicked
		$r_PIC	= $row['PIC'];		//user details: profile PIC
		$r_FN	= $row['FN'];		//Full Name
		$r_RST	= $row['MST'];		//Reply stats
		
		//change format of profile picture of user
		if($r_PIC != '')
			$r_PIC = $r_PIC;
		else
			$r_PIC = "images/icons/default_user.jpg";
		
		
		$Count++;
		//we create a div the will hold the result
		/**/
		
		if($msgFrom == $UID)
		{
			//do NOT display anything ***
			
		}
		
		else
		{
			$table = "
		
			<table width='100%' border='0' cellspacing='0' cellpadding='0'>
			
			<tr>
				<td rowspan='2' class='left' style=' background-color: #fff; padding-bottom: 2px; padding-top: 5px;'>
					<a href='g_msgs.php?user=".$r_UN."&ts=".$TS."'>
						<img src='".$r_PIC."' width='40' height='40' style='border: 1px solid #999;' title='View messages of ".$r_FN."' />
					</a>
				</td>
				<td class='left' style=' padding-bottom: 2px; padding-top: 5px;'>
					<a href='g_msgs.php?user=".$r_UN."&ts=".$TS."' title='View messages of ".$r_FN."'>
						".$r_FN."
					</a>
				</td>
				
				<td valign='top' align='left' rowspan='2' class='left' style=' background-color: #fff; padding-bottom: 2px; 
				padding-top: 5px;'>
					<span class='s_normal'>".$DT."</span>
				</td>
			</tr>
			";
			
			if($r_RST == 0)
			{
				$table.="
				 
				<tr>
				  <td class='left' style=' padding-bottom: 2px; padding-top:5px;'><span class='s_normal'>".$MSG."</span></td>
				</tr>
				
				</table>
				
				";
			}
			
			else
			{
				$table.="
				
				<tr>
				  <td class='left' style=' padding-bottom: 2px; padding-top:5px;'>
					  <span class='error'>*</span>
					  <span class='s_normal'>".$MSG."</span>
				  </td>
				</tr>
				
				</table>
				
				";
			}
		
			echo $table;
		}
		//we create a div the will hold the result
	}
	//end while
	?>
   		</div>
    </div>
    
    <!-- DISPLAY ALL RECENTLY CONTACTED PERSON -->
    <!-- Display Updates thats happening in the system -->
    
    <!-- pagination -->
    <div class="center" style="margin-top: 15px; margin-bottom: 15px; margin-left: 4px; margin-right: 4px; "><?php echo $paginationDisplay; ?></div>
    <!-- pagination -->
</td>


<?php include 'footer.php'; ?>