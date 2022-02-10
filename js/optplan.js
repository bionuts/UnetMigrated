$(function () {
		
    var optplan_hhh = $(window).height();
    $('#optplan_panel2').css('max-height', (optplan_hhh - 203) + 'px');
    $('#optplan_panel1').css('max-height', (optplan_hhh - 203) + 'px');


    var minlen = 5 * 1000 * 60;
    var optplan_refresh_inv = null;
    var ckloccman = $('#optplan').attr('occman');
    if ('false' == ckloccman) {
        optplan_refresh_inv = setInterval(optplan_refresh, minlen);
    }
    $('body').on('click', '#optplan_btn_refreshall_pm', function () {
        if ('false' == ckloccman) {
            clearInterval(optplan_refresh_inv);
        }
        optplan_refresh($(this).attr('isocc'));
        if ('false' == ckloccman) {
            optplan_refresh_inv = setInterval(optplan_refresh, minlen);
        }
    });
    function optplan_refresh(isocc) {
        $('#optplan_btn_refreshall_pm_img').hide();
        $('#optplan_btn_refreshall_pm_lbl').hide();
		
		if(isocc == 'true')
		{		
			$.ajax({
				url: 'apps/optplan/ui/occmain.php',
				type: 'GET',
				dataType: "json",
				beforeSend: function () {
					$('#optplan_btn_refreshall_pm_img').show();
				},
				success: function (data) {
					//optplan										
					$('#optplan_btn_refreshall_pm_img').hide();
					$('#optplan_btn_refreshall_pm_lbl').show();
					setTimeout(function () {
						$('#optplan_btn_refreshall_pm_lbl').fadeOut('fast');					
					}, 2000);
					$('#optplan_txtarea_hints_txt').val(data.commontxt);
					tinyMCE.get('optplan_txtarea_notes_today').setContent(data.today_note);
					tinyMCE.get('optplan_txtarea_optplan_for_today').setContent(data.today_opt);
					tinyMCE.get('optplan_txtarea_notes_tomorrow').setContent(data.tomorrow_note);
					tinyMCE.get('optplan_txtarea_optplan_for_tomorrow').setContent(data.tomorrow_opt);					
				}
			});			
		}
		else
		{			
			$.ajax({
				url: 'apps/optplan/ui/main.php',
				type: 'GET',
				beforeSend: function () {
					$('#optplan_btn_refreshall_pm_img').show();
				},
				success: function (data) {
					//optplan
					$('#optplan').remove();
					$(data).insertBefore('#desktop-main');
					var optplan_hhh = $(window).height();
					$('#optplan_panel2').css('max-height', (optplan_hhh - 203) + 'px');
					$('#optplan_panel1').css('max-height', (optplan_hhh - 203) + 'px');
					$('#optplan_btn_refreshall_pm_img').hide();
					$('#optplan_btn_refreshall_pm_lbl').show();
					setTimeout(function () {
						$('#optplan_btn_refreshall_pm_lbl').fadeOut('fast');					
					}, 2000);	
				}
			});
		}
    }
	$('body').on('click', '#opt_plan_btn_send_notes', function () {
		//var content = tinyMCE.get('optplan_txtarea_notes_today').getContent();
		//tinyMCE.get('optplan_txtarea_notes_today').setContent('hello');
		
        $('#span_optplan_img_noteloader').html('');
        var dt = $(this).attr('day');
        var txt = tinyMCE.get('optplan_txtarea_notes_today').getContent();//$('#optplan_txtarea_notes_today').val().trim();
        if (dt == "today") {
            $.ajax({
                url: 'apps/optplan/lib/ajaxhnd.php',
                type: 'POST',
                data: {'func': 'note_today', 'notes': txt},
                beforeSend: function () {
                    $('#optplan_img_noteloader').show();
                },
                success: function (data) {
                    if (data) {
                        $('#span_optplan_img_noteloader').css('color', '#4c0f5b');
                        $('#span_optplan_img_noteloader').html('ذخیره شد');
                        setTimeout(function () {
                            $('#span_optplan_img_noteloader').fadeOut('fast', function () {
                                $('#span_optplan_img_noteloader').html('');
                                $('#span_optplan_img_noteloader').show();
                            });
                        }, 3000);
                    }
                    else {
                        //$('#span_optplan_img_noteloader').css('color','#bf1313');
                        //$('#span_optplan_img_noteloader').html('خطا در سیستم دوباره سعی کنید');
                    }
                    $('#optplan_img_noteloader').hide();
                }
            });
        }
    });
	
	
	$('body').on('click', '#opt_plan_btn_send_daily', function () {
        $('#span_optplan_img_opt_loader').html('');
        var dt = $(this).attr('day');
        var txt = $('#optplan_txtarea_hints_txt').val().trim();
        if (dt == "today") {
            $.ajax({
                url: 'apps/optplan/lib/ajaxhnd.php',
                type: 'POST',
                data: {'func': 'save_hint_txt', 'notes': txt},
                beforeSend: function () {
                    $('#optplan_img_dailyloader').show();
                },
                success: function (data) {
                    if (data) {
                        $('#span_optplan_img_dailyloader').css('color', '#213e09');
                        $('#span_optplan_img_dailyloader').html('ذخیره شد');
                        setTimeout(function () {
                            $('#span_optplan_img_opt_loader').fadeOut('fast', function () {
                                $('#span_optplan_img_dailyloader').html('');
                                $('#span_optplan_img_dailyloader').show();
                            });
                        }, 3000);
                    }
                    else {
                        //$('#span_optplan_img_opt_loader').css('color','#bf1313');
                        //$('#span_optplan_img_opt_loader').html('خطا در سیستم دوباره سعی کنید');
                    }
                    $('#optplan_img_dailyloader').hide();
                }
            });
        }
    });

    $('body').on('click', '#opt_plan_btn_send_opttask_tomorrow', function () {
        $('#span_optplan_img_opt_loader').html('');
        var dt = $(this).attr('day');
        var txt = tinyMCE.get('optplan_txtarea_optplan_for_tomorrow').getContent();//$('#optplan_txtarea_optplan_for_tomorrow').val().trim();
        if (dt == "today") {
            $.ajax({
                url: 'apps/optplan/lib/ajaxhnd.php',
                type: 'POST',
                data: {'func': 'opt_for_tomorrow', 'notes': txt},
                beforeSend: function () {
                    $('#optplan_img_opt_loadertomorrow').show();
                },
                success: function (data) {
                    if (data) {
                        $('#span_optplan_img_opt_loadertomorrow').css('color', '#213e09');
                        $('#span_optplan_img_opt_loadertomorrow').html('ذخیره شد');
                        setTimeout(function () {
                            $('#span_optplan_img_opt_loadertomorrow').fadeOut('fast', function () {
                                $('#span_optplan_img_opt_loadertomorrow').html('');
                                $('#span_optplan_img_opt_loadertomorrow').show();
                            });
                        }, 3000);
                    }
                    else {
                        //$('#span_optplan_img_opt_loader').css('color','#bf1313');
                        //$('#span_optplan_img_opt_loader').html('خطا در سیستم دوباره سعی کنید');
                    }
                    $('#optplan_img_opt_loadertomorrow').hide();
                }
            });
        }
    });

    $('body').on('click', '#opt_plan_btn_send_opttask', function () {
        $('#span_optplan_img_opt_loader').html('');
        var dt = $(this).attr('day');
        var txt = tinyMCE.get('optplan_txtarea_optplan_for_today').getContent();//$('#optplan_txtarea_optplan_for_today').val().trim();
        if (dt == "today") {
            $.ajax({
                url: 'apps/optplan/lib/ajaxhnd.php',
                type: 'POST',
                data: {'func': 'opt_for_today', 'notes': txt},
                beforeSend: function () {
                    $('#optplan_img_opt_loader').show();
                },
                success: function (data) {					
                    if (data) {
                        $('#span_optplan_img_opt_loader').css('color', '#213e09');
                        $('#span_optplan_img_opt_loader').html('ذخیره شد');
                        setTimeout(function () {
                            $('#span_optplan_img_opt_loader').fadeOut('fast', function () {
                                $('#span_optplan_img_opt_loader').html('');
                                $('#span_optplan_img_opt_loader').show();
                            });
                        }, 3000);
                    }
                    else {
                        //$('#span_optplan_img_opt_loader').css('color','#bf1313');
                        //$('#span_optplan_img_opt_loader').html('خطا در سیستم دوباره سعی کنید');
                    }
                    $('#optplan_img_opt_loader').hide();
                }
            });
        }
    });

    

    $('body').on('click', '#opt_plan_btn_send_notes_tomorrow', function () {
        $('#span_optplan_img_noteloader').html('');
        var dt = $(this).attr('day');
        var txt = tinyMCE.get('optplan_txtarea_notes_tomorrow').getContent();//$('#optplan_txtarea_notes_tomorrow').val().trim();
        if (dt == "today") {
            $.ajax({
                url: 'apps/optplan/lib/ajaxhnd.php',
                type: 'POST',
                data: {'func': 'note_tomorrow', 'notes': txt},
                beforeSend: function () {
                    $('#optplan_img_noteloader_tomorrow').show();
                },
                success: function (data) {
                    if (data) {
                        $('#span_optplan_img_noteloader_tomorrow').css('color', '#4c0f5b');
                        $('#span_optplan_img_noteloader_tomorrow').html('ذخیره شد');
                        setTimeout(function () {
                            $('#span_optplan_img_noteloader_tomorrow').fadeOut('fast', function () {
                                $('#span_optplan_img_noteloader_tomorrow').html('');
                                $('#span_optplan_img_noteloader_tomorrow').show();
                            });
                        }, 3000);
                    }
                    else {
                        //$('#span_optplan_img_noteloader').css('color','#bf1313');
                        //$('#span_optplan_img_noteloader').html('خطا در سیستم دوباره سعی کنید');
                    }
                    $('#optplan_img_noteloader_tomorrow').hide();
                }
            });
        }
    });
    $('body').on('click', '#opt_plan_btn_sendimg_tomorrow', function () {
        var file_data = $('#opt_plan_map_file_tomorrow').prop('files')[0];
        var form_data = new FormData();
        form_data.append('fupload_tom_tag', file_data);
        form_data.append('func', 'fupload_tomorrow');
        $.ajax({
            url: 'apps/optplan/lib/ajaxhnd.php',
            cache: false,
            type: 'POST',
            processData: false, // Don't process the files
            contentType: false, // Set content type to false as jQuery will tell the server its a query string request
            data: form_data,
            beforeSend: function () {
                $('#optplan_img_fuploadloader_tomorrow').fadeIn('fast');
            },
            success: function (data) {
                $('#optplan_tomorrow_img_bib_tomorrow').attr('src', 'apps/optplan/img/' + data);
                $('#optplan_img_fuploadloader_tomorrow').fadeOut('fast');
            }
        });
    });
    $('body').on('click', '#opt_plan_btn_sendimg_today', function () {
        var file_data = $('#opt_plan_map_file').prop('files')[0];
        var form_data = new FormData();
        form_data.append('fupload', file_data);
        form_data.append('func', 'file_upload');
        $.ajax({
            url: 'apps/optplan/lib/ajaxhnd.php',
            cache: false,
            type: 'POST',
            processData: false, // Don't process the files
            contentType: false, // Set content type to false as jQuery will tell the server its a query string request
            data: form_data,
            beforeSend: function () {
                $('#optplan_img_fuploadloader').fadeIn('fast');
            },
            success: function (data) {
                $('#optplan_today_img_bib').attr('src', 'apps/optplan/img/' + data);
                $('#optplan_img_fuploadloader').fadeOut('fast');
            }
        });
    });
    $('body').on('click', '.optplan-tablinker', function () {
        $('.optplan-tablinker').removeClass('active');
        $('.optplan_tab_panel').hide();

        var thisobj = $(this);
        thisobj.addClass('active');
        var panel = thisobj.attr('relink');
        $('#' + panel).show();
    });
});