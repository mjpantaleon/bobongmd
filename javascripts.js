function hideGroup()
{
	document.getElementById("groupBG").style.display = "none";
	document.getElementById("groupFG").style.display = "none";
}


function hideOptOthrBox()
{
	document.getElementById("optOthrBG").style.display = "none";
	document.getElementById("optOthrFG").style.display = "none";
}

function hideOptStudBox()
{
	document.getElementById("optStudBG").style.display = "none";
	document.getElementById("optStudFG").style.display = "none";
}

function hideOrderBox()
{
	document.getElementById("orderBG").style.display = "none";
	document.getElementById("orderFG").style.display = "none";
}

function hideComment()
{
	document.getElementById("BG").style.display = "none";
	document.getElementById("FG").style.display = "none";
}

function hideItemBox()
{
	document.getElementById("itemBoxBG").style.display = "none";
	document.getElementById("itemBoxFG").style.display = "none";
}

function hideProfBox()
{
	document.getElementById("profBoxBG").style.display = "none";
	document.getElementById("profBoxFG").style.display = "none";
}

function hide_g_profBox()
{
	document.getElementById("g_profBoxBG").style.display = "none";
	document.getElementById("g_profBoxFG").style.display = "none";
}


function hideBusinessBox()
{
	document.getElementById("businessBG").style.display = "none";
	document.getElementById("businessFG").style.display = "none";
}


function hideCat()
{
	document.getElementById("catBG").style.display = "none";
	document.getElementById("catFG").style.display = "none";
}


function hideCat2()
{
	document.getElementById("catBG2").style.display = "none";
	document.getElementById("catFG2").style.display = "none";
}

//GUEST HOME PAGE-> INBOX
function hideShowInbox()
{
	document.getElementById("inboxBG").style.display = "none";
	document.getElementById("inboxFG").style.display = "none";
}

function hideShowInbox2()
{
	document.getElementById("inboxBG2").style.display = "none";
	document.getElementById("inboxFG2").style.display = "none";
}


function hideShowInbox3()
{
	document.getElementById("inboxBG3").style.display = "none";
	document.getElementById("inboxFG3").style.display = "none";
}

function hideShowInbox4()
{
	document.getElementById("inboxBG4").style.display = "none";
	document.getElementById("inboxFG4").style.display = "none";
}

function hideBlogs()
{
	document.getElementById("blogsBG").style.display = "none";
	document.getElementById("blogsFG").style.display = "none";
}

function hide_upic()
{
	document.getElementById("upicBG").style.display = "none";
	document.getElementById("upicFG").style.display = "none";
}


function hide_dload()
{
	document.getElementById("dloadBG").style.display = "none";
	document.getElementById("dloadFG").style.display = "none";
}




//when a row is hovered
function hoverTable(obj)
{
	obj.style.fontWeight="bold"		
	obj.style.backgroundColor="#FF9"			
}

//when a row is unhovered
function unhoverTable(obj, stl)
{
	obj.style.fontWeight="normal"	
	if(stl=='row0')
		obj.style.backgroundColor="#FFF";
	else if(stl=='row1')
		obj.style.backgroundColor="#FFF";			
}

	
//ajax function on searching user names
function showRec(Str)
{
	if (Str == "")
	{
		document.getElementById("txtHint").innerHTML="";
		return;
	}
	
	if(window.XMLHttpRequest)
	{
		xmlhttp = new XMLHttpRequest();
	}
	else
	{
		xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
	}
	xmlhttp.open("GET","m_users_get.php?q="+Str,false);
	xmlhttp.send(null);	
	
	var reply = xmlhttp.responseText;
	//alert(reply);
	document.getElementById("txtHint").innerHTML=reply;
}




function showLec(Str)
{
	if (Str == "")
	{
		document.getElementById("txtHint").innerHTML="";
		return;
	}
	
	if(window.XMLHttpRequest)
	{
		xmlhttp = new XMLHttpRequest();
	}
	else
	{
		xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
	}
	xmlhttp.open("GET","m_ts_getLec.php?q="+Str,false);
	xmlhttp.send(null);	
	
	var reply = xmlhttp.responseText;
	//alert(reply);
	document.getElementById("txtHint").innerHTML=reply;
}

//HOME TEACHER SECTION SEARCH TOOL
function showLecHome(Str)
{
	if (Str == "")
	{
		document.getElementById("txtHint").innerHTML="";
		return;
	}
	
	if(window.XMLHttpRequest)
	{
		xmlhttp = new XMLHttpRequest();
	}
	else
	{
		xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
	}
	xmlhttp.open("GET","m_ts_getLec2.php?q="+Str,false);
	xmlhttp.send(null);	
	
	var reply = xmlhttp.responseText;
	//alert(reply);
	document.getElementById("txtHint").innerHTML=reply;
}


