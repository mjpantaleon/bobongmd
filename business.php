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
<form action="#" method="post" enctype="multipart/form-data">


<td width="75%" valign="top" align="left">
	<!-- BREAD CRUMBS / SEARCH TOOL -->
	<div class="left" style="height:70px; background-color: #9C6;">
        <div class="left" style="border-bottom: 1px solid #CCC; height:68px; margin-right: 5px;">
        <span class="okw"><img src="images/icons/toolbar-icons/cart2.png" width="20" height="20" style="margin-top: 5px;" />&nbsp;BUSINESS</span>
            <div class="left" style="margin-left: 25px; margin-top: 10px;">Search by:
                <span class="left" style="margin-left: 5px; margin-top: 10px;">
                  <input type="text" name="txtSearch" onKeyUp="showSomething(this.value)" class="SearchBox" placeholder="Item Name" />
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
                    <img src="images/icons/toolbar-icons/link.png" width="20" height="20" style="margin-top: 2px;" />&nbsp;Exclusive Items for you
                </span>
                <span class="arrow"></span>
             </div>
         </div>
         
         <div class="center" style="margin-top: 15px; margin-bottom: 15px;">
			<?php echo $Message; #Echo message here ?>
         </div>
         
        
         <div class="center">
		 	<span class="s_normal">*For orders please contact bobongmd via your <a href="index.php">account</a> or <a href="register.php">register</a></span>
		</div>
        
   		 <!-- WILL DISPLAY THE DETAILS ABOUT THE USER -->
         <div id="txtHint" class="left" style=" margin-top: 15px; margin-bottom: 15px;" >
         	<!-- YOU TABLE HERE -->
            <table border="0" cellspacing="0" cellpadding="0">
            <?php
			$per_page	= 4;	#SET HOW MANY RECORDS WILL BE SHOW IN THE TABLE
			
			$Count		= 0; 	#SET DEFAULT AS ZERO
			
			#WE WILL QUERY TO GET THE NUMBER OF RECORDS IN THE TABLE
			$query		= mysql_query(" SELECT COUNT(`item_id`) FROM `business` WHERE `ST` = '1' ")
			or die(mysql_error());
			#WILL SET HOW MANY PAGES ARE THE BASED ON THE TOTAL RECORDS DIVIDED BY HOW MANY WILL BE SHOWN IN THE TABLE (6)
			$pages		= ceil(mysql_result($query, 0) / $per_page);
			#SETUP FOR THE PAGINATION DISPLAY
			$page		= ( isset( $_GET['page']))  ? (int)$_GET['page'] : 1;
			$start		= ($page - 1) * $per_page;
			
			#FETCHING THE RECORDS IN THE TABLE
			$query		= " SELECT * FROM `business` WHERE `ST` = '1'
							ORDER BY `item_id` DESC LIMIT ".$start.",".$per_page."
						";
			$result		= mysql_query($query)
			or die(mysql_error());
			while($row	= mysql_fetch_array($result))
			{
				#SET LOCAL VARIABLES
				$Count++;
				$id		= $row['item_id'];		#Item ID
				$IN		= $row['IN'];			#Item Name
				$IMG	= $row['IMG'];			#Image
				$DESK	= $row['DESK'];			#Description
				$PRCE	= $row['PRCE'];			#Price
				$ST		= $row['ST'];			#Status
				
				#DISPLAYING THE RECORD
				$table = "
				<tr>
					<td>
					
						<div class='item_wrap' style=' border: 1px solid #CCC; box-shadow: 0 0 5px #CCC; background: #FFC;  margin: 15px 25px 15px; width: 500px;'>
							<div class='item_name' style=' padding: 15px 5px; color:#900; border-bottom: 1px dashed #CCC; margin: 0 3px 3px;'>
								".$IN."
							</div>
							<div class='img_holder' style='border: 1px solid #CCC; width: 185px; height: 265px; padding: 2px; 
							margin: 0 5px 5px; background: #FFF;'>
								<img src='".$IMG."' width='185px' height='265px' />
								<div class='details_holder' style='margin: -268px 0 0 210px; width: 280px;'>
									<div class'desc' style='padding: 15px 15px 15px 0;  margin: 0 5px 5px; border-bottom: 1px dashed #CCC;'>
										<span class='s_normalb'>".$DESK."</span>
									</div>
									<div class='price' style='color:#03F; padding: 15px 15px 15px 0;'>
										<span class='s_normalb'>Selling Price:</span> ".number_format($PRCE, 2)." php
									</div>
								</div>
							</div>
						</div>
					 
					</td>
            	</tr>
				
				";
				
				echo $table;
			}
			#end while
			#FETCHING THE RECORDS IN THE TABLE
			
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