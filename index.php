<?php
//set flag that this is a parent file
define( 'MAIN', 1);

//require connection to the database
require('db_con.php');
log_hit();


//gets the page name
$page = mysql_real_escape_string(basename($_SERVER['SCRIPT_NAME']));

//start session
/*session_name( 'bbmdsession' );
session_start();
$FN 	= $_SESSION['fullname'];
$UT_ID 	= $_SESSION['access'];
$UID 	= $_SESSION['session_user_id'];
$UT_N 	= $_SESSION['access_type'];
$PIC	= $_SESSION['prof_pic'];
$UN		= $_SESSION['session_username'];*/


//set default equal to empty
$Message = "";

//if button is clicked or detected then
if( isset( $_POST['cmdLogin']))
{
	//escape and trim to minimize injection of malicious sql
	 $UN = trim( $_POST['txtUN']);
	 $PW = trim( $_POST['txtPW']);
	 
	 //if Username and password has a value then
	 if( $UN&&$PW )
	 {
		 $query = " SELECT * FROM users WHERE UN = '$UN' ";
		 $result = mysql_query($query);
		 while( $row = mysql_fetch_array($result) )
		 {
			 $UID 	= $row['UID'];		//user id
			 $UN	= $row['UN'];		//user name
			 $DB_PW = $row['PW'];		//password
			 $FN	= $row['FN'];		//fullname
			 $UT_ID = $row['UT_ID'];	//user level
			 $ST 	= $row['ST'];		//status
			
			 //ENCRYPTED
			 //if( md5($PW) == $DB_PW )
			 //	$loginok = true;	//then we set a varible to hold a true value
			 
			//check if the password inputed is the same with the password in the database
			if( $PW == $DB_PW )
				$loginok = true;	//then we set a varible to hold a true value
			
			//else if password inputed is not same
			else
				$loginok = false;	//then we set a varible to hold a false value
			
			
			//check if the password inputed is the same with the password in the database
			if($loginok == true)
			{
				
				//check if the status of user loggingin is active
				if($ST == 1)
				{
					//get the user type
					$query 	= "SELECT UT_N FROM user_types WHERE UT_ID = '$UT_ID' ";
					$result = mysql_query($query);
					$row 	= mysql_fetch_array($result);
					$UT_N 	= $row['UT_N'];
					
					//get the profile picture of the user
					$query 	= " SELECT PIC FROM user_details WHERE UN = '$UN' ";
					$result = mysql_query($query);
					$row	= mysql_fetch_array($result);
					$PIC	= $row['PIC'];
					
					//start session
					session_name( 'bbmdsession' );
					session_start();
					$_SESSION['session_id'] 		= $session_id;
					$_SESSION['session_user_id']	= $UID;
					$_SESSION['session_username']	= $UN;
					$_SESSION['password']			= $PW;
					$_SESSION['fullname']			= $FN;
					$_SESSION['access']				= $UT_ID;
					$_SESSION['access_type']		= $UT_N;
					$_SESSION['prof_pic']			= $PIC;
					session_write_close();	
					//$Message = "<span class='okb'>You are a valid user.</span>";
					
					//LOG THIS EVENT
					$time_in = date("H:i:sa");							
					$cur_date = date("Y-m-d");
					$cur_timestamp = $cur_date." ".$time_in;
					
					$query = "	INSERT INTO events SET E_TS = '$cur_timestamp', UID = '$UID', E_D = 'User login:[ ".$UN." ] '	";
					mysql_query($query)
					or die(mysql_error());
					
					unset( $_POST['txtUN']);
					unset( $_POST['txtPW']);
					
						//we the check user rights of the user who logged in
						//if the user loggedin is the admin then
						if($UT_ID == 1)
						{
							//we send him to the admin page
							echo "<script>document.location.href='m_home.php';</script>\n";
							exit();	
						}
						
						//else if the user loggedin is a guest then
						elseif($UT_ID == 2)
						{
							//we send him to the geust page
							echo "<script>document.location.href='g_home.php';</script>\n";
							exit();
						}
						
						//else if not valid user then
						else
						{
							//we send him to the unautorized page
							echo "<script>document.location.href='unauthorized.php';</script>\n";
							exit();
						}
						//we the check user rights of the user who logged in
				}
				//check if the status of user loggingin is active
				
				
				//else if status of users loggingin is NOT active then
				else
				{
					$Message = "<span class='error'>Your account is still pending for approval.</span>";
					
					//LOG THIS EVENT
					$time_in = date("H:i:sa");							
					$cur_date = date("Y-m-d");
					$cur_timestamp = $cur_date." ".$time_in;
					
					$query = "	INSERT INTO events SET E_TS = '$cur_timestamp', UID = '$UID', E_D = 'Invalid Login. User for approval:[ ".$UN." ] '	";
					mysql_query($query)
					or die(mysql_error());
				}
				//else if status of users loggingin is NOT active then
			}
			//check if the password inputed is the same with the password in the database
			
			//else if the password inputed is NOT the same with in the database
			else
			{
				$Message = "<span class='error'>Incorrect username/password. <br>Please try again later.</span>";
				
				//LOG THIS EVENT
				$time_in = date("H:i:sa");							
				$cur_date = date("Y-m-d");
				$cur_timestamp = $cur_date." ".$time_in;
				
				$query = "	INSERT INTO events SET E_TS = '$cur_timestamp', UID = '0', E_D = 'Invalid login:[ ".$UN." ] [".$PW."] '	";
				mysql_query($query)
				or die(mysql_error());
			}
			//check if the password inputed is the same with the password in the database
		 }
		 //end while
	 }
	 //if Username and password has a value then
	 
	 //else if Username and Password is empty then
	 else
		die();
}
//if button is clicked or detected then
?>