//GUEST TEACHER SECTION SEARCH TOOL
function showLecHome2(Str)
{
	if (Str == "")
	{
		document.getElementById("txtHint").innerHTML="";
		return;
	}
	
	if(window.XMLHttpRequest)
	{
		xmlhttp = new XMLHttpRequest();
	}
	else
	{
		xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
	}
	xmlhttp.open("GET","m_ts_getLec3.php?q="+Str,false);
	xmlhttp.send(null);	
	
	var reply = xmlhttp.responseText;
	//alert(reply);
	document.getElementById("txtHint").innerHTML=reply;
}


//ajaxfunction on getting the lectures


//ajax  fucntion on register page
function showFieldBox()
	{
		//these code will search for an element havent an ID 'BG' then turn its style.display into 'block'
		/*document.getElementById("BG").style.display = "block";
		document.getElementById("FG").style.display = "block";*/
		
		//this part says that you were creating an ajax object
		var ajaxObject = null;	//value is null
		
		//this parts checks the browser of the user
		//is the user browser is taking xmlhttp request then
		if(window.XMLHttpRequest)
			//we configure our ajax object to take a xmlhttp request
			ajaxObject = new XMLHttpRequest();	//firefox, chrome
		
		//but if the user browser is taking an activeXobject then
		else if(window.ActiveXObject)
			//we configure our ajax object to take an activeXobject
			ajaxObject = new ActiveXObject("microsoft.XMLHTTP");	//Internet Explorer
		
		//if the user has the compatible browser (fire fox, Chrome, I.E. etc) then
		if(ajaxObject != null)
		{
			//GET method is used to get whatever value is in hte 'lightbox.php' and we want this to happen syncronisly
			//WE SENDING THE REQUEST HERE
			ajaxObject.open("GET", "register_fieldBox.php", true);
			ajaxObject.send();
			
		}
		//else if user doesnt have a compatible browser then
		else
			//we alert the user
			alert("You do not have the compatible browser");
			
		ajaxObject.onreadystatechange=function()
		{
			//when ajaxObject got all the information that it needs then
			if(ajaxObject.readyState == 4 && ajaxObject.status == 200)
				//we place the all the info the div whos ID is 'showlightbox'
				document.getElementById("showStudentInfo").innerHTML = ajaxObject.responseText;	//Every the request got is store in responsetext
		};
		
		
		
	}// JavaScript Document
	
	function showOtherField()
	{
		//these code will search for an element havent an ID 'BG' then turn its style.display into 'block'
		/*document.getElementById("BG").style.display = "block";
		document.getElementById("FG").style.display = "block";*/
		
		//this part says that you were creating an ajax object
		var ajaxObject = null;	//value is null
		
		//this parts checks the browser of the user
		//is the user browser is taking xmlhttp request then
		if(window.XMLHttpRequest)
			//we configure our ajax object to take a xmlhttp request
			ajaxObject = new XMLHttpRequest();	//firefox, chrome
		
		//but if the user browser is taking an activeXobject then
		else if(window.ActiveXObject)
			//we configure our ajax object to take an activeXobject
			ajaxObject = new ActiveXObject("microsoft.XMLHTTP");	//Internet Explorer
		
		//if the user has the compatible browser (fire fox, Chrome, I.E. etc) then
		if(ajaxObject != null)
		{
			//GET method is used to get whatever value is in hte 'lightbox.php' and we want this to happen syncronisly
			//WE SENDING THE REQUEST HERE
			ajaxObject.open("GET", "register_Otherfield.php", true);
			ajaxObject.send();
			
		}
		//else if user doesnt have a compatible browser then
		else
			//we alert the user
			alert("You do not have the compatible browser");
			
		ajaxObject.onreadystatechange=function()
		{
			//when ajaxObject got all the information that it needs then
			if(ajaxObject.readyState == 4 && ajaxObject.status == 200)
				//we place the all the info the div whos ID is 'showlightbox'
				document.getElementById("showOtherField").innerHTML = ajaxObject.responseText;	//Every the request got is store in responsetext
		};
		
		
		
	}// JavaScript Document
//ajax  fucntion on register page		

