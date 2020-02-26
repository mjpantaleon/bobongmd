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


		//IF USER ADD NEW BLOGS THEN
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
		
		
		########################################################################################################################
		
		
		#IF BLOG IS DETECTED THEN
		$getBlogID	= $_GET['blogID'];	//blogid
		$getBlogger	= $_GET['blogger'];	//Blogger is
		
		if(isset( $_GET['blogID']))
		{
			#GET ALL THE DETAILS ABOUT THIS BLOG
			$query	= " SELECT * FROM blogs WHERE id = '".$getBlogID."' ";
			$result	= mysql_query($query);
			$row 	= mysql_fetch_array($result);
			
			#Initiate local variables
			$r_FN	= $row['FN'];		//Full name
			$r_TIT	= $row['TIT'];		//Title
		}
		
		if(isset($_GET['blogger']))
		{
			#GET ALL THE DETAILS ABOUT THIS BLOG
			$query	= " SELECT * FROM blogs WHERE UID = '".$getBlogger."' ";
			$result	= mysql_query($query);
			$row 	= mysql_fetch_array($result);
			
			#Initiate local variables
			$r_FN	= $row['FN'];		//Full name
			$r_TIT	= $row['TIT'];		//Title
		}
		
		########################################################################################################################
		
		#IF A COMMENT HAS BEEN DETECTED THEN
		if( isset($_POST['txtComment']))
		{
			#Initialize local variable
			$getComment	= trim($_POST['txtComment']);		//trim down to prevent malicious sql injection of whatever this field holds
			$today	= date("F j, Y");	//string/Int format
			$hr_s 	= date("H:i:sa");	//12hrs format
			
			#Check first if the field is not EMPTY
			if($getComment != '')
			{
				#INSERT TO COMMENT TABLE
				$query	= " INSERT INTO blog_comments
							SET 
							blogID 	= '".$getBlogID."',
							UID		= '".$UID."',
							FN		= '".$FN."',
							COM		= '".$getComment."',
							dateC	= '".$today."',
							timeC	= '".$hr_s."',
							ST		= '1'
				";
				
				mysql_query($query)
				or die(mysql_error());
				#INSERT TO COMMENT TABLE
				
				#UPDATE blogs table
				$query	= "	SELECT COMNT
							FROM blogs
							WHERE
							id	= '".$getBlogID."'
						  ";
				$result	= mysql_query($query);
				$row	= mysql_fetch_array($result);
				//initialize local variable
				$comments	= $row['COMNT'];	//total likes in the table
				$increment	= 1;
				
				$totalComnt	= $comments + $increment;
				
				//then we update it to the blogs table
				mysql_query(" UPDATE blogs
							  SET
							  COMNT = '".$totalComnt."'
							  WHERE id = '".$getBlogID."'
							")
				or die(mysql_error());
				#UPDATE blogs table
				
				#log this event
				$time_in = date("H:i:sa");							
				$cur_date = date("Y-m-d");
				$cur_timestamp = $cur_date." ".$time_in;	//concatinate time + current date
				
				$query = "	INSERT INTO events SET E_TS = '$cur_timestamp', UID = '$UID', E_D = '[ ".$FN." ] commented on blog: [ ".$getBlogID." ]'	";
				mysql_query($query)
				or die(mysql_error());
				
				
				#POST MESSAGE
				$Message	= "
					<div class='center'>
						<img src='images/icons/check.jpg' width='20' height='20'>
						<span class='okb'>Your comment has been successfully added.</span>
					</div>
				
				";
			}
			
			#Check first if the field is not EMPTY
			
			#else
			else
				#POST MESSAGE
				$Message	= "
					<div class='center'>
						<img src='images/icons/x.png' width='16' height='16'>
						<span class='error'>Ooops! Cannot accept empty comments.</span>
					</div>
					
					";
			#else
		}
		#IF A COMMENT HAS BEEN DETECTED THEN
		
		########################################################################################################################
?>

<?php include 'g_header.php'; ?>
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
                Blog (Web log) - Share anything under the sun. 
            </div>
        </div>
    </div>
    <!-- BREAD CRUMBS / SEARCH TOOL -->
    
    
    <!-- WILL DISPLAY THE CONTENT-->
    <div class="center" style="margin-top: 15px; margin-right: 5px; width: 97%;">
    
    	 <div class="left" style=" border-top-left-radius: 0.3em; border-top-right-radius: 0.3em; ">
             <div class="header_arrow">
                <span class="left">
                    <a href="g_blogs.php" title="back to Recent Blogs">
                    	<img src="images/icons/toolbar-icons/back.png" width="20" height="20" style="margin-top: 2px;" />
                    </a>&nbsp;
                    Blog Details
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
         
          <!-- LOAD ASSIGNMENT POPUP -->
         <div id="showAssign"></div>
         <!-- LOAD ASSIGNMENT POPUP -->
         
          <!-- LOAD THE MESSAGE BOX HERE -->
         <div id="showUserReg"></div>
         <!-- LOAD THE MESSAGE BOX HERE -->
         
        
   		 <!-- WILL DISPLAY THE DETAILS ABOUT THE USER -->
         <div id="txtHint" class="left" style=" margin-top: 15px; margin-bottom: 15px;">
         	<!-- YOU TABLE HERE -->
            <table border="0" cellspacing="0" cellpadding="0">
             <?php
					
					//MUST CHECK FIRST IF blogID or blogger is detected
					//IF BLOGID IS DETECTED THEN
					if(isset($_GET['blogID']))
					{
						$getBlogID	= $_GET['blogID'];	//blogid
						$getFlag	= $_GET['flag'];	//
						
						#MUST CHECK IF THIS blogID IS IN THE DATABASE
						#IF THIS blogID IS IN THE DATABSE THEN
						if(mysql_num_rows(mysql_query(" SELECT id FROM blogs WHERE id = '".$getBlogID."' ")) > 0)
						{
							#GET ALL THE DETAILS ABOUT THIS BLOG
							/*
							SELECT blogs.*, blog_hits.userID,blogID
							FROM `blogs`
							LEFT JOIN `blog_hits`
							ON blogs.id = blog_hits.blogID
							WHERE id = '".$getBlogID."'
							*/
							$query	= " SELECT *
										FROM `blogs`
										WHERE id = '".$getBlogID."'
									  ";
							$result	= mysql_query($query);
							$row 	= mysql_fetch_array($result);
							
							#Initiate local variables
							#$r_userID	= $row['userID'];	//userID from blog_hits
							#$r_blogID	= $row['blogID'];	//blogID from blog_hits
							
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
							
							
							$divBlogs = "
							
							<tr>
								<td>
									<div style=' margin: 25px 10px; '>
									";
									
								#IF THIS BLOG HAS NO PICTURE ATTACHED
								if($r_LINK != '')
								{
									#THEN WE DONT DISPLAY ANY PICTURE
									$divBlogs.="
										<img style='box-shadow: 0 1px 5px #666;' src='".$r_LINK."' width='540' height='410' />
									";
								}
								
								//else
								else
								{
									#Display nothing
									$divBlogs.="
										
									";
								}
									$divBlogs.="
										<div class='blogMainDetails'>
											<h5>".$r_TIT."</h5>
											
											<span class='s_normal'>
												by ".$r_FN." |
												<img src='images/icons/toolbar-icons/clock.png' width='16' height='16' /> 
												".$dateP."
											</span>
										</div>
										
									   
										
										<div class='left'>
											<span class='s_normal' style='float: right;'>
											";
											
											#WE WILL CHECK IF THE USER LOGGED HAS ALREADY LIKED THIS BLOG OR NOT
											#if the userID of this user is found in the blog_hits table then
											if(mysql_num_rows(mysql_query(" SELECT userID,blogID 
																		  	FROM `blog_hits` 
																			WHERE 
																			userID = '".$UID."' 
																			AND
																			blogID = '".$r_id."' ")) > 0)
											{
												#we prompt that the user like this
											$divBlogs.="
											You like this
											";
											}
											#if the userID of this user is found in the blog_hits table then
											
											
											#if the userID of this user is NOT found in the blog_hits table then
											else
											{
												#we provide a link to like this blog
											$divBlogs.="
												<a href='g_blogs_like.php?blogID=".$r_id."&userID=".$UID."'>
													<img src='images/icons/like.png' height='18' width='18' title='Like this' />
												</a>
											";
											}
											#if the userID of this user is NOT found in the blog_hits table then
											#WE WILL CHECK IF THE USER LOGGED HAS ALREADY LIKED THIS BLOG OR NOT
											
											$divBlogs.="
												(".$r_like.")&nbsp;Likes
											</span>
											<p class='blogDesc'>
											". nl2br($r_DESC)."
											</p>
										</div>
										
										
										 <div class='left'>
											<div class='blogCommBox'>
												<h5 class='gray'>Comment</h5>
												<input type='text' class='commentBox' name='txtComment' placeholder='Write a comment...' />
											</div>
										</div>
										
										
									
									</td>
								</tr>
										";
										
							echo $divBlogs;		//ECHO RESULT
							
							#THEN WE DISPLAY THE NUMBER OF USER'S COMMENTS HERE
						}
						#IF THIS blogID IS IN THE DATABSE THEN
						
						
						#IF THIS BLOG DOES NOT EXIST IN THE DATABASE THEN
						else
						{
							echo "
							<div class='center'><span class='error'>ERROR: This blog does NOT exists!</span></div>
							";
						}
						#IF THIS BLOG DOES NOT EXIST IN THE DATABASE THEN

					}
					//IF BLOGID IS DETECTED THEN
					
					
					?>
            	<tr>
                	<td>
                    	<div class='blogCommBox'>
							<h5 class='gray2'>Recent Comments</h5>
                            
						  	<?php
							//GET RECENT COMMENTS FOR THIS BLOG
							$getBlogID	= $_GET['blogID'];	//blogid
								
							#MUST CHECK IF THIS blogID IS IN THE DATABASE
							#IF THIS blogID IS IN THE DATABSE THEN
								$count	= 0;
								
								$query	= " SELECT  * 
											FROM blog_comments
											WHERE ST = '1' AND blogID = '".$getBlogID."'
											ORDER BY comment_id DESC 
											";
								$result	= mysql_query($query);
								while($row	= mysql_fetch_array($result))
								{
									#Initialize local variables
									$COM	= $row['COM'];		//comment
									$dateC	= $row['dateC'];	//date
									$timeC	= $row['timeC'];	//time
									$e_FN	= $row['FN'];		//Full Name
									
																
									if($result)
									{
										#THEN WE DISPLAY THE NUMBER OF USER'S COMMENTS HERE
										$divComments ="   
										 <div style=' border:1px dashed #CCC; margin: 25px 15px 15px; background-color:#FFF; 
										 padding: 3px; box-shadow: 0 1px 3px #999;'>
								
											<div>
												<div class=' left'>
													<a href='' title='View Profile of ".$e_FN."'><h5>".$e_FN."</h5></a>
												</div>
											</div>
											
											<div style=' margin: 3px 5px'>
												<p><span class='s_normal'>".$COM."</span></p>
											</div>
											
											<div style=' margin: 0 0 0 85px;'>
												<div class='right'><span class='s_normal'>".substr($dateC, 0, 8)." - ".substr($timeC, 0 , 10)."</span></div>
											</div>
										</div>
										";
									
										
									}
									
									#incase that the query is false then
									else
									{
										#prompt message
										$divComments ="
											<div class='center'><span class='s_normal'>No comment available for this blog.</span></div>
										";
									}
									
									
									echo $divComments;	//ECHO LIST OF COMMENTS
								}
								#end while
							
							?>
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
            <img src="images/icons/toolbar-icons/help.png"  width="20" height="20" />&nbsp;What do you want to do?            
        </div>
        
         <!--<div class="left" style="padding-top: 5px; padding-bottom: 5px; 
        border-left:1px solid #CCC;  border-right:1px solid #CCC; border-bottom: 1px solid #CCC;">
            <div class="left">
                <img src="images/icons/toolbar-icons/mail.png" width="20" height="20" />&nbsp;
                <a class="button" href="g_send_msg.php" title="Send Message">Send Mesage</a>  
            </div>
        </div>-->
        
         <div class="left" style="padding-top: 5px; padding-bottom: 5px; 
        border-left:1px solid #CCC;  border-right:1px solid #CCC; border-bottom: 1px solid #CCC;">
            <div class="left">
            <img src="images/icons/toolbar-icons/book.png" width="20" height="20" />&nbsp;
            <a class="button" href="g_send_asgn.php" title="Send Message">Send Assignment</a>  
            </div>
        </div>
        
       <?php
	   if($r_PIC != '')
	   {
		   echo "
		    <div class='left' style='padding-top: 5px; padding-bottom: 5px; 
			border-left:1px solid #CCC;  border-right:1px solid #CCC; border-bottom: 1px solid #CCC;'>
				<div class='left'>
				<img src='images/icons/toolbar-icons/user.png' width='20' height='20' />&nbsp;
				<a class='button' href='g_profile_edit.php' title='Edit Profile'>Edit Profile</a>  
				</div>
			</div>
			";
	   }
	   
	   else
	   {
		   echo "
		    <div class='left' style='padding-top: 5px; padding-bottom: 5px; 
			border-left:1px solid #CCC;  border-right:1px solid #CCC; border-bottom: 1px solid #CCC;'>
				<div class='left'>
				<img src='images/icons/toolbar-icons/user_alert.png' width='20' height='20' />&nbsp;
				<a class='button' href='g_profile_edit.php' title='Edit Profile'>Edit Profile</a>  
				</div>
			</div>
			";
	   }
	   
	   ?>
      
    </div>
        
        
        
       
      
        
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