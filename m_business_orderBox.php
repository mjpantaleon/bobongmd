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
<div id="orderBG" onclick="hideOrderBox()"></div>
<div id="orderFG">

	<div class="left">
    	<!-- RECENT ASSIGNENTS HERE -->
    	<div style=" margin: 20px 15px 15px; font-size:18px; text-align:left; border-bottom: 1px solid #999;">
           	  <img src="images/icons/toolbar-icons/cart2.png" width="35" height="35">
            	Recent Orders
              <span style="float: right; cursor: pointer; margin-right: 15px;">
              <img src="images/icons/x.png" width="20" height="20" onClick="hideOrderBox()" title="Close">
              <!--<input type="button" class="button" onClick="hideShowInbox()" value="X" title="Close" >-->
              </span>
        </div>
        <!-- RECENT ASSIGNENTS HERE -->
        
        <!-- CONTENT DIV HERE -->
        <?php
		//we gona get the list of assignment whos status is 1 then get the details of that user
		$query	= " SELECT `business_orders`.*, `business`.IMG,DESK
					FROM `business_orders`
					LEFT JOIN `business`
					ON `business_orders`.`item_id` = `business`.`item_id`
					WHERE `O_ST` = '1'
					ORDER BY `bus_order_id` DESC
				  ";
		$result = mysql_query($query);
		while($row = mysql_fetch_array($result))
		{
			#set local variables
			$r_IMG	= $row['IMG'];	#image of the item
			$r_DESC	= $row['DESK'];
			#details
			$r_PO	= $row['bus_order_id'];
			$r_UID	= $row['UID'];
			$r_FN	= $row['FN'];
			$r_PRCE	= $row['PRCE'];
			$r_QNT	= $row['QNT'];
			$r_TTL	= $row['SUBTTL'];
			$r_DTS	= $row['dateSnt'];
			$r_TSN	= $row['timeSnt'];
			
			$divResult = "
			
			<div style=' border:1px dashed #CCC; margin: 25px 15px 15px; background-color:#FFF; padding: 3px; height: 70px; box-shadow: 0 1px 3px #999;'>
            <div style='border: 1px solid #999; background-color: #FFF; height:65px; width: 65px; margin: -17px 0 0 0;'>
                <div class='center'>
                    <a href='m_business_orderDetails.php?order=".$r_PO." '>
						<img src='".$r_IMG."' width='65' height='65' title='View this order'>
					</a>
                </div>
            </div>
            
            <div style=' margin: -55px 0 0 75px;'>
                <div class=' left'>
				<a href='m_business_orderDetails.php?order=".$r_PO." ' title='View this order'><h5>".$r_FN."</h5></a></div>
            </div>
            <div style=' margin: 0 0 0 85px;'>
                <p><span class='s_normal'>".substr($r_DESC, 0,55)."..."."</span></p>
            </div>
            
            <div style=' margin: 0 0 0 85px;'>
                <div class='right'><span class='s_normal'>".substr($r_DTS, 0, 24)." - ".substr($r_TSN, 0 , 10)."</span></div>
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