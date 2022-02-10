$(function () {
    $('body').on('change', '#mas_cmbx_bug_nplace', function () {
        var npid = $(this).val();
        var tajid = $('#mas_cmbx_bug_equip').val();

        if (tajid != 0) {
            $('#mas_cmbx_bug_tajhiz_serial').prop("disabled", true);
            $('#mas_cmbx_bug_tajhiz_serial option').remove();
            if (npid != 0) {
                get_serial_from_tajhiz_nplace(tajid, npid);
            }
            else {
                $('#mas_cmbx_bug_tajhiz_serial option').remove();
            }
        }
    });

    function get_serial_from_tajhiz_nplace(tajid, npid) {
        $.ajax({
            url: 'apps/mas/lib/ajaxhnd.php',
            type: 'POST',
            data: {'func': 'get_serial_from_np_tajhiz', taj_id: tajid, np_id: npid},
            beforeSend: function () {
                $('#mas-bug-loader-nplace').show();
            },
            success: function (data) {
                var str = '<option value="0"></option>' + data;
                $('#mas_cmbx_bug_tajhiz_serial').html(str);
                $('#mas_cmbx_bug_tajhiz_serial').prop("disabled", false);
                $('#mas-bug-loader-nplace').hide();
            }
        });
    }

    $('body').on('change', '#mas_cmbx_bug_equip', function () {
        var tajid = $(this).val();
        $('#mas_cmbx_bug_nplace').prop("disabled", true);
        $('#mas_cmbx_bug_nplace option').remove();
        if (tajid != 0) {
            get_nplace_from_tajhiz(tajid);
        }
        else {
            $('#mas_cmbx_bug_nplace option').remove();
        }
    });

    function get_nplace_from_tajhiz(tajid) {
        $.ajax({
            url: 'apps/mas/lib/ajaxhnd.php',
            type: 'POST',
            data: {'func': 'get_nplace_from_tajhiz', taj_id: tajid},
            beforeSend: function () {
                $('#mas-bug-loader-tajhiz').show();
            },
            success: function (data) {
                var str = '<option value="0"></option>' + data;
                $('#mas_cmbx_bug_nplace').html(str);
                $('#mas_cmbx_bug_nplace').prop("disabled", false);
                $('#mas-bug-loader-tajhiz').hide();
            }
        });
    }

    $('body').on('change', '#mas_cmbx_bug_line', function () {
        var lineid = $(this).val();
        $('#mas-cmbx-bug-sys').prop("disabled", true);
        $('#mas-cmbx-bug-sys option').remove();
        if (lineid != 0) {
            get_sys_from_line(lineid);
        }
        else {
            $('#mas-cmbx-bug-sys option').remove();
        }
    });

    function get_sys_from_line(lineid) {
        $.ajax({
            url: 'apps/mas/lib/ajaxhnd.php',
            type: 'POST',
            data: {'func': 'get_sys_from_line', line_id: lineid},
            beforeSend: function () {
                $('#mas-bug-loader-line').show();
            },
            success: function (data) {
                var str = '<option value="0"></option>' + data;
                $('#mas-cmbx-bug-sys').html(str);
                $('#mas-cmbx-bug-sys').prop("disabled", false);
                $('#mas-bug-loader-line').hide();
            }
        });
    }

    $('body').on('change', '#mas-cmbx-bug-subsys', function () {
        var subsysid = $(this).val();
        $('#mas_cmbx_bug_equip').prop("disabled", true);
        $('#mas_cmbx_bug_equip option').remove();
        if (subsysid != 0) {
            get_equip_from_subsys(subsysid);
        }
        else {
            $('#mas_cmbx_bug_equip option').remove();
            $('#mas_cmbx_bug_nplace option').remove();
            $('#mas_cmbx_bug_tajhiz_serial option').remove();
            $('#mas_cmbx_bug_nplace').prop("disabled", true);
            $('#mas_cmbx_bug_tajhiz_serial').prop("disabled", true);
        }
    });

    function get_equip_from_subsys(subsysid) {
        $.ajax({
            url: 'apps/mas/lib/ajaxhnd.php',
            type: 'POST',
            data: {'func': 'get_equip_from_subsys', subsys_id: subsysid},
            beforeSend: function () {
                $('#mas-bug-loader-subsys').show();
            },
            success: function (data) {
                var str = '<option value="0"></option>' + data;
                $('#mas_cmbx_bug_equip').html(str);
                $('#mas_cmbx_bug_equip').prop("disabled", false);
                $('#mas-bug-loader-subsys').hide();
            }
        });
    }

    $('body').on('change', '#mas-cmbx-bug-sys', function () {
        var sysid = $(this).val();
        $('#mas-cmbx-bug-subsys').prop("disabled", true);
        $('#mas-cmbx-bug-subsys option').remove();

        $('#mas_cmbx_bug_equip').prop("disabled", true);
        $('#mas_cmbx_bug_equip option').remove();

        if (sysid != 0) {
            getsubsys(sysid);
        }
        else {
            $('#mas-cmbx-bug-subsys option').remove();
            $('#mas_cmbx_bug_equip option').remove();
            $('#mas_cmbx_bug_nplace option').remove();
            $('#mas_cmbx_bug_tajhiz_serial option').remove();
        }
    });

    function getsubsys(sysid) {
        $.ajax({
            url: 'apps/mas/lib/ajaxhnd.php',
            type: 'POST',
            data: {'func': 'get_subsys_list', sys_id: sysid},
            beforeSend: function () {
                $('#mas-bug-loader-sys').show();
            },
            success: function (data) {
                var str = '<option value="0"></option>' + data;
                $('#mas-cmbx-bug-subsys').html(str);
                $('#mas-cmbx-bug-subsys').prop("disabled", false);
                $('#mas-bug-loader-sys').hide();
            }
        });
    }

    $('body').on('click', '.img_show_details', function () {
        var trackshow = $('#mas_track_panel').attr('trackshow');
        if (trackshow == 'false') {
            $('#mas_track_panel').animate(
                {width: '50%'},
                "fast",
                function () {
                    $("#mas_track_content").fadeIn();
                });
            $('#mas_track_panel').attr('trackshow', 'true');
        }
        else {
            $("#mas_track_content").fadeOut('fast', function () {
                $('#mas_track_panel').animate({width: '0px'}, "fast");
            });
            $('#mas_track_panel').attr('trackshow', 'false');
        }
    });
    $('body').on('click', '#btn', function () {
        //alert('leila');
        var bugteller = $(this).attr('bugteller');
        if (bugteller == 'false') {
            $('#mas_tellbug_panel').animate(
                {width: '40%'},
                "fast",
                function () {
                    $("#mas_tellbug_content").fadeIn();
                    $("#mas_tellbug_content").find(":input").validationEngine();
                });
            $(this).attr('bugteller', 'true');
        }
        else {
            $("#mas_tellbug_content").fadeOut('fast', function () {
                $('#mas_tellbug_panel').animate({width: '0px'}, "fast");
            });
            $(this).attr('bugteller', 'false');
        }
    });


    $('body').on('click', '.mass_date_fromto', function () {
        var targetobj = $(this);
        var vis = $(this).attr('vis');
        if (vis == 'false') {
            $(this).next('.mass_date_fromto_content').fadeIn('fast');
            $(this).attr('vis', 'true');
        }
        else {
            $('.mass_date_fromto').html('از ' + $('#pcal1').val() + '<br/>' + 'تا ' + $('#pcal2').val());
            $(this).next('.mass_date_fromto_content').fadeOut('fast');
            $(this).attr('vis', 'false');
        }
    });

    $('body').on('click', '#btn_tbl_refresh', function () {
        if ($(this).attr('src') == 'img/fixloader.png') {
            $(this).attr('src', 'img/loader3.gif');
            $('#mas_loading_lable').fadeIn('fast');
        }
        else {
            $(this).attr('src', 'img/fixloader.png');
            $('#mas_loading_lable').fadeOut('fast');
        }
    });

    $('body').on('click', '.mass_range', function () {
        var targetobj = $(this);
        var vis = $(this).attr('vis');
        if (vis == 'false') {
            $(this).next('.mass_range_content').fadeIn('fast');
            $(this).attr('vis', 'true');
        }
        else {
            var exp = '';
            var gt = parseFloat($('#mas_gtrange').val().trim());
            var lt = parseFloat($('#mas_ltrange').val().trim());
            if (isNaN(gt))
                gt = 0;
            if (isNaN(lt))
                lt = 0;

            if (lt < gt && lt != 0) {
                var tmp = lt;
                lt = gt;
                gt = tmp;
            }
            if (gt != '' && gt != null && gt != 0)
                exp = gt + ' <= x ';
            if (lt != '' && lt != null && lt != 0) {
                if (gt != '' && gt != null && gt != 0)
                    exp += '<= ' + lt;
                else
                    exp += 'x <= ' + lt;
            }
            $('.mass_range').html(exp);
            $(this).next('.mass_range_content').fadeOut('fast');
            $(this).attr('vis', 'false');
        }
    });

    $('body').on('click', '#id_mas_tbl_track_btnimg,#mas_tbl_track_btnimg_panel_close', function () {
        $('#mas_tbl_track_btnimg_panel').slideToggle('fast');
    });

    $('body').on('click', '#id_mas_tbl_track_btnimg_ref,#mas_tbl_track_btnimg_panel_close_ref', function () {
        $('#mas_tbl_track_btnimg_panel_ref').slideToggle('fast');
    });
});