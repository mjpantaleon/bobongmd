<form action="#" method="post">
<div id="optOthrBG" onclick="hideOptOthrBox()"></div>
<div id="optOthrFG">
	
	<div class="left">
    	<!-- RECENT ASSIGNENTS HERE -->
    	<div style=" margin: 20px 15px 15px; font-size:18px; text-align:left; border-bottom: 1px solid #999;">
           	  <img src="images/icons/toolbar-icons/user.png" width="35" height="35">
            	How are you related?
              <span style="float: right; cursor: pointer; margin-right: 15px;">
              <img src="images/icons/x.png" width="20" height="20" onClick="hideOptOthrBox()" title="Close">
              </span>
        </div>

		<div class='left' style=" margin: 15px 0 5px 45px;">
		Choose from here: &nbsp;
			<div style="padding: 5px; margin-top: 5px;">
			<select name="cmbREL" required>
				<option value="Friend">Friend</option>
				<option value="Staff">Staff</option>
				<option value="Client">Client</option>
				<option value="Relative">Relative</option>
				<option value="Colleague">Colleague</option>
				<option value="Acquaintance">Acquaintance</option>
				<option value="Associate">Associate</option>
				<option value="Mentee">Mentee</option>
				<option value="Mentor">Mentor</option>
				<option value="Badminton Buddy">Badminton Buddy</option>
				<option value="Classmate">Classmate</option>
				<option value="Batchmate">Batchmate</option>
				<option value="Neighbor">Neighbor</option>
				<option value="Schoolmate">Schoolmate</option>
			</select>
			</div>
		</div>
		
		<div style=" padding: 10px 0 0; margin: 20px 15px 15px; font-size:18px; text-align:left; border-top: 1px solid #999;">
				<input type="submit" name="cmdAddOther" value="  Add Info  " />
		</div>
		
	</div>
	
</div>
</form>