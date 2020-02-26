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
$FN = $_SESSION['fullname'];
$UT_ID = $_SESSION['access'];
$UID = $_SESSION['session_user_id'];
$UT_N = $_SESSION['access_type'];

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

$Message	= "";
$Count 		= 0;


	//gets the value of uid
	$getUID = $_GET['uid'];
	
	//set that the record is NOT selected
	$flag = 0;
	
	//DISPLAY THE RECORD OF THE USER BASED ON WHAT USER ID IS CLICKED
	if( $getUID != '' )
	{
		$query 	= " SELECT * FROM user_details WHERE UID = '$getUID' ";
		$result = mysql_query($query);
		$row	= mysql_fetch_array($result);
		
		//declare variables
		$e_UN 	= $row['UN'];
		$e_FN	= $row['FN'];
		$e_EM	= $row['EM'];
		$e_CN	= $row['CN'];
		$e_AD	= $row['AD'];
		$e_REL	= $row['REL'];
		$e_ST	= $row['ST'];
		
		//this flag means that a record has been clicked
		$flag 	= 1;
	}
	//DISPLAY THE RECORD OF THE USER BASED ON WHAT USER ID IS CLICKED
	
	
	//CHANGE STATUS OF USER SELECTED
	//if button Accept is clicked/detected then
	if( isset( $_POST['cmdAccept']) )
	{
		//if the STATUS of user is 'For Approval' then
		if( $e_ST == 0 )
		{
			/**/
			//We change the status of the user selected so that he/she can access the system
			$query 	= " UPDATE user_details SET ST = '1' WHERE UID = '$getUID' ";
			$result	= mysql_query($query)
			or die(mysql_error());
			//$row	= mysql_fetch_array($result);
			
			//We change the status of the user selected so that he/she can access the system
			$query2	= " UPDATE users SET ST = '1' WHERE UID = '$getUID' ";
			$result2	= mysql_query($query2)
			or die(mysql_error());
			//$row2	= mysql_fetch_array($result2);
			
			
			//LOG THIS EVENT
			$time_in = date("H:i:sa");							
			$cur_date = date("Y-m-d");
			$cur_timestamp = $cur_date." ".$time_in;	//concatinate time + current date
			
			$query = "	INSERT INTO events SET E_TS = '$cur_timestamp', UID = '$UID', E_D = '[ ".$FN." ] Accepted User: [ ".$getUID." ]'	";
			mysql_query($query)
			or die(mysql_error());
			
			//we prompt our message
			$Message = "<span class='okb'>User [ ".$e_UN." ] with ID: [ ".$getUID." ] is now active.</span>";
			
			//declare variables
			$e_UN 	= "";
			$e_FN	= "";
			$e_EM	= "";
			$e_CN	= "";
			$e_AD	= "";
			$e_REL	= "";
			
			//set that the record is NOT selected
			$flag	= 0;
			
			//unset values
			unset( $_POST['txtUN']);
			unset( $_POST['txtFN']);
			unset( $_POST['txtEM']);
			unset( $_POST['txtCN']);
			unset( $_POST['txtAD']);
			unset( $_POST['txtREL']);
			
			/*echo "<script>document.location.href='m_users.php?msg=$Message';</script>\n";*/
			
		}
		//if the STATUS of user is 'For Approval' then
		
		
		else
			//not executing because of the disable flag
			$Message = "<span class='error'>ERROR: Please select a user you want to ACCEPT.</span>";
	}
	//if button Accept is clicked/detected then
	
	
	//if button Delete is clicked/detected then
	if( isset( $_POST['cmdDelete']) )
	{ 
		//if the STATUS of user is 'For Approval' then
		if( $e_ST == 0 )
		{
			/**/
			//execute query here
			$query 	= " DELETE FROM user_details WHERE UID = '$getUID' ";
			$result	= mysql_query($query)
			or die(mysql_error());
			//$row	= mysql_fetch_array($result);
			
			$query	= " DELETE FROM users WHERE UID = '$getUID' ";
			$result	= mysql_query($query)
			or die(mysql_error());
			//$row	= mysql_fetch_array($result);
			
			
			//LOG THIS EVENT
			$time_in = date("H:i:sa");							
			$cur_date = date("Y-m-d");
			$cur_timestamp = $cur_date." ".$time_in;	//concatinate time + current date
			
			$query = "	INSERT INTO events SET E_TS = '$cur_timestamp', UID = '$UID', E_D = '[ ".$FN." ] Deleted User: [ ".$getUID." ]'	";
			mysql_query($query)
			or die(mysql_error());
			
			
			$Message = "<span class='okb'>User [ ".$e_UN." ] with ID: [ ".$getUID." ] has been deleted.</span>";
			
		
			//declare variables
			$e_UN 	= "";
			$e_FN	= "";
			$e_EM	= "";
			$e_CN	= "";
			$e_AD	= "";
			$e_REL	= "";
			
			//set that the record is NOT selected
			$flag	= 0;
			
			
			//unset values
			unset( $_POST['txtUN']);
			unset( $_POST['txtFN']);
			unset( $_POST['txtEM']);
			unset( $_POST['txtCN']);
			unset( $_POST['txtAD']);
			unset( $_POST['txtREL']);
			
			/*echo "<script>document.location.href='m_users.php?msg=$Message';</script>\n";*/
		}
		//if the STATUS of user is 'For Approval' then
		
		
		//if the STATUS of user is 'Active' then
		elseif( $e_ST == 1 )
		{
			/**/
			//execute query here
			$query 	= " DELETE FROM user_details WHERE UID = '$getUID' ";
			$result	= mysql_query($query)
			or die(mysql_error());
			//$row	= mysql_fetch_array($result);
			
			$query	= " DELETE FROM users WHERE UID = '$getUID' ";
			$result	= mysql_query($query)
			or die(mysql_error());
			//$row	= mysql_fetch_array($result);
			
			
			//LOG THIS EVENT
			$time_in = date("H:i:sa");							
			$cur_date = date("Y-m-d");
			$cur_timestamp = $cur_date." ".$time_in;	//concatinate time + current date
			
			$query = "	INSERT INTO events SET E_TS = '$cur_timestamp', UID = '$UID', E_D = '[ ".$FN." ] Deleted User: [ ".$getUID." ]'	";
			mysql_query($query)
			or die(mysql_error());
			
			
			$Message = "<span class='okb'>User [ ".$e_UN." ] with ID: [ ".$getUID." ] has been deleted.</span>";
		
			//declare variables
			$e_UN 	= "";
			$e_FN	= "";
			$e_EM	= "";
			$e_CN	= "";
			$e_AD	= "";
			$e_REL	= "";
			
			//set that the record is NOT selected
			$flag	= 0;
			
			
			//unset values
			unset( $_POST['txtUN']);
			unset( $_POST['txtFN']);
			unset( $_POST['txtEM']);
			unset( $_POST['txtCN']);
			unset( $_POST['txtAD']);
			unset( $_POST['txtREL']);
			
		}
		//if the STATUS of user is 'Active' then
		
		else
			//not executing because of the disable flag
			$Message = "<span class='error'>ERROR: Please select a user you want to DELETE.</span>";
	}
	//if button Delete is clicked/detected then
	//CHANGE STATUS OF USER SELECTED

