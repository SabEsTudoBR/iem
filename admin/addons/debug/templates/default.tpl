<script src="includes/js/jquery/form.js"></script>
<script src="includes/js/jquery/thickbox.js"></script>
<link rel="stylesheet" type="text/css" href="includes/styles/thickbox.css" />
<script>
		function DeleteLogs()
		{
			if (!confirm('{$lang.Addon_systemlog_Alert_DeleteSelected}')) {
				return false;
			}

			frm = document.getElementById('frmTriggerLogList');
			logs_found = 0;
			for (var i=0;i < frm.length;i++)
			{
				fldObj = frm.elements[i];
				if (fldObj.type == 'checkbox')
				{
					if (fldObj.checked) {
						logs_found++;
						break;
					}
				}
			}

			if (logs_found == 0) {
				alert('{$lang.Addon_systemlog_ChooseLogsToDelete_Alert}');
				return false;
			}

			frm.action = frm.action + '&Action=Delete';
			
			frm.submit();
		}

		function DeleteAllLogs()
		{
			// Please choose at least one entry to delete.
			if (confirm('{$lang.Addon_systemlog_Alert_DeleteAll}')) {
				frm = document.getElementById('frmTriggerLogList');
				frm.action = frm.action + '&Action=DeleteAll';
				frm.submit();

				return true;
			}
			return false;
		}
		 
	</script>
											 