//ajax function for display a lightbox for messaging purposes
function showMessageBox()
{
	//these code will search for an element havent an ID 'BG' then turn its style.display into 'block'
	/*document.getElementById("BG").style.display = "block";
	document.getElementById("FG").style.display = "block";*/
	
	//this part says that you were creating an ajax object
	var ajaxObject = null;	//value is null
	
	//this parts checks the browser of the user
	//is the user browser is taking xmlhttp request then
	if(window.XMLHttpRequest)
		//we configure our ajax object to take a xmlhttp request
		ajaxObject = new XMLHttpRequest();	//firefox, chrome
	
	//but if the user browser is taking an activeXobject then
	else if(window.ActiveXObject)
		//we configure our ajax object to take an activeXobject
		ajaxObject = new ActiveXObject("microsoft.XMLHTTP");	//Internet Explorer
	
	//if the user has the compatible browser (fire fox, Chrome, I.E. etc) then
	if(ajaxObject != null)
	{
		//GET method is used to get whatever value is in hte 'lightbox.php' and we want this to happen syncronisly
		//WE SENDING THE REQUEST HERE
		ajaxObject.open("GET", "m_users_msgbox.php", true);
		ajaxObject.send();
		
	}
	//else if user doesnt have a compatible browser then
	else
		//we alert the user
		alert("You do not have the compatible browser");
		
	ajaxObject.onreadystatechange=function()
	{
		//when ajaxObject got all the information that it needs then
		if(ajaxObject.readyState == 4 && ajaxObject.status == 200)
			//we place the all the info the div whos ID is 'showlightbox'
			document.getElementById("showLightbox").innerHTML = ajaxObject.responseText;	//Every the request got is store in responsetext
	};
	
	
	
}// JavaScript Document



//ajax function for display a lightbox for messaging purposes
function showInputBox()
{
	//these code will search for an element havent an ID 'BG' then turn its style.display into 'block'
	/*document.getElementById("BG").style.display = "block";
	document.getElementById("FG").style.display = "block";*/
	
	//this part says that you were creating an ajax object
	var ajaxObject = null;	//value is null
	
	//this parts checks the browser of the user
	//is the user browser is taking xmlhttp request then
	if(window.XMLHttpRequest)
		//we configure our ajax object to take a xmlhttp request
		ajaxObject = new XMLHttpRequest();	//firefox, chrome
	
	//but if the user browser is taking an activeXobject then
	else if(window.ActiveXObject)
		//we configure our ajax object to take an activeXobject
		ajaxObject = new ActiveXObject("microsoft.XMLHTTP");	//Internet Explorer
	
	//if the user has the compatible browser (fire fox, Chrome, I.E. etc) then
	if(ajaxObject != null)
	{
		//GET method is used to get whatever value is in hte 'lightbox.php' and we want this to happen syncronisly
		//WE SENDING THE REQUEST HERE
		ajaxObject.open("GET", "g_user_msgbox.php", true);
		ajaxObject.send();
		
	}
	//else if user doesnt have a compatible browser then
	else
		//we alert the user
		alert("You do not have the compatible browser");
		
	ajaxObject.onreadystatechange=function()
	{
		//when ajaxObject got all the information that it needs then
		if(ajaxObject.readyState == 4 && ajaxObject.status == 200)
			//we place the all the info the div whos ID is 'showlightbox'
			document.getElementById("showMessagebox").innerHTML = ajaxObject.responseText;	//Every the request got is store in responsetext
	};
	
	
	
}// JavaScript Document

//g_profile_edit lightbox pop-up
function showProf()
{
	//this part says that you were creating an ajax object
	var ajaxObject = null;	//value is null
	
	//this parts checks the browser of the user
	//is the user browser is taking xmlhttp request then
	if(window.XMLHttpRequest)
		//we configure our ajax object to take a xmlhttp request
		ajaxObject = new XMLHttpRequest();	//firefox, chrome
	
	//but if the user browser is taking an activeXobject then
	else if(window.ActiveXObject)
		//we configure our ajax object to take an activeXobject
		ajaxObject = new ActiveXObject("microsoft.XMLHTTP");	//Internet Explorer
	
	//if the user has the compatible browser (fire fox, Chrome, I.E. etc) then
	if(ajaxObject != null)
	{
		//GET method is used to get whatever value is in hte 'lightbox.php' and we want this to happen syncronisly
		//WE SENDING THE REQUEST HERE
		ajaxObject.open("GET", "g_profBox.php", true);
		ajaxObject.send();
		
	}
	//else if user doesnt have a compatible browser then
	else
		//we alert the user
		alert("You do not have the compatible browser");
		
	ajaxObject.onreadystatechange=function()
	{
		//when ajaxObject got all the information that it needs then
		if(ajaxObject.readyState == 4 && ajaxObject.status == 200)
			//we place the all the info the div whos ID is 'showlightbox'
			document.getElementById("showProfbox").innerHTML = ajaxObject.responseText;	//Every the request got is store in responsetext
	};
	
}

//m_profile_edit lightbox pop-up