?>

<?php include 'm_header.php'; ?>
<form action="#" method="post">


<td width="75%" valign="top" align="left">

	<div class="left" style="height:70px; background-color: #9C6;">
        <div class="left" style="border-bottom: 1px solid #CCC; height:68px; margin-right: 5px;">
        <span class="okw"><img src="images/icons/user_icon1.png" width="20" height="20" style="margin-top: 5px;" />&nbsp;USERS</span>
            <div class="left" style="margin-left: 25px; margin-top: 10px;">Search by:
                <span class="left" style="margin-left: 5px; margin-top: 10px;">
                  <input type="text" name="txtSearch" onKeyUp="showRec(this.value)" class="SearchBox" placeholder="User Name" />
                </span> 
            </div>
        </div>
    </div>
    
    <div id="txtHint" class="center" style="margin-top: 15px; margin-left: 5px; margin-right: 5px; width: 97%;">
    
    	<table width="100%" border="0" cellspacing="0" cellpadding="4" style="
         border-top-left-radius: 0.3em; border-top-right-radius: 0.3em;">
    	  <tr>
    	    <td colspan="4">
                <div class="left" style=" border-top-left-radius: 0.3em; border-top-right-radius: 0.3em; 
         		height:25px; border-bottom: 1px solid #999;">
                 	<span class="error">FRIEND or FOE</span>
                </div>
            </td>
    	  </tr>
          
    	  <tr>
    	    <td width="25%">
            <div class="left" style="border-bottom: 1px solid #999; border-right: 1px solid #999; margin-top:5px; color:#999;">ID</div></td>
    	    <td width="25%">
            <div class="left" style="border-bottom: 1px solid #999; border-right: 1px solid #999; margin-top:5px; color:#999;">User Name</div></td>
    	    <td width="25%">
            <div class="left" style="border-bottom: 1px solid #999; border-right: 1px solid #999; margin-top:5px; color:#999;">Full Name</div></td>
            <td width="25%"><div class="left" style="border-bottom: 1px solid #999; margin-top:5px; color:#999;">Status</div></td>
  	      </tr>
         
         <!-- CONTENT HERE -->
         <?php
		 
		 //DISPLAY LIST OF SYSTEM USERS
		 
		$Count = 0;	//count default value is 0
		 
		$query = " SELECT * FROM users WHERE UT_ID = '2' ORDER BY UID DESC ";
		$result = mysql_query($query)
		or die(mysql_error());
	
		 //SELECT ALL RECORDS ORDER BY id Ascending
		//$query = mysql_query("SELECT * FROM myvids ORDER BY id asc");
		
		//////////////////////////////////////////////////// PAGINATION SET UP ///////////////////////////////////////////////////
		$nr = mysql_num_rows($result);					//get the total number of rows in the table
		if(isset($_GET['pn']))							//get pn from url vars if it is present
		{
			$pn = preg_replace('#[^0-9]#i','',$_GET['pn']); //filters everything except for numbers	
		}
		
		else //if the pn URL value is NOT present force it to be value of page number 1
		{
			$pn = 1;
		}
		
		$itemPerPage = 10; //setting for how many item will be shown in every page
		
		$lastPage = ceil($nr/$itemPerPage); //get the value of the last page in the last pagination set
		
		//ensuring that the page is NOT less than 1 and NOT greater than the last page
		if($pn < 1) //if page number is less than 1 then
		{
			$pn = 1; //force it to be 1
		}
		elseif($pn > $lastPage) //if page is greater than the last page then
		{
			$pn = $lastPage; //force it to be in the last page
		}
		
		///////////////////////////////////////creates a number to clicked between the next and the back
		/////////////////////////////////////////////////////////////////////////////////////////////////
		$centerPages = ""; //initialize the variable
		$sub1 = $pn - 1;
		$sub2 = $pn - 2;
		$add1 = $pn + 1;
		$add2 = $pn + 2;
		
		if($pn == 1)
		{
			$centerPages .= '<span class="pageActiveNum">'.$pn.'</span> &nbsp;';
			$centerPages .= '<span class="pageNumActive"><a href="'.$_SERVER['PHP_SELF']."?pn=".$add1.'">'.$add1.'</a></span> &nbsp;';
		}
		elseif($pn == $lastPage)
		{
			$centerPages .= '<span class="pageNumActive"><a href="'.$_SERVER['PHP_SELF']."?pn=".$sub1.'">'.$sub1.'</a></span> &nbsp;';
			$centerPages .= '<span class="pageActiveNum">'.$pn.'</span> &nbsp;';
		}
		elseif($pn > 2 && $pn < ($lastPage - 1))
		{
			$centerPages .= '<span class="pageNumActive"><a href="'.$_SERVER['PHP_SELF']."?pn=".$sub2.'">'.$sub2.'</a></span> &nbsp;';
			$centerPages .= '<span class="pageNumActive"><a href="'.$_SERVER['PHP_SELF']."?pn=".$sub1.'">'.$sub1.'</a></span> &nbsp;';
			$centerPages .= '<span class="pageActiveNum">'.$pn.'</span> &nbsp;';
			$centerPages .= '<span class="pageNumActive"><a href="'.$_SERVER['PHP_SELF']."?pn=".$add1.'">'.$add1.'</a></span> &nbsp;';
			$centerPages .= '<span class="pageNumActive"><a href="'.$_SERVER['PHP_SELF']."?pn=".$add2.'">'.$add2.'</a></span> &nbsp;';
		}
		elseif($pn > 1 && $pn < ($lastPage))
		{
			$centerPages .= '<span class="pageNumActive"><a href="'.$_SERVER['PHP_SELF']."?pn=".$sub1.'">'.$sub1.'</a></span> &nbsp;';
			$centerPages .= '<span class="pageActiveNum">'.$pn.'</span> &nbsp;';
			$centerPages .= '<span class="pageNumActive"><a href="'. $_SERVER['PHP_SELF']."?pn=".$add1.'">'.$add1.'</a></span> &nbsp;';
		}
		/////////////////////////////////////////////////////////////////////////////////////////////////
		///////////////////////////////////////end creates a number to clicked between the next and the back
		
		//setting the limit range..the 2 values we place to choose a range of rows in the database in our query
		$limit = 'LIMIT ' .($pn - 1) * $itemPerPage.','.$itemPerPage; 
		//setting the limit range..the 2 values we place to choose a range of rows in the database in our query		
		
		$query2 	= " SELECT * FROM users WHERE UT_ID = '2' ORDER BY UID DESC $limit ";//limit included
		$result2	= mysql_query($query2)
		or die(mysql_error());
		
		////////////////////////////////// If record of query2 is equal to 0 then DO NOT DISPLAY PAGINATION DISPLAY SETUP
		if($result2)
		{
		 
				 /////////////////////////////////////// PAGINATION DISPLAY SET UP /////////////////////////////////////////////
				$paginationDisplay = ""; //initialize the pagination output
					
				if($lastPage != "1")
				{
					//$paginationDisplay .= "Page <strong>".$pn."</strong> of ".$lastPage;
					
					if($pn != 1)
					{
						$previous = $pn - 1;
						$paginationDisplay .= '<span class="page_navs"><a href="'.$_SERVER['PHP_SELF']."?pn=".$previous.'">Back</a></span>';
					}
					
					$paginationDisplay .= "<span class='paginationNumbers'>".$centerPages."</span>";
					
					if($pn != $lastPage)
					{
						$nextPage = $pn + 1;
						$paginationDisplay .= '<span class="page_navs"><a href="'.$_SERVER['PHP_SELF']."?pn=".$nextPage.'">Next</a></span>';
					}
				}
 
 
	 
			 while($row = mysql_fetch_array($result2))
			 {
				$Count++;	//then we increament based on how many loops gona happen in the record
				 
				//we loop so we could get all records from the table users
				$UID2 	= $row['UID'];		//user id
				$UN 	= $row['UN'];		//user name
				$FN2  	= $row['FN'];		//full name
				$ST		= $row['ST'];		//Status
				
				//our status field is declared as integer so we convert it into varchar
				//if Status is equal 0 then
				if($ST == 0)
					$ST = "For Approval";	//change its value to 'Inactive'
				
				//else if Status is equal to 1 then
				elseif($ST == 1)
					$ST = "Active";		//change its value to 'Active'
				//our status field is declared as integer so we convert it into varchar	
				
				//if the record status is ACTIVE then
				if($ST == "Active")
				{
					//we create a row of records with color blue font
					 echo"
					 
					 <tr class='row0' style='cursor:pointer' onmouseover='hoverTable(this)' onmouseout='unhoverTable(this, \"row0\")'>
						<td width='25%'><div class=' left' style='border-bottom: 1px solid #999; border-right: 1px solid #999; margin-top:5px;'>
							<a href='m_users2.php?uid=$UID2'>$UID2</a></div></td>
						<td width='25%'><div class=' left' style='border-bottom: 1px solid #999; border-right: 1px solid #999; margin-top:5px;'>
							<span class='okb'>$UN</span></div></td>
						<td width='25%'><div class=' left' style='border-bottom: 1px solid #999; border-right: 1px solid #999; margin-top:5px;'>
							<span class='okb'>$FN2</span></div></td>
						<td width='25%'><div class=' left' style='border-bottom: 1px solid #999; margin-top:5px;'><span class='okb'>$ST</span></div></td>
					  </tr>
					 
					 ";
				}
				//if the record status is ACTIVE then
				
				//else if records status is FOR APPROVAL then
				else
				{
					//we create a row of color black font
					 echo"
					 
					 <tr class='row0' style='cursor:pointer' onmouseover='hoverTable(this)' onmouseout='unhoverTable(this, \"row0\")'>
						<td width='25%'><div class=' left' style='border-bottom: 1px solid #999; border-right: 1px solid #999; margin-top:5px;'>
						<a href='m_users2.php?uid=$UID2'>$UID2</a></div></td>
						<td width='25%'><div class=' left' style='border-bottom: 1px solid #999; border-right: 1px solid #999; margin-top:5px;'>$UN</div></td>
						<td width='25%'><div class=' left' style='border-bottom: 1px solid #999; border-right: 1px solid #999; margin-top:5px;'>$FN2</div></td>
						<td width='25%'><div class=' left' style='border-bottom: 1px solid #999; margin-top:5px;'>$ST</div></td>
					  </tr>
					 
					 ";
				}
				//else if records status is FOR APPROVAL then
				
			 }
			 //end while
		
		}
		////////////////////////////////// If record of query2 is equal to 0 then DO NOT DISPLAY PAGINATION DISPLAY SETUP
		 //DISPLAY LIST OF SYSTEM USERS
		 ?>
         <!-- CONTENT HERE -->
         
    	  <tr>
    	    <td colspan="4"><div class=" center" style="margin-top: 15px; margin-bottom: 5px;">
            <span class="okb">No. of records found (<?php echo $Count; ?>).</span></div></td>
    	    </tr>
  	  </table>
    </div>
    
    <div class="center" style="margin-top: 15px;"><?php echo $paginationDisplay; ?></div>
    
    <div class="center" style="margin-top: 15px; margin-bottom: 15px;"><?php echo $Message; ?></div>
