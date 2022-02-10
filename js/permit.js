$(function () {
    $('body').on('keypress', '#permit_txtbx_beyegani_pageindex', function (e) {
        if (e.which == 13) {
            var ee = $('#permit_txtbx_beyegani_pageindex').val().trim();
            if (ee <= 0 || ee == '') $('#permit_txtbx_beyegani_pageindex').val(1);
            get_bayegani_tbl($('#permit_txtbx_beyegani_pageindex').val());
        }
    });
	
	// vaghti makane gheire fanni entekhab shod , powercut unchecked shavad va baraks
	$('body').on('change','#permit_chkbx_no_critical_place',function(){				
		if (this.checked) {			
			$('#permit_chkbx_cut_power').attr('checked',false);			
		} 
	});
	
	$('body').on('change','#permit_chkbx_cut_power',function(){		
		// 
		if (this.checked) {	
			$('#permit_chkbx_no_critical_place').attr('checked',false);			
		} 
	});
	
	
	$('body').on('change','#permit_chkbx_non_critical_edit',function(){				
		if (this.checked) {			
			$('#permit_chkbx_cut_power_edit').attr('checked',false);			
			// $('#withsupervisor_panel').show();
		} 
		else
		{
			// $("#yessupervisor").prop("checked", true);
			// $('#withsupervisor_panel').hide();
		}
	});
	
	$('body').on('change','#permit_chkbx_cut_power_edit',function(){		
		// 
		if (this.checked) {	
			// $('#withsupervisor_panel').hide();
			// $("#yessupervisor").prop("checked", true);
			$('#permit_chkbx_non_critical_edit').attr('checked',false);			
		}		
	});
	
	
	$('body').on('change','#agreementrules',function(){		
		if (this.checked) {			
			$('#permit_btn_req_permit').prop('disabled', false);
		} else {
			$('#permit_btn_req_permit').prop('disabled', true);
		}
	});
	
	$('body').on('click', '.btn_delete_permit', function () {
		var pid = $(this).attr('data_permitid');
		$.ajax({
            url: 'apps/permit/ajax/peim_delete_permit.php?id=' + pid,
            type: 'GET',
            success: function (res) {
				// hide loading
				if(res == 'done')
				{
					// refresh today permits
					$.ajax({
						url: 'apps/permit/ajax/refreshtbl.php',
						type: 'GET',
						success: function (tblrows) {
							$('#permit_tbl_today_permits tr.data_row').remove();
							$('#permit_tbl_today_permits').append(tblrows);

							setTimeout(function () {
								$('#permit_tbl_refresh_ajax_laoder').css('visibility', 'hidden');
							}, 200);
						},
						beforeSend: function () {
							$('#permit_tbl_refresh_ajax_laoder').css("visibility", "visible");
						}
					});
				}
				else if(res == 'limit')
				{
					alert('Failed => Request sent after 11 AM');
				}
				else
				{
					alert('failed, try again');
				}
            },
            beforeSend: function () {
                // show loading
            }
        });		
	});

    $('body').on('click', '.permit_btn_nav', function () {
        var what = $(this).attr('navtxt');
        var targetpage = $('#permit_txtbx_beyegani_pageindex').val().trim();
        if (targetpage == '') targetpage = 0;
        switch (what) {
            case 'first':
                $('#permit_txtbx_beyegani_pageindex').val(1);
                break;
            case 'last':
                break;
            case 'pre':
                targetpage--;
                if (targetpage == -1 || targetpage == 0) targetpage = 1;
                $('#permit_txtbx_beyegani_pageindex').val(targetpage);
                break;
            case 'nxt':
                targetpage++;
                $('#permit_txtbx_beyegani_pageindex').val(targetpage);
                break;
        }
        get_bayegani_tbl($('#permit_txtbx_beyegani_pageindex').val());
    });

    $('body').on('click', '#btn_go', function () {
	
		var repo_id = $('#repostslist option:selected').val();
		
        $.ajax({
            url: 'apps/permit/ajax/report_haftegi.php',
            type: 'POST',
            dataType: 'json',
            data: { 'repo_id':repo_id , 'startdate': $('#txt_start_date').val(), 'enddate': $('#txt_end_date').val()},
            success: function (data) {
                $('#lbl_report_ajax_loader').html('');
				if(repo_id==1)
				{
					$('#tbl_report1').html('<tr style="background-color: #BCBCBC;color: black;font-family: Tahoma;text-align: center"><th style="width: 300px;">ایستگاه</th><th>بهره برداری</th><th>غیر بهره برداری</th></tr>');
					$.each(data, function (index, element) {
						$('#tbl_report1').append('<tr style="background-color: #f8f8f8;color: black;font-family: Tahoma;text-align: center"><td>' + element.stname + '</td><td>' + element.bahre + '</td><td>' + element.notbahre + '</td></tr>');
					});
				}
				else if (repo_id==2)
				{
					$('#tbl_report1').html('<tr style="background-color: #BCBCBC;color: black;font-family: Tahoma;text-align: center"><th style="width: 300px;">حوزه</th><th>بهره برداری</th><th>غیر بهره برداری</th></tr>');
					$.each(data, function (index, element) {
						$('#tbl_report1').append('<tr style="background-color: #f8f8f8;color: black;font-family: Tahoma;text-align: center"><td>' + element.scope_name + '</td><td>' + element.bahre + '</td><td>' + element.notbahre + '</td></tr>');
					});
				}
            },
            beforeSend: function () {
                $('#lbl_report_ajax_loader').html('please wait ...');
            }
        });
    });

    function get_bayegani_tbl(pindex) {
        $.ajax({
            url: 'apps/permit/ajax/refreshtbl_bayegani.php',
            type: 'POST',
            data: {'pindex': pindex},
            success: function (tblrows) {
                $('#permit_tbl_bayegani_permits tr.data_row').remove();
                $('#permit_tbl_bayegani_permits').append(tblrows);
                setTimeout(function () {
                    $('#permit_tbl_refresh_ajax_laoder_bayegani').css('visibility', 'hidden');
                }, 200);
            },
            beforeSend: function () {
                $('#permit_tbl_refresh_ajax_laoder_bayegani').css("visibility", "visible");
            }
        });
    }

    $('body').on('click', '#permit_btn_refresh_rows_bayegani', function () {
        var ee = $('#permit_txtbx_beyegani_pageindex').val().trim();
        if (ee <= 0 || ee == '') $('#permit_txtbx_beyegani_pageindex').val(1);
        get_bayegani_tbl($('#permit_txtbx_beyegani_pageindex').val());
    });

    $('body').on('click', '#permit_btn_refresh_rows_today_permitions', function () {
        var obj = $(this);
        $.ajax({
            url: 'apps/permit/ajax/refreshtbl_tpermitions.php',
            type: 'GET',
            success: function (tblrows) {
                $('#permit_tbl_today_permits_today_permitions tr.data_row').remove();
                $('#permit_tbl_today_permits_today_permitions').append(tblrows);
                setTimeout(function () {
                    $('#permit_tbl_refresh_ajax_laoder_tp').css('visibility', 'hidden');
                }, 200);
            },
            beforeSend: function () {
                $('#permit_tbl_refresh_ajax_laoder_tp').css("visibility", "visible");
            }
        });
    });


    $('body').on('click', '#permit_btn_refresh_rows', function () {
        var obj = $(this);
        $.ajax({
            url: 'apps/permit/ajax/refreshtbl.php',
            type: 'GET',
            success: function (tblrows) {
                $('#permit_tbl_today_permits tr.data_row').remove();
                $('#permit_tbl_today_permits').append(tblrows);
                setTimeout(function () {
                    $('#permit_tbl_refresh_ajax_laoder').css('visibility', 'hidden');
                }, 200);
				
				// ajax call for publish status , if roleid = signed-occ
            },
            beforeSend: function () {
                $('#permit_tbl_refresh_ajax_laoder').css("visibility", "visible");
            }
        });
    });


    $('body').on('click', '.permit_img_trpermit_req', function () {
        var intv1 = null;
        var obj = $(this);
        var permitid = $(this).attr('permitid');
        $.ajax({
            url: 'apps/permit/ajax/troccpanel.php',
            type: 'POST',
            data: {'permitid': permitid},
            success: function (msg) {
                $('#permit_body_panel').prepend(msg);
                //insert into panel then ...
                $('#permit_overlay_for_usermoreinfo').fadeIn('fast', function () {
                    $('.taeedradocc').show();
                });
                clearInterval(intv1);
            },
            beforeSend: function () {
                obj.fadeOut('700', function () {
                    obj.show();
                });
                intv1 = setInterval(function () {
                    obj.fadeOut('700', function () {
                        obj.show();
                    });
                }, 800);
            }
        });
    });
	
	$('body').on('click', '#permit_btn_publish_occ', function () {
		var status = $(this).attr('data-mode');
		if(status == 'unpublished')
		{
			var conf = confirm('اعلان کلی پرمیت ها به ناظران و پیمانکاران ؟');
			if (conf) {
				$.ajax({
                    url: 'apps/permit/ajax/taeedrad_publish_occ.php',
                    type: 'POST',
                    data: {'status': 'published'},
                    success: function (msg) {						
                        if (msg.trim() == 'updated') {
							$('#permit_btn_publish_occ').attr('data-mode','published');
							// background-color:green;
							$('#permit_btn_publish_occ').css("background-color","green");
							
							do_refresh();
						}
						else{
							// alert(msg);
						}
						setTimeout(function () {
							$('#permit_tbl_refresh_ajax_laoder').css('visibility', 'hidden');
						}, 200);
                    },
                    beforeSend: function () {
                        $('#permit_tbl_refresh_ajax_laoder').css("visibility", "visible");
                    }
                });
			}
		} 
		else if(status == 'published')
		{
			var conf = confirm('لغو اعلان کلی پرمیت ها ؟');
			if (conf) {
				$.ajax({
                    url: 'apps/permit/ajax/taeedrad_publish_occ.php',
                    type: 'POST',
                    data: {'status': 'unpublished'},
                    success: function (msg) {						
                        if (msg.trim() == 'updated') {
							$('#permit_btn_publish_occ').attr('data-mode','unpublished');
							$('#permit_btn_publish_occ').css("background-color","red");
							
							do_refresh();
						}
						else{
							// alert(msg);
						}
						setTimeout(function () {
							$('#permit_tbl_refresh_ajax_laoder').css('visibility', 'hidden');
						}, 200);
                    },
                    beforeSend: function () {
                        $('#permit_tbl_refresh_ajax_laoder').css("visibility", "visible");
                    }
                });
			}
		}
	});
	
	function do_refresh()
	{
		$.ajax({
			url: 'apps/permit/ajax/refreshtbl.php',
			type: 'GET',
			success: function (tblrows) {
				$('#permit_tbl_today_permits tr.data_row').remove();
				$('#permit_tbl_today_permits').append(tblrows);
				setTimeout(function () {
					$('#permit_tbl_refresh_ajax_laoder').css('visibility', 'hidden');
				}, 200);
			},
			beforeSend: function () {
				$('#permit_tbl_refresh_ajax_laoder').css("visibility", "visible");
			}
		});
	}
	
    $('body').on('click', '.permit_btn_trp_occ_req_permit', function () {
        var trp = $(this).attr('trp');
        var pi = $(this).attr('permitid');
        var txttip = $('#permit_txtarea_dalilradocc').val().trim();
        if (txttip != '') {
            var conf = confirm('ارسال شود ؟');
            if (conf) {
                $.ajax({
                    url: 'apps/permit/ajax/taeedrad_occ.php',
                    type: 'POST',
                    data: {'texttip': txttip, 'trp': trp, 'permitid': pi},
                    success: function (msg) {
                        if (msg.trim() == 'updated') {
                            $('#user_more_info_main').fadeOut('fast', function () {
                                $('#user_more_info_main').remove();
                                $('#permit_overlay_for_usermoreinfo').fadeOut('fast');
                            });
														
                            //$('#permit_tabs').tabs({ active: 0 });
                            $.ajax({
                                url: 'apps/permit/ajax/refreshtbl.php',
                                type: 'GET',
                                success: function (tblrows) {
                                    $('#permit_tbl_today_permits tr.data_row').remove();
                                    $('#permit_tbl_today_permits').append(tblrows);
                                    setTimeout(function () {
                                        $('#permit_tbl_refresh_ajax_laoder').css('visibility', 'hidden');
                                    }, 200);
                                },
                                beforeSend: function () {
                                    $('#permit_tbl_refresh_ajax_laoder').css("visibility", "visible");
                                }
                            });
							
							// make status unpublished
							$('#permit_btn_publish_occ').attr('data-mode','unpublished');
							$('#permit_btn_publish_occ').css("background-color","red");
                        }
                        else {
                            alert(msg);
                        }
                    },
                    beforeSend: function () {
                        //change the color of row
                    }
                });
            }
        }
        else {
            alert('لطفا شرح کاربر مرکز فرمان را وارد نمایید');
        }
    });
    $('body').on('click', '#permit_img_close_editpanel', function () {
        $('#permit_edit_fromnazer_req').remove();
    });
    $('body').on('click', '#permit_btn_dalil_rad_nazer', function () {
        var dalil_nazer = $('#permit_txtarea_dalilradnazer').val().trim()
        if (dalil_nazer != '') {
            var pid = $(this).attr('permitid');
            $.ajax({
                url: 'apps/permit/ajax/radnazer.php',
                type: 'POST',
                data: {'permitid': pid, 'whynazer': dalil_nazer},
                success: function (msg) {
                    if (msg.trim() == 'updated') {
                        alert('درخواست مجوز با موفقیت رد شد');
                        $('#permit_edit_fromnazer_req').fadeOut('fast').remove();
                        $('#permit_tabs').tabs({active: 0});
                        $.ajax({
                            url: 'apps/permit/ajax/refreshtbl.php',
                            type: 'GET',
                            success: function (tblrows) {
                                $('#permit_tbl_today_permits tr.data_row').remove();
                                $('#permit_tbl_today_permits').append(tblrows);

                                setTimeout(function () {
                                    $('#permit_tbl_refresh_ajax_laoder').css('visibility', 'hidden');
                                }, 200);
                            },
                            beforeSend: function () {
                                $('#permit_tbl_refresh_ajax_laoder').css("visibility", "visible");
                            }
                        });
                    }
                    else {
                        alert(msg);
                    }
                    $('#lbl_permit_btn_dalil_rad_nazer_ajaxgif').hide();
                    $('#permit_btn_dalil_rad_nazer_ajaxgif').hide();
                },
                beforeSend: function () {
                    //obj.attr('src','img/ajax-loader.gif');
                    $('#lbl_permit_btn_dalil_rad_nazer_ajaxgif').show();
                    $('#permit_btn_dalil_rad_nazer_ajaxgif').show();
                }
            });
        }
        else {
            alert('لطفا دلیل رد درخواست را مطرح کنید');
        }
    });
    $('body').on('click', '.permit_img_show_details_nazer_info', function () {
        var pid = $(this).attr('permit_id');
        var obj = $(this);

        $.ajax({
            url: 'apps/permit/ajax/editrequest.php',
            type: 'POST',
            data: {'permitid': pid},
            success: function (msg) {
                //alert(msg);
                $('#permit_body_panel').prepend(msg);
                obj.attr('src', 'img/edit2.png');
                obj.css('width', '24px');
                obj.css('height', '26px');
            },
            beforeSend: function () {
                obj.css('width', '16px');
                obj.css('height', '16px');
                obj.attr('src', 'img/ajax-loader.gif');
            }
        });
    });

    $('body').on('click', '.permit_img_show_details_noedit_info', function () {
        var pid = $(this).attr('permit_id');
        var win = window.open('apps/permit/lib/details.php?id=' + pid, '_blank');
        if (win) {
            //Browser has allowed it to be opened
            win.focus();
        } else {
            //Broswer has blocked it
            alert('Please allow popups for this site');
        }
    });

	var permit_btn_hint_by_nazer_vis = true;
    $('body').on('click', '#permit_btn_taeed_req_permit', function () {
        if (permit_btn_hint_by_nazer_vis) {
            $('#permit_hint_nazer').fadeIn('fast');
            permit_btn_hint_by_nazer_vis = false;
        }
        else {
            $('#permit_hint_nazer').fadeOut('fast');
            permit_btn_hint_by_nazer_vis = true;
        }
    });
	
	
    var permit_btn_rad_by_nazer_vis = true;
    $('body').on('click', '#permit_btn_rad_by_nazer', function () {
        if (permit_btn_rad_by_nazer_vis) {
            $('#permit_why_rad_nazer').fadeIn('fast');
            permit_btn_rad_by_nazer_vis = false;
        }
        else {
            $('#permit_why_rad_nazer').fadeOut('fast');
            permit_btn_rad_by_nazer_vis = true;
        }
    });
    $('body').on('click', '.show_printable_green', function () {
        var id = $(this).attr('permit_id');
        var win = window.open('apps/permit/lib/print_permit.php?id=' + id + '&r=' + Math.random(), '_blank');
        if (win) {
            //Browser has allowed it to be opened
            win.focus();
        } else {
            //Broswer has blocked it
            alert('Please allow popups for this site');
        }
    });


    function getplace_selections_edit() {
        var vals = '';
        var len = $('#permit_cmbx_working_place_div_edit input[type=checkbox]:checked').length;
        if (len > 0) {
            $('#permit_cmbx_working_place_div_edit input[type=checkbox]:checked').each(function () {
                vals += '-' + $(this).attr('value');
            });
            return vals.substring(1);
        }
        else {
            return 'nosel';
        }
    }

    $('body').on('click', '#permit_btn_dalil_hint_nazer', function () {
        var p_act_desc = $('#permit_txtbx_activity_desc_edit').val().trim();
        var p_power_chk = $('#permit_chkbx_cut_power_edit').is(":checked");
		
		var p_non_critical_chk = $('#permit_chkbx_non_critical_edit').is(":checked");
		var withsupervisor = $("#permit_chkbx_with_supervisor_edit").is(":checked");
		var supervisor_hint = $('#permit_txtarea_hint_nazer').val().trim();
		
        var p_keshik_tell = $('#permit_txtbx_keshik_tell_edit').val().trim();
        var p_listof_nazer = getselectednazer_id_edit();
        var p_listof_worker = getselectedworker_id_edit();
        var p_act_time = $('#permit_cmbx_activity_time_edit option:selected').val();
        var p_line_number_metro = $('#permit_cmbx_metroline_number_edit option:selected').val();
        var p_working_scope = $('#permit_cmbx_working_scope_edit option:selected').val();

        var p_working_place = getplace_selections_edit();//$('#permit_cmbx_working_place_edit option:selected').val();

        var p_permit_type_id = $('#permit_cmbx_permit_type_edit option:selected').val();
        var p_train_id = $('#permit_cmbx_train_list_edit option:selected').val();
        var p_vehicle_id = $('#permit_cmbx_helper_vehicle_list_edit option:selected').val();
        var p_opt_start_id = $('#permit_cmbx_opt_start_edit option:selected').val();
        //alert(p_opt_start_id);
        var p_opt_end_id = $('#permit_cmbx_opt_end_edit option:selected').val();
        var p_opt_desc = $('#permit_txtarea_opt_desc_edit').val().trim();

        var formok = true;
        if (p_permit_type_id != 0) {
            $('#permit_cmbx_permit_type_edit').removeClass('fieldrequire');
            if (p_permit_type_id == 2 || p_permit_type_id == 3 || p_permit_type_id == 4) {
                if ($('#permit_txtarea_opt_desc_edit').val().trim() == '') {
                    $('#permit_txtarea_opt_desc_edit').addClass('fieldrequire');
                    formok = false;
                }
                else {
                    $('#permit_txtarea_opt_desc_edit').removeClass('fieldrequire');
                    formok = true;
                }
            }
            if (p_permit_type_id == 1) {
                $('#permit_cmbx_opt_start_edit,#permit_txtarea_opt_desc_edit,#permit_cmbx_opt_end_edit,#permit_cmbx_helper_vehicle_list_edit,#permit_cmbx_train_list_edit').removeClass('fieldrequire');
            }
            if (p_permit_type_id == 2) {
                $('#permit_cmbx_train_list_edit').removeClass('fieldrequire');
                if ($('#permit_cmbx_helper_vehicle_list_edit option:selected').val() == 0) {
                    $('#permit_cmbx_helper_vehicle_list_edit').addClass('fieldrequire');
                    formok = false;
                }
                else {
                    $('#permit_cmbx_helper_vehicle_list_edit').removeClass('fieldrequire');
                }
                if ($('#permit_cmbx_opt_start_edit option:selected').val() == 0) {
                    $('#permit_cmbx_opt_start_edit').addClass('fieldrequire');
                    formok = false;
                }
                else {
                    $('#permit_cmbx_opt_start_edit').removeClass('fieldrequire');
                }
                if ($('#permit_cmbx_opt_end_edit option:selected').val() == 0) {
                    $('#permit_cmbx_opt_end_edit').addClass('fieldrequire');
                    formok = false;
                }
                else {
                    $('#permit_cmbx_opt_end_edit').removeClass('fieldrequire');
                }
            }
            if (p_permit_type_id == 3) {
                $('#permit_cmbx_helper_vehicle_list_edit').removeClass('fieldrequire');
                if ($('#permit_cmbx_train_list_edit option:selected').val() == 0) {
                    $('#permit_cmbx_train_list_edit').addClass('fieldrequire');
                    formok = false;
                }
                else {
                    $('#permit_cmbx_train_list_edit').removeClass('fieldrequire');
                }
                if ($('#permit_cmbx_opt_start_edit option:selected').val() == 0) {
                    $('#permit_cmbx_opt_start_edit').addClass('fieldrequire');
                    formok = false;
                }
                else {
                    $('#permit_cmbx_opt_start_edit').removeClass('fieldrequire');
                }
                if ($('#permit_cmbx_opt_end_edit option:selected').val() == 0) {
                    $('#permit_cmbx_opt_end_edit').addClass('fieldrequire');
                    formok = false;
                }
                else {
                    $('#permit_cmbx_opt_end_edit').removeClass('fieldrequire');
                }
            }
            if (p_permit_type_id == 4) {
                if ($('#permit_cmbx_train_list_edit option:selected').val() == 0) {
                    $('#permit_cmbx_train_list_edit').addClass('fieldrequire');
                    formok = false;
                }
                else {
                    $('#permit_cmbx_train_list_edit').removeClass('fieldrequire');
                }
                if ($('#permit_cmbx_helper_vehicle_list_edit option:selected').val() == 0) {
                    $('#permit_cmbx_helper_vehicle_list_edit').addClass('fieldrequire');
                    formok = false;
                }
                else {
                    $('#permit_cmbx_helper_vehicle_list_edit').removeClass('fieldrequire');
                }
                if ($('#permit_cmbx_opt_start_edit option:selected').val() == 0) {
                    $('#permit_cmbx_opt_start_edit').addClass('fieldrequire');
                    formok = false;
                }
                else {
                    $('#permit_cmbx_opt_start_edit').removeClass('fieldrequire');
                }
                if ($('#permit_cmbx_opt_end_edit option:selected').val() == 0) {
                    $('#permit_cmbx_opt_end_edit').addClass('fieldrequire');
                    formok = false;
                }
                else {
                    $('#permit_cmbx_opt_end_edit').removeClass('fieldrequire');
                }
            }
        }
        else {
            $('#permit_cmbx_permit_type_edit').addClass('fieldrequire');
            $('#permit_txtarea_opt_desc_edit,#permit_cmbx_opt_start_edit,#permit_cmbx_opt_end_edit,#permit_cmbx_helper_vehicle_list_edit,#permit_cmbx_train_list_edit').removeClass('fieldrequire');
            formok = false;
        }

        /*if (p_working_place == 0) {
         $('#permit_cmbx_working_place_edit').addClass('fieldrequire');
         formok = false;
         }
         else {
         $('#permit_cmbx_working_place_edit').removeClass('fieldrequire');
         }*/

        if (p_working_scope == 0) {
            $('#permit_cmbx_working_scope_edit').addClass('fieldrequire');
            formok = false;
        }
        else {
            $('#permit_cmbx_working_scope_edit').removeClass('fieldrequire');
        }

        if (p_line_number_metro == 0) {
            $('#permit_cmbx_metroline_number_edit').addClass('fieldrequire');
            formok = false;
        }
        else {
            $('#permit_cmbx_metroline_number_edit').removeClass('fieldrequire');
        }

        if (p_act_time == 0) {
            $('#permit_cmbx_activity_time_edit').addClass('fieldrequire');
            formok = false;
        }
        else {
            $('#permit_cmbx_activity_time_edit').removeClass('fieldrequire');
        }

        if (p_act_desc == '' || p_act_desc == null) {
            $('#permit_txtbx_activity_desc_edit').addClass('fieldrequire');
            formok = false;
        }
        else {
            $('#permit_txtbx_activity_desc_edit').removeClass('fieldrequire');
        }

        if (p_keshik_tell == '' || p_keshik_tell == null) {
            $('#permit_txtbx_keshik_tell_edit').addClass('fieldrequire');
            formok = false;
        }
        else {
            $('#permit_txtbx_keshik_tell_edit').removeClass('fieldrequire');
        }
        if (formok) {
            var chk = confirm("ارسال شود ؟");
            if (chk) {
                var ppermitid = $('#permit_btn_taeed_req_permit').attr('permitid');
                var p_act_desc = $('#permit_txtbx_activity_desc_edit').val().trim();
                var p_power_chk = $('#permit_chkbx_cut_power_edit').is(":checked");
				
				var p_non_critical_chk = $('#permit_chkbx_non_critical_edit').is(":checked");
				var p_with_supervisor = $("#permit_chkbx_with_supervisor_edit").is(":checked");
                var supervisor_hint = $('#permit_txtarea_hint_nazer').val().trim();
				
				var p_keshik_tell = $('#permit_txtbx_keshik_tell_edit').val().trim();
                var p_listof_nazer = getselectednazer_id_edit();
                var p_listof_worker = getselectedworker_id_edit();
                var p_act_time = $('#permit_cmbx_activity_time_edit option:selected').val();
                var p_line_number_metro = $('#permit_cmbx_metroline_number_edit option:selected').val();
                var p_working_scope = $('#permit_cmbx_working_scope_edit option:selected').val();
                var p_working_place = getplace_selections_edit();// $('#permit_cmbx_working_place_edit option:selected').val();
                var p_permit_type_id = $('#permit_cmbx_permit_type_edit option:selected').val();
                var p_train_id = $('#permit_cmbx_train_list_edit option:selected').val();
                var p_vehicle_id = $('#permit_cmbx_helper_vehicle_list_edit option:selected').val();
                var p_opt_start_id = $('#permit_cmbx_opt_start_edit option:selected').val();
                var p_opt_end_id = $('#permit_cmbx_opt_end_edit option:selected').val();
                var p_opt_desc = $('#permit_txtarea_opt_desc_edit').val().trim();

                var arrlist = {
                    permitid: ppermitid,
                    permit_desc: p_act_desc,
                    power_cut: p_power_chk,
					
					non_critical: p_non_critical_chk,
					with_supervisor:p_with_supervisor,
                    supervisor_hint:supervisor_hint,
					
					keshik_tell: p_keshik_tell,
                    listof_nazer: p_listof_nazer,
                    listof_worker: p_listof_worker,
                    act_time: p_act_time,
                    line_number_metro: p_line_number_metro,
                    working_scope: p_working_scope,
                    working_place: p_working_place,
                    permit_type_id: p_permit_type_id,
                    train_id: p_train_id,
                    vehicle_id: p_vehicle_id,
                    opt_start_id: p_opt_start_id,
                    opt_end_id: p_opt_end_id,
                    opt_desc: p_opt_desc
                };
                $.ajax({
                    url: 'apps/permit/ajax/updatenewreq.php',
                    type: 'POST',
                    data: {'dataforms': arrlist},
                    success: function (msg) {
					
						// 0: success
						// 1: zamane taeed mojavez be payan reside hast
						// 2: haghe taeed ya rad permit ro nadarid
						// 3: sql error
						
						if(msg == '1')
						{
							alert('زمان تایید مجوز به پایان رسیده است');
						}
						else if(msg == '2')
						{
							alert('حق تایید و یا رد مجوز ندارید');
						}
						else if (msg == '3') {
							alert('sql lol ...');
						}
						else if (msg == '0') {
                            alert('درخواست مجوز شما با موفقیت تایید شد');
                            $('#permit_edit_fromnazer_req').fadeOut('fast').remove();
                            $('#permit_tabs').tabs({active: 0});
                            $.ajax({
                                url: 'apps/permit/ajax/refreshtbl.php',
                                type: 'GET',
                                success: function (tblrows) {
                                    $('#permit_tbl_today_permits tr.data_row').remove();
                                    $('#permit_tbl_today_permits').append(tblrows);
                                    setTimeout(function () {
                                        $('#permit_tbl_refresh_ajax_laoder').css('visibility', 'hidden');
                                    }, 200);
                                },
                                beforeSend: function () {
                                    $('#permit_tbl_refresh_ajax_laoder').css("visibility", "visible");
                                }
                            });
                        }
                        else {
							alert('خطا در ارسال درخواست مجوز');
						}
                        $('#lbl_permit_btn_taeed_req_permit_ajaxgif').hide();
                        $('#permit_btn_taeed_req_permit_ajaxgif').hide();
                    },
                    beforeSend: function () {
                        $('#lbl_permit_btn_taeed_req_permit_ajaxgif').show();
                        $('#permit_btn_taeed_req_permit_ajaxgif').show();
                    }
                });
            }
        }
        else {
            alert('لطفا اطلاعات فرم را کامل وارد کنید');
        }
    });

    function getplace_selections() {
        var vals = '';
        var len = $('#permit_cmbx_working_place_div input[type=checkbox]:checked').length;
        if (len > 0) {
            $('#permit_cmbx_working_place_div input[type=checkbox]:checked').each(function () {
                vals += '-' + $(this).attr('value');
            });
            return vals.substring(1);
        }
        else {
            return 'nosel';
        }
    }
	
	function get_safty_hints_selections() {
        var vals = '';
        var len = $('#permit_cmbx_safty_hints_div input[type=checkbox]:checked').length;
        if (len > 0) {
            $('#permit_cmbx_safty_hints_div input[type=checkbox]:checked').each(function () {
                vals += '-' + $(this).attr('value');
            });
            return vals.substring(1);
        }
        else {
            return 'nosel';
        }
    }
	
    $('body').on('click', '#permit_btn_req_permit', function () {
        var p_act_desc = $('#permit_txtbx_activity_desc').val().trim();
		
		var p_safty_hints = get_safty_hints_selections();
		
        var p_power_chk = $('#permit_chkbx_cut_power').is(":checked");
		var p_non_critical = $('#permit_chkbx_no_critical_place').is(":checked");
        var p_nezarat_unit_id = $('#permit_cmbx_unit_nezarat option:selected').val();
        var p_keshik_tell = $('#permit_txtbx_keshik_tell').val().trim();
        var p_listof_nazer = getselectednazer_id();
        var p_peimankar_id = $('#permit_cmbx_peimankar_of_unitnezarat option:selected').val();
		
		// 
		var first_peimankar_supervisor  =  $('#permit_cmbx_supervisor_peimankar option:selected').val();
		var second_peimankar_supervisor =  $('#permit_cmbx_supervisor_peimankar2 option:selected').val();
		
        var p_listof_worker = getselectedworker_id();
        var p_act_time = $('#permit_cmbx_activity_time option:selected').val();
        var p_line_number_metro = $('#permit_cmbx_metroline_number option:selected').val();
        var p_working_scope = $('#permit_cmbx_working_scope option:selected').val();

        var p_working_place = getplace_selections();//$('#permit_cmbx_working_place option:selected').val();

        var p_permit_type_id = $('#permit_cmbx_permit_type option:selected').val();
        var p_train_id = $('#permit_cmbx_train_list option:selected').val();
        var p_vehicle_id = $('#permit_cmbx_helper_vehicle_list option:selected').val();
        var p_opt_start_id = $('#permit_cmbx_opt_start option:selected').val();
        var p_opt_end_id = $('#permit_cmbx_opt_end option:selected').val();
        var p_opt_desc = $('#permit_txtarea_opt_desc').val().trim();

        var formok = true;
        if (p_permit_type_id != 0) {
            $('#permit_cmbx_permit_type').removeClass('fieldrequire');
            if (p_permit_type_id == 2 || p_permit_type_id == 3 || p_permit_type_id == 4) {
                if ($('#permit_txtarea_opt_desc').val().trim() == '') {
                    $('#permit_txtarea_opt_desc').addClass('fieldrequire');
                    formok = false;
                }
                else {
                    $('#permit_txtarea_opt_desc').removeClass('fieldrequire');
                    formok = true;
                }
            }
            if (p_permit_type_id == 1) {
                $('#permit_cmbx_opt_start,#permit_txtarea_opt_desc,#permit_cmbx_opt_end,#permit_cmbx_helper_vehicle_list,#permit_cmbx_train_list').removeClass('fieldrequire');
            }
            if (p_permit_type_id == 2) {
                $('#permit_cmbx_train_list').removeClass('fieldrequire');
                if ($('#permit_cmbx_helper_vehicle_list option:selected').val() == 0) {
                    $('#permit_cmbx_helper_vehicle_list').addClass('fieldrequire');
                    formok = false;
                }
                else {
                    $('#permit_cmbx_helper_vehicle_list').removeClass('fieldrequire');
                }
                if ($('#permit_cmbx_opt_start option:selected').val() == 0) {
                    $('#permit_cmbx_opt_start').addClass('fieldrequire');
                    formok = false;
                }
                else {
                    $('#permit_cmbx_opt_start').removeClass('fieldrequire');
                }
                if ($('#permit_cmbx_opt_end option:selected').val() == 0) {
                    $('#permit_cmbx_opt_end').addClass('fieldrequire');
                    formok = false;
                }
                else {
                    $('#permit_cmbx_opt_end').removeClass('fieldrequire');
                }
            }
            if (p_permit_type_id == 3) {
                $('#permit_cmbx_helper_vehicle_list').removeClass('fieldrequire');
                if ($('#permit_cmbx_train_list option:selected').val() == 0) {
                    $('#permit_cmbx_train_list').addClass('fieldrequire');
                    formok = false;
                }
                else {
                    $('#permit_cmbx_train_list').removeClass('fieldrequire');
                }
                if ($('#permit_cmbx_opt_start option:selected').val() == 0) {
                    $('#permit_cmbx_opt_start').addClass('fieldrequire');
                    formok = false;
                }
                else {
                    $('#permit_cmbx_opt_start').removeClass('fieldrequire');
                }
                if ($('#permit_cmbx_opt_end option:selected').val() == 0) {
                    $('#permit_cmbx_opt_end').addClass('fieldrequire');
                    formok = false;
                }
                else {
                    $('#permit_cmbx_opt_end').removeClass('fieldrequire');
                }
            }
            if (p_permit_type_id == 4) {
                if ($('#permit_cmbx_train_list option:selected').val() == 0) {
                    $('#permit_cmbx_train_list').addClass('fieldrequire');
                    formok = false;
                }
                else {
                    $('#permit_cmbx_train_list').removeClass('fieldrequire');
                }
                if ($('#permit_cmbx_helper_vehicle_list option:selected').val() == 0) {
                    $('#permit_cmbx_helper_vehicle_list').addClass('fieldrequire');
                    formok = false;
                }
                else {
                    $('#permit_cmbx_helper_vehicle_list').removeClass('fieldrequire');
                }
                if ($('#permit_cmbx_opt_start option:selected').val() == 0) {
                    $('#permit_cmbx_opt_start').addClass('fieldrequire');
                    formok = false;
                }
                else {
                    $('#permit_cmbx_opt_start').removeClass('fieldrequire');
                }
                if ($('#permit_cmbx_opt_end option:selected').val() == 0) {
                    $('#permit_cmbx_opt_end').addClass('fieldrequire');
                    formok = false;
                }
                else {
                    $('#permit_cmbx_opt_end').removeClass('fieldrequire');
                }
            }
        }
        else {
            $('#permit_cmbx_permit_type').addClass('fieldrequire');
            $('#permit_txtarea_opt_desc,#permit_cmbx_opt_start,#permit_cmbx_opt_end,#permit_cmbx_helper_vehicle_list,#permit_cmbx_train_list').removeClass('fieldrequire');
            formok = false;
        }

        /*if (p_working_place == 0) {
         $('#permit_cmbx_working_place').addClass('fieldrequire');
         formok = false;
         }
         else {
         $('#permit_cmbx_working_place').removeClass('fieldrequire');
         }*/

        if (p_working_scope == 0) {
            $('#permit_cmbx_working_scope').addClass('fieldrequire');
            formok = false;
        }
        else {
            $('#permit_cmbx_working_scope').removeClass('fieldrequire');
        }

        if (p_line_number_metro == 0) {
            $('#permit_cmbx_metroline_number').addClass('fieldrequire');
            formok = false;
        }
        else {
            $('#permit_cmbx_metroline_number').removeClass('fieldrequire');
        }

        if (p_act_time == 0) {
            $('#permit_cmbx_activity_time').addClass('fieldrequire');
            formok = false;
        }
        else {
            $('#permit_cmbx_activity_time').removeClass('fieldrequire');
        }
        if (p_peimankar_id == 0) {
            $('#permit_cmbx_peimankar_of_unitnezarat').addClass('fieldrequire');
            formok = false;
        }
        else {
            $('#permit_cmbx_peimankar_of_unitnezarat').removeClass('fieldrequire');
        }

        if (p_nezarat_unit_id == 0) {
            $('#permit_cmbx_unit_nezarat').addClass('fieldrequire');
            formok = false;
        }
        else {
            $('#permit_cmbx_unit_nezarat').removeClass('fieldrequire');
        }
        if (p_act_desc == '' || p_act_desc == null) {
            $('#permit_txtbx_activity_desc').addClass('fieldrequire');
            formok = false;
        }
        else {
            $('#permit_txtbx_activity_desc').removeClass('fieldrequire');
        }
        if (p_keshik_tell == '' || p_keshik_tell == null) {
            $('#permit_txtbx_keshik_tell').addClass('fieldrequire');
            formok = false;
        }
        else {
            $('#permit_txtbx_keshik_tell').removeClass('fieldrequire');
        }
        //alert(p_act_desc + ' | ' + p_power_chk);
        if (formok)//if form fill completly
        {
            getReqPermitFormValues();
            $('#permit_confirm_req').animate(
                {width: '100%'},
                "fast",
                function () {
                    $("#permit_confirm_req_content").fadeIn();
                });
            $('#permit_formalarm').hide();
        }
        else {
            $('#permit_formalarm').fadeIn('fast');
        }
    });
    $('body').on('click', '#permit_btn_confirm_go', function () {
        sendReqDataForm();
    });
    function sendReqDataForm() {
        var p_act_desc = $('#permit_txtbx_activity_desc').val().trim();
		
		var p_safty_hints = get_safty_hints_selections();
		
        var p_power_chk = $('#permit_chkbx_cut_power').is(":checked");
		var p_non_critical_chk = $('#permit_chkbx_no_critical_place').is(":checked");
        var p_nezarat_unit_id = $('#permit_cmbx_unit_nezarat option:selected').val();
        var p_keshik_tell = $('#permit_txtbx_keshik_tell').val().trim();
        var p_listof_nazer = getselectednazer_id();
        var p_peimankar_id = $('#permit_cmbx_peimankar_of_unitnezarat option:selected').val();
        
		var p_first_peimankar_supervisor  =  $('#permit_cmbx_supervisor_peimankar option:selected').val();
		var p_second_peimankar_supervisor =  $('#permit_cmbx_supervisor_peimankar2 option:selected').val();
		
		var p_listof_worker = getselectedworker_id();
        var p_act_time = $('#permit_cmbx_activity_time option:selected').val();
        var p_line_number_metro = $('#permit_cmbx_metroline_number option:selected').val();
        var p_working_scope = $('#permit_cmbx_working_scope option:selected').val();

        var p_working_place = getplace_selections();// $('#permit_cmbx_working_place option:selected').val();

        var p_permit_type_id = $('#permit_cmbx_permit_type option:selected').val();
        var p_train_id = $('#permit_cmbx_train_list option:selected').val();
        var p_vehicle_id = $('#permit_cmbx_helper_vehicle_list option:selected').val();
        var p_opt_start_id = $('#permit_cmbx_opt_start option:selected').val();
        var p_opt_end_id = $('#permit_cmbx_opt_end option:selected').val();
        var p_opt_desc = $('#permit_txtarea_opt_desc').val().trim();

        var arrlist = {
            permit_desc: p_act_desc,
			
			safty_hints:p_safty_hints,
			
            power_cut: p_power_chk,
			non_critical:p_non_critical_chk,
            nezarat_unit_id: p_nezarat_unit_id,
            keshik_tell: p_keshik_tell,
            listof_nazer: p_listof_nazer,
            peimankar_id: p_peimankar_id,
			
			first_peim_supervisor:p_first_peimankar_supervisor,
			second_peim_supervisor:p_second_peimankar_supervisor,
            listof_worker: p_listof_worker,
            
			act_time: p_act_time,
            line_number_metro: p_line_number_metro,
            working_scope: p_working_scope,
            working_place: p_working_place,
            permit_type_id: p_permit_type_id,
            train_id: p_train_id,
            vehicle_id: p_vehicle_id,
            opt_start_id: p_opt_start_id,
            opt_end_id: p_opt_end_id,
            opt_desc: p_opt_desc
        };

		
        $.ajax({
            url: 'apps/permit/ajax/insertnewreq.php',
            type: 'POST',
            data: {'dataforms': arrlist},
            success: function (msg) {				
				// 0: success
				// 1: peimankar haghe darkhaste mojavez nadarad
				// 2: zamane gereftan mojavez be payan reside hast
				// 3: sql error
				
				if(msg == '1')
				{
					alert('حق ثبت و درخواست مجوز را ندارید');
				}
				else if(msg == '2')
				{
					alert('زمان درخواست مجوز به پایان رسیده است');
				}
				else if (msg == '3') {
                    alert('sql lol ...');
                }
                else if (msg == '0') {
                    alert('درخواست مجوز شما با موفقیت ارسال شد');
                    $("#permit_confirm_req_content").fadeOut('fast', function () {
                        $('#permit_confirm_req').animate({width: '0px'}, "fast", function () {
                            //$("#permit_tabs").tabs("option", "selected", 0);
                            $('#permit_tabs').tabs({active: 0});
                            var btnobj = $('#permit_btn_refresh_rows');
                            $.ajax({
                                url: 'apps/permit/ajax/refreshtbl.php',
                                type: 'GET',
                                success: function (tblrows) {
                                    $('#permit_tbl_today_permits tr.data_row').remove();
                                    $('#permit_tbl_today_permits').append(tblrows);

                                    setTimeout(function () {
                                        $('#permit_tbl_refresh_ajax_laoder').css('visibility', 'hidden');
                                    }, 200);
                                },
                                beforeSend: function () {
                                    $('#permit_tbl_refresh_ajax_laoder').css("visibility", "visible");
                                }
                            });
                        });
                    });
                }
                else {
                    alert('خطا در ارسال درخواست مجوز');
                }
                $('#permit_btn_confirm_go_ajaxgif').hide();
                $('#lbl_permit_btn_confirm_go_ajaxgif').hide();
            },
            beforeSend: function () {
                $('#permit_btn_confirm_go_ajaxgif').show();
                $('#lbl_permit_btn_confirm_go_ajaxgif').show();
            }
        });
    }

    function get_nazer_infoconfirm() {
        var liststr = '';
        $('#permit_tbl_nazer_of_nezarat tr').each(function () {
            if ($(this).hasClass('selected')) {
                liststr +=
                    $(this).find(':nth-child(2)').text() + ' ' + $(this).find(':nth-child(1)').text() + ' ( کدملی : ' +
                    $(this).find(':nth-child(3)').text() + ' , شماره موبایل : ' +
                    $(this).find(':nth-child(4)').text() + ' ) ' + '<br/>';
            }
        });
        $('#permit_summary_nazer_list').html(liststr);
    }

    function get_worker_infoconfirm() {
        var liststr = '';
        $('#permit_tbl_listof_worker1 tr').each(function () {
            if ($(this).hasClass('selected')) {
                liststr +=
                    $(this).find(':nth-child(2)').text() + ' ' + $(this).find(':nth-child(1)').text() + ' ( کدملی : ' +
                    $(this).find(':nth-child(3)').text() + ' ) ' + '<br/>';
            }
        });
        $('#permit_tbl_listof_worker2 tr').each(function () {
            if ($(this).hasClass('selected')) {
                liststr +=
                    $(this).find(':nth-child(2)').text() + ' ' + $(this).find(':nth-child(1)').text() + ' ( کدملی : ' +
                    $(this).find(':nth-child(3)').text() + ' ) ' + '<br/>';
            }
        });
        $('#permit_summary_listof_worker').html(liststr);
    }

    function getReqPermitFormValues() {
        get_nazer_infoconfirm();
        get_worker_infoconfirm();
        $('#permit_summary_descpermit').html($('#permit_txtbx_activity_desc').val().trim());
        var p_power_chk = $('#permit_chkbx_cut_power').is(":checked");
        if (p_power_chk) {
            $('#permit_summary_powercut').html('بلی');
        }
        else {
            $('#permit_summary_powercut').html('خیر');
        }
		
		var p_non_critical_chk = $('#permit_chkbx_no_critical_place').is(":checked");
        if (p_non_critical_chk) {
            $('#permit_summary_non_critical').html('بلی');
        }
        else {
            $('#permit_summary_non_critical').html('خیر');
        }
		
        $('#permit_summary_nezaratunit').html($('#permit_cmbx_unit_nezarat option:selected').text());
        $('#permit_summary_print_tel').html('تلفن کشیک : ' + $('#permit_txtbx_keshik_tell').val().trim());

        var p_listof_nazer = getselectednazer_id();
        var p_listof_worker = getselectedworker_id();

        $('#permit_summary_peimankar_name').html($('#permit_cmbx_peimankar_of_unitnezarat option:selected').text());
        $('#permit_summary_activityname').html($('#permit_cmbx_activity_time option:selected').text());

        $('#permit_summary_linenum').html($('#permit_cmbx_metroline_number option:selected').text());

        $('#permit_summary_wscope').html($('#permit_cmbx_working_scope option:selected').text());
        $('#permit_summary_placekar').html($('#permit_cmbx_working_place option:selected').text());

        $('#permit_summary_permittype').html($('#permit_cmbx_permit_type option:selected').text());

        $('#permit_summary_trainnum').html($('#permit_cmbx_train_list option:selected').text());
        $('#permit_summary_vhelper').html($('#permit_cmbx_helper_vehicle_list option:selected').text());

        $('#permit_summary_stplace').html($('#permit_cmbx_opt_start option:selected').text());
        $('#permit_summary_enplace').html($('#permit_cmbx_opt_end option:selected').text());

        $('#permit_summary_manover').html($('#permit_txtarea_opt_desc').val().trim());
    }

    $('body').on('click', '#permit_btn_correct_req', function () {
        $("#permit_confirm_req_content").fadeOut('fast', function () {
            $('#permit_confirm_req').animate({width: '0px'}, "fast");
        });
    });

    $('body').on('click', '#permit_tbl_listof_worker1_edit tr,#permit_tbl_listof_worker2_edit tr', function () {
        if (!$(this).hasClass('permit_tbl_listof_worker_header_edit')) {
            if ($(this).hasClass('selected')) {
                $(this).removeClass('selected');
            }
            else {
                $(this).addClass('selected');
            }
            var selectednum = get_numof_selectedworker();
            $('#permit_selected_num_of_worker').html(selectednum + ' ' + 'انتخاب');
        }
    });

    $('body').on('click', '#permit_tbl_listof_worker1 tr,#permit_tbl_listof_worker2 tr', function () {
        if (!$(this).hasClass('permit_tbl_listof_worker_header')) {
            if ($(this).hasClass('selected')) {
                $(this).removeClass('selected');
            }
            else {
                $(this).addClass('selected');
            }
            var selectednum = get_numof_selectedworker();
            $('#permit_selected_num_of_worker').html(selectednum + ' ' + 'انتخاب');
        }
    });

    function getselectednazer_id_edit() {
        var permit_arr_list_of_nazer = '';
        $('#permit_tbl_nazer_of_nezarat_edit tr').each(function () {
            if ($(this).hasClass('selected')) {
                permit_arr_list_of_nazer += ',' + $(this).attr('nazer_id');
            }
        });
        return permit_arr_list_of_nazer.substr(1);
    }

    function getselectedworker_id_edit() {
        var permit_arr_list_of_worker = '';
        $('#permit_tbl_listof_worker1_edit tr').each(function () {
            if ($(this).hasClass('selected')) {
                permit_arr_list_of_worker += ',' + $(this).attr('peimankar_worker_id');
            }
        });
        $('#permit_tbl_listof_worker2_edit tr').each(function () {
            if ($(this).hasClass('selected')) {
                permit_arr_list_of_worker += ',' + $(this).attr('peimankar_worker_id');
            }
        });
        return permit_arr_list_of_worker.substr(1);
    }

    function getselectednazer_id() {
        var permit_arr_list_of_nazer = '';
        $('#permit_tbl_nazer_of_nezarat tr').each(function () {
            if ($(this).hasClass('selected')) {
                permit_arr_list_of_nazer += ',' + $(this).attr('nazer_id');
            }
        });
        return permit_arr_list_of_nazer.substr(1);
    }

    function getselectedworker_id() {
        var permit_arr_list_of_worker = '';
        $('#permit_tbl_listof_worker1 tr').each(function () {
            if ($(this).hasClass('selected')) {
                permit_arr_list_of_worker += ',' + $(this).attr('peimankar_worker_id');
            }
        });
        $('#permit_tbl_listof_worker2 tr').each(function () {
            if ($(this).hasClass('selected')) {
                permit_arr_list_of_worker += ',' + $(this).attr('peimankar_worker_id');
            }
        });
        return permit_arr_list_of_worker.substr(1);
    }

    function get_numof_selectedworker() {
        var count = 0;
        $('#permit_tbl_listof_worker1 tr').each(function () {
            if ($(this).hasClass('selected')) {
                count++;
            }
        });
        $('#permit_tbl_listof_worker2 tr').each(function () {
            if ($(this).hasClass('selected')) {
                count++;
            }
        });
        return count;
    }


    function get_numof_selectednazer() {
        var count = 0;
        $('#permit_tbl_nazer_of_nezarat tr').each(function () {
            if ($(this).hasClass('selected')) {
                count++;
            }
        });
        return count;
    }

    $('body').on('click', '#permit_tbl_nazer_of_nezarat_edit tr', function () {
        if (!$(this).hasClass('permit_tbl_nazer_of_nezarat_header_edit')) {
            if ($(this).hasClass('selected')) {
                $(this).removeClass('selected');
            }
            else {
                $(this).addClass('selected');
            }
            var selectednum = get_numof_selectednazer();
            $('#permit_selected_num').html(selectednum + ' ' + 'انتخاب');
        }
    });

    $('body').on('click', '#permit_tbl_nazer_of_nezarat tr', function () {
        if (!$(this).hasClass('permit_tbl_nazer_of_nezarat_header')) {
            if ($(this).hasClass('selected')) {
                $(this).removeClass('selected');
            }
            else {
                $(this).addClass('selected');
            }
            var selectednum = get_numof_selectednazer();
            $('#permit_selected_num').html(selectednum + ' ' + 'انتخاب');
        }
    });

    var occlastcolor = '';
    $('body').on('click', '.permit_img_show_occ_cmnt', function () {
        var vh = $(this).attr('vhid');
        if (vh == 'true') {
            occlastcolor = $(this).parent().css('background-color');
            var wtd = $(this).parent().width();
            $(this).parent().css('background-color', '#a80b0b');
            $(this).parent().css('color', 'white');
            $(this).parent().find('.permit_occ_hidden').css('right', wtd + 8);
            $(this).parent().find('.permit_occ_hidden').fadeIn('fast');
            $(this).attr('vhid', 'false');
        }
        else {
            $(this).parent().css('background-color', occlastcolor);
            $(this).parent().css('color', 'black');
            $(this).parent().find('.permit_occ_hidden').fadeOut('fast');
            $(this).attr('vhid', 'true');
        }
    });

    var nazerlastcolor = '';
    $('body').on('click', '.permit_img_show_nazer_cmnt', function () {
        var vh = $(this).attr('vhid');
        if (vh == 'true') {
            nazerlastcolor = $(this).parent().css('background-color');
            var wtd = $(this).parent().width();
            $(this).parent().css('background-color', '#64644c');
            $(this).parent().css('color', 'white');
            $(this).parent().find('.permit_nazer_hidden').css('left', wtd + 8);
            $(this).parent().find('.permit_nazer_hidden').fadeIn('fast');
            $(this).attr('vhid', 'false');
        }
        else {
            $(this).parent().css('background-color', nazerlastcolor);
            $(this).parent().css('color', 'black');
            $(this).parent().find('.permit_nazer_hidden').fadeOut('fast');
            $(this).attr('vhid', 'true');
        }
    });

    var reqlastcolor = '';
    $('body').on('click', '.permit_img_show_info_req_cmnt', function () {
        var vh = $(this).attr('vhid');
        if (vh == 'true') {
            reqlastcolor = $(this).parent().css('background-color');
            var wtd = $(this).parent().width();
            $(this).parent().css('background-color', '#0158c1');
            $(this).parent().css('color', 'white');
            $(this).parent().find('.permit_info_req_hidden').css('right', wtd + 8);
            $(this).parent().find('.permit_info_req_hidden').fadeIn('fast');
            $(this).attr('vhid', 'false');
        }
        else {
            $(this).parent().css('background-color', reqlastcolor);
            $(this).parent().css('color', 'black');
            $(this).parent().find('.permit_info_req_hidden').fadeOut('fast');
            $(this).attr('vhid', 'true');
        }
    });

    $('body').on('click', '.img_get_moreinfo', function () {
        $('#permit_overlay_for_usermoreinfo').fadeIn('fast', function () {
            $('#user_more_info_main').fadeIn();
        });
    });

    $('body').on('click', '#permit_close_usermoreinfo', function () {
        $('#user_more_info_main').fadeOut('fast', function () {
            $('#user_more_info_main').remove();
            $('#permit_overlay_for_usermoreinfo').fadeOut('fast');
        });
    });

    $('body').on('change', '#permit_cmbx_permit_type_edit', function () {
        var pt = $(this).val();
        if (pt == 0 || pt == 1)//no selection or piyade
        {
            $('#permit_txtarea_opt_desc_edit').val('').prop("disabled", true);
            $('#permit_cmbx_train_list_edit,#permit_cmbx_helper_vehicle_list_edit,#permit_cmbx_opt_start_edit,#permit_cmbx_opt_end_edit').val(0).prop("disabled", true);
        }
        else if (pt == 2)//viechle
        {
            $('#permit_txtarea_opt_desc_edit').val('').prop("disabled", false);
            $('#permit_cmbx_train_list_edit').val(0).prop("disabled", true);
            $('#permit_cmbx_helper_vehicle_list_edit,#permit_cmbx_opt_start_edit,#permit_cmbx_opt_end_edit').val(0).prop("disabled", false);
        }
        else if (pt == 3)//garm
        {
            $('#permit_cmbx_helper_vehicle_list_edit').val(0).prop("disabled", true);
            $('#permit_txtarea_opt_desc_edit').val('').prop("disabled", false);
            $('#permit_cmbx_train_list_edit,#permit_cmbx_opt_start_edit,#permit_cmbx_opt_end_edit').val(0).prop("disabled", false);
        }
        else if (pt == 4)//sard
        {
            $('#permit_txtarea_opt_desc_edit').val('').prop("disabled", false);
            $('#permit_cmbx_train_list_edit,#permit_cmbx_helper_vehicle_list_edit,#permit_cmbx_opt_start_edit,#permit_cmbx_opt_end_edit').val(0).prop("disabled", false);
        }
    });

    $('body').on('click', '#permit_cmbx_permit_type', function () {
        var pt = $(this).val();
        if (pt == 0 || pt == 1)//no selection or piyade
        {
            $('#permit_txtarea_opt_desc').val('').prop("disabled", true);
            $('#permit_cmbx_train_list,#permit_cmbx_helper_vehicle_list,#permit_cmbx_opt_start,#permit_cmbx_opt_end').val(0).prop("disabled", true);
        }
        else if (pt == 2)//viechle
        {
            $('#permit_txtarea_opt_desc').val('').prop("disabled", false);
            $('#permit_cmbx_train_list').val(0).prop("disabled", true);
            $('#permit_cmbx_helper_vehicle_list,#permit_cmbx_helper_vehicle_list,#permit_cmbx_opt_start,#permit_cmbx_opt_end').val(0).prop("disabled", false);
        }
        else if (pt == 3)//garm
        {
            $('#permit_cmbx_helper_vehicle_list').val(0).prop("disabled", true);
            $('#permit_txtarea_opt_desc').val('').prop("disabled", false);
            $('#permit_cmbx_train_list,#permit_cmbx_opt_start,#permit_cmbx_opt_end').val(0).prop("disabled", false);
        }
        else if (pt == 4)//sard
        {
            $('#permit_txtarea_opt_desc').val('').prop("disabled", false);
            $('#permit_cmbx_train_list,#permit_cmbx_helper_vehicle_list,#permit_cmbx_opt_start,#permit_cmbx_opt_end').val(0).prop("disabled", false);
        }
    });
/////////////////////////AJAX///////////////////////////////
    $('body').on('change', '#permit_cmbx_working_scope_edit,#permit_cmbx_metroline_number_edit', function () {
        var hozeid = $('#permit_cmbx_working_scope_edit option:selected').val();
        var lineid = $('#permit_cmbx_metroline_number_edit option:selected').val();
        if (hozeid != 0 && lineid != 0) {
            GetPlaceFromScopeLine_edit(lineid, hozeid);
        }
        else {
            //$('#permit_cmbx_working_place_edit option').remove();
            //$('#permit_cmbx_working_place_edit').prop("disabled", true);
            $('#permit_cmbx_working_scope_ajaxloader_edit').fadeOut('fast');
        }
    });

    function GetPlaceFromScopeLine_edit(lineid, hozeid) {
        $.ajax({
            url: 'apps/permit/ajax/getplacefromhozeline.php',
            type: 'POST',
            dataType: 'json',
            data: {'id_line': lineid, 'id_hoze': hozeid},
            beforeSend: function () {
                $('#permit_cmbx_working_scope_ajaxloader_edit').fadeIn('fast');
                $('#permit_cmbx_working_place_div_edit').html('');
                //$('#permit_cmbx_working_place_edit option').remove();
                //$('#permit_cmbx_working_place_edit').prop("disabled", true);
            },
            success: function (data) {
                var output = '';
                $.each(data, function (i) {
                    output += '<input type="checkbox" id="permit_place_chk_' + data[i]['mahal_kar_id'] + '" value = "' + data[i]['mahal_kar_id'] + '" ' + ((data[i]["chk"] === 1) ? 'checked' : '') + ' />';
                    output += '<label style="position: relative; top:-3px;" for="permit_place_chk_' + data[i]['mahal_kar_id'] + '">' + data[i]['mahal_kar_name'] + '</label><br/>';
                });
                $('#permit_cmbx_working_place_div_edit').html(output);
                $('#permit_cmbx_working_scope_ajaxloader_edit').fadeOut('fast');
                //$('#permit_cmbx_working_place_edit option').remove();
                //var strtmp = '<option value="0"></option>' + data;                
                //$('#permit_cmbx_working_place_edit').append(strtmp);
                //$('#permit_cmbx_working_place_edit').prop("disabled", false);
            }
        });
    }

    var togall = false;
    $('body').on('click', '#btn_sall', function () {
        // alert($('#permit_cmbx_working_place_div > .togall').length);
        // permit_cmbx_working_place_div
        if (togall == false) {
            togall = true;
            $('#permit_cmbx_working_place_div > .togall').each(function () {
                $(this).prop("checked", togall);
            });
        }
        else if (togall == true) {
            togall = false;
            $('#permit_cmbx_working_place_div > .togall').each(function () {
                $(this).prop("checked", togall);
            });
        }
    });

    $('body').on('change', '#permit_cmbx_working_scope,#permit_cmbx_metroline_number', function () {
        var hozeid = $('#permit_cmbx_working_scope option:selected').val();
        var lineid = $('#permit_cmbx_metroline_number option:selected').val();
        if (hozeid != 0 && lineid != 0) {
            GetPlaceFromScopeLine(lineid, hozeid);
        }
        else {
            $('#permit_cmbx_working_place option').remove();
            $('#permit_cmbx_working_place').prop("disabled", true);
            $('#permit_cmbx_working_scope_ajaxloader').fadeOut('fast');
        }
    });
    function GetPlaceFromScopeLine(lineid, hozeid) {
        $.ajax({
            url: 'apps/permit/ajax/getplacefromhozeline.php',
            type: 'POST',
            dataType: 'json',
            data: {'id_line': lineid, 'id_hoze': hozeid},
            beforeSend: function () {
                $('#permit_cmbx_working_scope_ajaxloader').fadeIn('fast');
                $('#permit_cmbx_working_place_div').html('');
                //$('#permit_cmbx_working_place option').remove();
                //$('#permit_cmbx_working_place').prop("disabled", true);
            },
            success: function (data) {
                //alert(JSON.stringify(data));
                // {"mahal_kar_id":"4","mahal_kar_name":"ایستگاه احسان","chk":0}
                var output = '';
                $.each(data, function (i) {
                    output += '<input type="checkbox" class="togall" id="permit_place_chk_' + data[i]['mahal_kar_id'] + '" value = "' + data[i]['mahal_kar_id'] + '" ' + ((data[i]["chk"] === 1) ? 'checked' : '') + ' />';
                    output += '<label style = "position: relative; top:-3px;" for="permit_place_chk_' + data[i]['mahal_kar_id'] + '">' + data[i]['mahal_kar_name'] + '</label><br/>';
                });
                //alert(output);
                $('#permit_cmbx_working_place_div').html(output);
                //$('#permit_cmbx_working_place option').remove();
                //var strtmp = '<option value="0"></option>' + data;
                $('#permit_cmbx_working_scope_ajaxloader').fadeOut('fast');
                //$('#permit_cmbx_working_place').append(strtmp);
                //$('#permit_cmbx_working_place').prop("disabled", false);
            }
        });
    }

    $('body').on('change', '#permit_cmbx_activity_time_edit', function () {//
        var actid = $(this).val();
        if (actid != 0) {
            GetHozeFromAct_edit(actid);
        }
        else {
            $('#permit_cmbx_working_scope_edit option').remove();
            $('#permit_cmbx_working_place_edit option').remove();
            $('#permit_cmbx_working_scope_edit').prop("disabled", true);
            $('#permit_cmbx_working_place_edit').prop("disabled", true);
        }
    });

    function GetHozeFromAct_edit(actid) {
        $.ajax({
            url: 'apps/permit/ajax/gethozefromact.php',
            type: 'POST',
            data: {'actid': actid},
            beforeSend: function () {
                $('#permit_cmbx_working_scope_edit option').remove();
                $('#permit_cmbx_working_scope_edit').prop("disabled", true);
                $('#permit_cmbx_activity_time_ajaxloader_edit').fadeIn('fast');
            },
            success: function (data) {
                $('#permit_cmbx_working_scope_edit option').remove();
                var strtmp = '<option value="0"></option>' + data;
                $('#permit_cmbx_activity_time_ajaxloader_edit').fadeOut('fast');
                $('#permit_cmbx_working_scope_edit').append(strtmp);
                $('#permit_cmbx_working_scope_edit').prop("disabled", false);
            }
        });
    }

    $('body').on('change', '#permit_cmbx_activity_time', function () {
        var actid = $(this).val();
        if (actid != 0) {
            GetHozeFromAct(actid);
        }
        else {
            $('#permit_cmbx_working_scope option').remove();
            $('#permit_cmbx_working_scope').prop("disabled", true);
        }
    });
    function GetHozeFromAct(actid) {
        $.ajax({
            url: 'apps/permit/ajax/gethozefromact.php',
            type: 'POST',
            data: {'actid': actid},
            beforeSend: function () {
                $('#permit_cmbx_working_scope option').remove();
                $('#permit_cmbx_working_scope').prop("disabled", true);
                $('#permit_cmbx_activity_time_ajaxloader').fadeIn('fast');
            },
            success: function (data) {
                $('#permit_cmbx_working_scope option').remove();
                var strtmp = '<option value="0"></option>' + data;
                $('#permit_cmbx_activity_time_ajaxloader').fadeOut('fast');
                $('#permit_cmbx_working_scope').append(strtmp);
                $('#permit_cmbx_working_scope').prop("disabled", false);
            }
        });
    }

    $('body').on('change', '#permit_cmbx_peimankar_of_unitnezarat', function () {
        var peimankarid = $(this).val();
        if (peimankarid != 0) {
            GetPeimankarWorker(peimankarid);
        }
        else {
			$('#permit_cmbx_supervisor_peimankar option').remove();
			$('#permit_cmbx_supervisor_peimankar2 option').remove();
			
            $('#permit_tbl_listof_worker1 tr.permit_tbl_listof_worker_row').remove();
            $('#permit_tbl_listof_worker2 tr.permit_tbl_listof_worker_row').remove();
        }
    });
    function GetPeimankarWorker(peimankarid) {
        $.ajax({
            url: 'apps/permit/ajax/getworkerfrompeimankar.php',
            type: 'POST',
            dataType: 'json',
            data: {'peimankarid': peimankarid},
            beforeSend: function () {
                $('#permit_ajax_loader_peimankar_of_unitnezarat').fadeIn('fast');
                $('#permit_tbl_listof_worker1 tr.permit_tbl_listof_worker_row').remove();
                $('#permit_tbl_listof_worker2 tr.permit_tbl_listof_worker_row').remove();
            },
            success: function (data) {
                //alert(data);
                var orderchk = true;
                var trstr = '';
				var supervisorlist='';
				$('#permit_cmbx_supervisor_peimankar option').remove();
				$('#permit_cmbx_supervisor_peimankar2 option').remove();
				
                $.each(data, function (index) {
                    var id = data[index]['peimankar_listnafarat_id'];
                    var fn = data[index]['peimankar_listnafarat_fname'];
                    var ln = data[index]['peimankar_listnafarat_lname'];
					var mobile = data[index]['peimankar_listnafarat_mobile']; 
                    var code = data[index]['peimankar_listnafarat_codemelli'];
					
                    trstr += '<tr class="permit_tbl_listof_worker_row" peimankar_worker_id="' + id + '">';
                    trstr += '<td>' + ln + '</td>';
                    trstr += '<td>' + fn + '</td>';
                    trstr += '<td>' + code + '</td></tr>';
					
					supervisorlist += '<option value="' + id + '">' + fn + ' ' + ln + '(' + mobile + ')' + '</option>';
					
					// vase inke yeki az worker ha to setoone 1 va digari to settone 2 (zigzagi)
                    if (orderchk) {
                        $('#permit_tbl_listof_worker1').append(trstr);
                        orderchk = false;
                    }
                    else {
                        $('#permit_tbl_listof_worker2').append(trstr);
                        orderchk = true;
                    }
                    trstr = '';
                });
				
				$('#permit_cmbx_supervisor_peimankar').append(supervisorlist);
				$('#permit_cmbx_supervisor_peimankar2').append(supervisorlist);
				
                $('#permit_ajax_loader_peimankar_of_unitnezarat').fadeOut('fast');
            }
        });
    }

    $('body').on('change', '#permit_cmbx_unit_nezarat', function () {
        var nezaratid = $(this).val();
        if (nezaratid != 0) {
            GetNazerOfNezaratUnit(nezaratid);
            $('#permit_tbl_listof_worker1 tr.permit_tbl_listof_worker_row').remove();
            $('#permit_tbl_listof_worker2 tr.permit_tbl_listof_worker_row').remove();
            $('#permit_cmbx_peimankar_of_unitnezarat option').remove();
            GetPeimankarOfNezaratUnit(nezaratid);
        }
        else {
            $('#permit_tbl_nazer_of_nezarat tr.permit_tbl_nazer_of_nezarat_row').remove();
            $('#permit_tbl_listof_worker1 tr.permit_tbl_listof_worker_row').remove();
            $('#permit_tbl_listof_worker2 tr.permit_tbl_listof_worker_row').remove();
            $('#permit_cmbx_peimankar_of_unitnezarat option').remove();
        }
    });
    function GetPeimankarOfNezaratUnit(nezaratid) {
        $.ajax({
            url: 'apps/permit/ajax/getpeimankarfromnezarat.php',
            type: 'POST',
            data: {'nezaratid': nezaratid},
            success: function (data) {
                $('#permit_cmbx_peimankar_of_unitnezarat option').remove();
                var strtmp = '<option value="0"></option>' + data;
                $('#permit_ajax_loader_unit_nezarat').fadeOut('fast');
                $('#permit_cmbx_peimankar_of_unitnezarat').append(strtmp);
                $('#permit_cmbx_peimankar_of_unitnezarat').prop("disabled", false);
            }
        });
    }

    function GetNazerOfNezaratUnit(nezaratid) {
        $.ajax({
            url: 'apps/permit/ajax/getnazerfromnezarat.php',
            type: 'POST',
            dataType: 'json',
            data: {'nezaratid': nezaratid},
            beforeSend: function () {
                $('#permit_ajax_loader_unit_nezarat').fadeIn('fast');
                $('#permit_tbl_nazer_of_nezarat tr.permit_tbl_nazer_of_nezarat_row').remove();
                $('#permit_cmbx_peimankar_of_unitnezarat').prop("disabled", true);
            },
            success: function (data) {
                //alert(data);
                var trstr = '';
                $.each(data, function (index) {
                    var id = data[index]['users_id'];
                    var fn = data[index]['users_fname'];
                    var ln = data[index]['users_lname'];
                    var mob = data[index]['userdetail_mobile'];
                    var code = data[index]['userdetail_codemelli'];
                    trstr += '<tr class="permit_tbl_nazer_of_nezarat_row" nazer_id="' + id + '">';
                    trstr += '<td><img src = "img/ddetails.png" style="position:relative;top:2px;" class="img_get_moreinfo btnpointer" /></td>';
                    trstr += '<td>' + ln + '</td>';
                    trstr += '<td>' + fn + '</td>';
                    trstr += '<td>' + mob + '</td>';
                    trstr += '<td>' + code + '</td></tr>';
                });
                $('#permit_ajax_loader_unit_nezarat').fadeOut('fast');
                $('#permit_tbl_nazer_of_nezarat').append(trstr);
            }
        });
    }
});