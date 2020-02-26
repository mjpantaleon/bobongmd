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
	
	###!!!!!!!!!!!!!!!!!!!!!IMPORTANT!!!!!!!!!!!!!!!!!!!!!!!!###
	#OPTION STUDENT ---> came from the student info pop-up
	$SUBJ	= $_POST['cmbSubject'];
	$SECS	= $_POST['txtSecs'];
	$SEMS	= $_POST['cmbSem'];
	$SY		= $_POST['cmbSY'];
	
	#IF OTHER ---> came from the other info pop-up
	$OTHR	= $_POST['cmbREL'];
	###!!!!!!!!!!!!!!!!!!!!!IMPORTANT!!!!!!!!!!!!!!!!!!!!!!!!###
	

//if button SUBMIT is clicked/detected then	
if(	isset(	$_POST['cmdSubmit']))
{
	
	//declare variables and trim to minimize injection of maliciuos sql
	$FN 	= trim( $_POST[ 'txtFN']);
	$MN 	= trim( $_POST[ 'txtMN']);
	$LN 	= trim( $_POST[ 'txtLN']);
	$FLN 	= $FN.' '.$MN.' '.$LN; 				//sample 'Mark P. Nalulumbay'
	
	$EM 	= trim( $_POST[ 'txtEM']);
	$CN 	= trim( $_POST[ 'txtCN']);
	$AD 	= trim( $_POST[ 'txtAD']);
	
	$UN 	= trim( $_POST[ 'txtUN']);
	$PW 	= trim( $_POST[ 'txtPW']);			//not encrypted
	$CPW 	= trim( $_POST[ 'txtCPW']);			//not encrypted
	//$PW 	= trim(md5( $_POST[ 'txtPW']));		//encrypted
	//$CPW 	= trim(md5( $_POST[ 'txtCPW']));	//encrypted
	
	$PIC	= "";	//default user image
	
	//IF STUDENT
	$SUBJ2	= $_POST['txtSUBJ2'];
	$SECS2	= $_POST['txtSECS2'];
	$SEMS2	= $_POST['txtSEMS2'];
	$SY2	= $_POST['txtSY2'];
	
	//IF OTHERS
	$OTHR2	= $_POST['txtOTHR2'];
	
		
	//if Password didnt match then
	if(	strcmp($PW, $CPW)!=0 )
	{
		$Valid = false;
		$Message = "<span class='error'>Password didn't match.</span>";
	}
	//if Password didnt match then
	
		
	///check if User Name already exist||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
	if(	mysql_num_rows(mysql_query("	SELECT UN FROM user_details WHERE UN = '$UN'	"))	> 0)
	{
		$Message = "<span class='error'>This '<b>User</b>' already exist! Please create another '<b>Account</b>'.</span>";
		//CLEAR ALL FIELDS
		$FN = "";
		$MN = "";
		$LN = "";
		
		$EM = "";
		$CN = "";
		$AD = "";
		
		$UN = "";
		$PW = "";
		$CPW = "";
		
		$OPT = "";
	}
	///check if User Name already exist||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
	
	
	///else if this is a NEW user||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
	else
	{
		//setting up the date||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
		$today	= date("F j, Y");	//string/Int format
		$hr_s 	= date("H:i:sa");	//12hrs format
		$cur_Y = date('Y');			//get the current year
		
		//id format
		$limit = 4;		//set the limit into 4
		$sql = "SELECT * FROM user_details ORDER BY UID DESC LIMIT 0,1";	//query serves as our counter
		$result = mysql_query($sql);										//then its passed down to the varibale $result
		$row = mysql_fetch_array($result);									//so that we could access row 'UID' from the table
			$last_id = $row['UID'];
			$last_dgt = substr($last_id, -4);								
			
			$id 			= str_pad((int) $last_dgt,$limit,"0",STR_PAD_LEFT);
			$id				= $id + 1;										//increment by 1
			$id 			= str_pad((int) $id,$limit,"0",STR_PAD_LEFT);
			$UID			= "BBMD".$cur_Y."-".$id;						//BBDM2013-0001
		//id format
		//setting up the date||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
		
		
		###IF STUDENT HAS BEEN DETECTED THEN#####################################################################################################################
		if(isset($_POST['txtSUBJ2']))
		{
			$REL	= 'Student';
			
			#echo $SUBJ2;
			
			//INSERT INFOs in user_details table
			$query	= "	INSERT INTO user_details SET 
						UID 	= '".$UID."', 
						FN 		= '".$FLN."',
						EM 		= '".$EM."', 
						CN 		= '".$CN."', 
						AD 		= '".$AD."', 
						UN 		= '".$UN."',
						PW 		= '".$PW."', 
						REL 	= '".$REL."',
						SUBJ	= '".$SUBJ2."',
						SECS	= '".$SECS2."',
						SEMS	= '".$SEMS2."',
						SY		= '".$SY2."', 
						dateReg = '".$today."', 
						timeReg = '".$hr_s."', 
						UT_ID 	= '2', 
						PIC 	= '".$PIC."', 
						ST 		= '0'	";
			mysql_query($query)
			or die(mysql_error());
			
			
			//INSERT INFOS in the users table
			mysql_query("	INSERT INTO users SET UID = '".$UID."', UN = '".$UN."', PW = '".$PW."', FN = '".$FLN."', UT_ID = '2', ST = '0'	")
			or die(mysql_error());
			
			
			//PROMPT MESSAGE AFTER QUERY IS EXECUTED
			$Message = "	<span class='okb'><img src='images/icons/insert.gif' width='16' height='16' />&nbsp;&nbsp;Request has been submitted! 
			<br>Please wait a moment for your account to be activated.</span> ";
			
			
			//LOG THIS EVENT
			$time_in = date("H:i:sa");							
			$cur_date = date("Y-m-d");
			$cur_timestamp = $cur_date." ".$time_in;
			
			$query = "	INSERT INTO events SET E_TS = '$cur_timestamp', UID = '$UID', E_D = 'New User with ID:[ ".$UID." ] '	";
			mysql_query($query)
			or die(mysql_error());
			
			
			//CLEAR ALL FIELDS
			$FN = "";
			$MN = "";
			$LN = "";
			
			$EM = "";
			$CN = "";
			$AD = "";
			
			$UN = "";
			$PW = "";
			$CPW = "";
			
			$REL = "";
			$OPT = "";
			
			
			//UNSET VARIABLES
			unset( $_POST[ 'txtFN']);
			unset( $_POST[ 'txtMN']);
			unset( $_POST[ 'txtLN']);
			
			unset( $_POST[ 'txtEM']);
			unset( $_POST[ 'txtCN']);
			unset( $_POST[ 'txtAD']);
			
			unset( $_POST[ 'txtUN']);
			unset( $_POST[ 'txtPW']);
			unset( $_POST[ 'txtCPW']);
			
			unset( $_POST[ 'cmbREL']);
			
			unset( $_POST[ 'OptRel']);
			
			
		}
		
		###IF STUDENT HAS BEEN DETECTED THEN########################################################################################################################
		
		
		###IF OTHERS HAS BEEN DETECTED THEN#########################################################################################################################
		elseif(isset($_POST['txtOTHR2']))
		{
			$REL = $OTHR2;
			#IF OTHERS THEN
			
			#echo $OTHR2;
			
			$query	= "	INSERT INTO user_details SET 
						UID 	= '".$UID."', 
						FN 		= '".$FLN."',
						EM 		= '".$EM."', 
						CN 		= '".$CN."', 
						AD 		= '".$AD."', 
						UN 		= '".$UN."',
						PW 		= '".$PW."', 
						REL 	= '".$REL."',
						SUBJ	= 'NULL',
						SECS	= 'NULL',
						SEMS	= 'NULL',
						SY		= 'NULL', 
						dateReg = '".$today."', 
						timeReg = '".$hr_s."', 
						UT_ID 	= '2', 
						PIC 	= '".$PIC."', 
						ST 		= '0'	";
			mysql_query($query)
			or die(mysql_error());
			
			
			//INSERT INFOS in the users table
			mysql_query("	INSERT INTO users SET UID = '".$UID."', UN = '".$UN."', PW = '".$PW."', FN = '".$FLN."', UT_ID = '2', ST = '0'	")
			or die(mysql_error());
			
			
			//PROMPT MESSAGE AFTER QUERY IS EXECUTED
			$Message = "	<span class='okb'><img src='images/icons/insert.gif' width='16' height='16' />&nbsp;&nbsp;Request has been submitted! 
			<br>Please wait a moment for your account to be activated.</span> ";
			
			
			//LOG THIS EVENT
			$time_in = date("H:i:sa");							
			$cur_date = date("Y-m-d");
			$cur_timestamp = $cur_date." ".$time_in;
			
			$query = "	INSERT INTO events SET E_TS = '$cur_timestamp', UID = '$UID', E_D = 'New User with ID:[ ".$UID." ] '	";
			mysql_query($query)
			or die(mysql_error());
			
			
			//CLEAR ALL FIELDS
			$FN = "";
			$MN = "";
			$LN = "";
			
			$EM = "";
			$CN = "";
			$AD = "";
			
			$UN = "";
			$PW = "";
			$CPW = "";
			
			$REL = "";
			$OPT = "";
			
			
			//UNSET VARIABLES
			unset( $_POST[ 'txtFN']);
			unset( $_POST[ 'txtMN']);
			unset( $_POST[ 'txtLN']);
			
			unset( $_POST[ 'txtEM']);
			unset( $_POST[ 'txtCN']);
			unset( $_POST[ 'txtAD']);
			
			unset( $_POST[ 'txtUN']);
			unset( $_POST[ 'txtPW']);
			unset( $_POST[ 'txtCPW']);
			
			unset( $_POST[ 'cmbREL']);
			
			unset( $_POST[ 'OptRel']);
			
		}
		else
			$Message = "<span class='error'>Oppss! <b>'How are related'</b> section is left empty. <br>Please provide all the fields needed before you proceed.</span>";
		###IF OTHERS HAS BEEN DETECTED THEN#########################################################################################################################
		
	}
	///else if this is a NEW user||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
}
//if button SUBMIT is clicked/detected then

	
?>

