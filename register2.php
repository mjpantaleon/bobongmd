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

//if button SUBMIT is clicked/detected then
if(	isset(	$_POST['cmdSubmit']))
{
	//declare variables and trim to minimize injection of maliciuos sql
	$FN 	= trim( $_POST[ 'txtFN']);
	$MN 	= trim( $_POST[ 'txtMN']);
	$LN 	= trim( $_POST[ 'txtLN']);
	$FLN 	= $FN.' '.$MN.' '.$LN; 	//sample 'Mark P. Nalulumbay'
	
	$EM 	= trim( $_POST[ 'txtEmail']);
	$CN 	= trim( $_POST[ 'txtCN']);
	$AD 	= trim( $_POST[ 'txtADD']);
	
	$UN 	= trim( $_POST[ 'txtUN']);
	$PW 	= trim( $_POST[ 'txtPW']);			//not encrypted
	$CPW 	= trim( $_POST[ 'txtCPW']);			//not encrypted
	
	$PIC	= "";	//default user image
	
	//$PW 	= trim(md5( $_POST[ 'txtPW']));		//encrypted
	//$CPW 	= trim(md5( $_POST[ 'txtCPW']));	//encrypted
	
	$REL	= trim( $_POST[ 'optREL']);
	
	$Valid	= true;				//default value is true
	
	//if Password didnt match then
	if(	strcmp($PW, $CPW)!=0 )
		{
			$Valid = false;
			$Message = "<span class='error'>Password didn't match.</span>";
		}
	//if Password didnt match then
	
	//Adding new registry
	if($Valid == true)
	{
		//check if User Name already exist
		if(	mysql_num_rows(mysql_query("	SELECT UN FROM user_details WHERE UN = '$UN'	"))	> 0)
		{
			$Message = "<span class='error'>This '<b>User Name</b>' already exist! Please create another '<b>Account</b>'.</span>";
		}
		
		
		else
		{
			//setting up the date
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
			
			
			//INSERT INFOs in user_details table
			$query	= "	INSERT INTO user_details SET UID = '".$UID."', FN = '".$FLN."', EM = '".$EM."', CN = '".$CN."', AD = '".$AD."', UN = '".$UN."',
						PW = '".$PW."', REL = '".$REL."', dateReg = '".$today."', timeReg = '".$hr_s."', UT_ID = '2', PIC = '".$PIC."', ST = '0'	";
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
			
			
			//UNSET VARIABLES
			unset( $_POST[ 'txtFN']);
			unset( $_POST[ 'txtMN']);
			unset( $_POST[ 'txtLN']);
			
			unset( $_POST[ 'txtEmail']);
			unset( $_POST[ 'txtCN']);
			unset( $_POST[ 'txtADD']);
			
			unset( $_POST[ 'txtUN']);
			unset( $_POST[ 'txtPW']);
			unset( $_POST[ 'txtCPW']);
			
			unset( $_POST[ 'optREL']);
		}
		///check if User Name already exist
	}
	//Adding new registry
}
//if button SUBMIT is clicked/detected then

	
?>

<?php include 'header.php'; ?>
<form action="#" method="post">


