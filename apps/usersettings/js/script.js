$(function(){
	
	$('body').on('click','#setuser_btn_changepass',function()
	{
		var oldpass = $('#setuser_txt_oldpass').val().trim();
		var npass = $('#setuser_txt_newpass').val().trim();
		var nnpass = $('#setuser_txt_retype_new_pass').val().trim();
		var btnobj = $(this);
		if(oldpass!='' && oldpass!='' && oldpass!='')
		{
			if(npass === nnpass)
			{
				$.ajax({
                    url: 'apps/usersettings/lib/ajaxhnd.php',
                    type: 'POST',
					data: {func:'reset_pass',op:oldpass,np:npass},
                    beforeSend: function () {
                        btnobj.val('در حال تغییر ...');
                    },
                    success: function (data) {
						if(data === 'true')
						{
							btnobj.val('رمز جدید ذخیره شد');
							setTimeout(function(){
								btnobj.val('ذخیره');
							},3000);
						}
						else
						{
							alert('رمز قبلی اشتباه می باشد');
							btnobj.val('ذخیره');
						}
                    }
                });
			}
			else
			{
				alert('رمز جدید با تکرار آن مساوی نمی باشد');
			}
		}		
	});
	$('body').on('click','#userset_btn_dlg_close',function()
	{
		$('#setuser_panel_chanpass_black').remove();
		$('#setuser_overlay_black').remove();
	});
});

