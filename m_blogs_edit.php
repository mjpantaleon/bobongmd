<?php
/** Set flag that this is a parent file */
define( "MAIN", 1 );


//require connection to the database
require('db_con.php');
log_hit();

//gets the page name
$page = mysql_real_escape_string(basename($_SERVER['SCRIPT_NAME']));


//start session
session_name( 'bbmdsession' );
session_start();
$FN 	= $_SESSION['fullname'];
$UT_ID 	= $_SESSION['access'];
$UID	= $_SESSION['session_user_id'];
$UT_N 	= $_SESSION['access_type'];
$PIC	= $_SESSION['prof_pic'];
$UN		= $_SESSION['session_username'];

/**/
//LOG THIS EVENT
$time_in = date("H:i:sa");							
$cur_date = date("Y-m-d");
$cur_timestamp = $cur_date." ".$time_in;	//concatinate time + current date

$query = "	INSERT INTO events SET E_TS = '$cur_timestamp', UID = '$UID', E_D = '[ ".$FN." ] At page: [ ".$page." ]'	";
mysql_query($query)
or die(mysql_error());



//if user dont have access then redirect then to unautorized page
if( ($UT_ID!=1) && ($UT_ID!=2) && ($UID =='') )
{
	echo "<script>document.location.href='unauthorized.php';</script>\n";
	exit();
}

