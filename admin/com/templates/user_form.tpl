<link rel="stylesheet" type="text/css" href="includes/styles/thickbox.css" />
<form name="users" method="post" action="index.php?Page=%%PAGE%%&%%GLOBAL_FormAction%%">
	<input type="hidden" name="id_tab_num" id="id_tab_num" value="{$DefaultIdTab}" />
	<input type="hidden" name="csrfToken" id="csrfToken" value="{$csrfToken}" />

	<table cellspacing="0" cellpadding="0" width="100%" align="center">
		<tr>
			<td class="Heading1">%%GLOBAL_Heading%%</td>
		</tr>
		<tr>
			<td class="body pageinfo"><p>%%GLOBAL_Help_Heading%%</p></td>
		</tr>
		<tr>
			<td>
				%%GLOBAL_Message%%
			</td>
		</tr>
		<tr>
			<td class=body>
				<input class="FormButton" type="submit" value="%%LNG_Save%%"/>
				<input class="FormButton CancelButton" type="button" value="%%LNG_Cancel%%"/>
			</td>
		</tr>
		<tr>
			<td>
				<div>
					<br />
					
					<ul id="tabnav">
						<li><a href="#" class="active" id="tab1" onclick="ShowTab(1); SetDefaultTab(1); return false;"><span>%%LNG_UserSettings_Heading%%</span></a></li>
						<li><a href="#" id="tab2" onclick="ShowTab(2); SetDefaultTab(2); return false;"><span>%%LNG_UserSettingsAdvanced_Heading%%</span></a></li>
						<li><a href="#" id="tab3" onclick="ShowTab(3); SetDefaultTab(3); return false;"><span>%%LNG_EmailSettings_Heading%%</span></a></li>
						<li><a href="#" id="tab4" onclick="ShowTab(4); SetDefaultTab(4); return false;"><span>%%LNG_AdminNotifications_Heading%%</span></a></li>
					</ul>

					<div id="div1" style="padding-top:10px">
						<table border="0" cellspacing="0" cellpadding="2" width="100%" class="Panel">
							<tr>
								<td class="Heading2" colspan="2" style="padding-left: 10px">
									%%LNG_UserDetails%%
								</td>
							</tr>
							  <input type="hidden" name="trialuser" value="0" />
							 
							<tr>
								<td class="FieldLabel">
									{template="Required"}
									{$lang.UserName}:
								</td>
								<td>
									<input type="text" name="username" id="username" value="%%GLOBAL_UserName%%" id="username" class="Field250" />
								</td>
							</tr>
							<tr>
								<td class="FieldLabel">
									{if $canChangeUserGroup}
									{template="Required"}
									{else}
									{template="Not_Required"}
									{/if}
									{$lang.UsersGroups}:
								</td>
								<td>
									{if $canChangeUserGroup}
									<select name="groupid">
										<option value="0">{$lang.UsersGroups_Intro}</option>
										{foreach from=$AvailableGroups item=EachGroup}
											<option value="{$EachGroup.groupid}" {if $EachGroup.groupid == $record_groupid}selected="selected"{/if}>{$EachGroup.groupname}</option>
										{/foreach} 
									</select>
									{$lnghlp.UsersGroups}
                                        <br/>
                                        <a target="_self" href="index.php?Page=UsersGroups&Action=createGroup">Create user group</a>
									{else}
									{$lang.AdminCannotChangeGroup}
									<input type="hidden" name="groupid" value="{$groupid}" />
									{/if}
								</td>
							</tr>
							<tr>
								<td class="FieldLabel">
									{template="Not_Required"}
									%%LNG_FullName%%:
								</td>
								<td>
									<input type="text" name="fullname" value="%%GLOBAL_FullName%%" id="fullname" class="Field250" />
								</td>
							</tr>
							<tr>
								<td class="FieldLabel">
									{template="Required"}
									%%LNG_EmailAddress%%:
								</td>
								<td>
									<input type="text" name="emailaddress" id="emailaddress" value="%%GLOBAL_EmailAddress%%" class="Field250" />&nbsp;%%LNG_HLP_EmailAddress%%
								</td>
							</tr>
							<tr>
								<td class="FieldLabel">
									{template="Required"}
									%%LNG_TimeZone%%:
								</td>
								<td>
									<select name="usertimezone">
										%%GLOBAL_TimeZoneList%%
									</select>&nbsp;&nbsp;&nbsp;%%LNG_HLP_TimeZone%%
								</td>
							</tr>
                            {if $UserID != 0 && $UserID == $current_user}
                            <tr>
                                <td class="FieldLabel">
                                    {template="Required"}
                                    {$lang.PasswordCurrent}:
                                </td>
                                <td>
                                    <input type="password" name="ss_p_current" id="ss_p_current" value="" class="Field250" autocomplete="off" />
                                </td>
                            </tr>
                            {/if}
                            <tr>
                                <td class="FieldLabel">
                                    {template="Required"}
                                    {$lang.Password}:
                                </td>
                                <td>
								<input type="hidden" name="loginuser_groupid" value="{$loginuser_groupid}">
                                    <input type="password" name="ss_p" id="ss_p" value="" class="Field250" autocomplete="off" />&nbsp;%%LNG_HLP_Password%%
                                </td>
                            </tr>
                            <tr>
                                <td class="FieldLabel">
                                    {template="Required"}
                                    {$lang.PasswordConfirm}:
                                </td>
                                <td>
                                    <input type="password" name="ss_p_confirm" id="ss_p_confirm" value="" class="Field250" autocomplete="off" />
                                </td>
                            </tr>
							{if $ShowSendPassLink}
							<tr>
                                <td class="FieldLabel"> 
                                </td>
                                <td>
                                 <br /><a href="%%GLOBAL_ResetPassword%%" name="SubmitButton"  class="FormButton" style="display:%%GLOBAL_OTP_Status%%">%%LNG_SendPasswordRestText%%</a><br>
                                 <br> %%GLOBAL_Email_Message%%
								</td>
								<input type="hidden" name="loginuser_groupid" value="{$loginuser_groupid}">
                            </tr>
							{/if}
						</table>
					</div>
					
					<div id="div2" style="padding-top:10px">
						<table border="0" cellspacing="0" cellpadding="2" width="100%" class="Panel">
							<tr>
								<td class="Heading2" colspan="2" style="padding-left: 10px">
									%%LNG_UserDetailsAdvanced%%
								</td>
							</tr>
							<tr>
								<td class="FieldLabel">
									{template="Not_Required"}
									{$lang.UseXMLAPI}:
								</td>
								<td>
									<label for="xmlapi"><input type="checkbox" name="xmlapi" id="xmlapi" value="1" %%GLOBAL_Xmlapi%% {if trim($AgencyEdition.agencyid) != '' && $TrialUser == '1'}disabled="disabled"{/if}/> {$lang.YesUseXMLAPI}</label> {$lnghlp.UseXMLAPI}<br/>
									<table id="sectionXMLToken"%%GLOBAL_XMLTokenDisplay%% border="0" cellspacing="0" cellpadding="2" class="Panel">
										<tr>
											<td width="100">
												<img src="images/nodejoin.gif" width="20" height="20">&nbsp;{$lang.XMLPath}:
											</td>
											<td>
												<input type="text" name="xmlpath" id="xmlpath" value="%%GLOBAL_XmlPath%%" class="Field250 SelectOnFocus" readonly/> {$lnghlp.XMLPath}
											</td>
										</tr>
										<tr>
											<td>
												<img src="images/blank.gif" width="20" height="20">&nbsp;{$lang.XMLUsername}:
											</td>
											<td>
												<input type="text" name="xmlusername" id="xmlusername" value="%%GLOBAL_UserName%%" class="Field250 SelectOnFocus" readonly/> {$lnghlp.XMLUsername}
											</td>
										</tr>
										<tr>
											<td>
												<img src="images/blank.gif" width="20" height="20">&nbsp;{$lang.XMLToken}:
											</td>
											<td>
												<input type="text" name="xmltoken" id="xmltoken" value="%%GLOBAL_XmlToken%%" class="Field250 SelectOnFocus" readonly/> {$lnghlp.XMLToken}
											</td>
										</tr>
										<tr>
											<td>&nbsp;
												
											</td>
											<td>
												<a href="#" id="hrefRegenerateXMLToken" style="color: gray;">%%LNG_XMLToken_Regenerate%%</a>
											</td>
										</tr>
									</table>
								</td>
							</tr>
							<tr>
								<td class="FieldLabel">
									{template="Not_Required"}
									%%LNG_Active%%:
								</td>
								<td>
									<label for="status"><input type="checkbox" name="status" id="status" value="1"%%GLOBAL_StatusChecked%% /> %%LNG_YesIsActive%%</label> %%LNG_HLP_Active%%
								</td>
							</tr>
							<tr>
								<td class="FieldLabel">
									{template="Not_Required"}
									%%LNG_EditOwnSettings%%:
								</td>
								<td>
									<label for="editownsettings"><input type="checkbox" name="editownsettings" id="editownsettings" value="1"%%GLOBAL_EditOwnSettingsChecked%% /> %%LNG_YesEditOwnSettings%%</label> %%LNG_HLP_EditOwnSettings%%
								</td>
							</tr>
							<tr>
								<td class="FieldLabel">
									{template="Not_Required"}
									%%LNG_ShowInfoTips%%:
								</td>
								<td>
									<label for="infotips"><input type="checkbox" name="infotips" id="infotips" value="1"%%GLOBAL_InfoTipsChecked%% /> %%LNG_YesShowInfoTips%%</label> %%LNG_HLP_ShowInfoTips%%
								</td>
							</tr>
							<tr>
								<td class="FieldLabel">
									{template="Not_Required"}
									%%LNG_UseWysiwygEditor%%:
								</td>
								<td>
									<div><label for="usewysiwyg"><input type="checkbox" name="usewysiwyg" id="usewysiwyg" value="1" %%GLOBAL_UseWysiwyg%% /> %%LNG_YesUseWysiwygEditor%%</label> %%LNG_HLP_UseWysiwygEditor%%</div>
									<div id="sectionUseXHTML"%%GLOBAL_UseXHTMLDisplay%%><img src="images/nodejoin.gif" width="20" height="20"><label for="usexhtml"><input type="checkbox" name="usexhtml" id="usexhtml" value="1"%%GLOBAL_UseXHTMLCheckbox%%> %%LNG_YesUseXHTML%%</label> %%LNG_HLP_UseWysiwygXHTML%%</div>
								</td>
							</tr>
							<tr>
								<td class="FieldLabel">
									{template="Not_Required"}
									{$lang.EnableActivityLog}:
								</td>
								<td>
									<label for="enableactivitylog"><input type="checkbox" name="enableactivitylog" id="enableactivitylog" value="1" %%GLOBAL_EnableActivityLog%% /> {$lang.YesEnableActivityLog}</label> {$lnghlp.EnableActivityLog}
								</td>
							</tr>
							<tr>
								<td class="FieldLabel">
									{template="Not_Required"}
									{$lang.EventTypeList}:
								</td>
								<td>
									<textarea name="eventactivitytype" rows="10" cols="50" wrap="virtual">%%GLOBAL_EventActivityType%%</textarea>&nbsp;&nbsp;&nbsp;{$lnghlp.EventTypeList}
								</td>
							</tr>
						</table>
					</div>

					<div id="div3" style="display:none; padding-top:10px">
						<table border="0" cellspacing="0" cellpadding="2" width="100%" class="Panel">
							<tr>
								<td colspan="2" class="Heading2" style="padding-left:10px">
									%%LNG_SmtpServerIntro%%
								</td>
							</tr>
							<tr>
								<td class="FieldLabel" width="10%">
									<img src="images/blank.gif" width="200" height="1" /><br />
									{template="Not_Required"}
									%%LNG_SmtpServer%%:
								</td>
								<td width="90%" >
									<label for="usedefaultsmtp">
										<input type="radio" name="smtptype" id="usedefaultsmtp" value="0" {if $GLOBAL_DisplayDefaultMailSettings  == 'DISABLED'} {if !$showSmtpInfo}checked="checked"{/if}{/if}  %%GLOBAL_DisplayDefaultMailSettings%% />
										%%LNG_SmtpDefault%%
									</label>
									%%LNG_HLP_UseDefaultMail%%
								</td>
							</tr>
							<tr>
								<td class="FieldLabel"> </td>
								<td>
									<label for="usecustomsmtp">
										<input type="radio" name="smtptype" id="usecustomsmtp" value="1" {if $showSmtpInfo}checked="checked"{/if} />
										%%LNG_SmtpCustom%%
									</label>
									%%LNG_HLP_UseSMTP_User%%
								</td>
							</tr>
							<tr class="SMTPOptions" style="display:none">
								<td class="FieldLabel">
									{template="Required"}
									%%LNG_SmtpServerName%%:
								</td>
								<td>
									<img width="20" height="20" src="images/nodejoin.gif"/>
									<input type="text" name="smtp_server" value="%%GLOBAL_SmtpServer%%" class="Field250 smtpSettings"/> %%LNG_HLP_SmtpServerName%%
								</td>
							</tr>
							<tr class="SMTPOptions" style="display:none">
								<td class="FieldLabel">
									{template="Not_Required"}
									%%LNG_SmtpServerUsername%%:
								</td>
								<td>
									<img width="20" height="20" src="images/blank.gif"/>
									<input type="text" name="smtp_u" value="%%GLOBAL_SmtpUsername%%" class="Field250 smtpSettings"/> %%LNG_HLP_SmtpServerUsername%%
								</td>
							</tr>
							<tr class="SMTPOptions" style="display:none">
								<td class="FieldLabel">
									{template="Not_Required"}
									%%LNG_SmtpServerPassword%%:
								</td>
								<td>
									<img width="20" height="20" src="images/blank.gif"/>
									<input type="password" name="smtp_p" value="%%GLOBAL_SmtpPassword%%" class="Field250 smtpSettings" autocomplete="off" /> %%LNG_HLP_SmtpServerPassword%%
								</td>
							</tr>
							<tr class="SMTPOptions" style="display:none">
								<td class="FieldLabel">
									{template="Not_Required"}
									%%LNG_SmtpServerPort%%:
								</td>
								<td>
									<img width="20" height="20" src="images/blank.gif"/>
									<input type="text" name="smtp_port" value="%%GLOBAL_SmtpPort%%" class="field50 smtpSettings"/> %%LNG_HLP_SmtpServerPort%%
								</td>
							</tr>
							<tr class="SMTPOptions" style="display:none">
								<td class="FieldLabel">
									{template="Not_Required"}
									%%LNG_TestSMTPSettings%%:
								</td>
								<td>
									<img width="20" height="20" src="images/blank.gif"/>
									<input type="text" name="smtp_test" id="smtp_test" value="" class="Field250 smtpSettings"/> %%LNG_HLP_TestSMTPSettings%%
								</td>
							</tr>
							<tr class="SMTPOptions" style="display:none">
								<td class="FieldLabel">&nbsp;
									
								</td>
								<td>
									<img width="20" height="20" src="images/blank.gif"/>
									<input type="button" name="cmdTestSMTP" value="%%LNG_TestSMTPSettings%%" class="FormButton" style="width: 120px;"/>
								</td>
							</tr>
							
							<tr>
								<td colspan="2" class="Heading2" style="padding-left:10px">
									%%LNG_HeaderFooter_Heading%%
								</td>
							</tr>
							<tr>
								<td class="FieldLabel">
									{template="Not_Required"}
									%%LNG_HTMLFooter%%:
								</td>
								<td>
									<textarea name="htmlfooter" rows="10" cols="50" wrap="virtual">%%GLOBAL_HTMLFooter%%</textarea>&nbsp;&nbsp;&nbsp;%%LNG_HLP_HTMLFooter%%
								</td>
							</tr>
							<tr>
								<td class="FieldLabel">&nbsp;</td>
								<td>{$lang.ViewKB_ExplainDefaultFooter}</td>
							</tr>
							<tr>
								<td class="FieldLabel">
									{template="Not_Required"}
									%%LNG_TextFooter%%:
								</td>
								<td>
									<textarea name="textfooter" rows="10" cols="50" wrap="virtual">%%GLOBAL_TextFooter%%</textarea>&nbsp;&nbsp;&nbsp;%%LNG_HLP_TextFooter%%
								</td>
							</tr>
						</table>
					</div>
						
					<div id="div4" style="display: none; padding-top: 10px">
						<table border="0" cellspacing="0" cellpadding="2" width="100%" class="Panel">
							<tr>
								<td class=Heading2 colspan=2 style="padding-left:10px">
									%%LNG_AdminNotifications_SubHeading%%
								</td>
							</tr>
							<tr>
								<td class="FieldLabel">
									{template="Required"}
									%%LNG_EmailAddress%%:
								</td>
								<td>
									<textarea id="adminnotify_email" name="adminnotify_email" class="Field300" style="height: 50px;">%%GLOBAL_AdminNotifyEmailAddress%%</textarea>%%LNG_HLP_NotifyEmailAddress%%
								</td>
							</tr>
							<tr>
								<td class="FieldLabel">&nbsp;</td>
								<td> %%LNG_AdminNotifications_EmailInstruction%% </td>
							</tr>
							<tr>
								<td class="FieldLabel">
									{template="Not_Required"}
									%%LNG_AdminNotifications_Notify_Send%%
								</td>
								<td>
									<div><label for="adminnotify_send_flag"><input type="checkbox" name="adminnotify_send_flag" id="adminnotify_send_flag" value="1" %%GLOBAL_AdminNotificationsSend%%> %%LNG_AdminNotifications_Send_Desc%%</label> %%LNG_HLP_AdminNotifications_Notify_SendEnable%%</div>
									<div class="sectionNotifySend" %%GLOBAL_UseNotifySend%%"><img src="images/nodejoin.gif" width="20" height="20"><label for="adminnotify_send_threshold"><input type="textbox" name="adminnotify_send_threshold" id="adminnotify_send_threshold" value="%%GLOBAL_SendLimit%%" class="Field30"> %%LNG_AdminNotifications_Send_LimitDesc%%</label> %%LNG_HLP_AdminNotifications_Send_Enabled%%</div>
								</td>
							</tr>
							<tr class="sectionNotifySend" %%GLOBAL_UseNotifySend%%>
								<td class="FieldLabel">
									{template="Required"}%%LNG_AdminNotifications_Email_Text%%
								</td>
								<td>
									<div style="width: 20px; height: 20px; float: left;"></div><textarea class="Field300" id="adminnotify_send_emailtext" name="adminnotify_send_emailtext" rows="10" cols="50" wrap="virtual">%%GLOBAL_AdminNotifications_Send_Email%%</textarea>%%LNG_HLP_AdminNotifications_Send_Text%%
								</td>
							</tr>
							<tr>
								<td class="FieldLabel">
									{template="Not_Required"}
									%%LNG_AdminNotifications_Notify_Import%%
								</td>
								<td>
									<div><label for="adminnotify_import_flag"><input type="checkbox" name="adminnotify_import_flag" id="adminnotify_import_flag" value="1" %%GLOBAL_AdminNotificationsImport%%> %%LNG_AdminNotifications_Import_Desc%%</label> %%LNG_HLP_AdminNotifications_Notify_ImportEnable%%</div>
									<div class="sectionNotifyImport" %%GLOBAL_UseNotifyImport%%"><img src="images/nodejoin.gif" width="20" height="20"><label for="adminnotify_import_threshold"><input type="textbox" name="adminnotify_import_threshold" id="adminnotify_import_threshold" value="%%GLOBAL_ImportLimit%%" class="Field30"> %%LNG_AdminNotifications_Import_LimitDesc%%</label> %%LNG_HLP_AdminNotifications_Import_Enabled%%</div>
								</td>
							</tr>
							<tr class="sectionNotifyImport" %%GLOBAL_UseNotifyImport%%>
								<td class="FieldLabel">
									{template="Required"}
									%%LNG_AdminNotifications_Email_Text%%
								</td>
								<td>
									<div style="width: 20px; height: 20px; float: left;"></div><textarea class="Field300" id="adminnotify_import_emailtext" name="adminnotify_import_emailtext" rows="10" cols="50" wrap="virtual">%%GLOBAL_AdminNotifications_Import_Email%%</textarea>%%LNG_HLP_AdminNotifications_Import_Text%%
								</td>
							</tr>
						</table>
					</div>
				</div>
			</td>
		</tr>
	</table>
