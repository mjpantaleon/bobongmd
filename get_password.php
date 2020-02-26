<?php
/** Set flag that this is a parent file */
define( "MAIN", 1 );


//require connection to the database
require('db_con.php');
log_hit();

//gets the page name
$page = mysql_real_escape_string(basename($_SERVER['SCRIPT_NAME']));


	//default value
	$Count 		= 0;
	//default value empty
	$Message = '';
	
	
	#POST VALUES
	$EM		= $_POST['txtEM'];
	
	#IF SUBMIT BUTTON HAS BEEN CLICKED/DETECTED THEN
	if(isset($_POST['cmdSubmit']))
	{
		#CHECKING FOR THE RIGHT EMAIL FORMAT
		#if email does not match with the proper email format then
		if(!preg_match("/([\w\-]+\@[\w\-]+\.[\w\-]+)/",$EM))
		{
			#prompt an error message
			$Message	= "<span class='error'>Oppps! This not a valid email address.</span>";
		}
		#if email does not mathc with the proper email format then
		
		#else if email is in proper format then
		else
		{
			#!!!!!!!!!!!!!!!!!!!!!!!!!!! MUST CHECK IF THIS EMAIL IS IN THE DATABASE !!!!!!!!!!!!!!!!!!!!!!!!!!!#
			if(mysql_num_rows(mysql_query(' SELECT EM FROM user_details WHERE EM = "'.$EM.'" ')) > 0)
			{
				#QUERY
				$query	= ' SELECT PW FROM user_details WHERE EM = "'.$EM.'" ';
				$result	= mysql_query($query);
				$row	= mysql_fetch_array($result);
				$r_PW	= $row['PW'];
				
				#LOCAL VARIABLES
				$PW		= $r_PW;								#Users Password
				$BBMD		= 'http://bobongmd.com';				  		#bobongmd site
				$To		= $EM;									#inputed email
				$Subject	= 'Forgot password request (BobongMD)';					#Subject
				$Headers	= 'From: bobongmd@yahoo.com';						#Sender
				$Msg		= '	Good Day! A request has been sent to us and we have confirmed and retrieved this password ( '.$PW.' ). Please do not forget your password next time. Try to login '.$BBMD.' using your password. Thank you! ';
				
				#MAIL TO THE INPUTED EMAIL ADDRESS
				mail($To, $Subject, $Msg, $Headers);
				
				#PROMT SUCCESS MESSAGE
				$Message	= "<span class='okb'>
					<img src='images/icons/insert.gif' width='16' height='16' />&nbsp;Details of your request for 'forgot password' has been sent to your email.
				</span>";
			}
			
			else
				$Message	= "<span class='error'>
					<img src='images/icons/x.png' width='16' height='16' />&nbsp;It seems that the email you entered is NOT registered to this site.
				</span>";
			#!!!!!!!!!!!!!!!!!!!!!!!!!!! MUST CHECK IF THIS EMAIL IS IN THE DATABASE !!!!!!!!!!!!!!!!!!!!!!!!!!!#
		}
		#else if email is in proper format then
		#CHECKING FOR THE RIGHT EMAIL FORMAT	
	}
	#IF SUBMIT BUTTON HAS BEEN CLICKED/DETECTED THEN
?>

<?php include 'header.php'; ?>
<form action="#" method="post">


