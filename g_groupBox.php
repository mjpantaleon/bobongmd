

<form action="#" method="post" >
<div id="groupBG" onclick="hideGroup()"></div>
<div id="groupFG">
		
        <div class="left">
        
        	<div style=" margin: 20px 15px 15px; 
            font-size:18px; text-align:left; border-bottom: 1px solid #999;">
           	  <img src="images/icons/Users.png" width="35" height="35">
            	Add New Group
              <span style="float: right; cursor: pointer; margin-right: 15px;">
              <img src="images/icons/x.png" width="20" height="20" onClick="hideGroup()" title="Close">
              <!--<input type="button" class="button" onClick="hideShowInbox()" value="X" title="Close" >-->
              </span>
            </div>
            
            <div style=' border:1px dashed #CCC; margin: 25px 15px 15px; background-color:#FFF; padding: 3px;'>
				<div style="padding: 9px 0; margin: 0 15px; ">
                	Group Name: <br><input type="text" name="txtGN" class="InputBox4" placeholder="Enter Group Name here..." maxlength="35" required >
                    <span class="error">*</span><br />
					<div class="s_normal">*NOTE: Create a group name NOT more than 35 characters</div>
                </div>
                
                <div style="padding: 9px 0; margin: 0 15px;">
                	Description: <br>
                    
                    <br><textarea name="txtDESK" id="txtDESK" style="resize: none; "  class="textArea2" rows="10" cols="48" 
					required placeholder="Enter Description here..."></textarea>
                    <span class="error">*</span><br />
                    <span class="s_normal">&nbsp;</span>
                </div>
                		
               
            </div>
            
            <input type="submit" name="cmbAddNew" value="Add New Group">
            
		</div>


</div>
</form>