function showProfBox()
{
	//this part says that you were creating an ajax object
	var ajaxObject = null;	//value is null
	
	//this parts checks the browser of the user
	//is the user browser is taking xmlhttp request then
	if(window.XMLHttpRequest)
		//we configure our ajax object to take a xmlhttp request
		ajaxObject = new XMLHttpRequest();	//firefox, chrome
	
	//but if the user browser is taking an activeXobject then
	else if(window.ActiveXObject)
		//we configure our ajax object to take an activeXobject
		ajaxObject = new ActiveXObject("microsoft.XMLHTTP");	//Internet Explorer
	
	//if the user has the compatible browser (fire fox, Chrome, I.E. etc) then
	if(ajaxObject != null)
	{
		//GET method is used to get whatever value is in hte 'lightbox.php' and we want this to happen syncronisly
		//WE SENDING THE REQUEST HERE
		ajaxObject.open("GET", "m_profBox.php", true);
		ajaxObject.send();
		
	}
	//else if user doesnt have a compatible browser then
	else
		//we alert the user
		alert("You do not have the compatible browser");
		
	ajaxObject.onreadystatechange=function()
	{
		//when ajaxObject got all the information that it needs then
		if(ajaxObject.readyState == 4 && ajaxObject.status == 200)
			//we place the all the info the div whos ID is 'showlightbox'
			document.getElementById("showProfbox2").innerHTML = ajaxObject.responseText;	//Every the request got is store in responsetext
	};
	
}


//FUNCTION FOR GUEST HOME PAGE-> INBOX
function showSentBy()
{
	//this part says that you were creating an ajax object
	var ajaxObject = null;
	
	//this parts checks the browser of the user
	//is the user browser is taking xmlhttp request then
	if(window.XMLHttpRequest)
		//we configure our ajax object to take a xmlhttp request
		ajaxObject = new XMLHttpRequest();	//firefox, chrome
	
	//but if the user browser is taking an activeXobject then
	else if(window.ActiveXObject)
		//we configure our ajax object to take an activeXobject
		ajaxObject = new ActiveXObject("microsoft.XMLHTTP");	//Internet Explorer
	
	//if the user has the compatible browser (fire fox, Chrome, I.E. etc) then
	if(ajaxObject != null)
	{
		//GET method is used to get whatever value is in hte 'lightbox.php' and we want this to happen syncronisly
		//WE SENDING THE REQUEST HERE
		ajaxObject.open("GET", "g_h_sentBy.php", true);
		ajaxObject.send();
		
	}
	//else if user doesnt have a compatible browser then
	else
		//we alert the user
		alert("You do not have the compatible browser");
		
	ajaxObject.onreadystatechange=function()
	{
		//when ajaxObject got all the information that it needs then
		if(ajaxObject.readyState == 4 && ajaxObject.status == 200)
			//we place the all the info the div whos ID is 'showlightbox'
			document.getElementById("showSentByHere").innerHTML = ajaxObject.responseText;	//Every the request got is store in responsetext
	};
	
}

//FUNCTION FOR GUEST HOME PAGE-> INBOX
function showUserReg()
{
	//this part says that you were creating an ajax object
	var ajaxObject = null;
	
	//this parts checks the browser of the user
	//is the user browser is taking xmlhttp request then
	if(window.XMLHttpRequest)
		//we configure our ajax object to take a xmlhttp request
		ajaxObject = new XMLHttpRequest();	//firefox, chrome
	
	//but if the user browser is taking an activeXobject then
	else if(window.ActiveXObject)
		//we configure our ajax object to take an activeXobject
		ajaxObject = new ActiveXObject("microsoft.XMLHTTP");	//Internet Explorer
	
	//if the user has the compatible browser (fire fox, Chrome, I.E. etc) then
	if(ajaxObject != null)
	{
		//GET method is used to get whatever value is in hte 'lightbox.php' and we want this to happen syncronisly
		//WE SENDING THE REQUEST HERE
		ajaxObject.open("GET", "m_home_uReg.php", true);
		ajaxObject.send();
		
	}
	//else if user doesnt have a compatible browser then
	else
		//we alert the user
		alert("You do not have the compatible browser");
		
	ajaxObject.onreadystatechange=function()
	{
		//when ajaxObject got all the information that it needs then
		if(ajaxObject.readyState == 4 && ajaxObject.status == 200)
			//we place the all the info the div whos ID is 'showlightbox'
			document.getElementById("showUserReg").innerHTML = ajaxObject.responseText;	//Every the request got is store in responsetext
	};
	
}




//FUNCTION FOR GUEST HOME PAGE-> INBOX
function showSentBy2()
{
	//this part says that you were creating an ajax object
	var ajaxObject = null;
	
	//this parts checks the browser of the user
	//is the user browser is taking xmlhttp request then
	if(window.XMLHttpRequest)
		//we configure our ajax object to take a xmlhttp request
		ajaxObject = new XMLHttpRequest();	//firefox, chrome
	
	//but if the user browser is taking an activeXobject then
	else if(window.ActiveXObject)
		//we configure our ajax object to take an activeXobject
		ajaxObject = new ActiveXObject("microsoft.XMLHTTP");	//Internet Explorer
	
	//if the user has the compatible browser (fire fox, Chrome, I.E. etc) then
	if(ajaxObject != null)
	{
		//GET method is used to get whatever value is in hte 'lightbox.php' and we want this to happen syncronisly
		//WE SENDING THE REQUEST HERE
		ajaxObject.open("GET", "m_h_sentBy.php", true);
		ajaxObject.send();
		
	}
	//else if user doesnt have a compatible browser then
	else
		//we alert the user
		alert("You do not have the compatible browser");
		
	ajaxObject.onreadystatechange=function()
	{
		//when ajaxObject got all the information that it needs then
		if(ajaxObject.readyState == 4 && ajaxObject.status == 200)
			//we place the all the info the div whos ID is 'showlightbox'
			document.getElementById("showSentByHere2").innerHTML = ajaxObject.responseText;	//Every the request got is store in responsetext
	};
	
}


