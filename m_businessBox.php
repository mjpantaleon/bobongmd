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

$query = "	INSERT INTO events SET E_TS = '$cur_timestamp', UID = '$UID', E_D = '[ ".$FN." ] Adding New Item' ";
mysql_query($query)
or die(mysql_error());

?>


<form action="#" method="post" enctype="multipart/form-data">
<div id="businessBG" onclick="hideBusinessBox()"></div>
<div id="businessFG">
	<!-- CONTENT HERE -->
	<table width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td colspan="2">
            <div style=" margin-top:20px; margin-bottom: 15px; margin-left: 15px; margin-right: 15px; 
            font-size:18px; text-align:left; border-bottom: 1px solid #999;">
            	<img src="images/icons/toolbar-icons/new_blue.png" height="30" width="30" />
                Add New Item
                <span style="float:right; cursor: pointer;">
                <img src="images/icons/x.png" width="20" height="20" onClick="hideBusinessBox()" title="Close">
                <!--<input type="button" class="button" onclick="hideComment()" value="X" title="Close" />-->
                </span>
            </div>    	
      	</td>
      </tr>
      
      <tr>
        <td valign="top" align="left"><div class="left" style=" margin: 15px 0px 0 15px; border-right: 1px ridge #999;">Item Name:</div></td>
        <td>
            <div class="left" >
            	<input class="InputBox" type="text" name="txtTitle" placeholder="Insert Item Name here..." autofocus required />
            </div>
        </td>
      </tr>
      
      <tr>
        <td valign="top" align="left"><div class="left" style=" margin: 15px 0px 0 15px; border-right: 1px ridge #999;">Description:</div></td>
        <td>
        	<div class="left" style="margin: 15px 0 0;">
            	<textarea style="padding: 5px; background:#FFC; border: 1px solid #999; border-radius: 0.3em;" 
                name="txtDesc" cols="30" rows="5" required placeholder="Enter description here..."></textarea>
            </div>
        </td>
      </tr>
      
      <tr>
        <td valign="top" align="left"><div class="left" style=" margin: 15px 0px 0 15px; border-right: 1px ridge #999;">Attachment:</div></td>
        <td>
        	<div class="left" style=" margin-top: 15px;">
            	<input class="file" type="file" name="file1" id="file1" required />
            </div>
        </td>
      </tr>
      
      <tr>
        <td valign="top" align="left"><div class="left" style=" margin: 15px 0px 0 15px; border-right: 1px ridge #999;">Price:</div></td>
        <td>
        	<div class="left">
            	<input class="InputBox" type="text" name="txtPrice" placeholder="Insert Price here..." onkeypress="return isNumberKey(event)" required />
            </div>
        </td>
      </tr>
      
      <tr>
        <td colspan="2">
        	<div class="left" style=" padding: 10px 5px; margin-top:20px; margin-bottom: 15px; margin-left: 15px; margin-right: 15px; border-top: 1px solid #999;">
            	<input type="submit" name="cmdAddNewFile" value="Add New File" />
            </div>
        </td>
      </tr>
    </table>
    <!-- CONTENT HERE -->
</div>
</form>