<?php
/** Set flag that this is a parent file */
define( "MAIN", 1 );


//require connection to the database
require('db_con.php');
log_hit();

?>

<?php include 'header.php'; ?>
<form action="#" method="post" enctype="multipart/form-data">


<td width="686" valign="top" align="left">
	<!-- BREAD CRUMBS / SEARCH TOOL -->
	<div class="left" style="height:70px; background-color: #9C6;">
        <div class="left" style="border-bottom: 1px solid #CCC; height:68px; margin-right: 5px;">
        <span class="okw"><img src="images/icons/teacher.png" width="20" height="20" style="margin-top: 5px;" />&nbsp;TEACHER'S SECTION</span>
            <div class="left" style="margin-left: 25px; margin-top: 10px;">Search by:
                <span class="left" style="margin-left: 5px; margin-top: 10px;">
                  <input type="text" name="txtSearch" onKeyUp="showLecHome(this.value)" class="SearchBox" placeholder="Title" />
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
                    <img src="images/icons/desktop.png" width="20" height="20" style="margin-top: 2px;" />&nbsp;Recent Lectures</span>
                    <span class="arrow">
                </span>
             </div>
         </div>
         
         <div class="center" style="margin-top: 8px; margin-bottom: 15px;">
			<!-- PROMPT MESSAGE HERE -->&nbsp;<?php echo $Message; ?>
         </div>
         
         <!-- LOAD THE MESSAGE BOX HERE -->
         <div id="showLightbox"></div>
         <!-- LOAD THE MESSAGE BOX HERE -->
        
   		 <!-- CONTENT DIV -->
         <div id="txtHint" class="left" style=" margin-top: 15px; margin-bottom: 15px;">
         	<!-- YOUR TABLE HERE -->
            
           	<?php
			$per_page	= 5;
			
			$Limit	= 1;	//number of columns or records to display per row
			$Count	= 0;	//zero default value
			
			$query	= mysql_query(" SELECT COUNT(`id`) FROM `teachersec` WHERE ST = '1' ")		//this only serves as a counter
			or die(mysql_error());
			$pages	= ceil(mysql_result($query, 0) / $per_page);				//use ciel to get the round of the higher value
					
			$page	= ( isset( $_GET['page']))  ? (int)$_GET['page'] : 1;
			$start	= ($page - 1) * $per_page;
			
			
			$query2 	= mysql_query(" SELECT *
										FROM `teachersec`
										WHERE ST = '1'
										ORDER BY id DESC LIMIT ".$start.", ".$per_page." ")
			or die(mysql_error());
			
			while($row	= mysql_fetch_array($query2))
			{
				//set variables
				$r_id		= $row['id'];		//record id
				$r_Title	= $row['TIT'];		//Title
				$r_DESC		= $row['DESK'];		//Description
				$r_CAT		= $row['CAT'];		//Category
				$r_CatPic	= $row['CatPic'];	//Category picture
				//$r_LINK		= $row['LINK'];		//File link
				$r_TS		= $row['TS'];		//time stamp
				$r_ST		= $row['ST'];		//disable flag
				$r_LINK		= "a_ts_dload.php?id=".$r_id."";
				
				//getting the elapsed time of this post
				$now	= time();
				$past	= strtotime($r_TS);
				
				
				//change format of the category(int) into string
				if($r_CAT == 1)
					$r_CAT = "PDF";
				
				else
					$r_CAT = "Presentation";
					
				$table.="
				<table width='100%' border='0' cellspacing='0' cellpadding='0'>
				
				<tr>
            	<td valign='top' align='left'>
				
				<div class='LectureWrap2'>
                		<div style='float:left;'><img src='".$r_CatPic."' width='55' height='65' /></div>
                        <div style='padding-right: 15px;'><h5>".$r_Title."</h5></div>
                        <div style=''><span class='s_normal'><p style='padding-right: 15px;'>".substr($r_DESC, 0, 80)."...</p></span></div>
                        <div style=''>
							<span class='ss_normal'>".substr(time_elapsed($now-$past), 0, 15)."</span>
							<div style='float:right'><a href='".$r_LINK."' title='Download this file'>Download</a></div>
						</div>
                </div>
				
				    </td>
            	</tr>
				
				
				</table>
				";
				
			}
			
			//end while
			echo $table;	//echo the result table
	
	
			?>
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
            <td class="a"><div style=" margin-top: 5px;"><span class="okb">&nbsp;</span></div></td>
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