function showAssign()
{
	//this part says that you were creating an ajax object
	var ajaxObject = null;
	
	//this parts checks the browser of the user
	//is the user browser is taking xmlhttp request then
	if(window.XMLHttpRequest)
		//we configure our ajax object to take a xmlhttp request
		ajaxObject = new XMLHttpRequest();	//firefox, chrome
	
	//but if the user browser is taking an activeXobject then
	else if(window.ActiveXObject)
		//we configure our ajax object to take an activeXobject
		ajaxObject = new ActiveXObject("microsoft.XMLHTTP");	//Internet Explorer
	
	//if the user has the compatible browser (fire fox, Chrome, I.E. etc) then
	if(ajaxObject != null)
	{
		//GET method is used to get whatever value is in hte 'lightbox.php' and we want this to happen syncronisly
		//WE SENDING THE REQUEST HERE
		ajaxObject.open("GET", "m_h_assgn.php", true);
		ajaxObject.send();
		
	}
	//else if user doesnt have a compatible browser then
	else
		//we alert the user
		alert("You do not have the compatible browser");
		
	ajaxObject.onreadystatechange=function()
	{
		//when ajaxObject got all the information that it needs then
		if(ajaxObject.readyState == 4 && ajaxObject.status == 200)
			//we place the all the info the div whos ID is 'showlightbox'
			document.getElementById("showAssign").innerHTML = ajaxObject.responseText;	//Every the request got is store in responsetext
	};
	
}


/* BUSINESS SECTION - VIEWING ORDERS */

function showOrders()
{
	//this part says that you were creating an ajax object
	var ajaxObject = null;
	
	//this parts checks the browser of the user
	//is the user browser is taking xmlhttp request then
	if(window.XMLHttpRequest)
		//we configure our ajax object to take a xmlhttp request
		ajaxObject = new XMLHttpRequest();	//firefox, chrome
	
	//but if the user browser is taking an activeXobject then
	else if(window.ActiveXObject)
		//we configure our ajax object to take an activeXobject
		ajaxObject = new ActiveXObject("microsoft.XMLHTTP");	//Internet Explorer
	
	//if the user has the compatible browser (fire fox, Chrome, I.E. etc) then
	if(ajaxObject != null)
	{
		//GET method is used to get whatever value is in hte 'lightbox.php' and we want this to happen syncronisly
		//WE SENDING THE REQUEST HERE
		ajaxObject.open("GET", "m_business_orderBox.php", true);
		ajaxObject.send();
		
	}
	//else if user doesnt have a compatible browser then
	else
		//we alert the user
		alert("You do not have the compatible browser");
		
	ajaxObject.onreadystatechange=function()
	{
		//when ajaxObject got all the information that it needs then
		if(ajaxObject.readyState == 4 && ajaxObject.status == 200)
			//we place the all the info the div whos ID is 'showlightbox'
			document.getElementById("showOrders").innerHTML = ajaxObject.responseText;	//Every the request got is store in responsetext
	};
	
}

/* GROUP SECTION POP-UP */
function showGroup()
{
	//this part says that you were creating an ajax object
	var ajaxObject = null;
	
	//this parts checks the browser of the user
	//is the user browser is taking xmlhttp request then
	if(window.XMLHttpRequest)
		//we configure our ajax object to take a xmlhttp request
		ajaxObject = new XMLHttpRequest();	//firefox, chrome
	
	//but if the user browser is taking an activeXobject then
	else if(window.ActiveXObject)
		//we configure our ajax object to take an activeXobject
		ajaxObject = new ActiveXObject("microsoft.XMLHTTP");	//Internet Explorer
	
	//if the user has the compatible browser (fire fox, Chrome, I.E. etc) then
	if(ajaxObject != null)
	{
		//GET method is used to get whatever value is in hte 'lightbox.php' and we want this to happen syncronisly
		//WERE SENDING THE REQUEST HERE
		ajaxObject.open("GET", "g_groupBox.php", true);
		ajaxObject.send();
		
	}
	//else if user doesnt have a compatible browser then
	else
		//we alert the user
		alert("You do not have the compatible browser");
		
	ajaxObject.onreadystatechange=function()
	{
		//when ajaxObject got all the information that it needs then
		if(ajaxObject.readyState == 4 && ajaxObject.status == 200)
			//we place the all the info the div whos ID is 'showlightbox'
			document.getElementById("showGroups").innerHTML = ajaxObject.responseText;	//Every the request got is store in responsetext
	};
}
/* GROUP SECTION POP-UP */


