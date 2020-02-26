
<form action="#" method="post">
<!----><div id="catBG2" onclick="hideCat2()"></div>
<div id="catFG2">
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td colspan="2">
            <div style=" margin-top:20px; margin-bottom: 15px; margin-left: 15px; margin-right: 15px; 
            font-size:18px; text-align:left; border-bottom: 1px solid #999;">
            	<img src="images/icons/new.png" height="30" width="30" />
                Update Category
            <span style="float:right; cursor: pointer;"><input type="button" class="button" onclick="hideCat2()" value="X" title="Close" /></span></div>    	
      	</td>
      </tr>
      
      <tr>
        <td valign="top" align="left"><div class="left" style=" margin: 15px 0px 0 15px; border-right: 1px ridge #999;">Category:</div></td>
        <td>
        	<div class="left" style=" margin-top: 15px;">
            	<select name="cmbCat2" required autofocus >
                	<option value="">--Select Here--</option>
                    <option value="1">PDF</option>
                    <option value="2">Presentation</option>
                </select>
           </div>
        </td>
      </tr>
 
      <tr>
        <td colspan="2"><div class="center" style=" margin-top: 40px;">
        <input class="SubmitCat" type="submit" name="txtUpdateCat" value="Update New Category" /></div></td>
      </tr>
    </table>

</div><!-- -->
</form>