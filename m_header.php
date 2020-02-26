<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link rel="shortcut icon" href="images/icons/toolbar-icons/BobongMD_icon.gif" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>BobongMD Official</title>
	<style type="text/css">
		@import url(includes/styles.css);
	</style>
    <script type="text/javascript" src="javascripts.js">  </script>
  
	<script type="text/javascript">
	
		function isNumberKey(evt)
		{
		  var charCode = (evt.which) ? evt.which : event.keyCode;
		  if (charCode != 46 && charCode > 31 
			&& (charCode < 48 || charCode > 57))
			 return false;
		
		  return true;
		}
		
		function deleteBlog()
		{
			var answer = confirm("Deleting Blog with ID [ <?php echo $getBlogID ?> ]. You may 'Cancel' this action other wise just click 'OK'. ")
			
			if (answer)
				{
					alert("Blog with ID [ <?php echo $getBlogID ; ?> ] has been deleted. ")
					window.location = "m_blogs_del.php?blogID=<?php echo $getBlogID  ?>";
				}
			else
				{
				}
		}
	
		function deleteUser()
		{
			/*
			var userid	= "User ID is <?ph// echo $e_UID ?>.";
			
			document.write(userid);*/
			
			var answer = confirm("Deleting user with ID [ <?php echo $getUID ?> ]. This action is cannot be undone. ")
			
			if (answer)
				{
					alert("User with ID [ <?php echo $getUID ; ?> ] has been deleted. ")
					window.location = "m_users_del.php?uid=<?php echo $getUID  ?>";
				}
			else
				{
				}
		}
		
		function acceptUser()
		{
			/*
			var userid	= "User ID is <?ph// echo $e_UID ?>.";
			
			document.write(userid);*/
			
			var answer = confirm("Accepting user with ID [ <?php echo $getUID ?> ].")
			
			if (answer)
				{
					alert("User with ID [ <?php echo $getUID ; ?> ] is now active. ")
					window.location = "m_users_accpt.php?uid=<?php echo $getUID  ?>";
				}
			else
				{
				}
		}
		
		//editing lectures
		//locking lecture
		function lockItem()
		{
			var answer = confirm("Locking '<?php echo $e_Title; ?>' w/ID [ <?php echo $getID;  ?> ]. You may 'Cancel' this action other wise just click 'OK'")
			
			if (answer)
				{
					alert("'<?php echo $e_Title; ?>' has been locked. ")
					window.location = "m_ts_edit_lock.php?id=<?php echo $getID;  ?>";
				}
			else
				{
				}
		}
		
		//activating lecture
		function activateItem()
		{
			var answer = confirm("Activating '<?php echo $e_Title; ?>' w/ID [ <?php echo $getID;  ?> ]. You may 'Cancel' this action other wise just click 'OK'")
			
			if (answer)
				{
					alert("'<?php echo $e_Title; ?>' has been ACTIVATED. ")
					window.location = "m_ts_edit_activ8.php?id=<?php echo $getID;  ?>";
				}
			else
				{
				}
		}
		
		//deleting lecture
		function delLecture()
		{
			var answer = confirm("Deleting '<?php echo $e_Title; ?>' w/ID [ <?php echo $getID;  ?> ]. THIS ACTION CAN NOT BE UNDONE. You may 'Cancel' this action other wise just click 'OK' ")
			
			if (answer)
				{
					alert("'<?php echo $e_Title; ?>' has been DELETED. ")
					window.location = "m_ts_edit_delete.php?id=<?php echo $getID;  ?>";
				}
			else
				{
				}
		}
		
		function delAssgn()
		{
			var answer = confirm("Deleting '<?php echo $r_SUB; ?>'. THIS ACTION CAN NOT BE UNDONE. You may 'Cancel' this action other wise just click 'OK' ")
			
			if (answer)
				{
					alert("'<?php echo $r_SUB; ?>' has been DELETED. ")
					window.location = "m_ass_delete.php?id=<?php echo $getTS;  ?>";
				}
			else
				{
				}
		}
		
    </script>
	
	
	<!-- |||||||||||||||| BULLETIN BOARD SCRIPTS AND CSS |||||||||||||||| -->
	<!-- |||||||||||||||| BULLETIN BOARD SCRIPTS AND CSS |||||||||||||||| -->
	<!-- |||||||||||||||| BULLETIN BOARD SCRIPTS AND CSS |||||||||||||||| -->
	<script type="text/javascript">
	var pausecontent2=new Array();
		<?php
		
		#WE WILL GET THE CONTENT OF MSG_BOARD TABLE, WE ARE GONA DISPLAY ONLY ONE CONTENT
		$query = mysql_query("SELECT * FROM `msg_board` WHERE `ST` = '1' ORDER BY `msg_id` DESC LIMIT 1");
		$counter = 0;
		while($row = mysql_fetch_array($query))
		{
			#SET LOCAL VARIABLE
			$CONT = $row['CONT'];
			
			echo " pausecontent2[".$counter."]='".$CONT."' ";
			$counter++;
		}
		
		?>
	</script>
	<!--Example message arrays for the two demo scrollers -->
	
	<script type="text/javascript">
	
	/***********************************************
	* Pausing up-down scroller- © Dynamic Drive (www.dynamicdrive.com)
	* This notice MUST stay intact for legal use
	* Visit http://www.dynamicdrive.com/ for this script and 100s more.
	***********************************************/
	
	function pausescroller(content, divId, divClass, delay){
	this.content=content //message array content
	this.tickerid=divId //ID of ticker div to display information
	this.delay=delay //Delay between msg change, in miliseconds.
	this.mouseoverBol=0 //Boolean to indicate whether mouse is currently over scroller (and pause it if it is)
	this.hiddendivpointer=1 //index of message array for hidden div
	document.write('<div id="'+divId+'" class="'+divClass+'" style="position: relative; overflow: hidden"><div class="innerDiv" style="position: absolute; width: 100%" id="'+divId+'1">'+content[0]+'</div><div class="innerDiv" style="position: absolute; width: 100%; visibility: hidden" id="'+divId+'2">'+content[1]+'</div></div>')
	var scrollerinstance=this
	if (window.addEventListener) //run onload in DOM2 browsers
	window.addEventListener("load", function(){scrollerinstance.initialize()}, false)
	else if (window.attachEvent) //run onload in IE5.5+
	window.attachEvent("onload", function(){scrollerinstance.initialize()})
	else if (document.getElementById) //if legacy DOM browsers, just start scroller after 0.5 sec
	setTimeout(function(){scrollerinstance.initialize()}, 500)
	}
	
	// -------------------------------------------------------------------
	// initialize()- Initialize scroller method.
	// -Get div objects, set initial positions, start up down animation
	// -------------------------------------------------------------------
	
	pausescroller.prototype.initialize=function(){
	this.tickerdiv=document.getElementById(this.tickerid)
	this.visiblediv=document.getElementById(this.tickerid+"1")
	this.hiddendiv=document.getElementById(this.tickerid+"2")
	this.visibledivtop=parseInt(pausescroller.getCSSpadding(this.tickerdiv))
	//set width of inner DIVs to outer DIV's width minus padding (padding assumed to be top padding x 2)
	this.visiblediv.style.width=this.hiddendiv.style.width=this.tickerdiv.offsetWidth-(this.visibledivtop*2)+"px"
	this.getinline(this.visiblediv, this.hiddendiv)
	this.hiddendiv.style.visibility="visible"
	var scrollerinstance=this
	document.getElementById(this.tickerid).onmouseover=function(){scrollerinstance.mouseoverBol=1}
	document.getElementById(this.tickerid).onmouseout=function(){scrollerinstance.mouseoverBol=0}
	if (window.attachEvent) //Clean up loose references in IE
	window.attachEvent("onunload", function(){scrollerinstance.tickerdiv.onmouseover=scrollerinstance.tickerdiv.onmouseout=null})
	setTimeout(function(){scrollerinstance.animateup()}, this.delay)
	}
	
	
	// -------------------------------------------------------------------
	// animateup()- Move the two inner divs of the scroller up and in sync
	// -------------------------------------------------------------------
	
	pausescroller.prototype.animateup=function(){
	var scrollerinstance=this
	if (parseInt(this.hiddendiv.style.top)>(this.visibledivtop+5)){
	this.visiblediv.style.top=parseInt(this.visiblediv.style.top)-5+"px"
	this.hiddendiv.style.top=parseInt(this.hiddendiv.style.top)-5+"px"
	setTimeout(function(){scrollerinstance.animateup()}, 50)
	}
	else{
	this.getinline(this.hiddendiv, this.visiblediv)
	this.swapdivs()
	setTimeout(function(){scrollerinstance.setmessage()}, this.delay)
	}
	}
	
	// -------------------------------------------------------------------
	// swapdivs()- Swap between which is the visible and which is the hidden div
	// -------------------------------------------------------------------
	
	pausescroller.prototype.swapdivs=function(){
	var tempcontainer=this.visiblediv
	this.visiblediv=this.hiddendiv
	this.hiddendiv=tempcontainer
	}
	
	pausescroller.prototype.getinline=function(div1, div2){
	div1.style.top=this.visibledivtop+"px"
	div2.style.top=Math.max(div1.parentNode.offsetHeight, div1.offsetHeight)+"px"
	}
	
	// -------------------------------------------------------------------
	// setmessage()- Populate the hidden div with the next message before it's visible
	// -------------------------------------------------------------------
	
	pausescroller.prototype.setmessage=function(){
	var scrollerinstance=this
	if (this.mouseoverBol==1) //if mouse is currently over scoller, do nothing (pause it)
	setTimeout(function(){scrollerinstance.setmessage()}, 100)
	else{
	var i=this.hiddendivpointer
	var ceiling=this.content.length
	this.hiddendivpointer=(i+1>ceiling-1)? 0 : i+1
	this.hiddendiv.innerHTML=this.content[this.hiddendivpointer]
	this.animateup()
	}
	}
	
	pausescroller.getCSSpadding=function(tickerobj){ //get CSS padding value, if any
	if (tickerobj.currentStyle)
	return tickerobj.currentStyle["paddingTop"]
	else if (window.getComputedStyle) //if DOM2
	return window.getComputedStyle(tickerobj, "").getPropertyValue("padding-top")
	else
	return 0
	}
	
	</script>
		
	
	<style type="text/css">
		@import url(includes/styles.css);
	</style>
	
	<style type="text/css">
	/*Example CSS for the two demo scrollers*/
	
	#pscroller1{
		width: 200px;
		height: 100px;
		border: 1px solid black;
		padding: 5px;
		background-color: lightyellow;
	
	}
	
	/* CONTROL THE DISPLAY HEIGHT HERE */
	#pscroller2{
		width: 100%;
		height: 90px;
		border: 0px solid black;
		padding: 3px;
		color: #0000FF;
	}
	
	#pscroller2 a{
		text-decoration: none;
	}
	
	.someclass{ //class to apply to your scroller(s) if desired
	}
	
	</style>
	<!-- |||||||||||||||| BULLETIN BOARD SCRIPTS AND CSS |||||||||||||||| -->
	<!-- |||||||||||||||| BULLETIN BOARD SCRIPTS AND CSS |||||||||||||||| -->
	<!-- |||||||||||||||| BULLETIN BOARD SCRIPTS AND CSS |||||||||||||||| -->
	
	
