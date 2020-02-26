<form action="#" method="post" enctype="multipart/form-data">
<div id="profBoxBG" onclick="hideProfBox()"></div>
<div id="profBoxFG">
	<!-- CONTENT HERE -->

    	<div class="left">
        	<div style=" margin: 20px 15px 15px; 
            font-size:18px; text-align:left; border-bottom: 1px solid #999;">
           	  <img src="images/icons/default_user.jpg" width="35" height="35">
            	Change Profile Picture
              <span style="float: right; cursor: pointer; margin-right: 15px;">
              <img src="images/icons/x.png" width="20" height="20" onClick="hideProfBox()" title="Close" alt="img1" />
              </span>
            </div>
            
            <div style=" margin: 20px 15px 15px;">
            	<input type="file" class="file" name="fileProfPick" id="fileProfPick" required >&nbsp;
                <input type="submit" class="" name="cmdUpdatePick" value="Upload">
            </div>
            
    	</div>
        
        
    <!-- CONTENT HERE -->
</div>
</form>