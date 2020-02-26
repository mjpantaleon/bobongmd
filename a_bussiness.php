<?php
//set flag that this is a parent file
define( 'MAIN', 1);


//require connection to the database
require('db_con.php');
log_hit();
?>

<?php include 'header.php'; ?>

<td width="75%" valign="top" align="left">
	<div class="left" style="margin-top: 15px; margin-left: 15px; margin-right: 15px; height:55px; border-bottom: 1px solid #CCC;">
    <span class="okg2">BUSSINESS</span>
        <div class="left" style="margin-left: 25px; margin-top: 10px;">*All items is arranged from Latest to Oldest
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        Search by:&nbsp;<input type="text" name="txtSearch" class="SearchBox" placeholder="Item Name" />
        </div>
    </div>
    
    
    <!-- THIS PART WILL DISPLAY THE ITEMS -->
    <div style=" margin-top: 15px; margin-left: 40px; margin-right: 15px; margin-bottom: 35px; border-bottom: 1px solid #CCC; ">
    	<table width="100%">
            <tr>
                <td width="50%" valign="middle" align="center">
                	<div style=" text-align:center; font-style:italic; color: #03F;">Yellow Floral Dress</div>
                	<div style=" box-shadow: 1px 1px 5px #CF9; width: 180px; height: 200px; margin-bottom: 15px; margin-top: 15px;
                    border: 1px solid #CCC;">
                    	<img title="Click image to view specs and other details" src="bussiness/dress/DIY_dressFront.jpg" width="180" height="200" />
                    </div>
                    <div style=" margin-bottom: 15px; height: 30px;">
                    <span class="italic">Description:</span> Yellow semi-fit dress...</div>
                </td>
                
                <td width="50%" valign="middle" align="center" style="border-left:1px solid #CCC;">
                	<div style=" text-align:center; font-style:italic; color: #03F;">White Stylish Dress</div>
                	<div style=" box-shadow: 1px 1px 5px #CF9; width: 180px; height: 200px; margin-bottom: 15px; margin-top: 15px;
                    border: 1px solid #CCC;">
                    	<img title="Click image to view specs and other details" src="bussiness/dress/satin.jpg" width="180" height="200" />
                    </div>
                    <div style=" margin-bottom: 15px; height: 30px;">
                    <span class="italic">Description:</span>&nbsp;Semi-fit dress, ribbons are added to give extra appeal..</div>
                </td>
            </tr>
        </table>
    </div>
	<!-- THIS PART WILL DISPLAY THE ITEMS -->
    
</td>


<td width="25%" valign="top" align="center" style="border-left:1px solid #CCC;"><?php include 'followlinks.php'; ?></td>





<?php include 'footer.php'; ?>