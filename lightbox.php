
<form action="#" method="post" enctype="multipart/form-data">
<div id="BG" onclick="hideComment()"></div>
<div id="FG">
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td colspan="2">
            <div style=" margin-top:20px; margin-bottom: 15px; margin-left: 15px; margin-right: 15px; 
            font-size:18px; text-align:left; border-bottom: 1px solid #999;">
            	<img src="images/icons/new.png" height="30" width="30" />
                Add New Lectures
                <span style="float:right; cursor: pointer;">
                <img src="images/icons/x.png" width="20" height="20" onClick="hideComment()" title="Close">
                <!--<input type="button" class="button" onclick="hideComment()" value="X" title="Close" />-->
                </span>
            </div>    	
      	</td>
      </tr>
      
      <tr>
        <td valign="top" align="left"><div class="left" style=" margin: 15px 0px 0 15px; border-right: 1px ridge #999;">Title:</div></td>
        <td>
            <div class="left" >
            	<input class="InputBox" type="text" name="txtTitle" placeholder="Insert Title here..." autofocus required />
            </div>
        </td>
      </tr>
      
      <tr>
        <td valign="top" align="left"><div class="left" style=" margin: 15px 0px 0 15px; border-right: 1px ridge #999;">Description:</div></td>
        <td>
        	<div class="left">
            	<input class="InputBox" type="text" name="txtDesc" placeholder="Insert Description here..." required />
            </div>
        </td>
      </tr>
      
      <tr>
        <td valign="top" align="left"><div class="left" style=" margin: 15px 0px 0 15px; border-right: 1px ridge #999;">Attachment:</div></td>
        <td>
        	<div class="left" style=" margin-top: 15px;">
            	<input class="file" type="file" name="file1" id="file1" required />
            </div>
        </td>
      </tr>
      
      <tr>
        <td valign="top" align="left"><div class="left" style=" margin: 15px 0px 0 15px; border-right: 1px ridge #999;">Category:</div></td>
        <td>
        	<div class="left" style=" margin-top: 15px; margin-bottom: 15px;">
            	<select name="cmbCat" required>
                	<option value="">--Category--</option>
                    <option value="1">PDF</option>
                    <option value="2">Presentation</option>
                </select>
            </div>
        </td>
      </tr>
      
      <tr>
        <td colspan="2"><div class="center"><input class="Submit" type="submit" name="txtAdd" value="Add File" /></div></td>
      </tr>
    </table>

</div>
</form>