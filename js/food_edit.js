/**
 * Created by mr.vahdat on 02/09/2015.
 */

$(function () {
    var listTablecloth = {};
	var baseUrl = "apps/food/ajax/ajaxfood.php";
    var timeUpdateTablecloth = 0 ;
    var respondGetListTablecloth = false;
    var baseUrl2 = "apps/food/ajax/meal.php";
	
    function getListTablecloth() {
        respondGetListTablecloth = true;

        $.ajax({
            url: baseUrl,
            type: 'POST',
            data: {
                'act': 'get_list_tablecloth',
                'time': timeUpdateTablecloth
            },
            success: function (d) {
                try {
                    var obj = JSON.parse(d);
                    //alert(d);
                    var i;
                    var changeListTablecloth = false;
                    var countent = "";
                    var find = ',';
                    var re = new RegExp(find, 'g');
                    for (i in obj) {
                        if (obj[i].id) {
                            changeListTablecloth = true;
                            listTablecloth[obj[i].id] = {};
                            listTablecloth[obj[i].id].id = obj[i].id;
                            listTablecloth[obj[i].id].items = obj[i].items;
                            listTablecloth[obj[i].id].isvis = obj[i].isvis;
                            listTablecloth[obj[i].id].price = obj[i].price;

                        }
                    }
                    if (changeListTablecloth) {
                        //alert('change');
                        for (i in listTablecloth) {
                            if (listTablecloth[i]) {
                                var imgsrc = 'img/delmet.png';
                                var deleteclass = '';
                                var deletestyle = '';
                                if( listTablecloth[i].isvis == 0){
                                    imgsrc = 'img/addback.png';
                                    deleteclass = 'active_meal_request';
                                    deletestyle = ' style="background-color:#DDDDDD;border-color:#000;"';
                                }
                                var items = listTablecloth[i].items.replace(re, '<br/>');
                                var countent1 = '<div id="meal_target_' + listTablecloth[i].id + '"   class="food_vade_items box_sized radius5 ui-draggable ui-draggable-handle"' + deletestyle + '>';
                                
								countent1 += '<img class="food_img_vade_hico" src="img/vade.png">';
                                countent1 += '<img id="meal_del_' + listTablecloth[i].id + '" id_meal_del="' + listTablecloth[i].id
                                    + '" class="food_img_vade_setting  delete_meal_request '+ deleteclass  +'"   title="حذف" src="' + imgsrc + '" style="position:absolute;top:2px;left:3px;width:24px;">';
                                countent1 += '<img id="meal_edit_' + listTablecloth[i].id + '" id_meal_edit="' + listTablecloth[i].id
                                    + '" class="food_img_vade_setting edit_meal_request" title="ویرایش" src="img/editvade.png" style="position:absolute;top:2px;left:30px;width:24px;">';
                                countent1 += items;
                                countent1 += '<br><div class="pricemeal">' + listTablecloth[i].price + ' تومان';
                                countent1 += '</div></div>';
								countent = countent1 + countent;
                            }
                        }

                        $(".meal_lists").html(countent);
                    }
                    timeUpdateTablecloth = obj.time;
                }
                catch (err) {
                    alert(d + "\r\n" + err);
                }
                finally {
                }
            }
        });
    }

    $('body').on('click', '#addmeal', function () {
        // add meal
        // 1 - add meal to meal table
        // 2- add item to meal-item table

        var str = "";
        var arry_item = [];
        var arry_item_name = '';

        $("#foods_ul_sortable").find(" p.food_item_id").each(function () {
            arry_item.push($(this).text());
        });

        $("#foods_ul_sortable").find(" p.food_item").each(function () {
            arry_item_name += $(this).text();
            arry_item_name += '<br>';
        });

        var peymankar_id = $('#peymankarid').find(":selected").val();
        var meal_price = $('#meal_price').val();
        var is_number = parseInt(meal_price, 10);

        //validate user input
        if (!is_number) {
            alert('هزینه سفره وارد نشده است');
            exit();
        }
        if (arry_item.length === 0) {
            alert('please select item !!');
            exit();
        }


        //////////////////////////////////////////////////////////////////
        $.ajax({
            url: baseUrl2,
            data: {func: 'add_meal', peymankar_id: peymankar_id, meal_price: meal_price, arry_item: arry_item},
            //dataType: 'json',
            type: 'POST',
            success: function (data) {
                getListTablecloth();
                $("#foods_ul_sortable").html("");
                $("#meal_price").val('');
            },
            error: function () {

                $('#notification-bar').text('An error occurred');
            }
        });
        //////////////////////////////////////////////////////////////////


    });

    //Delete meal
    /////////////////////////
    //edit and update meal and items
    $('body').on('click', '#edit_meal', function () {
        var editt_id = $(this).attr("editid_meal");
        //list  meal and meal item for edit
        //
        var str = "";
        var arry_item = [];
        var arry_item_name = '';

        $("#foods_ul_sortable").find(" p.food_item_id").each(function () {
            arry_item.push($(this).text());
        });

        $("#foods_ul_sortable").find(" p.food_item").each(function () {
            arry_item_name += $(this).text();
            arry_item_name += '<br>';
        });

        var peymankar_id = $('#peymankarid').find(":selected").val();
        var meal_price = $('#meal_price').val();
        var is_number = parseInt(meal_price, 10);

        //validate user input
        if (!is_number) {
            alert('هزینه سفره وارد نشده است');
            exit();
        }
        if (arry_item.length === 0) {
            alert('please select item !!');
            exit();
        }

        ////start update
        $.ajax({
            url: baseUrl2,
            data: {
                func: 'update_meal',
                peymankar_id: peymankar_id,
                meal_price: meal_price,
                arry_item: arry_item,
                meal_edit_id: editt_id
            },

            type: 'POST',
            success: function (data) {
                getListTablecloth();
                $("#foods_ul_sortable").html("");
                $("#meal_price").val('');
                $("#meal_action_btn").html('<input type="button"  class="meal_btn" name="addfood" id="addmeal" value="ثبت سفره"> <input type="button" name="cancel"  class="meal_btn"  id="cancelmeal" value="انصراف"  >');

                //////////////////////////////
            }
        });
        ///end update
    });
    /////////////////////////
    //delte meal
    $('body').on('click', '.delete_meal_request', function () {
        var edit_id = $(this).attr("id_meal_del");
        ////
        $.ajax({
            url: baseUrl2,
            data: {func: 'del_meal', del_id: edit_id},
            //dataType: 'json',
            type: 'POST',
            success: function (data) {
                //alert(data);
                $("#meal_target_" + edit_id).css({"background-color": "#DDDDDD", "border-color": "#000"});
                // img
                $("#meal_target_" + edit_id).find(".delete_meal_request").attr("src", "img/addback.png").addClass("active_meal_request");

                //: ;
            }
        });

        ////

    });
    /////////////////////////
    //active meal
    $('body').on('click', '.active_meal_request', function () {
        var act_id = $(this).attr("id_meal_del");


        ////
        $.ajax({
            url: baseUrl2,
            data: {func: 'active_meal', act_id: act_id},
            type: 'POST',
            success: function (data) {
                //alert(data);
                $("#meal_target_" + act_id).css({"background-color": "#ABD5FF", "border-color": "#89c4ff"});
                // img
                $("#meal_target_" + act_id).find(".delete_meal_request").attr("src", "img/delmet.png").removeClass("active_meal_request");


            }
        });

        ////

    });
    /////////////////////////
    $('body').on('click', '#cancelmeal', function () {

        $("#foods_ul_sortable").html("");
        $("#meal_price").val('');
        $("#meal_action_btn").html('' +
        '<input type="button"  class="meal_btn" name="addfood" id="addmeal" value="ثبت سفره">' +
        '<input type="button" name="cancel"  class="meal_btn"  id="cancelmeal" value="انصراف"  >');
		
		

    });
    /////////////////////////
    $('body').on('click', '.edit_meal_request', function () {
        var editt_id = $(this).attr("id_meal_edit");


        ///  make  edit meal
        //
        $.ajax({
            url: baseUrl2,
            data: {func: 'load_edit_date', meal_id: editt_id},
            type: 'POST',
            success: function (data) {
                //alert(data);
                var obj = JSON.parse(data);
                $('#peymankarid').val(obj.peymankarid);
                $('#meal_price').val(obj.price);

                ///load items for edit
                $.ajax({
                    url: baseUrl2,
                    data: {func: 'load_items_4edit', meal_id: editt_id},
                    type: 'POST',
                    success: function (data) {
                        // looad item for edit
                        //alert(data);
                        var obj = JSON.parse(data);
                        var item = '';
                        $.each(obj, function (index, element) {

                            item += '<li pkidfood="' + element.id + '">';
                            item += '<div class="food_items_for_vade box_sized radius5">';
                            item += '<table style="width:100%;padding:0px;margin:0px;">';
                            item += '<tr style="padding:0px;margin:0px;">';
                            item += '<td style="width:20%;"><img src="img/bread.png" style="width:30px;display:inline-block;padding:0px;margin:0px;"/></td>';
                            item += '<td style="width:80%;"><div style="display:inline-block;font:13px BYekanRegular;"><p style="display: none" class="food_item_id">' + element.id + '</p> <p class="food_item">' + element.name + '</p></div></td>';
                            item += '</tr>';
                            item += '</table>';
                            item += '<img src="img/delfood.png" class="close_food_pick"/>';
                            item += '</div>';
                            item += '</li>';
                            $('#foods_ul_sortable').html(item);

                        });

                        $("#meal_action_btn").html('' +
                        ' <input type="button" class="meal_btn" name="edit_food" editid_meal="' + editt_id + '"  id="edit_meal" value="ویرایش">' +
                        ' <input type="button" name="cancel"  class="meal_btn"  id="cancelmeal" value="انصراف"  >');



                    }
                });
                ///

                //$("#meal_target_"+data).html('');
                //$("#meal_target_"+data).hide(1000);

            }
            ,
            error: function (req, status, err) {
                console.log('something went wrong', status, err);

            }
        });

    });
    /////////////////////////
	
	$('body').on('click', '#li-food-tabs-2', function () {
		if(firsttime_edit)
		{
			$(".food_vade_items").disableSelection();
			$(".food_items_for_vade").disableSelection();
			$(".fd_vade_hafte_users").disableSelection();

			$("#foods_ul_sortable").sortable({
				axis: "y"
			});
			$(".box_resize1").height($(".samancss").parent().parent().height());
			$(".box_resize2").height($(".samancss").height());
			listTablecloth = {};
			timeUpdateTablecloth = 0 ;
			respondGetListTablecloth = false;
			$('.rightclickdisable').bind('contextmenu', function (e) {
				e.preventDefault();
			});
		}
		firsttime_edit = false;
		getListTablecloth();
	});

});



