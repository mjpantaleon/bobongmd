<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Untitled Document</title>
	<script type="text/javascript">
	var TimeToFade = 1000.0;

	function fade(eid)
	{
	  var element = document.getElementById(eid);
	  if(element == null)
		return;
	
	  if(element.FadeState == null)
	  {
		if(element.style.opacity == null 
			|| element.style.opacity == '' 
			|| element.style.opacity == '1')
		{
		  element.FadeState = 2;
		}
		else
		{
		  element.FadeState = -2;
		}
	  }
	
	  if(element.FadeState == 1 || element.FadeState == -1)
	  {
		element.FadeState = element.FadeState == 1 ? -1 : 1;
		element.FadeTimeLeft = TimeToFade - element.FadeTimeLeft;
	  }
	  else
	  {
		element.FadeState = element.FadeState == 2 ? -1 : 1;
		element.FadeTimeLeft = TimeToFade;
		setTimeout("animateFade(" + new Date().getTime() + ",'" + eid + "')", 33);
	  }  
	}
	
	function animateFade(lastTick, eid)
	{  
	  var curTick = new Date().getTime();
	  var elapsedTicks = curTick - lastTick;
	
	  var element = document.getElementById(eid);
	
	  if(element.FadeTimeLeft <= elapsedTicks)
	  {
		element.style.opacity = element.FadeState == 1 ? '1' : '0';
		element.style.filter = 'alpha(opacity = ' 
			+ (element.FadeState == 1 ? '100' : '0') + ')';
		element.FadeState = element.FadeState == 1 ? 2 : -2;
		return;
	  }
	
	  element.FadeTimeLeft -= elapsedTicks;
	  var newOpVal = element.FadeTimeLeft/TimeToFade;
	  if(element.FadeState == 1)
		newOpVal = 1 - newOpVal;
	
	  element.style.opacity = newOpVal;
	  element.style.filter = 'alpha(opacity = ' + (newOpVal*100) + ')';
	
	  setTimeout("animateFade(" + curTick + ",'" + eid + "')", 33);
	}
	</script>
</head>

<body>
	<div id="fadeBlock" style="background-color:Lime;width:250px;
       height:65px;text-align:center;">
	  <br />
	  I'm Some Text
	</div>
	<br />
	<br />
	<input type="button" onclick="fade('fadeBlock');" value="Go" />
</body>
</html>