/* BLOGS SECTION POP-UP */
function showBlogs()
{
	//this part says that you were creating an ajax object
	var ajaxObject = null;
	
	//this parts checks the browser of the user
	//is the user browser is taking xmlhttp request then
	if(window.XMLHttpRequest)
		//we configure our ajax object to take a xmlhttp request
		ajaxObject = new XMLHttpRequest();	//firefox, chrome
	
	//but if the user browser is taking an activeXobject then
	else if(window.ActiveXObject)
		//we configure our ajax object to take an activeXobject
		ajaxObject = new ActiveXObject("microsoft.XMLHTTP");	//Internet Explorer
	
	//if the user has the compatible browser (fire fox, Chrome, I.E. etc) then
	if(ajaxObject != null)
	{
		//GET method is used to get whatever value is in hte 'lightbox.php' and we want this to happen syncronisly
		//WERE SENDING THE REQUEST HERE
		ajaxObject.open("GET", "m_blogs_show.php", true);
		ajaxObject.send();
		
	}
	//else if user doesnt have a compatible browser then
	else
		//we alert the user
		alert("You do not have the compatible browser");
		
	ajaxObject.onreadystatechange=function()
	{
		//when ajaxObject got all the information that it needs then
		if(ajaxObject.readyState == 4 && ajaxObject.status == 200)
			//we place the all the info the div whos ID is 'showlightbox'
			document.getElementById("showBlogs").innerHTML = ajaxObject.responseText;	//Every the request got is store in responsetext
	};
}
/* BLOGS SECTION POP-UP */

function updatePicture()
{
	//this part says that you were creating an ajax object
	var ajaxObject = null;
	
	//this parts checks the browser of the user
	//is the user browser is taking xmlhttp request then
	if(window.XMLHttpRequest)
		//we configure our ajax object to take a xmlhttp request
		ajaxObject = new XMLHttpRequest();	//firefox, chrome
	
	//but if the user browser is taking an activeXobject then
	else if(window.ActiveXObject)
		//we configure our ajax object to take an activeXobject
		ajaxObject = new ActiveXObject("microsoft.XMLHTTP");	//Internet Explorer
	
	//if the user has the compatible browser (fire fox, Chrome, I.E. etc) then
	if(ajaxObject != null)
	{
		//GET method is used to get whatever value is in hte 'lightbox.php' and we want this to happen syncronisly
		//WE SENDING THE REQUEST HERE
		ajaxObject.open("GET", "m_blogs_upic.php", true);
		ajaxObject.send();
		
	}
	//else if user doesnt have a compatible browser then
	else
		//we alert the user
		alert("You do not have the compatible browser");
		
	ajaxObject.onreadystatechange=function()
	{
		//when ajaxObject got all the information that it needs then
		if(ajaxObject.readyState == 4 && ajaxObject.status == 200)
			//we place the all the info the div whos ID is 'showlightbox'
			document.getElementById("showUpic").innerHTML = ajaxObject.responseText;	//Every the request got is store in responsetext
	};
}

function addPicture()
{
	//this part says that you were creating an ajax object
	var ajaxObject = null;
	
	//this parts checks the browser of the user
	//is the user browser is taking xmlhttp request then
	if(window.XMLHttpRequest)
		//we configure our ajax object to take a xmlhttp request
		ajaxObject = new XMLHttpRequest();	//firefox, chrome
	
	//but if the user browser is taking an activeXobject then
	else if(window.ActiveXObject)
		//we configure our ajax object to take an activeXobject
		ajaxObject = new ActiveXObject("microsoft.XMLHTTP");	//Internet Explorer
	
	//if the user has the compatible browser (fire fox, Chrome, I.E. etc) then
	if(ajaxObject != null)
	{
		//GET method is used to get whatever value is in hte 'lightbox.php' and we want this to happen syncronisly
		//WE SENDING THE REQUEST HERE
		ajaxObject.open("GET", "m_blogs_upic.php", true);
		ajaxObject.send();
		
	}
	//else if user doesnt have a compatible browser then
	else
		//we alert the user
		alert("You do not have the compatible browser");
		
	ajaxObject.onreadystatechange=function()
	{
		//when ajaxObject got all the information that it needs then
		if(ajaxObject.readyState == 4 && ajaxObject.status == 200)
			//we place the all the info the div whos ID is 'showlightbox'
			document.getElementById("showUpic").innerHTML = ajaxObject.responseText;	//Every the request got is store in responsetext
	};
}



