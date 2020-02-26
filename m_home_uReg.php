<?php
/** Set flag that this is a parent file */
define( "MAIN", 1 );


//require connection to the database
require('db_con.php');


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

$query = "	INSERT INTO events SET E_TS = '$cur_timestamp', UID = '$UID', E_D = '[ ".$FN." ] View User Registry' ";
mysql_query($query)
or die(mysql_error());

?>


<form action="#" method="post" enctype="multipart/form-data">
<div id="inboxBG" onclick="hideShowInbox()"></div>
<div id="inboxFG">
	<!-- CONTENT HERE -->

    	<div class="left">
        	<div style=" margin: 20px 15px 15px; 
            font-size:18px; text-align:left; border-bottom: 1px solid #999;">
           	  <img src="images/icons/toolbar-icons/user_alert.png" width="35" height="35">
            	User Registry
              <span style="float: right; cursor: pointer; margin-right: 15px;">
              <img src="images/icons/x.png" width="20" height="20" onClick="hideShowInbox()" title="Close">
              <!--<input type="button" class="button" onClick="hideShowInbox()" value="X" title="Close" >-->
              </span>
            </div>
            
            <?php
			
			//SELECT FULL NAME, PIC, DATE REGISTRY AND TIME REGISTRY
			$query	= " SELECT *
						FROM `user_details`
						WHERE UT_ID = '2' AND ST = '0'
						ORDER BY UID DESC
					  ";
			$result	= mysql_query($query);
			while($row	= mysql_fetch_array($result))
			{
				//declaire variables
				$r_UID	= $row['UID'];	//id of this user
				$r_UN	= $row['UN'];	//User name
				$r_FN	= $row['FN'];	//Full name
				$r_AD	= $row['AD'];	//Address
				$r_REL	= $row['REL'];	//Realtion
				$r_PIC	= $row['PIC'];	//Picture
				$r_ST	= $row['ST'];	//Status
				$r_DR	= $row['dateReg'];
				$r_TR	= $row['timeReg'];
				
				
				//change format of profile picture of user
				if($r_PIC != '')
					$r_PIC = $r_PIC;
				else
					$r_PIC = "images/icons/default_user.jpg";
					
					
			$divResult="
			
			<div style=' border:1px dashed #CCC; margin: 25px 15px 15px; background-color:#FFF; padding: 3px; height: 70px; box-shadow: 0 1px 3px #999;'>
			
				<div style='border: 1px solid #999; background-color: #FFF; height:65px; width: 65px; margin: -17px 0 0 0;'>
					<div class='center'>
						<a href='m_user_details.php?user=".$r_UID."'><img src='".$r_PIC."' width='65' height='65' title='View Profile of ".$r_FN."'></a>
					</div>
				</div>
				
				<div style=' margin: -55px 0 0 75px;'>
					<div class=' left'><a href='m_user_details.php?user=".$r_UID."' title='View Profile of ".$r_FN."'><h5>".$r_FN."</h5></a></div>
				</div>
				
				<div style=' margin: 0 0 0 85px;'>
					<p><span class='s_normal'>".substr($r_AD, 0,55)."...</span></p>
				</div>
				
				<div style=' margin: 0 0 0 85px;'>
					<div class='right'><span class='s_normal'>".substr($r_DR, 0, 8)." - ".substr($r_TR, 0 , 10)."</span></div>
				</div>
				
			</div>
			
			";
			//result div
			
			
			echo $divResult;
			
			}
			//end while
			
			
			?>
            
             
				
            
            
            <!-- IF MESSAGE DISPLAY IS LIMIT 4 THEN -->
            <?php
			//Display View all if the record is greater than the limit(4)
			if($limit < $divResult)
			{
				echo "
				<div style=' margin: 0 10px 5px 85px;'>
						<div class='right'><span class='s_normal'><a href='' class='button'>View all</a></span></div>
				</div>
				";
			}
			
			else
			{
				echo "
				
				";
			}
			?>
           
    	</div>
        
        
    <!-- CONTENT HERE -->
</div>
</form>