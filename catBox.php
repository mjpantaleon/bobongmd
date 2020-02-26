
<form action="#" method="post" enctype="multipart/form-data">
<!----><div id="catBG" onclick="hideCat()"></div>
<div id="catFG">
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td colspan="2">
            <div style=" margin-top:20px; margin-bottom: 15px; margin-left: 15px; margin-right: 15px; 
            font-size:18px; text-align:left; border-bottom: 1px solid #999;">
            	<img src="images/icons/new.png" height="30" width="30" />
                Add New File Attachement
            <span style="float:right; cursor: pointer;"><input type="button" class="button" onclick="hideCat()" value="X" title="Close" /></span></div>    	
      	</td>
      </tr>
     
      <tr>
        <td valign="top" align="left"><div class="left" style=" margin: 15px 0px 0 15px; border-right: 1px ridge #999;">Attachment:</div></td>
        <td>
        	<div class="left" style=" margin-top: 15px;">
            	<input class="file" type="file" name="fileAtt" id="fileAtt" required autofocus />
            </div>
         </td>
      </tr>

      <tr>
        <td colspan="2"><div class="center" style=" margin-top: 40px;">
        <input class="SubmitCat" type="submit" name="txtUpdateFile" value="Update File Attachment" /></div></td>
      </tr>
    </table>

</div><!---->
</form>