<form ENCTYPE="multipart/form-data" name="settings" id="frmSettings" method="post" action="index.php?Page=Addons&Addon=debug&action=emaildebug">
<input type="hidden" value="{$ShowTab}" name="tab_num" id="id_tab_num" />
<table cellspacing="0" cellpadding="0" width="100%" align="center" style="margin-left: 4px;">
	<tr>
		<td class="Heading1">%%LNG_Addon_debug_Menu_Text%%</td>
	</tr>	 
	<tr>
		<td>		 
			<span style="display:block">
			 %%LNG_Message%% 
			</span>
			<span style="display: %%GLOBAL_DisplayAttachmentsMessage%%">
				%%GLOBAL_Attachments_Message%% <br>
				{$flash_messages} 
			</span>
			%%GLOBAL_Send_TestMode_Report%%
		</td>
	</tr>
	<tr>
		<td>
			%%GLOBAL_CronWarning%%
		</td>
	</tr>
	 <tr>
		<td>
		 {$FlashMessages}
		</td>
	</tr>
	<tr>

		<td>
			<div>
				<br/>
				<ul id="tabnav">
					<li><a href="index.php?Page=Addons&Addon=debug&Tab=1" {if $ShowTab == 1}class="active"{/if} id="tab1" onclick="ShowTab(1); $('#id_tab_num').val(1); return false;"><span>{$lang.Email_Debugging}</span></a></li>
					<li><a href="index.php?Page=Addons&Addon=debug&Tab=2" {if $ShowTab == 2}class="active"{/if} id="tab2" onclick="ShowTab(2); $('#id_tab_num').val(2); return false;"><span>{$lang.Bounce_Debugging}</span></a></li>
					<li><a href="index.php?Page=Addons&Addon=debug&Tab=3" {if $ShowTab == 3}class="active"{/if} id="tab3" onclick="ShowTab(3); $('#id_tab_num').val(3); return false;"><span>{$lang.JobTriggerEmails_Debugging}</span></a></li>
					<li><a href="index.php?Page=Addons&Addon=debug&Tab=4" {if $ShowTab == 4}class="active"{/if} id="tab4" onclick="ShowTab(4); $('#id_tab_num').val(4); return false;"><span>{$lang.Autoresponder_Debugging}</span></a></li>
				</ul>
			 <br/>
				<form ENCTYPE="multipart/form-data" name="settings" id="frmSettings" method="post" action="index.php?Page=Addons&Addon=debug&action=emaildebug">
				 <div id="div1" style="padding-top: 0px; display:{if $ShowTab == 1}block{else}none{/if};">
					<table border="0" cellspacing="0" cellpadding="2" width="100%" class="Panel">
						<tr>
							<td colspan="2" class="Heading2">
							 %%LNG_Enable_Disable%%
							</td>
						</tr> 
						<tr>
							<td class="FieldLabel">
								{template="Not_Required"}
								%%LNG_Email_Debug%%:
							</td>
							<td>
								<label for="allow_embedimages">
									 <input type="checkbox" name="emaildebug" id="emaildebug"  {if $Emaildebug == 1}checked{/if}>%%LNG_Email_On_Off%%
								</label>								 
								%%LNG_HLP_UseEmailDebug%%
							</td>
						</tr>
						 <tr>
							<td class="FieldLabel">							
							</td>
							<td>							
							</td>
						</tr>
						<tr>
							<td>
								<input type="hidden" name="database_type" value="%%GLOBAL_DatabaseType%%" />
								<input name="setting_submit" class="FormButton SubmitButton" type="submit" value="%%LNG_Save%%"  />
								<input class="FormButton CancelButton" type="button" value="%%LNG_Cancel%%" />
							</td>
						</tr>
						 <tr>
							<td colspan="2" class="Heading2">
							 %%LNG_Manage_Debug_Log_File%%
							</td>
						</tr>
						 <tr>
							<td class="FieldLabel">
								{template="Not_Required"}
								 %%LNG_Email_Debug%%:
							</td>
							<td>
								<label for="emaildebugw">
									{$EmailDebugLogFile}									
								</label>
								 
							</td>	
						</tr>
						<tr>
							<td class="FieldLabel">
								
							</td>
							<td>
								<label for="emaildebug">
								{$GenerateDeleteButtonEmail}
								</label>								
							</td>
						</tr>
					</table>
				</div>				
				</form>	
				<form ENCTYPE="multipart/form-data" name="settings" id="frmSettings" method="post" action="index.php?Page=Addons&Addon=debug&action=bouncedebug">
					 
					<div id="div2" style="padding-top: 10px; display: {if $ShowTab == 2}block{else}none{/if};">
					<table border="0" cellspacing="0" cellpadding="2" width="100%" class="Panel">
					 <tr>
							<td colspan="2" class="Heading2">
								%%LNG_Enable_Disable%%
							</td>
					</tr>
						 
					<tr>
						<td class="FieldLabel">
							{template="Not_Required"}
							%%LNG_Bounce_Debug%%:
						</td>
						<td>
							<label for="bouncedebug">
								<input type="checkbox" name="bouncedebug" id="bouncedebug" value="1" {if $Bouncedebug == 1}checked{/if}>%%LNG_Bounce_On_Off%%			 
							</label>
							%%LNG_HLP_UseBounceDebug%%
						</td>
					</tr>
					<tr>
						<td class="FieldLabel">
							 
						</td>						 
					</tr>
						<tr>
						<td>
							<input type="hidden" name="database_type" value="%%GLOBAL_DatabaseType%%" />
							<input name="setting_submit" class="FormButton SubmitButton" type="submit" value="%%LNG_Save%%"  />
							<input class="FormButton CancelButton" type="button" value="%%LNG_Cancel%%" />
						</td>
						</tr>
						 <tr>
							<td colspan="2" class="Heading2">
							%%LNG_Manage_Debug_Log_File%%
							</td>
						</tr>
						 <tr>
							<td class="FieldLabel">
								{template="Not_Required"}
								 %%LNG_Bounce_Debug%%:
							</td>
							<td>
								<label for="emaildebug">
									{$bounceDebugLogFile}									
								</label>								 
							</td>	
						</tr>
						<tr>
							<td class="FieldLabel">								
							</td>
							<td>
								<label for="emaildebug">
								{$GenerateDeleteButtonBounce}
								</label>
								
							</td>
						</tr>
					</table>
				</div>
			 
			</form>
			<form ENCTYPE="multipart/form-data" name="settings" id="frmSettings" method="post" action="index.php?Page=Addons&Addon=debug&action=triggeremaildebug">
					 
					<div id="div3" style="padding-top: 10px; display: {if $ShowTab == 3}block{else}none{/if};">
					<table border="0" cellspacing="0" cellpadding="2" width="100%" class="Panel">
					 <tr>
							<td colspan="2" class="Heading2">
								%%LNG_Enable_Disable%%
							</td>
					</tr>
						 
					<tr>
						<td class="FieldLabel">
							{template="Not_Required"}
							%%LNG_TriggerEmail_Debug%%:
						</td>
						<td>
							<label for="triggeremaildebug">
								<input type="checkbox" name="triggeremaildebug" id="triggeremaildebug" value="1" {if $TriggerEmaildebug == 1}checked{/if}>%%LNG_TriggerEmail_On_Off%%			 
							</label>
							%%LNG_HLP_UseTriggerEmailDebug%%
						</td>
					</tr>
					<tr>
						<td class="FieldLabel">
							 
						</td>
						 
					</tr>
						<tr>
						<td>
							<input type="hidden" name="database_type" value="%%GLOBAL_DatabaseType%%" />
							<input name="setting_submit" class="FormButton SubmitButton" type="submit" value="%%LNG_Save%%"  />
							<input class="FormButton CancelButton" type="button" value="%%LNG_Cancel%%" />
						</td>
						</tr>
						 <tr>
							<td colspan="2" class="Heading2">
							 
							</td>
						</tr>
						  
					</table>
					
		</form>			
	<!-----------------Start of Logs Viewer----------------->	
	<form ENCTYPE="multipart/form-data" name="frmTriggerLogList" id="frmTriggerLogList" method="post" action="index.php?Page=Addons&Addon=debug&action=triggeremaildebug">
			