<td width="686" valign="top" align="left">
	<!-- BREAD CRUMBS / SEARCH TOOL -->
	<div class="left" style="height:70px; background-color: #9C6;">
        <div class="left" style="border-bottom: 1px solid #CCC; height:68px; margin-right: 5px;">
        <span class="okw"><img src="images/icons/toolbar-icons/lock.png" width="20" height="20" style="margin-top: 5px;" />&nbsp;FORGOT PASSWORD</span>
            <div class="left" style="margin-left: 25px; margin-top: 10px;"><!--Search by:-->
                *All fields with <span class="error">*</span> is required. Please provide all the necessary fields.
            </div>
        </div>
    </div>
    <!-- BREAD CRUMBS / SEARCH TOOL -->
    
    
    <!-- WILL DISPLAY THE CONTENT-->
    <div class="center" style="margin-top: 15px; margin-right: 5px; width: 97%;">
    
    	 <div class="left" style=" border-top-left-radius: 0.3em; border-top-right-radius: 0.3em; ">
             <div class="header_arrow">
                <span class="left">
                <img src="images/icons/toolbar-icons/lock.png" width="20" height="20" style="margin-top: 2px;" />&nbsp;Forgot Password</span><span class="arrow">
                </span>
             </div>
         </div>
         
         <div class="center" style="margin-top: 15px; margin-bottom: 15px;">
			<!-- PROMPT MESSAGE HERE -->&nbsp;<?php echo $Message; ?>
         </div>
         
         <!-- LOAD THE MESSAGE BOX HERE -->
         <div id="showLightbox"></div>
         <!-- LOAD THE MESSAGE BOX HERE -->
        
   		 <!-- WILL DISPLAY THE DETAILS ABOUT THE USER -->
		<div id="txtHint" class="left" style=" margin-top: 15px; margin-bottom: 15px;">
		<!-- YOU TABLE HERE -->
		
			<div class="RegisterWrap">
				<div style="margin: -25px 5px 5px 5px; padding: 5px 10px; border: 1px dashed #999; font-weight: bold; background: #FFFFCC; width: 200px;">
				Provide your email
				</div>
				
				<div class="left">
					<div class="s_normalb">&nbsp;</div>
				</div>
				
				<div class="left">
					<input type="text" class="InputBox7" name="txtEM" value="" required placeholder="Enter your email here.." />
					<div class="s_normal">HINT: Use the email that you used during registration.</div>
				</div>
				
				<div class="left">
					<div class="s_normalb">&nbsp;</div>
				</div>
		
			</div>
			
			<div style=" margin: 0 55px;">
				<input type="submit" name="cmdSubmit" value="SUBMIT REQUEST" />
			</div>
		<!-- YOU TABLE HERE -->
		</div>	 
         <!-- WILL DISPLAY THE DETAILS ABOUT THE USER -->
    </div>
    
    <div class="center" style="margin-top: 15px; margin-bottom: 15px; "><?php echo $paginationDisplay; ?></div>
    
    <!-- WILL DISPLAY THE CONTENT-->
</td>





<!-- RIGHT NAV -->
<td width="224" valign="top" align="center" style="border-left:1px solid #999;">
	<!-- DISPLAY PICTURE OF USER THAT LOGGED IN AND DETAILS -->
	<div class="right" style="height: 68px;  background-color: #9C6; border-top-right-radius: 0.3em;">
    	<table width="100%" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td>
            <div style=" margin-top: 5px; margin-left: 25px;">
            	<?php include('followlinks.php'); ?>
            </div>
            </td>
            <td><div style=" margin-top: 5px;"><span class="okb">&nbsp;</span></div></td>
          </tr>
        </table>	
    </div>
    <!-- DISPLAY PICTURE OF USER THAT LOGGED IN AND DETAILS -->
    
   <div style="margin-top: 15px; margin-left: 5px; margin-right: 5px; margin-bottom: 15px;">
        	<div class="left" style=" height: 25px; border: 1px solid #CCC; border-top-left-radius: 0.3em; border-top-right-radius: 0.3em; 
            background-color:#b6e56d">
            	<img src="images/icons/toolbar-icons/help.png"  width="20" height="20" />&nbsp;Latest Updates            
            </div>
            
            <div class="left" style="padding-top: 5px; padding-bottom: 5px; 
            border-left:1px solid #CCC;  border-right:1px solid #CCC; border-bottom: 1px solid #CCC;">
            	<div class="left">
            	<img src="images/icons/toolbar-icons/star.png" width="20" height="20" />&nbsp;
                <a class="button" href="">All features of the site has been successfully updated.</a>
                </div>
                <div class="right" style="border-bottom:1px solid #CCC; margin-bottom: 5px;"><span class="s_normal">January 28, 2014</span></div>
                
                <div class="right"><a class="button" href="">View All</a></div>
            </div>

     </div>
</td>
<!-- RIGHT NAV -->


</form>
<?php include 'footer.php'; ?>