</td>

<td width="25%" valign="top" align="center" style="border-left:1px solid #999;" class="a">
	<!-- DISPLAY PICTURE OF USER THAT LOGGED IN AND DETAILS -->
	<div class="right" style="height: 68px;  background-color: #9C6; border-top-right-radius: 0.3em;">
    	<table width="100%" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td><div style=" margin-top: 5px; margin-left: 25px;"><img src="" height="50" width="50" /></div></td>
            <td><div style=" margin-top: 5px;"><span class="okb"><?php echo $FN.'<br> '.$UT_N.'<br>'; ?><a href="#">Change Password</a></span></div></td>
          </tr>
        </table>	
    </div>
    <!-- DISPLAY PICTURE OF USER THAT LOGGED IN AND DETAILS -->
    
    <div class="left" style=" margin-top: 1px; margin-left: 2px; margin-right: 2px; border-top: 1px solid #9C6;">
    	<div style="margin-top: 5px; color:#9C3;"><img src="images/icons/InfoIcon.png" width="16" height="16" />&nbsp;Manage this user</div>
    </div> 
    
    <div class="center" style=" margin-left: 2px; margin-right: 2px;">
        <div style="margin-top: 15px; ">
        <input type="text" name="txtUN" value="<?php echo $e_UN; ?>" placeholder="User Name" class="InputBox2" disabled="disabled" /></div>
        <div style="margin-top: 15px; ">
        <input type="text" name="txtFN" value="<?php echo $e_FN; ?>" placeholder="Full Name" class="InputBox2" disabled="disabled"  /></div>
        <div style="margin-top: 15px; ">
        <input type="text" name="txtEM" value="<?php echo $e_EM; ?>" placeholder="Email" class="InputBox2" disabled="disabled"  /></div>
        <div style="margin-top: 15px; ">
        <input type="text" name="txtCN" value="<?php echo $e_CN; ?>" placeholder="Contact #" class="InputBox2" disabled="disabled"  /></div>
        <div style="margin-top: 15px; ">
        <input type="text" name="txtAD" value="<?php echo $e_AD; ?>" placeholder="Address" class="InputBox2" disabled="disabled"  /></div>
        <div style="margin-top: 15px; ">
        <input type="text" name="txtREL" value="<?php echo $e_REL; ?>" placeholder="Relation" class="InputBox2" disabled="disabled"  /></div>
        <div style="margin-top: 15px; ">
        
        <?php 
		
		//if Status is 'For Approval' and a record is selected then
		if( ($e_ST == 0) && ($flag == 1) )
		{
			//the user can either accept or delete the record
			echo 
			'
				<input type="submit" name="cmdAccept" value="Accept" class="Submit2" />&nbsp;&nbsp;&nbsp;&nbsp;
				<input type="submit" name="cmdDelete" value="Delete" class="Submit2" />
			'; 
		}
		
		//else if STATUS is 'Active' and a record is selected then
		elseif ( ($e_ST == 1) && ($flag == 1) )
		{
			//the user can only DELETE the record
			echo 
			'
				<input type="submit" name="cmdAccept" value="Accept" class="Submit2" disabled="disabled" />&nbsp;&nbsp;&nbsp;&nbsp;
				<input type="submit" name="cmdDelete" value="Delete" class="Submit2" />
			'; 
		}
		
		//else if NO record has been selected then
		else
		{
			//both ACCEPT and DELETE button is disabled
			echo
			'
				<input type="submit" name="cmdAccept" value="Accept" class="Submit2" disabled="disabled"  />&nbsp;&nbsp;&nbsp;&nbsp;
				<input type="submit" name="cmdDelete" value="Delete" class="Submit2" disabled="disabled" />
			';
		}
		?>
        	
        </div>
    </div>
</td>

</form>
<?php include 'footer.php'; ?>