<table width="100%" border="0" class="Panel" cellpadding="2" cellspacing="0">	
		<tr>
			<td valign="bottom">
				<div>
				%%LNG_Total_Files%%: {$TotalTriggerEmailFiles}  
               </div>
			</td>
			<td align="right">
				<div align="right">
				 	 
				</div>
			</td>
		</tr>
	</table>	
	 
</div>
			 
</form>
<!-----------------End of Logs Viewer----------------->


<form ENCTYPE="multipart/form-data" name="frmAutoResponderLogList" id="frmAutoResponderLogList" method="post" action="index.php?Page=Addons&Addon=debug&action=autoresponderdebug">
					 
					<div id="div4" style="padding-top: 10px; display: {if $ShowTab == 4}block{else}none{/if};">
					<table border="0" cellspacing="0" cellpadding="2" width="100%" class="Panel">
					 <tr>
							<td colspan="2" class="Heading2">
								%%LNG_Enable_Disable%%
							</td>
					</tr>
						 
					<tr>
						<td class="FieldLabel">
							{template="Not_Required"}
							%%LNG_Autoresponder_Debug%%: 
						</td>
						<td>
							<label for="responderdebug">
							 	<input type="checkbox" name="autoresponderdebug" id="autoresponderdebug" value="1" {if $Autoresponderdebug == 1}checked{/if}>%%LNG_Autoresponder_On_Off%%			 
							</label>
							%%LNG_HLP_UseAutoresponderDebug%% 
						</td>
					</tr>
					<tr>
						<td class="FieldLabel">
							 
						</td>
						 
					</tr>
						<tr>
						<td>
							<input type="hidden" name="database_type" value="%%GLOBAL_DatabaseType%%" />
							<input name="setting_submit" class="FormButton SubmitButton" type="submit" value="%%LNG_Save%%"  />
							<input class="FormButton CancelButton" type="button" value="%%LNG_Cancel%%" />
						</td>
						</tr>
						 <tr>
							<td colspan="2" class="Heading2">
							  
							</td>
						</tr>
						 
					</table>
		 <table width="100%" border="0" class="Panel" cellpadding="2" cellspacing="0">	
		<tr>
			<td valign="bottom">
				<div>
                 %%LNG_Total_Files%%: {$TotalAutoresponderFiles}  
				 </div>
			</td>
			<td align="right">
				<div align="right">
				 
				</div>
			</td>
		</tr>
	</table>
				 
				</div>				
			
</form>
</div>
		</td>
	</tr>
	
</table>
%%GLOBAL_ExtraScript%%
