<form action="index.php?Page=%%PAGE%%&Action=%%GLOBAL_SubmitAction%%" method="post" name="frmLogin" id="frmLogin" autocomplete="off">
	<div id="box" class="loginBox">
		<table><tr><td style="border:solid 2px #DDD; padding:20px; background-color:#FFF; width:300px;">
		<table>
			<tr>
			<td class="Heading1">
				<img src="%%WHITELABEL_ApplicationLogoImage%%" alt="{$lang.SendingSystem}" />
			</td>
			</tr>
			<tr>
			<td style="padding:10px 0px 5px 0px">%%GLOBAL_Message%%</td>
			</tr>
			<tr>
			<td>
				<table>
				<tr>
					<td nowrap="nowrap" style="padding:0px 10px 0px 10px">%%LNG_OTP%%:</td>
					<td>
					<input type="text" name="otp" id="otp" class="Field150" autocomplete="off">
					</td>
				</tr>
				 
				 
					<tr>
					<td>&nbsp;</td>
					<td>
						<input type="submit" name="SubmitButton" value="%%LNG_Login%%" class="FormButton">
						 
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

			if(f.otp.value == '')
			{
				alert('Please enter your OTP.');
				f.otp.focus();
				
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
