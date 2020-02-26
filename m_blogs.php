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
						
						#|||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
						#UPDATE NOTIFICATION TABLE
						$SUBJ	= "Blog";
						mysql_query(" 	INSERT INTO `notify` SET
										SUBJ	= '".$SUBJ."',
										CONT	= '".$getBlogTitle."',
										dateP	= '".$today."',
										timeP	= '".$hr_s ."',
										ST		= '1',
										UN		= '".$UN."'
						")
						or die(mysql_error());
												
						#SUBJECT	: New Blog
						#CONTENT	: Added a Blog with title [ ".$getBlogTitle." ]
						#DATE		: August 29, 2013
						#TIME		: 14:58:36pm
						#STATUS		: 1
						#ADDED BY	: ".$UID."
						
						
						#|||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
						
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
						
						
						#|||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
						#UPDATE NOTIFICATION TABLE
						$SUBJ	= "Blog";
						mysql_query(" 	INSERT INTO `notify` SET
										SUBJ	= '".$SUBJ."',
										CONT	= '".$getBlogTitle."',
										dateP	= '".$today."',
										timeP	= '".$hr_s ."',
										ST		= '1',
										UN		= '".$UN."'
						")
						or die(mysql_error());
						
						#SUBJECT	: New Blog
						#CONTENT	: Added a Blog with title [ ".$getBlogTitle." ]
						#DATE		: August 29, 2013
						#TIME		: 14:58:36pm
						#STATUS		: 1
						#ADDED BY	: ".$UID."
						
						
						#|||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
						
						
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
                    <img src="images/icons/toolbar-icons/photos.png" width="20" height="20" style="margin-top: 2px;" />&nbsp;Recent Blogs
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
      
      
   		 <!-- WILL DISPLAY THE DETAILS ABOUT THE USER -->
         <div id="txtHint" class="left" style=" margin-top: 15px; margin-bottom: 15px;">
         	<!-- YOU TABLE HERE -->
            <table border="0" cellspacing="0" cellpadding="0">
             <?php
			 $per_page	= 4;	//total number of record

			$Limit	= 2;	//number of columns or records to display per row
			$Count	= 0;	//zero default value
			
			
			$query	= mysql_query(" SELECT COUNT(`id`) FROM `blogs` WHERE B_ST = '1' AND SHR = '1' ")		//this only serves as a counter
			or die(mysql_error());
			$pages	= ceil(mysql_result($query, 0) / $per_page);				//use ciel to get the round of the higher value
					
			$page	= ( isset( $_GET['page']))  ? (int)$_GET['page'] : 1;
			$start	= ($page - 1) * $per_page;
			
			
			/* DO NOT FORGET THE START.", ".per_page  IMPORTANT
			 SELECT blogs.*, blog_hits.userID,blogID
			FROM `blogs`
			LEFT JOIN `blog_hits`
			ON blogs.id = blog_hits.blogID
			WHERE B_ST = '1' AND SHR = '1'
			ORDER BY id DESC LIMIT ".$start.", ".$per_page."
			
			*/
			$query	= " SELECT  *
						FROM `blogs`
						WHERE B_ST = '1' AND SHR = '1'
						ORDER BY id DESC LIMIT ".$start.", ".$per_page."
					  ";
			$result	= mysql_query($query)
			or die(mysql_error());
			
			while($row = mysql_fetch_array($result))
			{
				
				//initialize local variables	
				//table blog_hits
				#$r_uID	= $row['userID'];		//user ID
				#$r_bID	= $row['blogID'];		//blog ID

				//table blogs
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
				
				
				
				//if COunt(0) is less then limit(2)
			
				if($Count < $Limit)
				{
					//if count is equal to 0 then
					if($Count == 0)
					{
						//we echo the opening row
						$divBlogs="
						<tr valign='top' align='left'>
						";
					}
					
					//then we echo the column inside the row
					$divBlogs.="
					<td>
					<div style=' margin: 25px 10px; '>
						<div class='blogWrapper' style='width: 220px; margin: 25px 15px; padding: 3px;
						border: 1px solid #CCC; box-shadow: 0 1px 5px #CCC; background-color:#FFF;'>
						
							<div class='blogTitle'>
								<div class='left'>
									<h3><a href='m_blogs_details.php?blogID=".$r_id."' title='View this blog'>".$r_TIT."</a></h3>
								</div>
							</div>
							
							";
							
							//we will filter if theres a picture in this blog
							//if theres a picture attached then
							if($r_LINK != '')
							{
							 $divBlogs.="
							 
							<div style='margin: 3px; padding: 3px; border: 1px solid #F5F5F5; '>
								<div class='center' style='margin:2px;'>
									<img src='".$r_LINK."' width='200' height='220' alt='Attached Image' />
								</div>
							</div>
							";
							}
							//if theres a picture attached then
							
							//else if theres no picture attached then
							else
							{
							$divBlogs.="
							
							";
							}
							//else if theres no picture attached then
							
							$divBlogs.="
							<div class='blogDescription'>
								<div class='left'>
								<p style=' height: 90px; margin: 15px 8px; padding-right: 3px;'>
								".nl2br(substr($r_DESC, 0, 70))."...
								</p>
								</div>
							</div>
							
							<div class='blogDetails' style='margin: 18px 8px 8px;'>
									<span class='ss_normal'>(".$r_COM.") 
									<a href='m_blogs_details.php?blogID=".$r_id."' title='view comments of this blog'>Comments</a></span> |
									<span class='ss_normal'>(".$r_like.") 
									<a href='m_blogs_details.php?blogID=".$r_id."' title='view likes of this blog'>Likes</a> </span>
							</div>
							
							<div style=' height: 55px; padding: 3px; border-top: 1px solid #CCC; background-color:#F1F1F1;'>
								<div  class='left'>
									<span class='s_normal'>Uploaded by
										<a href='m_blogs_details.php?blogger=".$r_UID."' title='View Blogs of this person'>".$r_FN."</a><br />
										<img src='images/icons/toolbar-icons/clock.png' width='16' height='16' />
											<span class='s_normal'>".$dateP."</span>
									</span>
								</div>
								
								<div class='right'>
									<span class='s_normal'>
									";
									//filter if this blog belongs to this user
									//if user is same with the logged user then
									if($r_UID == $UID)
									{
										//he/she can edit this blog
										$divBlogs.="
										<img src='images/icons/toolbar-icons/tools.png' width='16' height='16' title='Edit' alt='Edit' />
										<a href='m_blogs_edit.php?blogID=".$r_id."' title='Edit this blog' >Edit</a>
										";
									}
									//if user is same with the logged user then
									
									//else if this user is NOT the owner of this blog then
									else
									{
										//do not provide any link to edit this blog
										$divBlogs.="
										";
									}
									//else if this user is NOT the owner of this blog then
									
									$divBlogs.="
									</span>
								</div>
							</div>

						</div>
                    </div>	
					</td>
					";
				}
				//if COunt(0) is less then limit(2)
				
				
				//else
				else
				{
					$Count = 0;	//set the value of count into 0
					
					//close the 1st row then open another opening row
					$divBlogs.="
					</tr>
					
					<tr valign='top' align='left'>
						<td>
							<div style=' margin: 25px 10px; '>
							<div class='blogWrapper' style='width: 220px; margin: 25px 15px; padding: 3px;
							border: 1px solid #CCC; box-shadow: 0 1px 5px #CCC; background-color:#FFF;'>
							
								<div class='blogTitle'>
									<div class='left'>
										<h3><a href='m_blogs_details.php?blogID=".$r_id."' title='View this blog'>".$r_TIT."</a></h3>
									</div>
								</div>
								
								";
								
								//we will filter if theres a picture in this blog
								//if theres a picture attached then
								if($r_LINK != '')
								{
								 $divBlogs.="
								 
								<div style='margin: 3px; padding: 3px; border: 1px solid #F5F5F5; '>
									<div class='center' style='margin:2px;'>
										<img src='".$r_LINK."' width='200' height='220' alt='Attached Image' />
									</div>
								</div>
								";
								}
								//if theres a picture attached then
								
								//else if theres no picture attached then
								else
								{
								$divBlogs.="
										
								";
								}
								//else if theres no picture attached then
								
								$divBlogs.="
								<div class='blogDescription'>
									<div class='left'>
									<p style=' height: 90px; margin: 15px 8px; padding-right: 3px;'>
									".nl2br(substr($r_DESC, 0, 70))."...
									</p>
									</div>
								</div>
								
								<div class='blogDetails' style='margin: 18px 8px 8px;'>
										<span class='s_normal'>(".$r_COM.")
										<a href='m_blogs_details.php?blogID=".$r_id."' title='view comments of this blog'>Comments</a></span> |
										<span class='s_normal'>(".$r_like.") 
										<a href='m_blogs_details.php?blogID=".$r_id."' title='view likes of this blog'>Likes</a> </span>
								</div>
								
								<div style=' height: 55px; padding: 3px; border-top: 1px solid #CCC; background-color:#F1F1F1;'>
									<div  class='left'>
										<span class='s_normal'>Uploaded by
											<a href='m_blogs_details.php?blogger=".$r_UID."' title='View Blogs of this person'>".$r_FN."</a><br />
											<img src='images/icons/toolbar-icons/clock.png' width='16' height='16' />
												<span class='s_normal'>".$dateP."</span>
										</span>
									</div>
									
									<div class='right'>
										<span class='s_normal'>
											<img src='images/icons/toolbar-icons/tools.png' width='16' height='16' title='Edit' alt='Edit' />
											<a href='m_blogs_edit.php?blogID=".$r_id."' title='Edit this blog' >Edit</a>
										</span>
									</div>
								</div>
	
							</div>
							
                        </div>	
						</td>
						
					";
				}
				//else
				$Count++;	//increment value of count
				
				
			}
			//end while
			
					//then we close the row
					$divBlogs.="
					</tr>
					";
			
			echo $divBlogs;	//echo result
	
			
			?>
            	
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