<?php

//set flag that this is a parent file
define( 'MAIN', 1);

//require connection to the database
require('db_con.php');

#log_hit();

?>



<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="viewport" content="width=device-width,initial-scale=1.0" />
	<title>BobongMD Admin Page</title>
	<!-- CSS LOCATION -->
	<link href="css/bootstrap.css" rel="stylesheet" type="text/css" />

	<!-- SB ADMIN CSS -->
	<link href="css/sb-admin.css" rel="stylesheet">

    <!-- Morris Charts CSS -->
    <link href="css/plugins/morris.css" rel="stylesheet">

    <!-- data-Tables CSS -->
    <link href="css/plugins/dataTables.bootstrap.css" rel="stylesheet">

    <!-- Custom Fonts -->
    <link href="font-awesome/css/font-awesome.css" rel="stylesheet" type="text/css">

    <!-- Custom CSS -->
	<link href="css/custom.css" rel="stylesheet" type="text/css" />

</head>

<body>

	<!-- LOGO AND OTHER LINKS -->
	<!-- <div class='top_nav_fix'>
		<!-- FLOAT THIS SPAN TO THE RIGHT -->
		<!-- <div class='links_wrap'>	
		<span class='links'>
			<li><a href='' target='_blank' title=''>Home</a></li>
			<li><a href='' target='_blank' title=''>FAQ</a></li>
			<li><a href='' target='_blank' title=''>Contact</a></li>
		</span>
		</div>
	</div> -->

	<div class='container'>
		<div class='row'>
			<article class='col-lg-10 col-md-10 col-sm-10'>
				<table id='events' class="table table-bordered table-striped table-hover">
					<thead>
						<tr class='success'>
							<th>User ID</th>
							<th>Full Name</th>
							<th>Event Details</th>
							<th>Date</th>
							<th>User Type</th>
						</tr>
					</thead>

					<tbody>
					</tbody>	
				</table>

			</article>

			<aside class='col-lg-4 col-sm-5'>

			</aside>
			
		</div>
	</div>


	<!-- jQuery -->
    <script src="js/jquery.js"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="js/bootstrap.min.js"></script>

    <!-- Morris Charts JavaScript -->
    <script src="js/plugins/morris/raphael.min.js"></script>
    <!--<script src="js/plugins/morris/morris.min.js"></script>
    <script src="js/plugins/morris/morris-data.js"></script> -->

    <!-- FOR BOOTSTAP ADMIN -->
    <script src="js/plugins/dataTables/jquery.dataTables.js"></script>
    <!--<script src="js/plugins/dataTables/dataTables.bootstrap.js"></script>-->

    <!-- for continouos fluid container
    <script src="js/sb-admin-2.js"></script>
    <script src="js/plugins/metisMenu/metisMenu.js"></script> -->

    <script type="text/javascript">
    $(document).ready(function() {  
        $('#events').dataTable({
        	ajax : '../api.php?fn=get_events',
        	order : [
        		[3,'desc']
        	]
        });             
        $('.has-tooltip').tooltip();                      
    });
    
    </script>
    <!-- JAVASCRIPT -->
	
</body>

</html>