function showAddNew()
{
	//these code will search for an element havent an ID 'BG' then turn its style.display into 'block'
	/*document.getElementById("BG").style.display = "block";
	document.getElementById("FG").style.display = "block";*/
	
	//this part says that you were creating an ajax object
	var ajaxObject = null;
	
	//this parts checks the browser of the user
	//is the user browser is taking xmlhttp request then
	if(window.XMLHttpRequest)
		//we configure our ajax object to take a xmlhttp request
		ajaxObject = new XMLHttpRequest();	//firefox, chrome
	
	//but if the user browser is taking an activeXobject then
	else if(window.ActiveXObject)
		//we configure our ajax object to take an activeXobject
		ajaxObject = new ActiveXObject("microsoft.XMLHTTP");	//Internet Explorer
	
	//if the user has the compatible browser (fire fox, Chrome, I.E. etc) then
	if(ajaxObject != null)
	{
		//GET method is used to get whatever value is in hte 'lightbox.php' and we want this to happen syncronisly
		//WE SENDING THE REQUEST HERE
		ajaxObject.open("GET", "lightbox.php", true);
		ajaxObject.send();
		
	}
	//else if user doesnt have a compatible browser then
	else
		//we alert the user
		alert("You do not have the compatible browser");
		
	ajaxObject.onreadystatechange=function()
	{
		//when ajaxObject got all the information that it needs then
		if(ajaxObject.readyState == 4 && ajaxObject.status == 200)
			//we place the all the info the div whos ID is 'showlightbox'
			document.getElementById("showLightbox").innerHTML = ajaxObject.responseText;	//Every the request got is store in responsetext
	};
	
	
	
}

///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

function showDloads()
{
	//this part says that you were creating an ajax object
	var ajaxObject = null;
	
	//this parts checks the browser of the user
	//is the user browser is taking xmlhttp request then
	if(window.XMLHttpRequest)
		//we configure our ajax object to take a xmlhttp request
		ajaxObject = new XMLHttpRequest();	//firefox, chrome
	
	//but if the user browser is taking an activeXobject then
	else if(window.ActiveXObject)
		//we configure our ajax object to take an activeXobject
		ajaxObject = new ActiveXObject("microsoft.XMLHTTP");	//Internet Explorer
	
	//if the user has the compatible browser (fire fox, Chrome, I.E. etc) then
	if(ajaxObject != null)
	{
		//GET method is used to get whatever value is in hte 'lightbox.php' and we want this to happen syncronisly
		//WE SENDING THE REQUEST HERE
		ajaxObject.open("GET", "m_dloads_add.php", true);
		ajaxObject.send();
		
	}
	//else if user doesnt have a compatible browser then
	else
		//we alert the user
		alert("You do not have the compatible browser");
		
	ajaxObject.onreadystatechange=function()
	{
		//when ajaxObject got all the information that it needs then
		if(ajaxObject.readyState == 4 && ajaxObject.status == 200)
			//we place the all the info the div whos ID is 'showlightbox'
			document.getElementById("showDloads").innerHTML = ajaxObject.responseText;	//Every the request got is store in responsetext
	};
}

///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

function showCatBox()
{
	//these code will search for an element havent an ID 'BG' then turn its style.display into 'block'
	/*document.getElementById("BG").style.display = "block";
	document.getElementById("FG").style.display = "block";*/
	
	//this part says that you were creating an ajax object
	var ajaxObject = null;
	
	//this parts checks the browser of the user
	//is the user browser is taking xmlhttp request then
	if(window.XMLHttpRequest)
		//we configure our ajax object to take a xmlhttp request
		ajaxObject = new XMLHttpRequest();	//firefox, chrome
	
	//but if the user browser is taking an activeXobject then
	else if(window.ActiveXObject)
		//we configure our ajax object to take an activeXobject
		ajaxObject = new ActiveXObject("microsoft.XMLHTTP");	//Internet Explorer
	
	//if the user has the compatible browser (fire fox, Chrome, I.E. etc) then
	if(ajaxObject != null)
	{
		//GET method is used to get whatever value is in hte 'lightbox.php' and we want this to happen syncronisly
		//WE SENDING THE REQUEST HERE
		ajaxObject.open("GET", "catBox.php", true);
		ajaxObject.send();
		
	}
	//else if user doesnt have a compatible browser then
	else
		//we alert the user
		alert("You do not have the compatible browser");
		
	ajaxObject.onreadystatechange=function()
	{
		//when ajaxObject got all the information that it needs then
		if(ajaxObject.readyState == 4 && ajaxObject.status == 200)
			//we place the all the info the div whos ID is 'showlightbox'
			document.getElementById("showCatBoxHere").innerHTML = ajaxObject.responseText;	//Every the request got is store in responsetext
	};
	
	
	
}

