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


//LOG THIS EVENT
$time_in = date("H:i:sa");							
$cur_date = date("Y-m-d");
$cur_timestamp = $cur_date." ".$time_in;	//concatinate time + current date

$query = "	INSERT INTO events SET E_TS = '$cur_timestamp', UID = '$UID', E_D = '[ ".$FN." ] View Assignments' ";
mysql_query($query)
or die(mysql_error());

?>

<form action="#" method="post" enctype="multipart/form-data">
<div id="inboxBG2" onclick="hideShowInbox2()"></div>
<div id="inboxFG2">

	<div class="left">
    	<!-- RECENT ASSIGNENTS HERE -->
    	<div style=" margin: 20px 15px 15px; font-size:18px; text-align:left; border-bottom: 1px solid #999;">
           	  <img src="images/icons/toolbar-icons/folder-alert.png" width="35" height="35">
            	Recent Assignments
              <span style="float: right; cursor: pointer; margin-right: 15px;">
              <img src="images/icons/x.png" width="20" height="20" onClick="hideShowInbox2()" title="Close">
              <!--<input type="button" class="button" onClick="hideShowInbox()" value="X" title="Close" >-->
              </span>
        </div>
        <!-- RECENT ASSIGNENTS HERE -->
        
        <!-- CONTENT DIV HERE -->
        <?php
		//we gona get the list of assignment whos status is 1 then get the details of that user
		$query	= " SELECT assignment.*, user_details.PIC, FN, UN
					FROM `assignment`
					LEFT JOIN `user_details`
					ON assignment.UID = user_details.UID
					WHERE A_ST = '1'
					ORDER BY id ASC
				  ";
		$result = mysql_query($query);
		while($row = mysql_fetch_array($result))
		{
			//set local variables
			$r_FN	= $row['FN'];	//Full of the user who sent the message
			$r_PIC	= $row['PIC'];	//Profile Pick or the sender
			$r_UN	= $row['UN'];	//user name of the sender
			
			$r_ID	= $row['id'];
			$r_SUB	= $row['SUB'];	//Message of the sender
			$r_TS	= $row['TS'];	//Timstamp of this message
			$dateS	= $row['dateSent'];
			$timeS	= $row['timeSent'];
			
			$divResult = "
			
			<div style=' border:1px dashed #CCC; margin: 25px 15px 15px; background-color:#FFF; padding: 3px; height: 70px; box-shadow: 0 1px 3px #999;'>
            <div style='border: 1px solid #999; background-color: #FFF; height:65px; width: 65px; margin: -17px 0 0 0;'>
                <div class='center'>
                    <a href='m_assign_details.php?user=".$r_UN."&ts=".$r_ID."'>
						<img src='".$r_PIC."' width='65' height='65' title='View assignment of ".$r_FN."'>
					</a>
                </div>
            </div>
            
            <div style=' margin: -55px 0 0 75px;'>
                <div class=' left'>
				<a href='m_assign_details.php?user=".$r_UN."&ts=".$r_ID."' title='View assignment of ".$r_FN."'><h5>".$r_FN."</h5></a></div>
            </div>
            <div style=' margin: 0 0 0 85px;'>
                <p><span class='s_normal'>".substr($r_SUB, 0,55)."..."."</span></p>
            </div>
            
            <div style=' margin: 0 0 0 85px;'>
                <div class='right'><span class='s_normal'>".substr($dateS, 0, 7)." - ".substr($timeS, 0 , 10)."</span></div>
            </div>
        </div>
			
			";
			
		echo $divResult;
		}
		//end while
		
		?>
        
        
        <!-- CONTENT DIV HERE -->
    </div>
</div>
</form>