<?php include 'header.php'; ?>

<!-- CONTENT HERE -->
<td width="75%" valign="top" align="left">
	<!-- BREAD CRUMBS / SEARCH TOOL -->
	<div class="left" style="height:70px; background-color: #9C6;">
        <div class="left" style="border-bottom: 1px solid #CCC; height:68px; margin-right: 5px;">
        <span class="okw"><img src="images/icons/toolbar-icons/home.png" width="20" height="20" style="margin-top: 5px;" />&nbsp;Home</span>
            <div class="left" style="margin:0 15px;">
                <span class="a">
                 Welcome! This for all the people who know Bobong. Feel Free to browse the contents of this site. Please leave a message or register.
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
                    <img src="images/icons/Info-icon.png" width="20" height="20" style="margin-top: 2px;" />&nbsp;
                    BobongMD
                </span>
                    
               	<span class="arrow">
                </span>
             </div>
         </div>
         
         <div class="center" style="margin-top: 15px; margin-bottom: 15px;">
			<!-- PROMPT MESSAGE HERE -->
         </div>
         
         <!-- LOAD THE MESSAGE BOX HERE -->
         <div id="showLightbox"></div>
         <!-- LOAD THE MESSAGE BOX HERE -->
        
   		 <!-- WILL DISPLAY THE DETAILS ABOUT THE USER -->
         <div id="txtHint" class="left" style=" margin-top: 15px; margin-bottom: 15px;">
         	<!-- YOU TABLE HERE -->
            <div class="recentWorkWrapper">
            <table width="100%" border="0" cellspacing="0" cellpadding="0">
            
            <tr>
                <td colspan="2" align="left" valign="top">
                	<h3>Featured web sites</h3>
                </td>
            </tr>
            
            <?php
			//DISPLAY FEATURED WEB SITES HERE
			
			$query	= " SELECT * FROM `feat_site` WHERE ST = '1' ORDER BY id ASC ";
			$result	= mysql_query($query)
			or die(mysql_error());
			while($row	= mysql_fetch_array($result))
			{
				//intiate variables
				$IMG	= $row['IMG'];		//Image that will be shown in the page
				$SITE	= $row['SITE'];		//Site name
				$DESC	= $row['DESC'];		//Description of the site
				$LINK	= $row['LINK'];		//Link 
				$ST		= $row['ST'];		//Status
				
				$divFeatSite="
				<tr>
					<td valign='top' align='left' width='240'>
						<div class='recentWorkPic'>
							<a href='".$LINK."' target='_blank'>
								<img src='".$IMG."' width='238' height='106' alt='Image 1' title='View this page' />
							</a>
						</div>
					</td>
					<td valign='top' align='left'>
						<div class='left' style='margin-top: 15px; margin-left: 15px; padding-right: 15px;'> 
							<h5>".$SITE."</h5>
							<p style='margin-top: 5px; margin-left: 5px;'>
							".substr($DESC, 0, 120)." ...
							</p>
						</div>
						<div class='right' style=' margin-left: 15px; margin-right: 15px;'>
							<a href='".$LINK."' target='_blank'>read more</a>
						</div>
					</td>
				</tr>
				
				
				";
				
				echo $divFeatSite;
			}
			//end while
			
			?>
            
           
            </table>

            </div>
            
            <div class="adverticements">
            	<div class="col_left">
                	<h5 class="cart_icon">Find Great deals!</h5>
                    
                    <p style="margin-top: 5px; margin-left: 5px; height: 93px;">
                    Looking for gadgets, laptops, phones and other stuffs. Just hit the links below and I'm sure you gona find a good deal here.
                    </p>
                    
                    <div class="list">
                    	<li><img src="images/btn/templatemo_list.png" width="17" height="14" />&nbsp;<a href="business.php">Bussiness</a></li>
                        <li><img src="images/btn/templatemo_list.png" width="17" height="14" />&nbsp;<a href="">Latest Updates</a></li>
                    </div>
                </div>
                <div class="col_right">
                	<h5 class="new_icon">Check this out!</h5>
                    <p style="margin-top: 5px; margin-left: 5px;">
                    Unlike in my other web sites, <a href="#">BobongMD</a> is unique in a way that people who know me can have access in this site.
                    Just hit the links below to see the other contents.
                    </p>
                    
                     <div class="list">
                     	<li><img src="images/btn/templatemo_list.png" width="17" height="14" />&nbsp;<a href="register.php">Register</a></li>
                    	<li><img src="images/btn/templatemo_list.png" width="17" height="14" />&nbsp;<a href="a_teacherSec.php">Teachers Section</a></li>
                        <li><img src="images/btn/templatemo_list.png" width="17" height="14" />&nbsp;<a href="blogs.php">Blogs</a></li>
                    </div>
                </div>
            </div>
            
            
            <!-- PAGE HIT HERE -->
            <div class="page_hits">
            	<div class="col_left">
                	<h5>Page View</h5>
                    <p style="margin-top: 5px; margin-left: 5px;">
                    <?php
					
					$query	= " SELECT hits FROM hits WHERE page_name = '".$page."' ";
					$result	= mysql_query($query);
					$row	= mysql_fetch_array($result);
					$page_hits	= $row['hits'];
					
                    echo $page_hits;
					
					?>
                    </p>
                </div>
                
                <div class="col_right">
                	<h5>Last Update</h5>
                    <p style="margin-top: 5px; margin-left: 5px;">January 28, 2014</p>
                </div>
            </div>
            
        	<!-- YOU TABLE HERE -->
         </div>
         <!-- WILL DISPLAY THE DETAILS ABOUT THE USER -->
    </div>
    
    <div class="center" style="margin-top: 15px; margin-bottom: 15px; "><?php #echo $paginationDisplay; ?></div>
    
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
    
    <div style="margin-top: 15px; margin-left: 5px; margin-right: 5px;">
        	<div class="left" style=" height: 25px; border: 1px solid #CCC; border-top-left-radius: 0.3em; border-top-right-radius: 0.3em; 
            background-color:#b6e56d">
            	<img src="images/icons/toolbar-icons/user.png" width="16" height="16" />&nbsp;User Login
				<span style="float:right; padding: 5px 3px;"><a href="get_password.php">Forgot password?</a></span>
            </div>
            
        	<div class="left" style="padding: 5px; 
            border-left:1px solid #CCC;  border-right:1px solid #CCC; border-bottom: 1px solid #CCC;">
            	<?php include 'login.php'; ?><?php echo $Message; ?>
				
				
            </div>
           
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
                <a class="button" href="">All features of the site has been successfully updated.</a>
                </div>
                <div class="right" style="border-bottom:1px solid #CCC; margin-bottom: 5px;"><span class="s_normal">January 28, 2014</span></div>
                
                <div class="right"><a class="button" href="">View All</a></div>
            </div>

     </div>
</td>


<!-- CONTENT HERE -->

<?php include'footer.php'; ?>