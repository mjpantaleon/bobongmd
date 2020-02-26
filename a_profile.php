<?php
//set flag that this is a parent file
define( 'MAIN', 1);

//require connection to the database
require('db_con.php');
log_hit();
?>


<?php include 'header.php'; ?>

<td width="75%" valign="top" align="left">
	<div class="left" style="margin-top: 15px; margin-left: 15px; margin-right: 15px; height:55px; border-bottom: 1px solid #CCC;">
    <span class="okg2">PROFILE</span>
    <div class="left" style="margin-left: 25px; margin-top: 15px;"><span class="okg">NAME</span>: Pedrito "Bobong" Yabot Tagayuna</div>
    </div>
    
    <div class="left" style=" margin-top: 15px; margin-left:40px; margin-right: 15px;"><img src="#" width="175px" height="155px" /></div>
    
    <div class="left" style=" margin-top: 15px; margin-left: 40px; margin-right: 15px; margin-bottom: 35px; 
    border-top: 1px solid #CCC;"><span class="okg">AFFILIATIONS:</span>
    	<ul>
        	<li style=" margin-left: 15px;">Pathologist - Makati Medical Center (2012-up to present)</li>
            <li style=" margin-left: 15px;">Pathologist - National Voluntary Blood Services Program (1999-up to present)</li>
            <li style=" margin-left: 15px;">Information Tech. Head - Information Management Unit (1999-up to present)</li>
        </ul>
    </div>
    
    <div class="left" style=" margin-top: 15px; margin-left: 40px; margin-right: 15px; margin-bottom: 35px;
    border-top: 1px solid #CCC;"><span class="okg">EXPERTISE:</span>
    	<ul>
        	<li style=" margin-left: 15px;">Heart by Pass</li>
            <li style=" margin-left: 15px;">Surgery</li>
            <li style=" margin-left: 15px;">Eye Transplant</li>
        </ul>
    </div>
    
    <div class="left" style=" margin-top: 15px; margin-left: 40px; margin-right: 15px; margin-bottom: 35px; 
    border-top: 1px solid #CCC;"><span class="okg">CURRENT TOWN:</span>
    	<ul>
        	<li style="margin-left: 15px;">Taguig City, Philippines</li>
        </ul>
    </div>
    
    <div class="left" style=" margin-top: 15px; margin-left: 40px; margin-right: 15px; margin-bottom: 35px; 
    border-top: 1px solid #CCC;"><span class="okg">CONTACT ME:</span>
    	<ul>
        	<li style="margin-left: 15px;">bobongmd@yahoo.com</li>
        </ul>
    </div>
    
</td>


<td width="25%" valign="top" align="center" style="border-left:1px solid #CCC;"><?php include 'followlinks.php'; ?></td>


<?php include 'footer.php'; ?>