function showCatBox2()
{
	//these code will search for an element havent an ID 'BG' then turn its style.display into 'block'
	/*document.getElementById("BG").style.display = "block";
	document.getElementById("FG").style.display = "block";*/
	
	//this part says that you were creating an ajax object
	var ajaxObject = null;
	
	//this parts checks the browser of the user
	//is the user browser is taking xmlhttp request then
	if(window.XMLHttpRequest)
		//we configure our ajax object to take a xmlhttp request
		ajaxObject = new XMLHttpRequest();	//firefox, chrome
	
	//but if the user browser is taking an activeXobject then
	else if(window.ActiveXObject)
		//we configure our ajax object to take an activeXobject
		ajaxObject = new ActiveXObject("microsoft.XMLHTTP");	//Internet Explorer
	
	//if the user has the compatible browser (fire fox, Chrome, I.E. etc) then
	if(ajaxObject != null)
	{
		//GET method is used to get whatever value is in hte 'lightbox.php' and we want this to happen syncronisly
		//WE SENDING THE REQUEST HERE
		ajaxObject.open("GET", "catBox2.php", true);
		ajaxObject.send();
		
	}
	//else if user doesnt have a compatible browser then
	else
		//we alert the user
		alert("You do not have the compatible browser");
		
	ajaxObject.onreadystatechange=function()
	{
		//when ajaxObject got all the information that it needs then
		if(ajaxObject.readyState == 4 && ajaxObject.status == 200)
			//we place the all the info the div whos ID is 'showlightbox'
			document.getElementById("showCatBoxHere2").innerHTML = ajaxObject.responseText;	//Every the request got is store in responsetext
	};
	
	
	
}



function showBusiness()
{
	//these code will search for an element havent an ID 'BG' then turn its style.display into 'block'
	/*document.getElementById("BG").style.display = "block";
	document.getElementById("FG").style.display = "block";*/
	
	//this part says that you were creating an ajax object
	var ajaxObject = null;
	
	//this parts checks the browser of the user
	//is the user browser is taking xmlhttp request then
	if(window.XMLHttpRequest)
		//we configure our ajax object to take a xmlhttp request
		ajaxObject = new XMLHttpRequest();	//firefox, chrome
	
	//but if the user browser is taking an activeXobject then
	else if(window.ActiveXObject)
		//we configure our ajax object to take an activeXobject
		ajaxObject = new ActiveXObject("microsoft.XMLHTTP");	//Internet Explorer
	
	//if the user has the compatible browser (fire fox, Chrome, I.E. etc) then
	if(ajaxObject != null)
	{
		//GET method is used to get whatever value is in hte 'lightbox.php' and we want this to happen syncronisly
		//WE SENDING THE REQUEST HERE
		ajaxObject.open("GET", "m_businessBox.php", true);
		ajaxObject.send();
		
	}
	//else if user doesnt have a compatible browser then
	else
		//we alert the user
		alert("You do not have the compatible browser");
		
	ajaxObject.onreadystatechange=function()
	{
		//when ajaxObject got all the information that it needs then
		if(ajaxObject.readyState == 4 && ajaxObject.status == 200)
			//we place the all the info the div whos ID is 'showlightbox'
			document.getElementById("showBusiness").innerHTML = ajaxObject.responseText;	//Every the request got is store in responsetext
	};	
}

//POPUP FOR m_business_edit

function showItemBox()
{
	//these code will search for an element havent an ID 'BG' then turn its style.display into 'block'
	/*document.getElementById("BG").style.display = "block";
	document.getElementById("FG").style.display = "block";*/
	
	//this part says that you were creating an ajax object
	var ajaxObject = null;
	
	//this parts checks the browser of the user
	//is the user browser is taking xmlhttp request then
	if(window.XMLHttpRequest)
		//we configure our ajax object to take a xmlhttp request
		ajaxObject = new XMLHttpRequest();	//firefox, chrome
	
	//but if the user browser is taking an activeXobject then
	else if(window.ActiveXObject)
		//we configure our ajax object to take an activeXobject
		ajaxObject = new ActiveXObject("microsoft.XMLHTTP");	//Internet Explorer
	
	//if the user has the compatible browser (fire fox, Chrome, I.E. etc) then
	if(ajaxObject != null)
	{
		//GET method is used to get whatever value is in hte 'lightbox.php' and we want this to happen syncronisly
		//WE SENDING THE REQUEST HERE
		ajaxObject.open("GET", "m_showItemBox.php", true);
		ajaxObject.send();
		
	}
	//else if user doesnt have a compatible browser then
	else
		//we alert the user
		alert("You do not have the compatible browser");
		
	ajaxObject.onreadystatechange=function()
	{
		//when ajaxObject got all the information that it needs then
		if(ajaxObject.readyState == 4 && ajaxObject.status == 200)
			//we place the all the info the div whos ID is 'showlightbox'
			document.getElementById("showItemBox").innerHTML = ajaxObject.responseText;	//Every the request got is store in responsetext
	};	
}
