<?php
/** Set flag that this is a parent file */
define( "MAIN", 1 );


//require connection to the database
require('db_con.php');

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Image Aspect Ratio Sample</title>
	<script type="text/javascript" src="javascripts.js">  </script>
    <SCRIPT language=Javascript>
       <!--
       function isNumberKey(evt)
       {
          var charCode = (evt.which) ? evt.which : event.keyCode;
          if (charCode != 46 && charCode > 31 
            && (charCode < 48 || charCode > 57))
             return false;

          return true;
       }
       //-->
    </SCRIPT>
	<style type="text/css">
		@import url(includes/styles.css);
	</style>
	
</head>

<body>
	
	<!--<div class="item_wrap2"> *IMPORTANT!!!! this div has the target width and heigth  !!!!IMPORTANT*
		<div class="image_div">
			<img class="img" src="bussiness/phones/sonyz.png" />
		</div>
	</div>-->
	
	<div class='item_wrap2'>
		<a href='#?itemID=".$id."' title='Edit this item'>
			<div class='image_div'>
				<img class='img' src='bussiness/phones/sonyz.png' />
			</div>
		</a>
		
		<div class='details_holder' style='margin: -268px 0 0 210px; width: 280px;'>
		
			<div class'desc' style='padding: 15px 15px 15px 0;  margin: 0 5px 5px; border-bottom: 1px dashed #CCC;'>
				<span class='s_normalb'>".$DESK."</span>
			</div>
			
			<div class='price' style='color:#03F; padding: 15px 15px 15px 0;'>
				<span class='s_normalb'>Selling Price:</span> ".number_format($PRCE, 2)." php
			</div>
			
			<div class='flagMe'>
				<a href='#?item=".$id."' title='Click this if you want to view comments on this Item'>
					View Comments >>>
				</a>
			</div>
		</div>
	</div>
	
</body>
</html>