$Count 		= 0;


		##################################################################################################################################################
		
		#IF USER UPDATE HIS BLOG AND CHANGE/ADD A PICTURE ATTACHMENT

		//Initiate Local Variables
		$getBlogID	= $_GET['blogID'];
		
		//GET THE PATH/LINK OF THIS BLOG WHOS ID IS EQUAL TO THE SELECTED BLOG
		$query	= " SELECT LINK FROM blogs WHERE id = '".$getBlogID."' ";
		$result = mysql_query($query);
		$row	= mysql_fetch_array($result);
		//Initialize local variable
		$r_LINK = $row['LINK'];		//LINK or PATH of the picture * this will be use to delete the existing picture and be replace by the new upload
		$oldPic	= $r_LINK;
		
		#CHECK FIRST IF THE FILE IS DETECTED
		//if file upload is detected then
		if(isset($_POST['cmdUploadNew']))
		{
			
			#CHECK IF THERES NO ERROR WITH THE FILE UPLOAD
			if($_FILES['uPic']['error'] <= 0)
			{
				//File upload attributes
				$FILE	= $_FILES['uPic']['name'];
				$Ftemp	= $_FILES['uPic']['tmp_name'];
				$Path 	= "blog_images/".$FILE;					//path to upload / the name of the file
				
				#UNLINK FIRST THE CURRENT PICTURE ATTACHMENT
				//THIS WILL CHECK IF THERE IS A PICTURE ATTACHED TO THIS BLOG
				//If there no picture attached then
				if($oldPic == '')		
				{
					#there will be no execution of unlink
				}
				
				//else if theres picture attached then
				else
				{
					unlink($oldPic);		//will remove the current picture attachment in the folder and in the database
				}
				
				#THEN UPLOAD NEW PICTURE ATTACHMENT
				mysql_query(" UPDATE blogs SET LINK = '".$Path."' WHERE id = '".$getBlogID."' ")
				or die(mysql_error());
				move_uploaded_file($Ftemp, $Path);	//code structure for moving this file
				
				
				#LOG THIS EVENT
				$time_in = date("H:i:sa");							
				$cur_date = date("Y-m-d");
				$cur_timestamp = $cur_date." ".$time_in;	//concatinate time + current date
				
				$query = "	INSERT INTO events 
							SET E_TS = '$cur_timestamp', UID = '$UID', E_D = '[ ".$FN." ] Uploaded new picture attachment on [".$getBlogID."]'	";
				mysql_query($query)
				or die(mysql_error());
				
				//then prompt message
				
				
				$Message	= "
				<span class='okb'>
					<img src='images/icons/check.jpg' width='20' height='20'>
					Your <b>new picture</b> attachment has been successfully uploaded.
				</span>
				";
			}
			
			else
				#ERROR MESSAGE
				$Message = " 
				<img src='images/icons/x.png' width='20' height='20'>&nbsp;<span class='error'>
				Having problems regarding the file upload...
				";
		}
		
		
		#IF USER UPDATE HIS BLOG AND CHANGE/ADD A PICTURE ATTACHMENT
		
		##################################################################################################################################################
		
		#IF USER ADD NEW BLOGS THEN
		//If Blog Title is detected then
		if(isset( $_POST['txtTitle']))
		{
			//Initiate Local Variables
			$getBlogTitle	= trim($_POST['txtTitle']);
			$getBlogDesc	= trim($_POST['txtDescription']);
			$getFile		= $_POST['fileAttch'];
			$Valid			= true;
			
			
			//simple check
			if($getBlogTitle == '')
				$Valid		= false;
				
			elseif($getBlogDesc	== '')
				$Valid 		= false;
				
				
				
			//All is valid then
			if($Valid == true)
			{
				//setting up the date
				$today	= date("F j, Y");	//string/Int format
				$hr_s 	= date("H:i:sa");	//12hrs format
				
				
				//check first if this file already exists
				if(mysql_num_rows(mysql_query(" SELECT TIT FROM blogs WHERE TIT = '".$getBlogTitle."' ")) > 0)
				{
					$Message = " <img src='images/icons/x.png' width='20' height='20'>&nbsp;<span class='error'>
					This Blog already exist! Please add another Blog. ";
				}
				//check first if this file already exists
				
				//else if there are no duplicate titles then
				else
				{
					//check first if the file has no error
					if($_FILES['fileAttch']['error'] <= 0)
					{
						//File upload attributes
						$FILE	= $_FILES['fileAttch']['name'];
						$Ftemp	= $_FILES['fileAttch']['tmp_name'];
						$Path 	= "blog_images/".$FILE;					//path to upload / the name of the file
						
						//query
						mysql_query (" 	INSERT INTO blogs
									 	SET 
										UID 	= '".$UID."',
										FN		= '".$FN."',
										TIT		= '".$getBlogTitle."',
										DESK	= '".$getBlogDesc."',
										LINK	= '".$Path."',
										dateP	= '".$today."',
										timeP	= '".$hr_s ."',
										B_ST	= '1',
										SHR		= '1'
										
									")
						or die(mysql_error());
						move_uploaded_file($Ftemp, $Path);	//code structure for moving this file
						
						
						//log this event
						$time_in = date("H:i:sa");							
						$cur_date = date("Y-m-d");
						$cur_timestamp = $cur_date." ".$time_in;	//concatinate time + current date
						
						$query = "	INSERT INTO events SET E_TS = '$cur_timestamp', UID = '$UID', E_D = '[ ".$FN." ] Added new Blog: [ ".$getBlogTitle." ]'	";
						mysql_query($query)
						or die(mysql_error());
						
						//unset post variables
						unset($_POST['txtTitle']);
						unset($_POST['txtDescription']);
						unset($_POST['fileAttch']);
						
						
						$Message	= "<span class='okb'><img src='images/icons/check.jpg' width='20' height='20'>New Blog has been added.</span>";
						
					}
					//check first if the file has no error
					
					//if theres no file attachment then
					else
					{
						//query
						mysql_query (" 	INSERT INTO blogs
									 	SET 
										UID 	= '".$UID."',
										FN		= '".$FN."',
										TIT		= '".$getBlogTitle."',
										DESK	= '".$getBlogDesc."',
										LINK	= '',
										dateP	= '".$today."',
										timeP	= '".$hr_s ."',
										B_ST	= '1',
										SHR		= '1'
										
									")
						or die(mysql_error());
						move_uploaded_file($Ftemp, $Path);	//code structure for moving this file
						
						
						//log this event
						$time_in = date("H:i:sa");							
						$cur_date = date("Y-m-d");
						$cur_timestamp = $cur_date." ".$time_in;	//concatinate time + current date
						
						$query = "	INSERT INTO events SET E_TS = '$cur_timestamp', UID = '$UID', E_D = '[ ".$FN." ] Added new Blog: [ ".$getBlogTitle." ]'	";
						mysql_query($query)
						or die(mysql_error());
						
						//unset post variables
						unset($_POST['txtTitle']);
						unset($_POST['txtDescription']);
						unset($_POST['fileAttch']);
						
						
						$Message	= "<span class='okb'><img src='images/icons/check.jpg' width='20' height='20'>New Blog has been added.</span>";
					
					}
					//if theres no file attachment then
				}
				//else if there are no duplicate titles then
			}
			//All is valid then
			
			
		}
		//If Blog Title is detected then
		
		#IF USER ADD NEW BLOGS THEN
		
		
		##################################################################################################################################################
		
		
		#IF A USER UPDATES AN EXISTING BLOG TRU BLOGID
		if(isset($_POST['cmbUpdate']))
		{
			#CHECK IF THE TITEL HAS BEEN DETECTED
			if(isset( $_POST['txtTitle2']))
			{
				//Initiate Local Variables
				$getBlogID	= $_GET['blogID'];
				$BlogTitle	= trim($_POST['txtTitle2']);
				$BlogDesc	= trim($_POST['txtDescription2']);
				$Valid		= true;
				
				
				//simple check
				if($BlogTitle == '')
					$Valid		= false;
					
				elseif($BlogDesc	== '')
					$Valid 		= false;
					
					
					
				//All is valid then
				if($Valid == true)
				{
					//setting up the date
					$today	= date("F j, Y");	//string/Int format
					$hr_s 	= date("H:i:sa");	//12hrs format
					
					
					//check first if this blog is in the database
					if(mysql_num_rows(mysql_query(" SELECT id FROM blogs WHERE id = '".$getBlogID."' ")) > 0)
					{
						
						#IF BLOG ID IS IN THE DATABASE THEN
						#UPDATE QUERY
						/**/
						mysql_query (" 	UPDATE blogs SET TIT = '".$BlogTitle."', DESK = '".$BlogDesc."'
										WHERE id = '".$getBlogID."' AND UID = '".$UID."'
										
									")
						or die(mysql_error());
						move_uploaded_file($Ftemp, $Path);	//code structure for moving this file
						
						
						//log this event
						$time_in = date("H:i:sa");							
						$cur_date = date("Y-m-d");
						$cur_timestamp = $cur_date." ".$time_in;	//concatinate time + current date
						
						$query = "	INSERT INTO events SET E_TS = '$cur_timestamp', UID = '$UID', E_D = '[ ".$FN." ] Update Blog: [ ".$BlogTitle." ]'	";
						mysql_query($query)
						or die(mysql_error());
						
						//unset post variables
						unset($_POST['txtTitle2']);
						unset($_POST['txtDescription2']);
						unset($_POST['fileAttch2']);
						
						
						#PROMTP SUCCESS MESSAGE
						$Message	= "<span class='okb'><img src='images/icons/check.jpg' width='20' height='20'>&nbsp;
						Changes on this blog has been successfully updated.</span>";
						
						
						#PROMTP SUCCESS MESSAGE
						#$Message	= "<span class='okb'><img src='images/icons/check.jpg' width='20' height='20'>&nbsp;
						#Changes on this blog has been successfully updated.</span>";
					}
					//check first if this file already exists
					
					//else if there NO existing file then
					else
					{
						#LOG THIS EVENT
						
						
						#ERROR MESSAGE
						$Message = " <img src='images/icons/x.png' width='20' height='20'>&nbsp;<span class='error'>
						ERROR! This action is prohibited.";
					}
					//else if there NO existing file then
			
				}
				//All is valid then
				
			}
			#CHECK IF THE TITEL HAS BEEN DETECTED
		}
		#IF A USER UPDATES AN EXISTING BLOG
		
		#IF A USER UPDATES AN EXISTING BLOG TRU BLOGID
		
		##################################################################################################################################################
?>

<?php include 'm_header.php'; ?>
<form action="#" method="post">


<td width="75%" valign="top" align="left">
	<!-- BREAD CRUMBS / SEARCH TOOL -->
	<div class="left" style="height:70px; background-color: #9C6;">
        <div class="left" style="border-bottom: 1px solid #CCC; height:68px; margin-right: 5px;">
        <span class="okw"><img src="images/icons/toolbar-icons/page.png" width="20" height="20" style="margin-top: 5px;" />&nbsp;BLOGS</span>
            <div class="left" style="margin-left: 25px; margin-top: 10px;"><!--Search by:
                <span class="left" style="margin-left: 5px; margin-top: 10px;">
                  <input type="text" name="txtSearch" onKeyUp="showSomething(this.value)" class="SearchBox" placeholder="Type" />
                </span> -->
                <span class="error">*</span>&nbsp;Please do not leave the necessary fields empty. 
            </div>
        </div>
    </div>
    <!-- BREAD CRUMBS / SEARCH TOOL -->
    
    
    <!-- WILL DISPLAY THE CONTENT-->
    <div class="center" style="margin-top: 15px; margin-right: 5px; width: 97%;">
    
    	 <div class="left" style=" border-top-left-radius: 0.3em; border-top-right-radius: 0.3em; ">
             <div class="header_arrow">
                <span class="left">
                    <a href="m_blogs.php" title="back to Recent Blogs">
                    	<img src="images/icons/toolbar-icons/back.png" width="20" height="20" style="margin-top: 2px;" />
                    </a>&nbsp;
                    &nbsp;Edit Blog
                </span>
                <span class="arrow">
                </span>
             </div>
         </div>
         
         <div class="center" style="margin-top: 15px; margin-bottom: 15px;">
			<!-- PROMPT MESSAGE HERE --><?php echo $Message; ?>
         </div>
         
         <!-- LOAD THE MESSAGE BOX HERE -->
         <div id="showLightbox"></div>
         <!-- LOAD THE MESSAGE BOX HERE -->
         
         <!-- LOAD BLOGS POP-UP HERE-->
         <div id="showBlogs"></div>
         <!-- LOAD BLOGS POP-UP HERE-->
         
          <!-- LOAD BLOGS POP-UP HERE-->
         <div id="showUpic"></div>
         <!-- LOAD BLOGS POP-UP HERE-->
         
          <!-- LOAD ASSIGNMENT POPUP -->
         <div id="showAssign"></div>
         <!-- LOAD ASSIGNMENT POPUP -->
         
          <!-- LOAD THE MESSAGE BOX HERE -->
         <div id="showUserReg"></div>
         <!-- LOAD THE MESSAGE BOX HERE -->
         
        
   		 <!-- WILL DISPLAY THE DETAILS ABOUT THE USER -->
         <div id="txtHint" class="left" style=" margin-top: 15px; margin-bottom: 15px;">
         	<!-- YOU TABLE HERE -->
            <?php
			#WELL GET FIRST THE BLOGID
			$getBlogID	= $_GET['blogID'];	//blogID of the last selected record of blogs
			
			#WE WILL SELECT ALL THE INFO FROM THE BLOGID THAT WE GET
			$query	= " SELECT * FROM blogs WHERE id = '".$getBlogID."' ";
			$result	= mysql_query($query);
			$row	= mysql_fetch_array($result);
			
			#WE CREATE LOCAL VARIABLES SO THAT WE CAN CALL IT IN THIS PAGE
			$r_id	= $row['id'];		//blog id
			$r_UID	= $row['UID'];		//id of the user/blogger
			$r_FN	= $row['FN'];		//Full name
			$r_TIT	= $row['TIT'];		//Title
			$r_DESC	= $row['DESK'];		//description of the blog
			$r_LINK	= $row['LINK'];		//Link of attached picture
			$r_COM	= $row['COMNT'];	//number of comments
			$r_like	= $row['LIKES'];	//number of likes
			$dateP	= $row['dateP'];	//date posted
			$timeP	= $row['timeP'];	//time posted
			$r_ST	= $row['ST'];		//status
			$r_SHR	= $row['SHR'];		//share
			
			
			?>
            <table border="0" cellspacing="0" cellpadding="0">
            	<tr>
                	<td>
                    <div class="left">
                    
                        <div style=' border:1px dashed #CCC; margin: 25px 15px 15px; background-color:#FFF; padding: 3px; box-shadow: 0 1px 5px #999;'>
                        	
                            <div style="padding: 9px 0; margin: 0 15px; border-bottom: 1px ridge #CCC;">
                                Title: <br>
                                <input type="text" name="txtTitle2" class="InputBox4" value="<?php echo $r_TIT; ?>" placeholder="Enter Title here..." required >
                                <span class="error">*</span><br />
                            </div>
                            
                            <div style="padding: 9px 0; margin: 0 15px; border-bottom: 1px ridge #CCC;">
                                Description: <br>
                                
                                <br>
                                <textarea name="txtDescription2" id="txtDescription"  class="textArea2" rows="10" cols="48" required ><?php echo $r_DESC; ?>
                                </textarea>
                                <span class="error">*</span><br />
                                <span class="s_normal">&nbsp;</span>
                            </div>
                            
                            <div style="padding: 9px 0; margin: 0 15px; border-bottom: 1px ridge #CCC;">
                                Attach File: <br />
                                <?php
								#WE MUST CHECK IF THERE IS A PICTURE ATTACHED TO THIS BLOG
								//if there is NO picture attachement then
								if($r_LINK == '')
									$r_LINK = "
										<span class='s_normal'>NO PICTURE ATTACHED</span>&nbsp;
										<img src='images/icons/toolbar-icons/folder.png' width='18' height='18'
									 	style=' cursor: pointer;' title=' Add Picture' onClick='addPicture()' />
										<br />
                                		<span class='s_normal'>* Attach a picture that best suits this blog</span>
										";
									
								//else if there is a picture attachment then
								else
									$r_LINK = "
										<a href=''>".substr($r_LINK, 12 )."</a>&nbsp;
										<img src='images/icons/writing-4.png' width='20' height='20' style='cursor:pointer' 
										title='Change picture attachment' onClick='updatePicture()' />
										<br />
                                		<span class='s_normal'>*&nbsp;Click the pencil to change the picture attachment.</span>
									";
								
								echo $r_LINK;
								?>
                            </div>	
                            
                            
                            <?php
							#CHECK THIS BLOG IF SHARED OR NOT
							if($r_SHR == 0)
								echo "
								<div style='padding: 9px 0; margin: 5px 15px;'>
                            
                            	</div>
								";
								
							else
								echo "
								<div style='padding: 9px 0; margin: 5px 15px;'>
									<span class='s_normalbb'>This blog is seen by everyone</span>
								</div>
								";
							?>
                        	
                           
                        </div>
                        
                        
                        <div style="padding: 9px 0; margin: 0 15px;">
                            <input type="submit" name="cmbUpdate" value="Update Blog">&nbsp;&nbsp;&nbsp;
                            <input type="button" class="btnRed" name='cmdUnshare' value='Unshare Blog' />
                            <div style="float: right">
                            	<img src="images/icons/toolbar-icons/rubbish-bin.png" width="25" height="25" style="cursor:pointer;"
                                title="Delete this blog" onclick="deleteBlog()" />
                            </div>
                        </div>
                        
                    </div>

                    </td>
                </tr>
            </table>
        	<!-- YOU TABLE HERE -->
         </div>

         <!-- WILL DISPLAY THE DETAILS ABOUT THE USER -->
    </div>
    
     <!-- pagination -->
    <div class="pagination" style="margin: 2px 15px 15px;">
		<?php 
		if($pages >= 1 && $page <= $pages)
		{
			for ($x = 1; $x <= $pages; $x++)
			{
				echo ($x == $page) ? '<b><a id="onLink" href="?page='.$x.'">'.$x.'</a></b> ' :  '<a href="?page='.$x.'">'.$x.'</a> ';
			}
		} 
		?>
    </div>
    <!-- pagination -->	
    
    <!-- WILL DISPLAY THE CONTENT-->
</td>





<!-- RIGHT NAV -->
<td width="25%" valign="top" align="center" style="border-left:1px solid #999;">
	<!-- DISPLAY PICTURE OF USER THAT LOGGED IN AND DETAILS -->
	<div class="right" style="height: 68px;  background-color: #9C6; border-top-right-radius: 0.3em;">
     <?php
	//GETTING ALL THE INFORMATION OF THIS USER
	$query	= " SELECT * FROM user_details WHERE UID = '".$UID."' ";
	$result	= mysql_query($query);
	$row	= mysql_fetch_array($result);
	//declaire variable
	$r_PIC	= $row['PIC'];	//Picture
	
	?>
    	<table width="100%" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td>
            <div style=" margin-top: 5px; margin-left: 25px;">
             <?php
			//set default profile pick if user doesnt have profile pick
			//if profile picks is equal to empty then
			if($r_PIC == '')
				//we set the defaul profile pick 
				echo ' <img src="images/icons/default_user.jpg" height="50" width="50" style="border: 1px solid #999;" /> ';
			
			//else if profile pick is NOT empty then
			else
				//we display the users profile pick
				echo ' <img src="'.$r_PIC.'" height="50" width="50" style="border: 1px solid #999;" /> ';
			//set default profile pick if user doesnt have profile pick
			?>
            </div>
            </td>
            <td class="a">
                <div style=" margin-top: 5px;">
                    <span class="okb"><?php echo $UN.'<br> '.$UT_N.'<br>'; ?>
                    	<a href="#">Edit Account</a>
                    </span>
                </div>
            </td>
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
            <img src="images/icons/warning_48.png"  width="20" height="20" />&nbsp;Daily Updates            
        </div>
        
        
        <!-- DIV FOR NEW USER REGISTRY -->
		<?php 
        //we want to get the total number of users whos status is 0 or not yet active
        $query		= " SELECT COUNT(ST) AS ST FROM users WHERE ST = '0' ";
        $result		= mysql_query($query);
        $row		= mysql_fetch_array($result);
        //we get the total count based on the 0 status and place it in a variable '$T_ST'
        $T_ST		= $row['ST'];	//will hold watever number is in the table
       
        //if there are users whos status is O then
        if($T_ST)
        //display an alert message to alert the admin
        echo '
        <div class="left" style="padding-top: 5px; padding-bottom: 5px; 
        border-left:1px solid #CCC;  border-right:1px solid #CCC; border-bottom: 1px solid #CCC;">
                <div class="left">
                    <img src="images/icons/toolbar-icons/user_alert.png" width="20" height="20" />
                    <input type="button" class="button" onclick="showUserReg()" value="User Registry" title="View Users" />
					(<span class="error" style="font-size:14px">'.$T_ST.'</span>)
                </div>
        </div>
             ';
             
        //if theres are no user whos status is O then
        else
        //display a gray colored text + unclickable link
        echo '
        <div class="left" style="padding-top: 5px; padding-bottom: 5px; 
        border-left:1px solid #CCC;  border-right:1px solid #CCC; border-bottom: 1px solid #CCC;">
            <div class="left">
                <img src="images/icons/toolbar-icons/user.png" width="20" height="20" />&nbsp;
                <span class="s_normal">User Registry</span>
            </div>
        </div>
            ';
        ?>	
        <!-- DIV FOR NEW USER REGISTRY -->
        
        
        
        <!-- DIV FOR MESSAGES -->
         <?php
		//we have to check if theres a message whos status is 1 to this user
		// DONR FORGET THE ALIAS!!!!
		$query		= " SELECT COUNT(MST) AS MST FROM msgs WHERE msgTo= '".$UID."' AND MST = '1'  ";
		$result		= mysql_query($query);
		$row		= mysql_fetch_array($result);
		//variable fot the total result
		$T_MSG		= $row['MST'];
		
		//if theres a message to this user whos status is equal to 1 then
		if($T_MSG)
		
		echo "
		<div class='left' style='padding-top: 5px; padding-bottom: 5px; 
		border-left:1px solid #CCC;  border-right:1px solid #CCC; border-bottom: 1px solid #CCC;'>
			<div class='left'>
				<img src='images/icons/email-alert.png' width='20' height='20' />&nbsp;
				<input type='button' class='button' onclick='showSentBy2()' value='Inbox' title='View Messages' />
				(<span class='error' style='font-size:14px'>".$T_MSG."</span>)
			</div>
		</div>
		";
	
		
		//else if theres no message whos status is equal to 1 then
		else
		
		echo "
		<div class='left' style='padding-top: 5px; padding-bottom: 5px; 
		border-left:1px solid #CCC;  border-right:1px solid #CCC; border-bottom: 1px solid #CCC;'>
			<div class='left'>
				<img src='images/icons/Email-icon.png' width='20' height='20' />&nbsp;
				<span class='s_normal'/>Inbox</span>
			</div>
		</div>
		";

		
		
		?>
        <!-- DIV FOR MESSAGES -->
        
        
         <!-- DIV FOR ASSIGNMENTS -->
        <?php
		//were gona get the count of all records in the assignment table whos status is equal to 1
		$query		= " SELECT COUNT(id) AS id FROM assignment WHERE A_ST = '1' ";
		$result		= mysql_query($query);
		$row		= mysql_fetch_array($result);
		//variable fot the total result
		$T_ASGN		= $row['id'];	//Total assignment in the table
		
		//count of assignment having status 1 is NOT equal to 0 then
		if($T_ASGN)
		echo "
		
		<div class='left' style='padding-top: 5px; padding-bottom: 5px; 
        border-left:1px solid #CCC;  border-right:1px solid #CCC; border-bottom: 1px solid #CCC;'>
            <div class='left'>
                <img src='images/icons/toolbar-icons/folder-alert.png' width='20' height='20' />&nbsp;
                <input type='button' class='button' onclick='showAssign()' value='New Assignment' />
				(<span class='error' style='font-size:14px'>".$T_ASGN."</span>)
            </div>
        </div>
		
		";
		
		else
		echo"
		<div class='left' style='padding-top: 5px; padding-bottom: 5px; 
        border-left:1px solid #CCC;  border-right:1px solid #CCC; border-bottom: 1px solid #CCC;'>
            <div class='left'>
                <img src='images/icons/toolbar-icons/folder.png' width='20' height='20' />&nbsp;
                <span class='s_normal'/>New Assignment</span>
            </div>
        </div>
		
		";
		
		?>
        
    	<!-- DIV FOR ASSIGNMENTS -->
        
        <!-- DIV FOR BLOGS MANAGEMENT -->
        <div style="margin-top: 15px; margin-left: 5px; margin-right: 5px; margin-bottom: 15px;">
            <div class="left" style=" height: 25px; border: 1px solid #CCC; border-top-left-radius: 0.3em; border-top-right-radius: 0.3em; 
            background-color:#b6e56d">
                <img src="images/icons/toolbar-icons/settings.png"  width="20" height="20" />&nbsp;Manage Blogs           
            </div>
            
            <div class='left' style='padding-top: 5px; padding-bottom: 5px; 
            border-left:1px solid #CCC;  border-right:1px solid #CCC; border-bottom: 1px solid #CCC;'>
                <div class='left'>
                 <img src="images/icons/toolbar-icons/comment.png" width='20' height='20' />&nbsp;
                <input type='button' class='button' onclick='showBlogs()' value='Add New' title="Add New Blog" />
                </div>
            </div>
        
        </div>

	</div> 

</td>
<!-- RIGHT NAV -->


</form>
<?php include 'footer.php'; ?>