<?php

?>

<div class="unet_black_overlay" id="setuser_overlay_black"></div>
<div class="unet_dialog_panel setting_main_panel radius5" id="setuser_panel_chanpass_black">
	<div class="unet_dlg_header">
		<div class="unet_hd_ico"></div>
		<div class="unet_hd_lbl"> تغییر رمز عبور</div>
		<div class="unet_hd_cmd_btn" id="userset_btn_dlg_close"></div>
	</div>
	<div class="unet_dlg_content userset_dlg_content box_sized">
		<input type="password" id="setuser_txt_oldpass" placeholder="Current Password"  class="box_sized radius3 setuser_txt_chngpass_style"/>
		<input type="password" id="setuser_txt_newpass" placeholder="New Password"  class="box_sized radius3 setuser_txt_chngpass_style"/>
		<input type="password" id="setuser_txt_retype_new_pass" placeholder="Retype New Password" class="box_sized radius3 setuser_txt_chngpass_style"/>
		<input type="button" id="setuser_btn_changepass" class="setuser_btn_chngpass_style" value="ذخیره" />
	</div>
</div>