</form>

<script src="includes/js/jquery/form.js"></script>
<script src="includes/js/jquery/thickbox.js"></script>
<script>
	var CurrentUserID = parseInt('{$UserID}');
	$(function() {
		SetDefaultTab({$DefaultIdTab});
		ShowTab({$DefaultIdTab});
		$(document.users).submit(function() {
			if ($('#username').val().trim().length < 3) {
				ShowTab(1);
				alert("%%LNG_EnterUsername%%");
				$('#username').focus();
				return false;
			}
 
			if (CurrentUserID == 0 || $('#ss_p').val() != "") {
				if ($('#ss_p').val().trim().length < 8) {
					ShowTab(1);
					alert("%%LNG_NoValidPassword%%");
					$('#ss_p').focus().select();
					return false;
				}     
				var re = /^(?=.*\d)(?=.*[!@#$%^&*])(?=.*[a-z])(?=.*[A-Z]).{8,}$/; 
				if(!re.test($('#ss_p').val())){   
					alert("%%LNG_NoValidPassword%%");
					$('#ss_p').focus().select();
					return false;
				}   

				if ($('#ss_p').val() != $('#ss_p_confirm').val()) {
					ShowTab(1);
					alert("%%LNG_PasswordsDontMatch%%");
					$('#ss_p_confirm').focus().select();
					return false;
				}
			}

			if ($(document.users.groupid).val() == 0) {
				ShowTab(1);
				alert("{$lang.UsersGroups_Choose_Group}");
				$(document.users.groupid).focus();
				return false;
			}

			if ($('#emailaddress').val().indexOf('@') == -1 || $('#emailaddress').val().indexOf('.') == -1) {
				ShowTab(1);
				alert("%%LNG_EnterEmailaddress%%");
				$('#emailaddress').focus().select();
				return false;
			}

			if ($('#adminnotify_email').val().indexOf('@') == -1 || $('#emailaddress').val().indexOf('.') == -1) {
				ShowTab(6);
				alert("%%LNG_EnterNotifyAdminEmail%%");
				$('#adminnotify_email').focus().select();
				return false;
			}
			
			if ($('#adminnotify_email').val() == "") {
				ShowTab(6);
				alert("%%LNG_EnterNotifyAdminEmail%%");
				$('#adminnotify_email').focus().select();
				return false;
			}  
			
			if ($('#adminnotify_send_flag').attr('checked')) {
				var sendThresholdValue = $('#adminnotify_send_threshold').val();
				var sendThreshold = sendThresholdValue.replace(/[ ]/ig, "");
				
				if (isNaN(sendThreshold)) {
					ShowTab(6);
					alert("%%LNG_EnterNotifyAdminThresholdNotNumber%%");
					$('#adminnotify_send_threshold').focus().select();
					return false;
				}
			
				if ( sendThresholdValue <= 0 ) {
					ShowTab(6);
					alert("%%LNG_EnterNotifyAdminThreshold%%");
					$('#adminnotify_send_threshold').focus().select();
					return false;
				}
				
				if($('#adminnotify_send_emailtext').val() == "") {
					ShowTab(6);
					alert("%%LNG_EnterNotifyAdminEmailText%%");
					$('#adminnotify_send_emailtext').focus().select();
					return false;
				}
			}
			
			if ($('#adminnotify_import_flag').attr('checked')) {
				var importThresholdValue = $('#adminnotify_import_threshold').val();
				var importThreshold = importThresholdValue.replace(/[ ]/ig, "");
				
				if (isNaN(importThreshold)) {
					ShowTab(6);
					alert("%%LNG_EnterNotifyAdminThresholdNotNumber%%");
					$('#adminnotify_import_threshold').focus().select();
					return false;
				}
			
				if ( importThresholdValue <= 0 ) {
					ShowTab(6);
					alert("%%LNG_EnterNotifyAdminThreshold%%");
					$('#adminnotify_import_threshold').focus().select();
					return false;
				}
				
				if($('#adminnotify_import_emailtext').val() == "") {
					ShowTab(6);
					alert("%%LNG_EnterNotifyAdminEmailText%%");
					$('#adminnotify_import_emailtext').focus().select();
					return false;
				}
			}

			$('input#xmlapi', document.users).attr('disabled', false);
			
			return true;
		});

		$('.CancelButton', document.users).click(function() { if(confirm('%%LNG_ConfirmCancel%%')) document.location.href='index.php?Page=Users'; });

		$(document.users.usewysiwyg).click(function() { $('#sectionUseXHTML')[this.checked? 'show' : 'hide'](); });
		$(document.users.adminnotify_send_flag).click(function() { $('.sectionNotifySend')[this.checked? 'show' : 'hide'](); });
		$(document.users.adminnotify_import_flag).click(function() { $('.sectionNotifyImport')[this.checked? 'show' : 'hide'](); });

		$(document.users.xmlapi).click(function() {
			$('#sectionXMLToken').toggle();
		});

		$('.SelectOnFocus').focus(function() { this.select(); });

		$('#hrefRegenerateXMLToken').click(function() {
			$.post('index.php?Page=Users&Action=GenerateToken',
					{	'username':	document.users.username.value,
						'fullname':	document.users.fullname.value,
						'emailaddress': document.users.emailaddress.value},
					function(token) { $("#xmltoken").val(token); });
			return false;
		});

		$(document.users.listadmintype).change(function() { $('#PrintLists')[this.selectedIndex == 0? 'hide' : 'show'](); });
		$(document.users.segmentadmintype).change(function() { $('#PrintSegments')[this.selectedIndex == 0? 'hide' : 'show'](); });
		$(document.users.templateadmintype).change(function() { $('#PrintTemplates')[this.selectedIndex == 0? 'hide' : 'show'](); });

		$('#subscribers_add, #subscribers_edit, #subscribers_delete').click(function() {
			$('#subscribers_manage').attr('checked', ($('#subscribers_add, #subscribers_edit, #subscribers_delete').filter(':checked').size() != 0));
		});

		$('#subscribers_manage').click(function(event) {
			if($('#subscribers_add, #subscribers_edit, #subscribers_delete').filter(':checked').size() != 0) {
				event.preventDefault();
				event.stopPropagation();
			}
		});

		$('#segment_create, #segment_edit, #segment_delete, #segment_send').click(function() {
			$('#segment_view').attr('checked', ($('#segment_create, #segment_edit, #segment_delete, #segment_send').filter(':checked').size() != 0));
		});

		$('#segment_view').click(function(event) {
			if($('#segment_create, #segment_edit, #segment_delete, #segment_send').filter(':checked').size() != 0) {
				event.preventDefault();
				event.stopPropagation();
			}
		});

		$(document.users.smtptype).click(function() {
			$('.SMTPOptions')[document.users.smtptype[1].checked? 'show' : 'hide']();
		});

		$(document.users.cmdTestSMTP).click(function() {
			var f = document.forms[0];
			if (f.smtp_server.value == '') {
				alert("%%LNG_EnterSMTPServer%%");
				f.smtp_server.focus();
				return false;
			}

			if (f.smtp_test.value == '') {
				alert("%%LNG_EnterTestEmail%%");
				f.smtp_test.focus();
				return false;
			}

			tb_show('%%LNG_SendPreview%%', 'index.php?Page=Users&Action=SendPreviewDisplay&keepThis=true&TB_iframe=true&height=250&width=420', '');
			return true;
		});

		$('.SMTPOptions')[document.users.smtptype[1].checked? 'show' : 'hide']();
	});

	function getSMTPPreviewParameters() {
		var values = {};
		$($('.smtpSettings', document.users).fieldSerialize().split('&')).each(function(i,n) {
			var temp = n.split('=');
			if (temp.length == 2) values[temp[0]] = temp[1];
		});
		return values;
	}

	function closePopup() {
		tb_remove();
	}

	/**
	 * Checks that all $(name)s matching 'pattern' are checked, or if
	 * reversed, checks that all $(name)s not matching 'pattern' are
	 * not checked.
	 */
	function allItemsChecked(opts, pattern, reverse)
	{
		var all_checked = true;
		$(opts).each(function() {
			if ((!reverse && this.name.match(pattern) && !this.checked) || (reverse && !this.name.match(pattern) && this.checked)) {
				all_checked = false;
				return false;
			}
		});
		return all_checked;
	}

	/**
	 * Loads/caches the checked state of boxes into bucket.
	 */
	function loadCheckboxes(opts)
	{
		var bucket = [];
		opts.each(function() {
			bucket.push({"name": this.name, "checked": this.checked});
		});
		return bucket;
	}

	// To load up default value from whatever form saved last
	function SetDefaultTab(id) {
		$('#id_tab_num').val(id);
	}
	
</script>
