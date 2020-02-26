
<form action="#" method="post" enctype="multipart/form-data">
<div id="upicBG" onclick="hide_upic()"></div>
<div id="upicFG">

	<div class="left">
        
        <div style=" margin: 20px 15px 15px; 
        font-size:18px; text-align:left; border-bottom: 1px solid #999;">
          <img src="images/icons/toolbar-icons/photos.png" width="35" height="35">
            Upload new picture attachment
          <span style="float: right; cursor: pointer; margin-right: 15px;">
          <img src="images/icons/x.png" width="20" height="20" onClick="hide_upic()" title="Close">
          <!--<input type="button" class="button" onClick="hideShowInbox()" value="X" title="Close" >-->
          </span>
        </div>
        
        <div style=' border:1px dashed #CCC; margin: 25px 15px 15px; background-color:#FFF; padding: 3px;'>
            <div style="padding: 9px 0; margin: 0 15px;">
                Attach File: <br>
                <img src="images/icons/toolbar-icons/photos.png" width="20" height="20">
                <input class="file" type="file" name="uPic" id="uPic" required >
                <br />
                <span class="s_normal">* A high definition picture may take a while to load.</span>
            </div>
        </div>
        
        <input type="submit" name="cmdUploadNew" value="Upload New Picture" />
	</div>

</div>

</form>