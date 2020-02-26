<?
	define( "MAIN", 1 );	
	//start session
	session_name( 'bbmdsession' );
	session_start();	
	include "header.php";
?>
<td width="75%" valign="top" align="left">
<div class="left">

	<div id="txtHint" class="left" style=" margin-top: 55px; margin-bottom: 15px;">
    
    	<div>
            <div class="center"><img src="images/icons/toolbar-icons/stop.png" /></div>
            <div class="center"><h5>THIS ACTION IS NOT ALLOWED</h5></div>
            <div>&nbsp;</div>
            <div class="center">
                Please <a href="logout.php">Login</a> to an account with 
                appropriate access privileges to continue.</a>
            </div>
        </div>
    </div>

</div>

</td>






<?php include 'footer.php'; ?>