<?php include 'header.php'; ?>
<form action="#" method="post">


<td width="686" valign="top" align="left">
	<!-- BREAD CRUMBS / SEARCH TOOL -->
	<div class="left" style="height:70px; background-color: #9C6;">
        <div class="left" style="border-bottom: 1px solid #CCC; height:68px; margin-right: 5px;">
        <span class="okw"><img src="images/icons/Users.png" width="20" height="20" style="margin-top: 5px;" />&nbsp;REGISTER</span>
            <div class="left" style="margin-left: 25px; margin-top: 10px;"><!--Search by:-->
                *All fields with <span class="error">*</span> is required. Please provide all the necessary fields to complete the registration.
            </div>
        </div>
    </div>
    <!-- BREAD CRUMBS / SEARCH TOOL -->
    
    
    <!-- WILL DISPLAY THE CONTENT-->
    <div class="center" style="margin-top: 15px; margin-right: 5px; width: 97%;">
    
    	 <div class="left" style=" border-top-left-radius: 0.3em; border-top-right-radius: 0.3em; ">
             <div class="header_arrow">
                <span class="left">
                <img src="images/icons/toolbar-icons/user.png" width="20" height="20" style="margin-top: 2px;" />&nbsp;User Registration</span><span class="arrow">
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
				<div class="infoFloat">How are you related?</div>
				<div class="left">
					<label>
						<input type="radio" name="OptRel" <?php if($OPT == 'Student') echo 'checked' ?> value="Student" onclick="showFieldBox()" />
						&nbsp;Student
					</label>
					&nbsp;&nbsp;&nbsp;&nbsp;
					<label>
						<input type="radio" name="OptRel" <?php if($OPT == 'Others') echo 'checked' ?> value="Others" onclick="showOtherField()" />
						&nbsp;Others
					</label>
				</div>
				
				<?php
				#THE ADD INFO BUTTON MUST BE DETECTED FIRST BEFORE WE DISPLAY THIS PART
				if(isset($_POST['cmdAddInfo']))
				{
					$StudDetails = "
					
					<div style='padding: 3px; margin-top: 3px;'>
						<span class='s_normal'>You are bobong's </span> Student
					</div>
					
					<div style='padding: 3px; margin-top: 3px;'>
						Subject: <span style='margin: 0 5px 0 25px;'><input type='text' class='InputBox7' name='txtSUBJ2' value='".$SUBJ."' readonly='' /></span>
					</div>
					
					<div style='padding: 3px; margin-top: 3px;'>
						Section: <span style='margin: 0 5px 0 28px;'><input type='text' class='InputBox7' name='txtSECS2' value='".$SECS."' readonly='' /></span>
					</div>
					
					<div style='padding: 3px; margin-top: 3px;'>
						Semester: <span style='margin: 0 5px 0 18px;'><input type='text' class='InputBox7' name='txtSEMS2' value='".$SEMS."' readonly='' /></span>
					</div>
					
					<div style='padding: 3px; margin-top: 3px;'>
						School year: <span style='margin: 0 5px 0 5px;'><input type='text' class='InputBox7' name='txtSY2' value='".$SY."' readonly='' /></span>
					</div>
					";
					
					echo $StudDetails;
				}
				
				if(isset($_POST['cmdAddOther']))
				{
					$otherDetails = "
					<div style='padding: 3px; margin-top: 3px;'>
						 <span class='s_normal'>You are bobong's </span>
						 <span style='margin: 0 5px;'><input type='text' class='InputBox7' name='txtOTHR2' value='".$OTHR."' readonly='' /></span>
					</div>
					";
					
					echo $otherDetails;
				}
				
				#THE ADD INFO BUTTON MUST BE DETECTED FIRST BEFORE WE DISPLAY THIS PART
				?>
	
			</div>
			
            <div class="RegisterWrap">
            	<div class="infoFloat">Personal Information</div>
                
            	<table width="100%" border="0" cellspacing="0" cellpadding="0">
                  <tr>
                    <td class="regisCaption">First Name:</td>
                    <td class="left">
                        <input type="text" class="InputBox7" name="txtFN" required placeholder="Enter your first name here..." 
                        value="<?php echo $FN; ?>" />
                        <span class="error">*</span>
                    </td>
                  </tr>
                  
                  <tr>
                    <td class="regisNote">&nbsp;</td>
                    <td class="regisNote">&nbsp;Sample: 'Juan Marco' or 'Juan'</td>
                  </tr>
                  
                  <tr>
                    <td class="regisCaption">Middle Innitial:</td>
                    <td class="left">
                    	<input type="text" class="InputBox7" name="txtMN" required placeholder="Enter your middle innitial here..." value="<?php echo $MN; ?>" />
                        <span class="error">*</span>
                    </td>
                  </tr>
                  
                  <tr>
                    <td class="regisNote">&nbsp;</td>
                    <td class="regisNote">&nbsp;Sample: 'A.'</td>
                  </tr>
                  
                  
                  <tr>
                    <td class="regisCaption">Last Name:</td>
                    <td class="left">
                    	<input type="text" class="InputBox7" name="txtLN" required placeholder="Enter your last name here..." value="<?php echo $LN; ?>" />
                        <span class="error">*</span>
                    </td>
                  </tr>
                  
                  <tr>
                    <td class="regisNote">&nbsp;</td>
                    <td class="regisNote">&nbsp;Sample: 'Bautista'</td>
                  </tr>
                  
                  
                   <tr>
                    <td class="regisCaption">Email Address:</td>
                    <td class="left">
                    	<input type="email" class="InputBox7" name="txtEM" required placeholder="Enter your email here..." value="<?php echo $EM; ?>" />
                        <span class="error">*</span>
                    </td>
                  </tr>
                  
                  <tr>
                    <td class="regisNote">&nbsp;</td>
                    <td class="regisNote">&nbsp;Sample: 'juanmarco@gmail.com'</td>
                  </tr>
                  
                   <tr>
                    <td class="regisCaption">Contact Number:</td>
                    <td class="left">
                    	<input type="text" class="InputBox7" name="txtCN" required placeholder="Enter your contact number here..." value="<?php echo $CN; ?>"
                        onkeypress="return isNumberKey(event)" />
                        <span class="error">*</span>
                    </td>
                  </tr>
                  
                  <tr>
                    <td class="regisNote">&nbsp;</td>
                    <td class="regisNote">&nbsp;Sample: 'juanmarco@gmail.com'</td>
                  </tr>
                  
                  
                   <tr>
                    <td valign="top" align="left" class="regisCaption">Address:</td>
                    <td class="left">
                    	<textarea name="txtAD" class="textArea2" placeholder="Type your address here..." 
                        required rows="5" cols="26"><?php echo $AD; ?></textarea>
                        <span class="error">*</span>
                    </td>
                  </tr>
                  
                  <tr>
                    <td class="regisNote">&nbsp;</td>
                    <td class="regisNote">&nbsp;Sample: 'Rizal Ave., Sta.Cruz, Manila'</td>
                  </tr>
                  
                  
                  
                </table>	
            </div>
            
            <div class="RegisterWrap">
            	<div class="infoFloat">User Account</div>
                <table width="100%" border="0" cellspacing="0" cellpadding="0">
                    <tr>
                        <td class="regisCaption">User Name:</td>
                        <td class="left">
                            <input type="text" class="InputBox7" name="txtUN" required placeholder="Enter your user name here..." value="<?php echo $UN; ?>" />
                            <span class="error">*</span>
                        </td>
                    </tr>
                    
                    <tr>
                        <td class="regisNote">&nbsp;</td>
                        <td class="regisNote">&nbsp;Sample: 'juanmarco27'</td>
                    </tr>
                    
                     <tr>
                        <td class="regisCaption">Password:</td>
                        <td class="left">
                            <input type="password" class="InputBox7" name="txtPW" required placeholder="Enter your password here..." value="<?php echo $PW; ?>" />
                            <span class="error">*</span>
                        </td>
                    </tr>
                    
                    <tr>
                        <td class="regisNote">&nbsp;</td>
                        <td class="regisNote">* To make a good password, combine special characters and numbers to your password.</td>
                    </tr>
                    
                     <tr>
                        <td class="regisCaption">Confirm Password:</td>
                        <td class="left">
                            <input type="password" class="InputBox7" name="txtCPW" required placeholder="Re-enter your password here..." 
                            value="<?php echo $CPW; ?>" />
                            <span class="error">*</span>
                        </td>
                    </tr>
                    
                    <tr>
                        <td class="regisNote">&nbsp;</td>
                        <td class="regisNote">* Password and Confirm password must be exactly the same.</td>
                    </tr>
                </table>
            </div>
            
           
			
			<div class="left" style="margin: 5px 0 0 45px; padding: 5px 0; border-top: 1px solid #CCC;">
				<input type="submit" name="cmdSubmit" class="Submit" value="Submit" />
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