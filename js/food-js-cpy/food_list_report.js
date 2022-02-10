$(function () {
	var respondGetListDayMeal = false;
	var respondSetSettingSelectFood = false;
	var respondSetSettingSelectFoodTamdid = false;
	var baseUrl = "apps/food/ajax/ajaxfood.php";
	var listMeals = {};
	var listPlace = {};
	var listPlaceMeals = [];
	var selectfooddate = "";
	function getListDayMeal(day, part){
		respondGetListDayMeal = true;
		$("#loading3").fadeIn(0);
		$("#loading3").find('span').html("در حال بارگذاري ...");
		$.ajax({
            url: baseUrl,
            type: 'POST',
            data: {
                'act': 'get_list_day_meal',
				'day': day,
				'day_part': part
            },
            success: function (d) {
                try {
					//alert(d);
					part = parseInt(part);
					listMeals = {};
					listPlace = {};
					listPlaceMeals = [];
					var obj = JSON.parse(d);
					var count = 0;
					var count2 = 0;
					var ctime = 0;
					var selday = 0;
					var taeed_bottom = false;					
					if(obj['ctime'])
						ctime = obj['ctime'];
					if(obj['selday'])
						selday = obj['selday'];
					if(obj['code'])
						taeed_bottom = obj['code'];
					selectfooddate = selday * 3 + part;					
					for (i in obj) {
						if(obj[i].items){
							listMeals[obj[i].meal_id] = {};
							listMeals[obj[i].meal_id].id = obj[i].meal_id;
							listMeals[obj[i].meal_id].items = obj[i].items;
							listMeals[obj[i].meal_id].count = 0;
							count++;
						}
						if(obj[i].place_name){
							listPlace[obj[i].dutyplace_id] = {};
							listPlace[obj[i].dutyplace_id].id = obj[i].dutyplace_id;
							listPlace[obj[i].dutyplace_id].name = obj[i].place_name
						}
						if(obj[i].number){
							var itemPlaceMeals = {};
							itemPlaceMeals.dutyplace_id = obj[i].dutyplace_id;
							itemPlaceMeals.meal_id = obj[i].meal_id;
							itemPlaceMeals.count = obj[i].number;
							listPlaceMeals.push(itemPlaceMeals);
						}
					}
					var taeed = 0;
					if(selday == ctime || selday == ctime + 1){
						if(taeed_bottom)
							taeed = 2;
						else 
							taeed = 1;					
					}
					var tbl = '';
					if (count > 0){
						var percent = 84 / count;
						var sumpercent = 0;
						tbl += '<tr style="background-color: #515151;color: white;">'
							+ '<td style="width:16%;background-color:#515151;">	'				
							+ '</td>';
						for (i in listMeals){
							count2++
							if( count2 == count){
								percent = 85 - sumpercent;
							}else{
								sumpercent += percent;
							}
							tbl += '<td style="width:'+ percent +'%;">' + listMeals[i].items + '</td>';
						}
						tbl += '</tr>';
						for (i in listPlace){
							tbl += '<tr class="food_rw_haftegi">';
							tbl += '<td class="food_td_date">' + listPlace[i].name + '</td>';

							for (j in listMeals){
								var c = true;
								for (k in listPlaceMeals){
									if(listMeals[j] && listPlaceMeals[k] &&
										listMeals[j].id == listPlaceMeals[k].meal_id && listPlaceMeals[k].dutyplace_id==i){
										c = false;
										tbl += '<td class="fd_drop_vade">' + listPlaceMeals[k].count + '</td>';
										listMeals[j].count += parseInt(listPlaceMeals[k].count);
									}
								}
								if(c){
									tbl += '<td class="fd_drop_vade">0</td>';		
								}
							}
							tbl += '</tr>';
						}
						
						tbl +=' <tr class="food_rw_haftegi" style="background-color:#4e6b92;color:white;">'
                            + '<td class="food_td_date" style="background-color:#4e6b92 !important;color:white;">جمع کل</td>';
						for (i in listMeals){
							tbl += '<td class="fd_drop_vade">'+ listMeals[i].count + '</td>';
						}
						tbl +='</tr>';
					}
					else{
						tbl += '<tr class="food_rw_haftegi">'
							+ '<td style="width:16%;background-color:#4e6b92;">	'				
							+ '</td>'
							+ '<td style="width:84%;background-color:#4e6b92;">هيچ رکودي يافت نشد</td></tr>';
					}
					tbl +='<tr class="food_f_footertbl" style="border: 1px solid #727272;">'
                        + '<td style="text-align: center;font: 13px BYekanRegular;border: none;position:relative;">'
                        + '<div id="loading3" style="right:4px; top:10px;width: 200px;text-align: right;direction: rtl;">'
                        + '<div style="vertical-align: top;display: inline-block;padding: 0px;">'
						+ '<img style="margin: 0;padding: 0;display: block;" src="img/savehafteebadi.gif"/></div>'
                        + '<div style="vertical-align: top;line-height:30px;display: inline-block;direction: rtl;text-align: right;padding-right: 5px;">'
                        + '<span>درحال ذخيره ...</span>'
                        + '</div>'
                        + '</div>'
						+ '</td>'
						+ '<td colspan="3" id="haftehid1" style="text-align: center;font: 13px BYekanRegular;border: none;">';
					if(taeed == 1){
						tbl +='<input id="food_report_taeed" type="button" value="تاييد" style="width:100px;height:25px;font:13px tahoma;" />'
							+ '<input id="food_report_tamdid" type="button" value="تمديد" style="width:100px;height:25px;font:13px tahoma;" disabled/>';
					}else if(taeed == 2){
						tbl +='<input id="food_report_taeed" type="button" value="تاييد" style="width:100px;height:25px;font:13px tahoma;" disabled/>'
							+ '<input id="food_report_tamdid" type="button" value="تمديد" style="width:100px;height:25px;font:13px tahoma;" />';
					}
					
					tbl +='</td>'
						+ '</tr>';
					//alert(tbl);
					$('#food_report_table').html(tbl);
                }
                catch (err) {
                    alert( d + "\r\n" + err);
                }
                finally {
					respondGetListDayMeal = false;
					if(!enableDrag())
						$("#loading3").fadeOut('fast');
                }
            }
        });
	}
	
	function setSettingSelectFood(){
		respondSetSettingSelectFood = true;
		$("#loading3").fadeIn(0);
		$("#loading3").find('span').html("در حال ذخيره ...");
		$.ajax({
            url: baseUrl,
            type: 'POST',
            data: {
                'act': 'set_setting_selectfood',
				'day': selectfooddate
            },
            success: function (d) {
                try {
					var da = $('#date_food_report_day').val();
					var pa  = $('#date_food_report_part').find('option:selected').val();
					getListDayMeal(da,pa);
                }
                catch (err) {
                    alert( d + "\r\n" + err);
                }
                finally {
					respondSetSettingSelectFood = false;
					if(!enableDrag())
						$("#loading3").fadeOut('fast');
                }
            }
        });
	}
	
	function setSettingSelectFoodTamdid(){
		respondSetSettingSelectFoodTamdid = true;
		$("#loading3").fadeIn(0);
		$("#loading3").find('span').html("در حال ذخيره ...");
		$.ajax({
            url: baseUrl,
            type: 'POST',
            data: {
                'act': 'set_setting_selectfood_tamdid',
				'day': selectfooddate
            },
            success: function (d) {
                try {
					var da = $('#date_food_report_day').val();
					var pa  = $('#date_food_report_part').find('option:selected').val();
					getListDayMeal(da,pa);
                }
                catch (err) {
                    alert( d + "\r\n" + err);
                }
                finally {
					respondSetSettingSelectFoodTamdid = false;
					if(!enableDrag())
						$("#loading3").fadeOut('fast');
                }
            }
        });
	}
	
	function enableDrag() {
		if(respondGetListDayMeal)
			return true;
		if(respondSetSettingSelectFood)
			return true;
		if(respondSetSettingSelectFoodTamdid)
			return true;
		return false;
	}

	$('body').on('click', '#btn_food_report_day', function () { 
		var da = $('#date_food_report_day').val();
		var pa  = $('#date_food_report_part').find('option:selected').val();
		getListDayMeal(da,pa);
	});
	
	$('body').on('click', '#food_report_taeed', function () { 
		var r = confirm( "آیا مطمئنید؟");
		if (r == true)
			setSettingSelectFood();
	});
	
	$('body').on('click', '#food_report_tamdid', function () { 
		var r = confirm( "آیا مطمئنید؟");
		if (r == true)
			setSettingSelectFoodTamdid();
	});
	
	$('body').on('click', '#li-food-tabs-4', function () {
		var da = $('#date_food_report_day').val();
		var pa  = $('#date_food_report_part').find('option:selected').val();
		getListDayMeal(da,pa);
	});
	
	$('body').on('click', '#li-food-tabs-6', function () {
		if(firsttime_report)
		{
			firsttime_report = false;
			getUserList();
		}
	});
	
	var respondGetUserList = false;
	var respondGetUserListDetails = false;
	var userList = {};
	
	function getUserList(){
		respondGetUserList = true;
		$("#loading5").fadeIn(0);
		$.ajax({
            url: baseUrl,
            type: 'POST',
            data: {
                'act': 'get_user_list'
            },
            success: function (d) {
                try {
					var obj = JSON.parse(d);

					for (i in obj) {
						if(obj[i].id){
							userList[obj[i].id] = {};
							userList[obj[i].id].id = obj[i].id;
							userList[obj[i].id].name = obj[i].name;
						}
					}
										
					var sel = '<option value="0">همه افراد</option>';
					for(i in userList){
						sel += '<option value="'+ i +'">' +userList[i].name + ' </option>';
					}
					$('#food_report_user_select').html(sel);		
                }
                catch (err) {
                    alert( d + "\r\n" + err);
                }
                finally {
					respondGetUserList = false;
					if(!checkLoading())
						$("#loading5").fadeOut('fast');
                }
            }
        });
	}
	
	function getUserListDetails(uid, dt1, dt2){
		respondGetUserListDetails = true;
		$("#loading5").fadeIn(0);
		$.ajax({
            url: baseUrl,
            type: 'POST',
            data: {
                'act': 'get_user_list_details',
				'uid': uid,
				'dt1': dt1,
				'dt2': dt2
            },
            success: function (d) {
                try {	
					var ContentType = 0;
					
					if(d.length==0)
						d = "[]";
					var obj = JSON.parse(d);
					list = [];	
					for (i in obj) {
						if(obj[i].items){
							var tmpobj = {};
							tmpobj.history_id = obj[i].id;
							tmpobj.date_jalali = obj[i].dt;
							tmpobj.vahdeh = obj[i].va;
							tmpobj.items = obj[i].items;
							tmpobj.price = obj[i].price;
							tmpobj.how_many = obj[i].hm;
							list.push(tmpobj);
							haveContent = 1;
						}else if(obj[i].name){
							var tmpobj = {};
							tmpobj.history_id = obj[i].id;
							tmpobj.name = obj[i].name;
							tmpobj.number = obj[i].number;
							tmpobj.prices = obj[i].prices;
							list.push(tmpobj);
							haveContent = 2;
						
						}						
					}
					var tbl = "";
					var sum = 0;
					if(haveContent == 1){
						tbl +='<tr style="background-color:gray;color:white;">'
							+ '<td style="width:20%;">تاریخ</td>'
							+ '<td style="width:20%;">وعده</td>'
							+ '<td style="width:33%;">نوع غذا</td>'
							+ '<td style="width:17%;">قیمت</td>'
							+ '<td style="width:10%;">تعداد</td>'
							+ '</tr>';
						for (i in list) {
							tbl += '<tr>'
								+ '<td>'+ list[i].date_jalali +'</td>'
								+ '<td>'+ list[i].vahdeh +'</td>'
								+ '<td>'+ list[i].items +'</td>'
								+ '<td>'+ list[i].price +' تومان </td>'
								+ '<td>'+ list[i].how_many +'</td>'
								+ '</tr>';
								sum += list[i].how_many *  list[i].price;
						}
						tbl +='<tr style="background-color:gray;color:white;">'
							+ '<td colspan="5" style="background-color:gray;color:white;">جمع کل: '+sum+' تومان </td>'
							+ '</tr>';
						
					}else if(haveContent == 2){
						tbl +='<tr style="background-color:gray;color:white;">'
							+ '<td style="width:10%;">ردیف</td>'
							+ '<td style="width:50%;">نام و نام خانوادگی</td>'
							+ '<td style="width:20%;">تعداد غذا</td>'
							+ '<td style="width:20%;">مبلغ</td>'
							+ '</tr>';
						var j = 1;
						for (i in list) {
							tbl += '<tr>'
								+ '<td>'+ (j++) +'</td>'
								+ '<td>'+ list[i].name +'</td>'
								+ '<td>'+ list[i].number +'</td>'
								+ '<td>'+ list[i].prices +' تومان </td>'
								+ '</tr>';
								sum += list[i].how_many *  list[i].price;
						}
					}else{
						tbl +='<tr style="background-color:gray;color:white;">'
							+ '<td style="width:100%;">هیچ رکوردی یافت نشد</td>'
							+ '</tr>';
					}
					$('#food_user_detail').html(tbl);
                }
                catch (err) {
                    alert( d + "\r\n" + err);
                }
                finally {
					respondGetUserListDetails = false;
					if(!checkLoading())
						$("#loading5").fadeOut('fast');
                }
            }
        }); 
	}
	
	function checkLoading() {
		if(respondGetUserListDetails)
			return true;
		if(respondGetUserList)
			return true;
		return false;
	}

	$('body').on('click', '#btn_food_report_user', function () {
		var dt1 = $('#food_report_user_date1').val();
		var dt2 = $('#food_report_user_date2').val();
		var uid  = $('#food_report_user_select').find('option:selected').val();
		getUserListDetails(uid, dt1, dt2);
	});

	var respondLoadSetting = false;
	var respondSeveSetting = false;
		
	function loadSetting(){
		respondLoadSetting = true;
		$("#loading6").fadeIn(0);
		$.ajax({
            url: baseUrl,
            type: 'POST',
            data: {
                'act': 'load_setting'
            },
            success: function (d) {
                try {
					var obj = JSON.parse(d);
					setStting(obj);
                }
                catch (err) {
                    alert( d + "\r\n" + err);
                }
                finally {
					respondLoadSetting = false;
					if(!checkLoading_setting())
						$("#loading6").fadeOut('fast');
                }
            }
        });
	}
	
	function saveSetting(jsondata){
		respondSeveSetting = true;
		$("#loading6").fadeIn(0);
		$.ajax({
            url: baseUrl,
            type: 'POST',
            data: {
                'act': 'save_setting',
				'json': JSON.stringify(jsondata)
            },
            success: function (d) {
                try {	
					var obj = JSON.parse(d);
					setStting(obj);
                }
                catch (err) {
                    alert( d + "\r\n" + err);
                }
                finally {
					respondSeveSetting = false;
					if(!checkLoading_setting())
						$("#loading6").fadeOut('fast');
                }
            }
        }); 
	}
	
	function setStting(obj){
		if(obj.weeks){
			$('#setting_weeks').val(obj.weeks);
			$('#setting_weeks').attr('saved',obj.weeks);
		}
			
		if(obj.numberDayForSelction){
			$('#setting_numberDayForSelction').val(obj.numberDayForSelction);
			$('#setting_numberDayForSelction').attr('saved',obj.numberDayForSelction);
		}

		if(obj.ShowMoney == 1){
			$('#setting_ShowMoney').attr('checked','checked');
			$('#setting_ShowMoney').attr('saved','1');
		}
		else{
			$('#setting_ShowMoney').prop('checked', false); 
			$('#setting_ShowMoney').attr('saved','0');
		}
		
	}
	
	function checkLoading_setting() {
		if(respondLoadSetting)
			return true;
		if(respondSeveSetting)
			return true;
		return false;
	}

	$('body').on('click', '#li-food-tabs-7', function () {
		loadSetting();
	});
	
	$('body').on('click', '#save_setting', function () {
		var jsondata = {};
		var changed = false;
		$('#setting_table input').each(function(){
			if ($(this).attr('type') == 'checkbox'){
				if(($(this).attr('saved') == '0' && $(this).is(':checked')) ||
					($(this).attr('saved') == '1' && !$(this).is(':checked'))){
					var name = $(this).attr('name');
					changed = true;
					if($(this).is(':checked'))
						jsondata[name] = 1;
					else
						jsondata[name] = 0;
					
				}			
			}else{
				if($(this).attr('saved')!= $(this).val()){
					var name = $(this).attr('name');
					changed = true;
					jsondata[name] = $(this).val();
				}
			}
		});
		if(changed){
			saveSetting(jsondata);
		}
	});
	
	$('body').on('keydown', '.numberic', function (e) {
        // Allow: backspace, delete, tab, escape, enter and .
        if ($.inArray(e.keyCode, [46, 8, 9, 27, 13, 110, 190]) !== -1 ||
             // Allow: Ctrl+A
            (e.keyCode == 65 && e.ctrlKey === true) || 
             // Allow: home, end, left, right, down, up
            (e.keyCode >= 35 && e.keyCode <= 40)) {
                 // let it happen, don't do anything
                 return;
        }
        // Ensure that it is a number and stop the keypress
        if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
            e.preventDefault();
        }
    });
});