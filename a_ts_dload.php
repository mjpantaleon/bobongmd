<?php
/** Set flag that this is a parent file */
define( "MAIN", 1 );


//require connection to the database
require('db_con.php');
log_hit();

/*
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
*/

//default values
$Count 		= 0;
$getID		= $_GET['id'];		//get the POST id

	//if lecture id selected is clicked/detected then
	if( isset( $_GET['id']))
	{
		//select info in the data base
		$query	= " SELECT * FROM teachersec WHERE id = '".$getID."' ";
		$result	= mysql_query($query);
		$row	= mysql_fetch_array($result);
		
		$r_Title	= $row['TIT'];	//Title of the lecture selected
	}


?>

<?php include 'header.php'; ?>
<form action="#" method="post" enctype="multipart/form-data">


<td width="75%" valign="top" align="left">
	<!-- BREAD CRUMBS / SEARCH TOOL -->
	<div class="left" style="height:70px; background-color: #9C6;">
        <div class="left" style="border-bottom: 1px solid #CCC; height:68px; margin-right: 5px;">
        <span class="okw"><img src="images/icons/teacher.png" width="20" height="20" style="margin-top: 5px;" />&nbsp;TEACHER'S SECTION</span>
            <div class="left" style="margin-left: 25px; margin-top: 10px;">
            	<span class="error">*</span> All lectures uploaded here is free but need to register to be able to download the contents.
            </div>
        </div>
    </div>
    <!-- BREAD CRUMBS / SEARCH TOOL -->
    
    
    <!-- WILL DISPLAY THE CONTENT-->
    <div class="center" style="margin-top: 15px; margin-right: 5px; width: 97%;">
    
    	 <div class="left" style=" border-top-left-radius: 0.3em; border-top-right-radius: 0.3em; ">
             <div class="header_arrow">
                <span class="left">
                	<a href="a_teacherSec.php"><img src="images/icons/prev.png" width="20" height="20" title="Return Previous Page" /></a>
                    &nbsp;Download&nbsp;
                    <img src="images/icons/download_icon.png" width="20" height="20" style="margin-top: 2px;" />
                </span>
                    <span class="arrow"></span>
             </div>
         </div>
         
         <div class="center" style="margin-top: 8px; margin-bottom: 15px;">
			<!-- PROMPT MESSAGE HERE -->&nbsp;<?php echo $Message; ?>
         </div>
         
         <!-- LOAD THE MESSAGE BOX HERE -->
         <div id="showLightbox"></div>
         <!-- LOAD THE MESSAGE BOX HERE -->
        
   		 <!-- CONTENT DIV -->
         <div id="txtHint" class="left" style=" margin-top: 15px; margin-bottom: 15px; height: 340px;">
         	<!-- YOUR TABLE HERE -->
			<div class="left" style="border: 1px solid #06C; margin: 0 15px; box-shadow: inset 0 1px 10px #06C;">
            
            	<div style="padding: 15px; margin: 0 15px; border-bottom: 1px dashed #999;">
                	<img src="images/icons/Info-icon.png" width="30" height="30" />
                    Download information on '<b><?php echo $r_Title; ?></b>'
                </div>
            	<div class="center" style="padding: 25px;">
                	<p>For registered users, please <a href="index.php" class="button">Login</a> to your account to download this lecture.<br /><br />
                    For non-registered users, kindly <a href="register.php" class="button">Register</a> first before downloading this lecture.</p>
                </div>
            </div>
        	<!-- YOUR TABLE HERE -->
         </div>
         <!-- CONTENT DIV -->
    </div>
    
    <div class="center" style="margin-top: 15px; margin-bottom: 45px; "><?php echo $paginationDisplay; ?></div>
    
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