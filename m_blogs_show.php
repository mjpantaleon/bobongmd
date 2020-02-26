

<form action="#" method="post" enctype="multipart/form-data">
<div id="blogsBG" onclick="hideBlogs()"></div>
<div id="blogsFG">
		
        <div class="left">
        
        	<div style=" margin: 20px 15px 15px; 
            font-size:18px; text-align:left; border-bottom: 1px solid #999;">
           	  <img src="images/icons/toolbar-icons/page.png" width="35" height="35">
            	Add New Blog
              <span style="float: right; cursor: pointer; margin-right: 15px;">
              <img src="images/icons/x.png" width="20" height="20" onClick="hideBlogs()" title="Close">
              <!--<input type="button" class="button" onClick="hideShowInbox()" value="X" title="Close" >-->
              </span>
            </div>
            
            <div style=' border:1px dashed #CCC; margin: 25px 15px 15px; background-color:#FFF; padding: 3px;'>
				<div style="padding: 9px 0; margin: 0 15px; border-bottom: 1px ridge #CCC;">
                	Title: <br><input type="text" name="txtTitle" class="InputBox4" placeholder="Enter Title here..." required >
                    <span class="error">*</span><br />
                </div>
                
                <div style="padding: 9px 0; margin: 0 15px; border-bottom: 1px ridge #CCC;">
                	Description: <br>
                    
                    <br><textarea name="txtDescription" id="txtDescription"  class="textArea2" rows="10" cols="48" required ></textarea>
                    <span class="error">*</span><br />
                    <span class="s_normal">&nbsp;</span>
                </div>
                
                <div style="padding: 9px 0; margin: 0 15px;">
                	Attach File: <br><br>
                    <img src="images/icons/toolbar-icons/photos.png" width="20" height="20">
                    <input class="file" type="file" name="fileAttch" id="fileAttch">
                    <br />
                    <span class="s_normal">*(Optional) Add a picture that best suit this blog.</span>
                </div>		
               
            </div>
            
            <input type="submit" name="cmbAddNew" value="Add New Blog">
            
		</div>


</div>
</form>