<td width="75%" valign="top" align="left">
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
			<!-- PROMPT MESSAGE HERE --><?php echo $Message; ?>
         </div>
         
         <!-- LOAD THE MESSAGE BOX HERE -->
         <div id="showLightbox"></div>
         <!-- LOAD THE MESSAGE BOX HERE -->
        
   		 <!-- WILL DISPLAY THE DETAILS ABOUT THE USER -->
         <div id="txtHint" class="left" style=" margin-top: 15px; margin-bottom: 15px;">
         	<!-- YOU TABLE HERE -->
            
            <!-- PERSONNAL INFO SECTION -->
            <div style=' width: 445px; border: 1px solid #666; margin-left: 45px; margin-top: 25px; margin-bottom: 25px; 
            padding-bottom: 15px; padding-left: 15px; box-shadow: 0 1px 5px #999; '>
				<div style='margin-top:-11px; margin-left: 25px; background-color:#FFF; width: 140px;'>&nbsp;Personal Information</div>
                
                 <div class="left">
                    <input type="text" name="txtFN" class="InputBox4" placeholder="First Name" value="<?php echo $FN; ?>" autofocus required />
                    <span class="error">*</span>&nbsp;
                 </div>
                 <div class="left"><span class="s_normal">Example: 'Juan' or 'Juan Manuel'</span></div>
                 
                 <div class="left">
        			<input type="text" name="txtMN" class="InputBox4" placeholder="Middle Innitial" value="<?php echo $MN; ?>" required />
                    <span class="error">*</span>&nbsp;
                 </div>
                 <div class="left"><span class="s_normal">Example: 'I.'</span></div>
                 
                 <div class="left">
                    <input type="text" name="txtLN" class="InputBox4" placeholder="Last Name" value="<?php echo $LN; ?>" required />
                    <span class="error">*</span>&nbsp;
   				 </div>
                 <div class="left"><span class="s_normal">Example: 'Dela Cruz, JR.'</span></div>
                 
                 <div class="left">
                    <input type="email" name="txtEmail" class="InputBox4" placeholder="Email" value="<?php echo $EM; ?>" required />
                    <span class="error">*</span>&nbsp;
   				 </div>
                 <div class="left"><span class="s_normal">Example: 'jmdelacruz@yahoo.com.'</span></div>
                 
                 <div class="left">
                    <input type="text" name="txtCN" class="InputBox4" placeholder="Contact #" value="<?php echo $CN; ?>" required />
                    <span class="error">*</span>&nbsp;
   				 </div>
                 <div class="left"><span class="s_normal">Example: '0906-000-0000'</span></div>
                 
                 <div class="left">
                    <input type="text" name="txtADD" class="InputBox4" placeholder="Address" value="<?php echo $AD; ?>" required />
                    <span class="error">*</span>&nbsp;
   				 </div>
                 <div class="left"><span class="s_normal">Example: 'San Vicente, Sta.Maria, Bulacan'</span></div>
            </div>
            <!-- PERSONNAL INFO SECTION -->
            
            <!-- USER ACCOUNT SECTION -->
             <div style=' width: 445px; border: 1px solid #666; margin-left: 45px; margin-top: 25px; margin-bottom: 25px; 
            padding-bottom: 15px; padding-left: 15px; box-shadow: 0 1px 5px #999; '>
				<div style='margin-top:-11px; margin-left: 25px; background-color:#FFF; width: 100px;'>&nbsp;User Account</div>
             	
                
                <div class="left">
                    <input type="text" name="txtUN" class="InputBox4" placeholder="User Name" value="<?php echo $UN; ?>" required />
                    <span class="error">*</span>&nbsp;
   				 </div>
                 <div class="left"><span class="s_normal">Example: 'juan_27'</span></div>
                 
                 <div class="left">
                    <input type="password" name="txtPW" class="InputBox4" placeholder="Password" value="<?php echo $PW; ?>" required />
                    <span class="error">*</span>&nbsp;
   				 </div>
                 <div class="left"><span class="s_normal">* To make a good password, combine special characters and numbers to your password.</span></div>
                 
                 <div class="left">
                    <input type="password" name="txtCPW" class="InputBox4" placeholder="Confirm Password" value="<?php echo $CPW; ?>" required />
                    <span class="error">*</span>&nbsp;
   				 </div>
                 <div class="left"><span class="s_normal">* Password and Confirm password must be exactly the same.</span></div>
             </div>
             <!-- USER ACCOUNT SECTION -->
             
             
             <!-- HOW ARE YOU RELATED SECTION -->
            <div style=' width: 445px; border: 1px solid #666; margin-left: 45px; margin-top: 25px; margin-bottom: 25px; 
            padding-bottom: 15px; padding-left: 15px; box-shadow: 0 1px 5px #999; '>
            <div style='margin-top:-11px; margin-left: 25px; background-color:#FFF; width: 145px;'>&nbsp;How are you related?</div>
            
                <div class="left" style=" margin-top: 15px; margin-right: 15px;">
                <label class="opt"><input type="radio" name="optREL"value="Student" <?php if($REL == 'Student') echo 'checked'; ?> required />
                &nbsp;&nbsp;Student</label> &nbsp;&nbsp;|&nbsp;&nbsp;
                <label class="opt"><input type="radio" name="optREL" value="Friend" <?php if($REL == 'Friend') echo 'checked'; ?> required />
                &nbsp;&nbsp;Friend</label> &nbsp;&nbsp;|&nbsp;&nbsp;
                <label class="opt"><input type="radio" name="optREL" value="Staff" <?php if($REL == 'Staff') echo 'checked'; ?> required  />
                &nbsp;&nbsp;Staff</label> &nbsp;&nbsp;|&nbsp;&nbsp;
                <label class="opt"><input type="radio" name="optREL" value="Client" <?php if($REL == 'Client') echo 'checked'; ?> required />
                &nbsp;&nbsp;Client</label> &nbsp;&nbsp;|&nbsp;&nbsp;
               
               	</div>
                <div class="left" style=" margin-top: 15px; margin-right: 15px;">
                <label class="opt"><input type="radio" name="optREL" value="Relatives" <?php if($REL == 'Relatives') echo 'checked'; ?> required />
                &nbsp;&nbsp;Relatives</label> &nbsp;&nbsp;|&nbsp;&nbsp;
                <label class="opt"><input type="radio" name="optREL" value="Colleague" <?php if($REL == 'Colleague') echo 'checked'; ?> required />
                &nbsp;&nbsp;Colleague</label> &nbsp;&nbsp;|&nbsp;&nbsp;
                <label class="opt"><input type="radio" name="optREL" value="Acquaintance" <?php if($REL == 'Acquaintance') echo 'checked'; ?> required />
                &nbsp;&nbsp;Acquaintance</label> &nbsp;&nbsp;|&nbsp;&nbsp;
              
                </div>
                <div class="left" style=" margin-top: 15px; margin-right: 15px;">
                <label class="opt"><input type="radio" name="optREL" value="Associate" <?php if($REL == 'Associate') echo 'checked'; ?> required />
                &nbsp;&nbsp;Associate</label> &nbsp;&nbsp;|&nbsp;&nbsp;
                <label class="opt"><input type="radio" name="optREL" value="Mentee" <?php if($REL == 'Mentee') echo 'checked'; ?> required />
                &nbsp;&nbsp;Mentee</label> &nbsp;&nbsp;|&nbsp;&nbsp;
                <label class="opt"><input type="radio" name="optREL" value="Mentor" <?php if($REL == 'Mentor') echo 'checked'; ?> required />
                &nbsp;&nbsp;Mentor</label> &nbsp;&nbsp;|&nbsp;&nbsp;
               
                
                </div>
                
                <div class="left" style=" margin-top: 15px; margin-right: 15px;">
                 <label class="opt"><input type="radio" name="optREL" value="Badminton Buddy" <?php if($REL == 'Badminton Buddy') echo 'checked'; ?> required />
                &nbsp;&nbsp;Badminton Buddy</label> &nbsp;&nbsp;|&nbsp;&nbsp;
                <label class="opt"><input type="radio" name="optREL" value="Classmate" <?php if($REL == 'Classmate') echo 'checked'; ?> required />
                &nbsp;&nbsp;Classmate</label> &nbsp;&nbsp;|&nbsp;&nbsp;
                <label class="opt"><input type="radio" name="optREL" value="Batchmate" <?php if($REL == 'Batchmate') echo 'checked'; ?> required />
                &nbsp;&nbsp;Batchmate</label> &nbsp;&nbsp;|&nbsp;&nbsp;
                </div>
                
                <div class="left" style=" margin-top: 15px; margin-right: 15px;">
                 <label class="opt"><input type="radio" name="optREL" value="Neighbor" <?php if($REL == 'Neighbor') echo 'checked'; ?> required />
                &nbsp;&nbsp;Neighbor</label> &nbsp;&nbsp;|&nbsp;&nbsp;
                <label class="opt"><input type="radio" name="optREL" value="School Mate" <?php if($REL == 'School Mate') echo 'checked'; ?> required />
                &nbsp;&nbsp;School Mate</label>
                </div>
            </div>
            
             <div class="left" style="margin-left: 45px; margin-right: 15px;">
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
<td width="25%" valign="top" align="center" style="border-left:1px solid #999;">
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
    
    <div class="left" style=" margin-top: 1px; margin-left: 2px; margin-right: 2px; border-top: 1px solid #9C6;">
    	<div style="margin-top: 5px; color:#9C3;"></div>
    </div>

    <div style="margin-top: 15px; margin-left: 5px; margin-right: 5px; margin-bottom: 15px;">
        	<div class="left" style=" height: 25px; border: 1px solid #CCC; border-top-left-radius: 0.3em; border-top-right-radius: 0.3em; 
            background-color:#b6e56d">
            	<img src="images/icons/toolbar-icons/help.png"  width="20" height="20" />&nbsp;Latest Updates            
            </div>
            
            <div class="left" style="padding-top: 5px; padding-bottom: 5px; 
            border-left:1px solid #CCC;  border-right:1px solid #CCC; border-bottom: 1px solid #CCC;">
            	<div class="left">
            	<img src="images/icons/toolbar-icons/star.png" width="20" height="20" />&nbsp;
                <a class="button" href="">Blogs, Downloadables and Bussiness tabs is currently work in progress</a>
                </div>
                <div class="right" style="border-bottom:1px solid #CCC; margin-bottom: 5px;"><span class="s_normal">May 21, 2013</span></div>
                
                <div class="right"><a class="button" href="">View All</a></div>
            </div>

     </div>
</td>
<!-- RIGHT NAV -->


</form>
<?php include 'footer.php'; ?>