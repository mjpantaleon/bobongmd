<form action="#" method="post" enctype="multipart/form-data">
<div id="itemBoxBG" onclick="hideItemBox()"></div>
<div id="itemBoxFG">
	<!-- CONTENT HERE -->

    	<div class="left">
        	<div style=" margin: 20px 15px 15px; 
            font-size:18px; text-align:left; border-bottom: 1px solid #999;">
           	  <img src="images/icons/toolbar-icons/cart2.png" width="35" height="35">
            	Change Item Image
              <span style="float: right; cursor: pointer; margin-right: 15px;">
              <img src="images/icons/x.png" width="20" height="20" onClick="hideItemBox()" title="Close" alt="img1" />
              </span>
            </div>
            
            <div style=" margin: 20px 15px 15px;">
            	<input type="file" class="file" name="itemPick" id="itemPick" required >&nbsp;
                <input type="submit" name="cmdUpdatePick" value="Upload">
            </div>
            
    	</div>
        
        
    <!-- CONTENT HERE -->
</div>
</form>