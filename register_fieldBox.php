<form action="#" method="post">
<div id="optStudBG" onclick="hideOptStudBox()"></div>
<div id="optStudFG">

	<div class="left">
    	<!-- RECENT ASSIGNENTS HERE -->
    	<div style=" margin: 20px 15px 15px; font-size:18px; text-align:left; border-bottom: 1px solid #999;">
           	  <img src="images/icons/toolbar-icons/user.png" width="35" height="35">
            	Student Info
              <span style="float: right; cursor: pointer; margin-right: 15px;">
              <img src="images/icons/x.png" width="20" height="20" onClick="hideOptStudBox()" title="Close">
              </span>
        </div>
		
		<div class='left' style=" margin: 15px 0 5px 45px;">
			<div style="padding: 5px; margin-top: 5px;">
			Subject: &nbsp;
			<span style="margin: 0 5px 0 25px;">
			<select name="cmbSubject" required>
				<option value="LabDx">LabDx</option>
				<option value="ClinDx">ClinDx</option>
				<option value="PathoA">PathoA</option>
				<option value="PathoB">PathoB</option>
				<option value="HistoTech">HistoTech</option>
				<option value="PT Patho">PT Patho</option>
			</select>
			</span>
			</div>
			
			<div style="padding:5px;">
			Section:&nbsp; 
			<span style="margin: 0 5px 0 28px;">
			<input type="text" name="txtSecs" style="border: 1px solid #CCC; padding: 3px 5px; border-radius: 0.2em;" required placeholder="write your section" />
			</span>
			</div>
			
			<div style="padding: 5px;">
			Semester:&nbsp; 
			<span style="margin: 0 5px 0 18px;">
			<select name="cmbSem" required>
				<option value="1st Sem">1st Sem</option>
				<option value="2nd Sem">2nd Sem</option>
			</select>
			</span>
			</div>
			
			<div style="padding: 5px;">
			School year:&nbsp; 
			<span style="margin: 0 5px 0 5px;">
			<select name="cmbSY" required>
				<option value="2010-2011">2010-2011</option>
				<option value="2011-2012">2011-2012</option>
				<option value="2012-2013">2012-2013</option>
				<option value="2013-2014">2013-2014</option>
				<option value="2014-2015">2014-2015</option>
				<option value="2015-2016">2015-2016</option>
				<option value="2016-2017">2016-2017</option>
				<option value="2017-2018">2017-2018</option>
				<option value="2018-2019">2018-2019</option>
				<option value="2019-2020">2019-2020</option>
			</select>
			</span>
			</div>
			
			
		</div>
		
		<div style=" padding: 10px 0 0; margin: 20px 15px 15px; font-size:18px; text-align:left; border-top: 1px solid #999;">
				<input type="submit" name="cmdAddInfo" value="  Add Info  " />
		</div>
		
	</div>
	
</div>
</form>