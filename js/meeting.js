$(function(){
	$('body').on('click','#meeting_btn_newmetting_add',function()
	{
		var checkrequire = true;
		$('.txt_required').each(function()
		{
			if($(this).val().trim() == '')
			{
				checkrequire = false;
				$(this).addClass('fieldrequire');
			}
			else
			{
				$(this).removeClass('fieldrequire');
			}
		});
		
		if(checkrequire)
		{
			var txt_moteghasi = $('#meeting_txt_body_needer').val().trim();
			var txt_subject = $('#meeting_txt_meeting_subject').val().trim();
			
			var txt_meeting_ydate = $('#meeting_txt_sal').val().trim();
			var txt_meeting_mdate = $('#meeting_txt_mah').val().trim();
			var txt_meeting_ddate = $('#meeting_txt_rooz').val().trim();			
			var txt_meeting_stime = $('#meeting_txt_hour').val().trim() + ':' + $('#meeting_txt_min').val().trim();
			var txt_meeting_ftime = $('#meeting_txt_phour').val().trim()  + ':' + $('#meeting_txt_pmin').val().trim();
			
			var txt_meeting_txt_coname = $('#meeting_txt_coname').val().trim();
			var txt_meeting_txtarea_members = $('#meeting_txtarea_members').val().trim();
			
			var arrlist = 
			{
				neederman:txt_moteghasi, 
				subj:txt_subject, 
				ydate:txt_meeting_ydate,
				mdate:txt_meeting_mdate,
				ddate:txt_meeting_ddate,
				stime:txt_meeting_stime,
				ftime:txt_meeting_ftime,
				coname:txt_meeting_txt_coname,
				members:txt_meeting_txtarea_members
			};
			$.ajax({
				url: 'apps/meeting/ajax/addmeeting.php',
				type: 'POST',
				dataType: 'json',
				data: {'arrvalues' :arrlist},
				success: function(msg) 
				{
					//alert(msg);
					$.each(msg, function(index, value)
					{
						alert(index + ' : ' + value);
					});
				},
				beforeSend:function()
				{
				}
			});
		}
	});
	
	$('body').on('click','#meeting_img_conflict_close',function()
	{
		$('#meeting_conflict_content_id').fadeOut('fast',function(){
			$('#meeting_overlay_for_conflict').fadeOut('fast');
		});
	});
	$('body').on('click','#meeting_btn_newmetting_cancel',function()
	{
		$("#meeting_new_seesion_content").fadeOut('fast',function()
		{
			$('#meeting_new_seesion').animate({width:'0px'},"fast")
		});
	});
	$('body').on('click','#meeting_btn_newmetting',function()
	{
		$('#meeting_new_seesion').animate(
			{width:'65%'},
			"fast",
			function(){
				$("#meeting_new_seesion_content").fadeIn();
			});
	});
	
	$('body').on('blur','.len2jump',function () 
	{
		if($(this).val().length < $(this).attr('maxlength'))
		{
			var curvalue = $(this).val().length;
			var tmp = '';
			for(var i=0;i<$(this).attr('maxlength') - curvalue;i++)
			{
				tmp += '0';
			}
			$(this).val(tmp+$(this).val());
		}
	});
	$('body').on('keyup','.len2jump',function () 
	{	
		if($(this).val().length == $(this).attr('maxlength'))
		{
			if($(this).val() <= $(this).attr('maxvalue'))
			{
				var nextid = $(this).attr('jumptoid');
				$('#'+nextid).focus().select();
			}
			else
			{
				$(this).val($(this).attr('maxvalue'));
			}
		}
	});
	
	/*$('body').on('keydown','.justnumber',function (e) {
		// Allow: backspace, delete, tab, escape, enter and .
		if ($.inArray(e.keyCode, [46, 8, 9, 27, 13, 110, 190]) !== -1 ||
			 // Allow: Ctrl+A
			(e.keyCode == 65 && e.ctrlKey === true) || 
			 // Allow: home, end, left, right
			(e.keyCode >= 35 && e.keyCode <= 39)) {
				 // let it happen, don't do anything
				 return;
		}
		// Ensure that it is a number and stop the keypress
		if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
			e.preventDefault();
		}
	});*/
		
});