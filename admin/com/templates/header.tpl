<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
	<head>
	<title>%%GLOBAL_ApplicationTitle%%</title>
	<link rel="shortcut icon" href="%%GLOBAL_ApplicationFavicon%%" type="image/vnd.microsoft.icon">
	<link rel="icon" href="%%GLOBAL_ApplicationFavicon%%" type="image/vnd.microsoft.icon">
	<meta http-equiv="Content-Type" content="text/html; charset=%%GLOBAL_CHARSET%%">
	<link rel="stylesheet" href="includes/styles/stylesheet.css" type="text/css">
	<link rel="stylesheet" href="includes/styles/tabmenu.css" type="text/css">
	<link rel="stylesheet" href="includes/styles/thickbox.css" type="text/css">
	<link rel="stylesheet" href="includes/js/imodal/imodal.css" type="text/css">

	<!--[if IE]>
	<style type="text/css"> 
		@import url("includes/styles/ie.css");
	</style>
	<![endif]-->

	<script src="includes/js/jquery.js" type="text/javascript"></script>
	<script src="includes/js/jquery/jquery.json-1.3.min.js" type="text/javascript"></script>
	<script src="includes/js/jquery/thickbox.js" type="text/javascript"></script>
	<script src="includes/js/javascript.js" type="text/javascript"></script>
	<!--<script src="includes/js/tiny_mce/tiny_mce.js" type="text/javascript"></script>-->
    <script src="includes/js/tiny_mce/tinymce.min.js" type="text/javascript"></script>
    <script src="includes/js/amcharts/amcharts.js" type="text/javascript"></script>
    <script src="includes/js/amcharts/pie.js" type="text/javascript"></script>
    <script src="includes/js/amcharts/serial.js" type="text/javascript"></script>

	<script type="text/javascript">
        AmCharts.loadJSON = function(url) {
            // create the request
            if (window.XMLHttpRequest) {
                // IE7+, Firefox, Chrome, Opera, Safari
                var request = new XMLHttpRequest();
            } else {
                // code for IE6, IE5
                var request = new ActiveXObject('Microsoft.XMLHTTP');
            }
            request.open('GET', url, false);
            request.send();
            return JSON.parse(request.responseText);
        };
    </script>

	<script type="text/javascript">
		 
		var UnsubLinkPlaceholder = "%%LNG_UnsubLinkPlaceholder%%";
		var UsingWYSIWYG = '%%GLOBAL_UsingWYSIWYG%%';
		var Searchbox_Type_Prompt = "%%LNG_Searchbox_Type_Prompt%%";
		var Searchbox_List_Info = '%%GLOBAL_Searchbox_List_Info%%';
		var Application_Title = '%%LNG_ApplicationTitle%%';

		Application.Misc.specifyDocumentMinWidth(935);
		Application.Misc.setPingServer('ping.php', 120000);
	</script>
	<script type="text/javascript">

		var IDLE_TIMEOUT_MIN =<?php echo SENDSTUDIO_SECURITY_SESSION_TIME;?>;
		var IDLE_TIMEOUT = IDLE_TIMEOUT_MIN * 60;  //   minutes of inactivity
		var _idleSecondsCounter = 0;
		document.onclick = function() {
			_idleSecondsCounter = 0;
		};
		document.onmousemove = function() {
			_idleSecondsCounter = 0;
		};
		document.onkeypress = function() {
			_idleSecondsCounter = 0;
		};
		window.setInterval(CheckIdleTime, 1000);
		function CheckIdleTime() {
        _idleSecondsCounter++;
	   
        if (_idleSecondsCounter >= IDLE_TIMEOUT) {
		 if(IDLE_TIMEOUT != 0){
            // destroy the session 			 
			window.location="index.php?Page=Logout"; 
			}			
        }
    }
	</script>
</head>

<body>
{if $TrialNotification}<div id="IEM_Header_TrialNotificationBar">{$TrialNotification}</div>{/if}
<div id="IEM_HTML_Body">
	<div class="Header">
		<div class="Header_Top"></div>

		<div class="logo">
			<a href="index.php"><img id="logo" src="%%WHITELABEL_ApplicationLogoImage%%" alt="{$lang.SendingSystem}" border="0" /></a>
		</div>

		<div class="textlinks" align="right">
			<div class="MenuText">
				%%GLOBAL_TextLinks%%
				<div class="loggedinas">
					%%GLOBAL_UserLoggedInAs%%%%GLOBAL_SystemDateTime%%
				</div>
				<span class="emailcredits">%%GLOBAL_MonthlyEmailCredits%%</span>
				<span class="emailcredits">%%GLOBAL_TotalEmailCredits%%</span>
			</div>
		</div>

		<div class="Header_Bottom"></div>
	</div>

	<div class="menuBar">
		<div style="height:0px;">&nbsp;</div>
		<div>%%GLOBAL_MenuLinks%%</div>
	</div>

	<div class="ContentContainer">
		<div class="BodyContainer">
			{if $ShowTestModeWarning}
				<div class="TestModeEnabled"><div style="valign: top"><img src="images/critical.gif"  align="left" hspace="5">{$SendTestWarningMessage}</div></div>
			{/if}

	%%GLOBAL_BodyAddons%%
