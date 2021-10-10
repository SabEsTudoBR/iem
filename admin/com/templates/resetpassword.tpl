<script>
	$(function() {
		$(document.frmLogin.ss_takemeto).val('%%GLOBAL_ss_takemeto%%');
	});
</script>

<form action="index.php?Page=Login&Action=%%GLOBAL_UpdatePassword%%" method="post" name="frmLogin" id="frmLogin" autocomplete="off">
	<div id="box" class="loginBox">
		<table><tr><td style="border:solid 2px #DDD; padding:20px; background-color:#FFF; width:300px;">
		<table>
			<tr>
			<td class="Heading1">
				<img src="%%WHITELABEL_ApplicationLogoImage%%" alt="{$lang.SendingSystem}" /> {$GLOBAL_Message}
			</td>
			</tr>
			<tr>
			<td style="padding:5px 0px 5px 0px">Please enter your new and confirm password.</td>
			</tr>
			<tr>
			<td>
				<table>

				<tr>
					<td nowrap="nowrap" style="padding:0px 10px 0px 10px">New %%LNG_EnterPassword%%:</td>

					<td>
					<input type="password" name="ss_password" id="password" class="Field150" autocomplete="off">
					</td>
				</tr>
				<tr>
					<td nowrap="nowrap" style="padding:0px 10px 0px 10px">Confirm %%LNG_EnterPassword%%:</td>

					<td>
					<input type="password" name="ss_password_confirm" id="password" class="Field150" autocomplete="off">
					</td>
				</tr>
				 
					<tr>
					<td>&nbsp;</td>
					<td>
						<input type="submit" name="resetpassword" value="Reset" class="FormButton">
						 
					</td>
					</tr>

					<tr><td class="Gap"></td></tr>
				</table>
			</td>
			</tr>
		</table>
		</td></tr>

		<tr>
			<td>

				<div class="PageFooter" style="padding: 10px 10px 10px 0px; margin-bottom: 20px; text-align: center;">
					%%LNG_Copyright%%
				</div>
			</td>
		</tr>

		</table>

	</div>

	</form>

	<script>

		$('#frmLogin').submit(function() {
			var f = document.frmLogin;

			if(f.username.value == '')
			{
				alert('Please enter your username.');
				f.username.focus();
				f.username.select();
				return false;
			}

			if(f.password.value == '')
			{
				alert('Please enter your password.');
				f.password.focus();
				f.password.select();
				return false;
			}

			// Everything is OK
			f.action = 'index.php?Page=Login&Action=%%GLOBAL_SubmitAction%%';
			return true;
		});

		function sizeBox() {
			var w = $(window).width();
			var h = $(window).height();
			$('#box').css('position', 'absolute');
			$('#box').css('top', h/2-($('#box').height()/2)-50);
			$('#box').css('left', w/2-($('#box').width()/2));
		}

		$(document).ready(function() {
			sizeBox();
			$('#username').focus();
		});

		$(window).resize(function() {
			sizeBox();
		});
		createCookie("screenWidth", screen.availWidth, 1);

	</script>
