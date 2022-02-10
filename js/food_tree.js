var firsttime_hafte_user = true;
var firsttime_edit = true;
var firsttime_hafte_edit = true;
var firsttime_ready = true;
var firsttime_report = true;
$(function () {
    var isrootgap = true;
    var treenewnode = false;
    var lblobj = null;
    var lastname = null;
    var g_pkidmenu = 0;

	$('body').on('click','#food_close_resdls',function(){
		//$('#food_res_maat,#food_res_dlg').fadeOut('fast');
		$("#selectPlace").fadeOut('fast');
	});

    $('body').on('click', '.food_img_show_report', function (e) {
        var p = $(this).parent().parent().next('.fd_chart_content');
        if (p.css('display') == 'none') {
            var p = p.fadeIn('fast');
        }
        else {
            var p = p.fadeOut('fast');
        }
    });

	$('body').on('mouseover', '.parent_rating_panel div', function (e) {
    //$('.parent_rating_panel div').mouseover(function () {
        var thisval = parseInt($(this).attr('sval'));
        $(this).parent().find('div').each(function () {
            if (parseInt($(this).attr('sval')) <= thisval) {
                $(this).css('background-position', '-20px 0px');
            }
            else {
                $(this).css('background-position', '0px 0px');
            }
        });
    });

	$('body').on('click', '.parent_rating_panel div', function (e) {
    //$('.parent_rating_panel div').click(function () {
        $(this).parent().attr('ischk', $(this).attr('sval'));
    });

	$('body').on('mouseleave', '.parent_rating_panel', function (e) {
    //$('.parent_rating_panel').mouseleave(function () {
        $(this).find('div').each(function () {
            if (parseInt($(this).attr('sval')) <= parseInt($(this).parent().attr('ischk')))
                $(this).css('background-position', '-20px 0px');
            else {
                $(this).css('background-position', '0px 0px');
            }
        });
    });

    $('body').on('click', '.fd_dv_cmnt_plf', function (e) {
        e.stopPropagation();
        alert('Under Construction ...');
    });

    $('body').on('click', '.close_food_pick', function () {
        var obj = $(this);
        $(this).parent().parent().fadeOut('fast', function () {
            obj.parent().parent().remove();
        });
    });

    function addtomeal(e) {
        var pkid = $(e).parent().parent().attr('nodeid');
        var fname = $(e).parent().find('.lbl_node').text().trim();
        var foodid = $(e).parent().parent().attr('nodeid');
        //alert(foodid);
        var item = '';
        item += '<li pkidfood="' + pkid + '">';
        item += '<div class="food_items_for_vade box_sized radius5">';
        item += '<table style="width:100%;padding:0px;margin:0px;">';
        item += '<tr style="padding:0px;margin:0px;">';
        item += '<td style="width:20%;"><img src="img/bread.png" style="width:30px;display:inline-block;padding:0px;margin:0px;"/></td>';
        item += '<td style="width:80%;"><div style="display:inline-block;font:13px BYekanRegular;"><p style="display: none" class="food_item_id">' + foodid + '</p> <p class="food_item">' + fname + '</p></div></td>';
        item += '</tr>';
        item += '</table>';
        item += '<img src="img/delfood.png" class="close_food_pick"/>';
        item += '</div>';
        item += '</li>';
        $('#foods_ul_sortable').append(item);
    }

    $('body').on('dblclick', '.leafnode', function () {
        addtomeal(this);
    });

    $('body').on('dblclick', '.lbl_node', function () {
        if ($(this).parent().find('.leafnode').attr('class'))
            addtomeal(this);
    });

    $('body').on('click', '.max_bottom,.max_middle', function () {
        openclose(this);
    });

    function openclose(e) {
        var divnode = $(e).parent();
        var linode = divnode.parent();
        var selfobj = $(e);
        var isopened = divnode.next('ul').length;

        if (!isopened) {
            var nodeid = divnode.parent().attr('nodeid');
            getNodeChildren(linode, nodeid, selfobj);
        }
        else {
            divnode.next('ul').show();
            var tmpiconnode = divnode.find('.parent_icon_node_close').removeClass('parent_icon_node_close').addClass('parent_icon_node_open');
            selfobj.removeClass('max_bottom max_middle');
            if (linode.attr('lastnode') == 'true') {
                selfobj.addClass('min_down');
            }
            else {
                selfobj.addClass('min_middle');
            }
        }
    }

    function getNodeChildren(linode, nodeid, selfobj, addchild) {
        var datasend = {func: 'get_node_children', nid: nodeid};
        if (addchild) {
            datasend.newnode = 1;
        }
        else {
            datasend.newnode = 0;
        }
        $.ajax({
            url: 'apps/food/ajax/ajxtree.php',
            data: datasend,
            type: 'POST',
            success: function (dddd) {
                //alert(dddd);
                var data = JSON.parse(dddd);
                create_sub_node(linode, data);
                var tmpiconnode = linode.find('.parent_icon_node_close').removeClass('node_ajax_gif').removeClass('parent_icon_node_close').addClass('parent_icon_node_open');
                selfobj.removeClass('max_bottom max_middle');
                if (linode.attr('lastnode') == 'true') {
                    selfobj.addClass('min_down');
                    if (treenewnode)
                        lblobj = selfobj;
                }
                else {
                    selfobj.addClass('min_middle');
                    if (treenewnode)
                        lblobj = selfobj;
                }
                if (treenewnode)
                    treenew();
            },
            beforeSend: function () {
                linode.find('.parent_icon_node_close').addClass('node_ajax_gif');
            },
            error: function () {
                alert('error');
            }
        });
    }

    function create_sub_node(mydiv, data) {
        var ulstr = '<ul class="ulcat">';
        var item_count = data.length - 1;
        var itemindex = 0;

        if (isrootgap) {
            //ulstr += '<span class="tree_node gap_block"></span>';
            isrootgap = false;
        }
        var newitem = -1;
        $.each(data, function (i) {
            if (data[i]['item_id']) {
                var depth = data[i]['depth'];
                var pkid = data[i]['item_id'];
                var islastnode = false;
                if (itemindex == item_count) {
                    islastnode = true;
                }

                if (data[i]['leaf'] == 1) //leaf
                {
                    ulstr += '<li lastnode="' + islastnode + '" nodeid="' + data[i]['item_id'] + '" main_lev="' + data[i]['depth'] + '">';
                    ulstr += '<div>';
                    var myli = mydiv.parent();

                    for (j = 0; j < depth; j++) {
                        ulstr += '<span class="tree_node gap_block"></span>';
                    }

                    /*if (myli.attr('lastnode') != 'true') {
                     ulstr += '<span class="tree_node gap_block"></span>';
                     for (j = 0; j < depth - 2; j++) {
                     ulstr += '<span class="tree_node joined_line"></span>';
                     }

                     }
                     else {
                     for (j = 0; j < depth - 1; j++) {
                     ulstr += '<span class="tree_node gap_block"></span>';
                     }
                     }*/
                    var stringnewitem = '';
                    if (pkid == newitem) {
                        stringnewitem = ' new_item';
                    }
                    if (islastnode) {
                        ulstr += '<span class="tree_node th2wayroad_bottom"></span>';
                    }
                    else {
                        ulstr += '<span class="tree_node th3wayroad"></span>';
                    }
                    ulstr += '<span class="tree_node  leafnode"></span> ';
                    ulstr += '<span class="lbl_node' + stringnewitem + '" style="">' + data[i]['name'] + '</span>';
                    ulstr += '</div>';
                    ulstr += '</li>';
                }
                else //pnode
                {
                    ulstr += '<li lastnode="' + islastnode + '" nodeid="' + data[i]['item_id'] + '" main_lev="' + data[i]['depth'] + '">';
                    ulstr += '<div>';
                    var myli = mydiv.parent();

                    for (j = 0; j < depth; j++) {
                        ulstr += '<span class="tree_node gap_block"></span>';
                    }

                    /*if (myli.attr('lastnode') != 'true') {
                     ulstr += '<span class="tree_node gap_block"></span>';
                     for (j = 0; j < depth - 2; j++) {
                     ulstr += '<span class="tree_node joined_line"></span>';
                     }
                     }
                     else {
                     for (j = 0; j < depth - 1; j++) {
                     ulstr += '<span class="tree_node gap_block"></span>';
                     }
                     }*/
                    var stringnewitem = '';
                    if (pkid == newitem) {
                        stringnewitem = ' new_item';
                    }
                    if (islastnode) {
                        ulstr += '<span class="tree_node max_bottom"></span>';
                    }
                    else {
                        ulstr += '<span class="tree_node max_middle"></span>';
                    }
                    ulstr += '<span class="tree_node parent_icon_node_close"></span> ';
                    ulstr += '<span class="lbl_node' + stringnewitem + '">' + data[i]['name'] + '</span>';
                    ulstr += '</div>';
                    ulstr += '</li>';

                }
                itemindex++;
            } else {
                if (data[i]['new_item']) {
                    newitem = data[i]['new_item'];
                    item_count--;
                }
            }
        });
        ulstr += '</ul>';
        //$(ulstr).insertAfter(mydiv);
        mydiv.append(ulstr);
        if (newitem > 0) {
            lblobj = mydiv.find('span.new_item');
            var input = '<input type="text" value="' + lblobj.html().trim() + '" class="txt_menu_edit"/>';
            lblobj.html(input);
            $('#treemenu').hide();
            $(lblobj).find('input').focus();
            $(lblobj).find('input').select();
            $(".txt_menu_edit").blur(function () {
                editnodelbl(lblobj, $(this).val().trim());
            });
            setCaretToPos($(lblobj).find('input'), $(lblobj).find('input').val().length);
        }
    }

    function editnodelbl(lblobj, new_name) {
        if (new_name.trim() != '') {
            $.ajax({
                url: 'apps/food/ajax/ajxtree.php',
                data: {func: 'editnodelbl', nid: lblobj.parent().parent().attr('nodeid'), nname: new_name},
                type: 'POST',
                success: function (data) {
                    if (data == 'ok') {
                        lblobj.html(new_name);

                    }
                    else {
                        alert('بروز خطا در تغییر نام غذا ، لطفا دوباره سعی کنید');
                        lblobj.html(lastname);

                    }
                    $('span.highlighttext').removeClass('highlighttext');
                    lblobj.parent().find('.node_ajax_gif').removeClass('node_ajax_gif');
                    lblobj.parent().find('span.new_item').removeClass('new_item');
                    lblobj = null;
                    lastname = null;
                    //$('#tt .highlighttext').removeClass('highlighttext');
                },
                beforeSend: function () {
                    lblobj.parent().find('.parent_icon_node_close,.parent_icon_node_close,.leafnode').addClass('node_ajax_gif');
                },
                error: function () {
                }
            });
        }
    }

    $('body').on('keypress', '.txt_menu_edit', function (e) {
        if (e.keyCode == 13) {
            editnodelbl(lblobj, $(this).val().trim());
        }
    });

    $('body').on('click', '#treenew', function (e) {
        treenew();
    });

    function treenew() {
        treenewnode = false;
        var isopened = false;
        //alert(lblobj.html());
        var tmp = lblobj.parent().children('.max_bottom').attr('class');
        if (!tmp) {
            tmp = lblobj.parent().children('.max_middle').attr('class');
            if (!tmp) {
                tmp = lblobj.parent().children('.min_down').attr('class');
                isopened = true;
                if (!tmp) {
                    tmp = lblobj.parent().children('.min_middle').attr('class');
                    if (tmp) {
                        lblobj = lblobj.parent().children('.min_middle');
                        isopened = true;
                    }
                }
                else {
                    lblobj = lblobj.parent().children('.min_down');
                }
            } else {
                lblobj = lblobj.parent().children('.max_middle');
            }
        } else {
            lblobj = lblobj.parent().children('.max_bottom');
        }

        if (tmp) {
            if (isopened) {
                lblobj.parent().next().remove();
                var divnode = $(lblobj).parent();
                var linode = divnode.parent();
                var selfobj = $(lblobj);
                var nodeid = divnode.parent().attr('nodeid');
                getNodeChildren(linode, nodeid, selfobj, true);
            } else {
                treenewnode = true;
                openclose(lblobj);
            }
        }
        else {
            //alert(lblobj.html());
            lblobj.parent().find('span.leafnode').removeClass('leafnode').addClass('parent_icon_node_open');
            lblobj.parent().find('span.th2wayroad_bottom').removeClass('th2wayroad_bottom').addClass('min_down');
            lblobj.parent().find('span.th3wayroad').removeClass('th3wayroad').addClass('min_middle');
            lblobj.parent().after('<ul class="ulcat"></ul>');
            $('span.highlighttext').removeClass('highlighttext');
            treenew();
        }
        $('#treemenu').hide();
    }

    $('body').on('click', '#treeedit', function (e) {
        var input = '<input type="text" value="' + lblobj.html().trim() + '" class="txt_menu_edit"/>';
        lblobj.html(input);
        $('#treemenu').hide();
        $(lblobj).find('input').focus();
        $(".txt_menu_edit").blur(function () {
            editnodelbl(lblobj, $(this).val().trim());
        });
        $(lblobj).find('input').select();
        setCaretToPos($(lblobj).find('input'), $(lblobj).find('input').val().length);
    });   

    $('body').on('mouseup', '.lbl_node', function (e) {
        if (e.which == 3) {
            mousedown_lblnode(this);
        }
    });

    function mousedown_lblnode(e) {

        $(".lbl_node").bind(".contextmenu", function () {
            return false;
        });
        lblobj = $(e);
        //alert(lblobj.html());
        lastname = lblobj.html().trim();
        g_pkidmenu = $(e).parent().attr('nodeid');
        $('span.highlighttext').removeClass('highlighttext');
        $(e).addClass('highlighttext');
        var pos = $(e).position();
        //var x = $('.rightclickdisable').width() - pos.left + 30;
		var x = pos.left;
        var y = pos.top + 40;
        $('#treemenu').css({left: x, top: y});
        $('#treemenu').fadeIn('fast');

    }

    $('body').click(function () {
        if ($('#treemenu').css('display') != 'none') {
            $('#treemenu').hide();
            $('span.highlighttext').removeClass('highlighttext');
        }
    });

    function setCaretToPos(input, pos) {
        setSelectionRange(input, pos, pos);
    }

    function setSelectionRange(input, selectionStart, selectionEnd) {
        if (input.setSelectionRange) {
            input.focus();
            input.setSelectionRange(selectionStart, selectionEnd);
        }
        else if (input.createTextRange) {
            var range = input.createTextRange();
            range.collapse(true);
            range.moveEnd('character', selectionEnd);
            range.moveStart('character', selectionStart);
            range.select();
        }
    }

    $('body').on('click', '.min_down,.min_middle,.min_up', function () {
        var selfobj = $(this);
        var mydiv = $(this).parent();
        var myli = mydiv.parent();
        mydiv.next('ul').hide();

        var tmpiconnode = mydiv.find('.parent_icon_node_open');

        tmpiconnode.removeClass('parent_icon_node_open');
        tmpiconnode.addClass('parent_icon_node_close');
        selfobj.removeClass('min_middle min_up min_down');

        if (myli.attr('lastnode') == 'true') {
            selfobj.addClass('max_bottom');
        }
        else {
            selfobj.addClass('max_middle');
        }

    });

    $('body').on('dblclick', '.leafnode', function () {
        var icoleaf_obj = $(this);
    });
});