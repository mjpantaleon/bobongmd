<?php
/** Set flag that this is a parent file */
define( "MAIN", 1 );


//require connection to the database
require('db_con.php');
log_hit();

//gets the page name
$page = mysql_real_escape_string(basename($_SERVER['SCRIPT_NAME']));

		
?>

<?php include 'header.php'; ?>
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
									<h3><a href='blogs_details.php?blogID=".$r_id."' title='View this blog'>".$r_TIT."</a></h3>
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
									<a href='blogs_details.php?blogID=".$r_id."' title='view comments of this blog'>Comments</a></span> |
									<span class='ss_normal'>(".$r_like.") 
									<a href='blogs_details.php?blogID=".$r_id."' title='view likes of this blog'>Likes</a> </span>
							</div>
							
							<div style=' height: 55px; padding: 3px; border-top: 1px solid #CCC; background-color:#F1F1F1;'>
								<div  class='left'>
									<span class='s_normal'>Uploaded by
										<a href='blogs_details.php?blogger=".$r_UID."' title='View Blogs of this person'>".$r_FN."</a><br />
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
										<h3><a href='blogs_details.php?blogID=".$r_id."' title='View this blog'>".$r_TIT."</a></h3>
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
										<a href='blogs_details.php?blogID=".$r_id."' title='view comments of this blog'>Comments</a></span> |
										<span class='s_normal'>(".$r_like.") 
										<a href='blogs_details.php?blogID=".$r_id."' title='view likes of this blog'>Likes</a> </span>
								</div>
								
								<div style=' height: 55px; padding: 3px; border-top: 1px solid #CCC; background-color:#F1F1F1;'>
									<div  class='left'>
										<span class='s_normal'>Uploaded by
											<a href='blogs_details.php?blogger=".$r_UID."' title='View Blogs of this person'>".$r_FN."</a><br />
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