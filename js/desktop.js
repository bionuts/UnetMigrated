$(function () {
	var rcheck = $('#rcheck').val();
	
    $('#idforclose').click(function () {
        //$("#aforlinkcloze").trigger("click");
    });
    $('body').on('click', '.ul_profile_desktop li', function (e) {
        var task = $(this).attr('task');
        if (task === 'user_settings') {
            $.ajax({
                url: 'apps/usersettings/ui/main.php',
                type: 'GET',
                beforeSend: function () {
                    $('.unet_panel_load_app').fadeIn('fast');
                },
                success: function (data) {
                    $('body').append(data);
                    $('.unet_panel_load_app').hide();
                }
            });
        }
    });
    var max_zlayer = 10;
    $('body').on('mousedown', ".windowpanel", function () {
        max_zlayer++;
        $(this).css('z-index', max_zlayer);
    });
    initwin();
    function initwin() {
        $(".windowpanel").resizable({
            minHeight: 400,
            minWidth: 500
        });
        $(".windowpanel").draggable(
            {
                handle: ".windowpanel_header",
                cursor: "move",
                drag: function () {
                    var win = $(this);
                    $(this).attr('lastypos', win.offset().top + 'px');
                    $(this).attr('lastxpos', win.offset().left + 'px');
                    if ($(this).attr('maximize') == 'true') {
                        $(this).css('height', $(this).attr('lastheight'));
                        $(this).css('width', $(this).attr('lastwidth'));
                        $(this).css('top', $(this).attr('lastypos'));
                        $(this).css('left', $(this).attr('lastxpos'));
                        $(this).css('bottom', '');
                        $(".windowpanel").attr('maximize', "false");
                    }
                }
            });
    }

    $('body').on('keydown', '.justnumber', function (e) {
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
    });

    $('body').on('resize', ".windowpanel", function () {
        $(".windowpanel").attr('maximize', "false");
        $(".windowpanel").attr('lastheight', $(".windowpanel").height());
        $(".windowpanel").attr('lastwidth', $(".windowpanel").width());
    });

    $('body').on('click', '.windowpanel_header_btns_max', function () {

        var winobj = $(this).parent().parent().parent();
        if (winobj.attr('maximize') == 'false') {
            maximizeobj(winobj);
        }
        else {
            restoreobjdimension(winobj);
        }
        setTimeout(function () {
            $('#tbodynum_one').height($('#permit_tab1_content').height() - 55);
            $('#tbodynum_two').height($('#permit_tab1_content').height() - 55);
            $('#tbodynum_three').height($('#permit_tab1_content').height() - 100);
            $('#permit_tab4_content').height($('#permit_tab1_content').height() - 55);
        }, 200);

    });

    $('body').on('click', '.windowpanel_header_btns_close', function () {
        closewindow($(this).parent().parent().parent());
    });


    function closewindow(obj) {
        obj.remove();
    }

    function restoreobjdimension(obj) {
        obj.css('width', obj.attr('lastwidth'));
        obj.css('height', obj.attr('lastheight'));//height
        obj.css('top', obj.attr('lastypos'));
        obj.css('left', obj.attr('lastxpos'));
        obj.attr('maximize', 'false');
    }

    function maximizeobj(obj) {
        obj.css('width', '100%');
        obj.css('height', '');
        obj.css('top', '0px');
        obj.css('left', '0px');
        obj.css('right', '0px');
        obj.css('bottom', '0px');

        obj.attr('maximize', 'true');
    }

///////////////////////////////////////////////////////////////////////////////////
    $('.tbl_search_ddl_chkbx').click(function () {
        var targetobj = $(this);
        var vis = $(this).attr('vis');
        if (vis == 'false') {
            $('.tbl_search_ddl_chkbx').each(function () {
                if (targetobj != $(this)) {
                    $(this).attr('vis', 'false');
                    $(this).next('.tbl_search_ddl_chkbx_content').hide();
                }
            });
            $(this).next('.tbl_search_ddl_chkbx_content').fadeIn('fast');
            $(this).attr('vis', 'true');
        }
        else {
            $(this).next('.tbl_search_ddl_chkbx_content').fadeOut('fast');
            $(this).attr('vis', 'false');
        }

    });
    $('.st_chkbx_group').change(function () {
        var chkbxpanel = $(this).parent().parent().parent().prev();
        var total = $(this).parent().parent().find('.st_chkbx_group').size() - 1;
        var select = 0;

        var targetobj = $(this);
        if ($(this).hasClass('st_chkbx_all')) {
            var vchk = $(this).is(':checked');
            $(this).parent().parent().find('.st_chkbx_group').prop('checked', vchk);
            if (vchk) {
                chkbxpanel.html('همه موارد');
                select = total;
            }
            else {
                chkbxpanel.html('بدون انتخاب');
            }
        }
        else {
            var curcheck = $(this).is(':checked');
            if (!curcheck) {
                $(this).parent().parent().find('.st_chkbx_all').prop('checked', false);
                $(this).parent().parent().find('.st_chkbx_group').each(function () {
                    if (!$(this).hasClass('st_chkbx_all')) {
                        //alert($(this).is(':checked'));
                        if ($(this).is(':checked') != true) {
                            //alert($(this).is(':checked'));
                            // mass_ddl_chkbx
                            tmp = tmp && $(this).is(':checked');
                        }
                        else {
                            select++;
                        }
                    }
                });
            }
            else {
                var tmp = true;
                $(this).parent().parent().find('.st_chkbx_group').each(function () {
                    if (!$(this).hasClass('st_chkbx_all')) {
                        //alert($(this).is(':checked'));
                        if ($(this).is(':checked') != true) {
                            //alert($(this).is(':checked'));
                            // mass_ddl_chkbx
                            tmp = tmp && $(this).is(':checked');
                        }
                        else {
                            select++;
                        }
                    }
                });
                if (tmp) {
                    $(this).parent().parent().find('.st_chkbx_all').prop('checked', true);
                    chkbxpanel.html('همه موارد');
                }
            }
        }
        if (select > 1) {
            if (select == total)
                chkbxpanel.html('همه موارد');
            else
                chkbxpanel.html(select + ' ' + 'انتخاب');
        }
        else {
            if (select == 0)
                chkbxpanel.html('همه موارد');
            $(this).parent().parent().find('.st_chkbx_group').each(function () {
                if (!$(this).hasClass('st_chkbx_all')) {
                    //alert($(this).is(':checked'));
                    if ($(this).is(':checked') == true) {
                        chkbxpanel.html($(this).attr('lbl'));
                    }
                }
            });
        }
        //alert(select);
    });
///////////////////////////////////////////////////////////////////////////////////
    $('#pm_div').click(function () {
        var vis = $('#user_pm').attr('vis');
        if (vis == 'false') {
            $('#user_pm').css('z-index', '99999');
            $('#user_pm').animate(
                {width: '20%'},
                "fast",
                function () {
                    $("#user_pm_content").fadeIn();
                });
            $('#user_pm').attr('vis', 'true');
        }
        else {
            $("#user_pm_content").fadeOut('fast', function () {
                $('#user_pm').animate({width: '0px'}, "fast",
                    function () {
                        $('#user_pm').css('z-index', '999');
                    });
            });
            $('#user_pm').attr('vis', 'false');
        }
    });
    $('#img_profile').click(function () {
        var vis = $('#profile_menu').attr('vis');
        if (vis == 'false') {
            $('#profile_menu').fadeIn('fast');
            $('#profile_menu').attr('vis', 'true');
        }
        else {
            $('#profile_menu').fadeOut('fast');
            $('#profile_menu').attr('vis', 'false');
        }
    });
    $('.icon-img').click(function () {
        if ($(this).attr('app') == 'mas') {
            if ($('#app_mas').size() == 0) {
                var height = $(document).height() - 40;
                $('#app_mas').attr('lastheight', height + 'px');
                $('#app_mas').css('height', height + 'px');
                $('#app_mas').fadeIn('fast');
                max_zlayer++;
                $('#app_mas').css('z-index', max_zlayer);
                $.ajax({
                    url: 'apps/mas/ui/main.php',
                    type: 'GET',
                    beforeSend: function () {
                        $('.unet_panel_load_app').fadeIn('fast');
                    },
                    success: function (data) {
                        $('body').append(data);
                        var height = $(document).height() - 40;
                        $('#app_mas').attr('lastheight', height + 'px');
                        $('#app_mas').css('height', height + 'px');
                        $('#app_mas').fadeIn('fast');
                        max_zlayer++;
                        $('#app_mas').css('z-index', max_zlayer);
                        //$('#app_mas').tabs();
                        initwin();
                        $('.unet_panel_load_app').hide();
                    }
                });
            }
        }
        else if ($(this).attr('app') == 'permit') {
            if ($('#app_permit').size() == 0) {
                var height = $(document).height() - 40;
                $('#app_permit').attr('lastheight', height + 'px');
                $('#app_permit').css('height', height + 'px');
                $('#app_permit').fadeIn('fast');
                max_zlayer++;
                $('#app_permit').css('z-index', max_zlayer);

                $.ajax({
                    url: 'apps/permit/ui/main.php',
                    type: 'GET',
                    beforeSend: function () {
                        //$('#permit_ajax_loader_unit_nezarat').fadeIn('fast');
                        $('.unet_panel_load_app').fadeIn('fast');
                    },
                    success: function (data) {
                        $('body').append(data);
                        var height = $(document).height() - 40;
                        $('#app_permit').attr('lastheight', height + 'px');
                        $('#app_permit').css('height', height + 'px');
                        $('#app_permit').fadeIn('fast');
                        max_zlayer++;
                        $('#app_permit').css('z-index', max_zlayer);
                        $('#permit_tabs').tabs();
                        initwin();
                        $('.unet_panel_load_app').hide();
                        $('#tbodynum_one').height($('#permit_tab1_content').height() - 55);
                        $('#tbodynum_two').height($('#permit_tab1_content').height() - 55);
                        $('#tbodynum_three').height($('#permit_tab1_content').height() - 100);
                        $('#permit_tab4_content').height($('#permit_tab1_content').height() - 55);
						
						if(rcheck == 3 || rcheck == 4)
						{
							setTimeout(function () {
								//alert('d');
								Calendar.setup({
									inputField: 'txt_start_date',
									button: 'date_btn_1',
									ifFormat: '%Y/%m/%d',
									dateType: 'jalali',
									weekNumbers: false
								});
								Calendar.setup({
									inputField: 'txt_end_date',
									button: 'date_btn_2',
									ifFormat: '%Y/%m/%d',
									dateType: 'jalali',
									weekNumbers: false
								});
							}, 1000);
						}

                    }
                });
            }
        }
        else if ($(this).attr('app') == 'food') {
            if ($('#app_food').size() == 0) {
                var height = $(document).height() - 40;
                $('#app_food').attr('lastheight', height + 'px');
                $('#app_food').css('height', height + 'px');
                $('#app_food').fadeIn('fast');
                max_zlayer++;
                $('#app_food').css('z-index', max_zlayer);

                $.ajax({
                    url: 'apps/food/ui/main.php',
                    type: 'GET',
                    beforeSend: function () {
                        //$('#permit_ajax_loader_unit_nezarat').fadeIn('fast');
                        $('.unet_panel_load_app').fadeIn('fast');
                    },
                    success: function (data) {
                        $('body').append(data);
                        var height = $(document).height() - 40;
                        $('#app_food').attr('lastheight', height + 'px');
                        $('#app_food').css('height', height + 'px');
                        $('#app_food').fadeIn('fast');
                        max_zlayer++;
                        $('#app_food').css('z-index', max_zlayer);
                        $('#food_tabs').tabs();
                        initwin();
                        $('.unet_panel_load_app').hide();
                        $('#tbodynum_one').height($('#permit_tab1_content').height() - 55);
                        $('#tbodynum_two').height($('#permit_tab1_content').height() - 55);
                        $('#tbodynum_three').height($('#permit_tab1_content').height() - 100);
                        $('#permit_tab4_content').height($('#permit_tab1_content').height() - 55);
                        firsttime_hafte_user = true;
                        firsttime_hafte_edit = true;
                        firsttime_edit = true;
                        firsttime_ready = true;
                        firsttime_report = true;
                        //alert();
                        $('#li-food-tabs-1').trigger('click');
                    }
                });
            }
        }
        else if ($(this).attr('app') == 'meeting') {
            $.ajax({
                url: 'apps/meeting/ui/main.php',
                type: 'GET',
                beforeSend: function () {
                    //$('#permit_ajax_loader_unit_nezarat').fadeIn('fast');
                },
                success: function (data) {
                    $('body').append(data);
                    var height = $(document).height() - 50;
                    $('#app_meeting').attr('lastheight', height + 'px');
                    $('#app_meeting').css('height', height + 'px');
                    $('#app_meeting').fadeIn('fast');
                    max_zlayer++;
                    $('#app_meeting').css('z-index', max_zlayer);
                    initwin();
                }
            });
        }
        else if ($(this).attr('app') == 'present') {
            var win = window.open('http://sct.shirazmetro.ir/?r=' + Math.random(), '_blank');
            if (win) {
                win.focus();
            }
        }
        else if ($(this).attr('app') == 'stnd') {
            var win = window.open('http://stnd.shirazmetro.ir/?r=' + Math.random(), '_blank');
            if (win) {
                win.focus();
            }
        }
        else if ($(this).attr('app') == 'archive') {
            var win = window.open('http://segalsun.shirazmetro.ir/segalsun/?r=' + Math.random(), '_blank');
            if (win) {
                win.focus();
            }
        }
    });
    $('.icon-img').mouseenter(function () {
        $(this).parent().css('background-color', 'lightblue');
        $(this).next('.icon-label').css('color', 'blue');
    });
    $('.icon-img').mouseleave(function () {
        $(this).parent().css('background-color', '');
        $(this).next('.icon-label').css('color', 'black');
    });
});