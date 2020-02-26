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
<title>Ajax ComboBox Sample</title>
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

	<div class="RegisterWrap">
		<div class="infoFloat">How are you related?</div>
		<div class="left">
			<label><input type="radio" name="OptRel" value="Student" onclick="showFieldBox()" />&nbsp;Student</label>&nbsp;&nbsp;&nbsp;&nbsp;
			<label><input type="radio" name="OptRel" value="Others" onclick="showOtherField()" />&nbsp;Others</label>
		</div>
		
		<div class="left">
			<div id="showFields"></div>
		</div>
		
	</div>
</body>
</html>
