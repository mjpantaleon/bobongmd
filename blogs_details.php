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
                    <a href="m_blogs.php" title="back to Recent Blogs">
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
												(".$r_like.")&nbsp;Likes
											</span>
											<p class='blogDesc'>
											". nl2br($r_DESC)."
											</p>
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
													<a href='m_user_details.php?user=".$r_UID."' title='View Profile of ".$e_FN."'><h5>".$e_FN."</h5></a>
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