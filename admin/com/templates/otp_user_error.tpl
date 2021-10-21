<div id="box" class="loginBox">
		<table><tr><td style="border:solid 2px #DDD; padding:20px; background-color:#FFF; width:300px;">
		<table>
			<tr>
			<td class="Heading1">
				<img src="%%WHITELABEL_ApplicationLogoImage%%" alt="{$lang.SendingSystem}" />
			</td>
			</tr>
			<tr>
			<td style="">%%GLOBAL_Message%%</td>
			</tr>
			<tr>
			<td>
				<table>
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


	<script>
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
	  