<!--<meta http-equiv="refresh" content="30" />-->
</head>

<body background="images/btn/body_bg.png">

	<!-- LOAD DLOADS POP-UP HERE-->
	<div id="showOrders"></div>
    <!-- LOAD DLOADS POP-UP HERE-->

	<!-- LOAD DLOADS POP-UP HERE-->
	<div id="showItemBox"></div>
    <!-- LOAD DLOADS POP-UP HERE-->
	
    <!-- LOAD DLOADS POP-UP HERE-->
	<div id="showProfbox2"></div>
	<!-- LOAD DLOADS POP-UP HERE-->
    
	<!-- LOAD DLOADS POP-UP HERE-->
    <div id="showBusiness"></div>
    <!-- LOAD DLOADS POP-UP HERE-->

    <!-- LOAD DLOADS POP-UP HERE-->
    <div id="showDloads"></div>
    <!-- LOAD DLOADS POP-UP HERE-->

    <!-- LOAD THE MESSAGE BOX HERE -->
    <div id="showLightbox"></div>
    <!-- LOAD THE MESSAGE BOX HERE -->

    <!-- LOAD BLOGS POP-UP HERE-->
    <div id="showBlogs"></div>
    <!-- LOAD BLOGS POP-UP HERE-->

    <!-- LOAD THE MESSAGE BOX HERE -->
    <div id="showUserReg"></div>
    <!-- LOAD THE MESSAGE BOX HERE -->
    
     <!-- LOAD THE MESSAGE BOX HERE -->
    <div id="showSentByHere2"></div>
    <!-- LOAD THE MESSAGE BOX HERE -->
    
    <!-- LOAD ASSIGNMENT POPUP -->
    <div id="showAssign"></div>
    <!-- LOAD ASSIGNMENT POPUP -->
    
<center>
<table width="75%" style="background-color:#FFF; border-radius: 0.3em;" border="0" cellpadding="0" cellspacing="0">
	<!-- HEADER HERE -->
	<tr>
    	<td height="145px" style="border-bottom: 1px solid #FFF;">
        	<?php include 'includes/m_menu.php'; ?>
        </td>
    </tr>
	<!-- HEADER HERE -->
    
    
    <!-- BODY HERE -->
    <tr>
    	<td>
        	<table width="100%" style="background-color: #FFF; border-top: 1px solid #999; border-left: 1px solid #999; border-right:1px solid #999;  
            border-top-right-radius: 0.3em; border-top-left-radius: 0.3em; box-shadow: 1px 1px 8px #CCC;" cellpadding="0" cellspacing="0">
            	<tr height="420" valign="top" align="left">
                <!-- CONTENT HERE -->