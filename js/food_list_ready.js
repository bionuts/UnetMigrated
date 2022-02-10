$(function () {

	var respondGetListUserPlace = false;
	var baseUrl = "apps/food/ajax/ajaxfood.php";
	var listPlace = {};
	var respondGetStatus = false;
	var respondUpdateHowMany = false;
	var list = [];
	var getdataprint = "";
	var taeed = false;
	function getStatusReady(){
		respondGetStatus = true;
		$("#loading4").fadeIn(0);
		
		$.ajax({
            url: baseUrl,
            type: 'POST',
            data: {
                'act': 'get_list_day_meal',
				'day': '0',
				'day_part': '0'
            },
            success: function (d) {
                try {
					//alert(d);
					listPlace = {};
					var obj = JSON.parse(d);

					for (i in obj) {
						if(obj[i].place_name){
							listPlace[obj[i].dutyplace_id] = {};
							listPlace[obj[i].dutyplace_id].id = obj[i].dutyplace_id;
							listPlace[obj[i].dutyplace_id].name = obj[i].place_name;
						}
					}
										
					var sel2 = '<option value="0">همه افراد</option>';
					for(i in listPlace){
						sel2 += '<option value="'+ i +'">' +listPlace[i].name + ' </option>';
					}
					$('#food_list_detail_select').html(sel2);					
                }
                catch (err) {
                    alert( d + "\r\n" + err);
                }
                finally {
					respondGetStatus = false;
					if(!enableDrag())
						$("#loading4").fadeOut('fast');
                }
            }
        });
	}
		
	function getListUserPlace(place,day, part){
		respondGetListUserPlace = true;
		$("#loading4").fadeIn(0);
		getdataprint = '&place=' + place + '&day=' + day + '&day_part=' + part;
		$.ajax({
            url: baseUrl,
            type: 'POST',
            data: {
                'act': 'get_list_user_place',
				'day': day,
				'day_part': part,
				'place': place
            },
            success: function (d) {
                try {	
					var haveContent = false;
					list = [];	
					if(d.length==0)
						d = "[]";
					var obj = JSON.parse(d);
					var count = 0;
					if(obj.code)
						taeed = true;
					for (i in obj) {
						if(obj[i].user_id){
							var tmpobj = {};
							tmpobj.id = obj[i].id;
							tmpobj.user_id = obj[i].user_id;
							tmpobj.users_lname = obj[i].users_lname;
							tmpobj.users_fname = obj[i].users_fname;
							tmpobj.items = obj[i].items;
							tmpobj.how_many = obj[i].how_many;
							tmpobj.extra = obj[i].ext;
							list.push(tmpobj);
							haveContent = true;
						}
					}
					var tbl = "";
					if(obj['no']){
						tbl +='<tr style="background-color:#511111;color:white;">'
							+ '<td style="width:100%;">در انتظار تائید می باشد</td>'
							+ '</tr>';
					}
					else if(haveContent){
						tbl +='<tr style="background-color:gray;color:white;">'
							+ '<td style="width:6%;">ردیف</td>'
							+ '<td style="width:20%;">نام خانوادگی</td>'
							+ '<td style="width:20%;">نام</td>'
							+ '<td style="width:20%;">نوع غذا</td>'
							+ '<td style="width:8%;">تعداد</td>'
							+ '<td style="width:26%;">توضیحات</td>'
							+ '</tr>';
						var j = 1;
						for (i in list) {
							tbl += '<tr>'
								+ '<td>'+ j +'</td>'
								+ '<td>'+ list[i].users_lname +'</td>'
								+ '<td>'+ list[i].users_fname +'</td>'
								+ '<td>'+ list[i].items +'</td>'
								+ '<td list_id="' + list[i].id + '" class="list_how_many">'+ list[i].how_many +'</td>'
								+ '<td list_id="' + list[i].id + '" class="list_extra">'+ list[i].extra +'</td>'
								+ '</tr>';
								count += parseInt(list[i].how_many);
								j++;
						}
						tbl +=' <tr class="food_rw_haftegi" style="background-color:#4e6b92;color:white;">'
						tbl += '<td colspan="6" style="background-color:#4e6b92 !important;color:white;" class="fd_drop_vade"> جمع کل: '+ count + '</td>';	
						tbl +='</tr>';
					}else{
						tbl +='<tr style="background-color:gray;color:white;">'
							+ '<td style="width:100%;">هیچ رکوردی یافت نشد</td>'
							+ '</tr>';
					}
					$('#food_list_detail').html(tbl);
                }
                catch (err) {
                    alert( d + "\r\n" + err);
                }
                finally {
					respondGetListUserPlace = false;
					if(!enableDrag())
						$("#loading4").fadeOut('fast');
                }
            }
        });
	}
	
	function enableDrag() {
		if(respondGetStatus)
			return true;
		if(respondGetListUserPlace)
			return true;
		if(respondUpdateHowMany)
			return true;
		return false;
	}
	
	$('body').on('click', '.list_how_many', function () { 
		if(taeed && !autoload)
			if(!enableDrag()){
				var b1 = true;
				var list_id = $(this).attr('list_id');
				var how_many_obj = $(this).find('#food_ready_edit_how_many');
				if(!how_many_obj.attr('id'))
				{
					var how_many_obj = $(this).parent().parent().parent().find('#food_ready_edit_how_many');
					if(how_many_obj.attr('id'))
					{
						var number = parseInt(how_many_obj.val());
						if(number)
						{
							if(number> 0 && number<100)
							{
								b1 = false;
								updateHowMany(this, how_many_obj, number)
							}
							else
								how_many_obj.parent().html(how_many_obj.parent().attr('old_val'));
						}else
							how_many_obj.parent().html(how_many_obj.parent().attr('old_val'));
						//updateHowMany(this, how_many_obj, list_id, number);	
					}
					if(b1){
						var number = $(this).html();
						$(this).attr('old_val', number);
						var contnet = '<input type="number"  min="1" max="100" id="food_ready_edit_how_many" value="' + number +
							'" style="width:90%;text-align:center;font:12px tahoma;padding:2px;">';
						$(this).html(contnet);
						$('#food_ready_edit_how_many').focus();
						$('#food_ready_edit_how_many').select();
						$('#food_ready_edit_how_many').focusout(function(){
							food_ready_edit_how_many_focusout($(this));
						});
					}
					//alert(list_id);	
				}
			}
	});
	
	function food_ready_edit_how_many_focusout(how_many_obj){
		var number = parseInt(how_many_obj.val());
		if(number)
		{
			if(number> 0 && number<100)
			{
				b1 = false;
				updateHowMany(false, how_many_obj, number)
			}
			else
				how_many_obj.parent().html(how_many_obj.parent().attr('old_val'));
		}else
			how_many_obj.parent().html(how_many_obj.parent().attr('old_val'));
	}
	
	function updateHowMany(obj, how_many_obj, number){
		respondUpdateHowMany = true;
		$("#loading4").fadeIn(0);
		
		$.ajax({
            url: baseUrl,
            type: 'POST',
            data: {
                'act': 'update_how_many',
				'user_meal_week_id': how_many_obj.parent().attr('list_id'),
				'number': number
            },
            success: function (d) {
                try {
                }
                catch (err) {
                    alert( d + "\r\n" + err);
                }
                finally {
					var da = $('#date_food_ready_day').val();
					var pa  = $('#date_food_ready_part').find('option:selected').val();
					getListUserPlace($('#food_list_detail_select').find('option:selected').attr('value'),da ,pa);
					respondUpdateHowMany = false;
					if(!enableDrag())
						$("#loading4").fadeOut('fast');
                }
            }
        });
		
	}
	
	$('body').on('click', '#print_ready', function () { 
		if(getdataprint == ""){
			var da = $('#date_food_ready_day').val();
			var pa  = $('#date_food_ready_part').find('option:selected').val();		
			getdataprint = '&place=' + $('#food_list_detail_select').find('option:selected').attr('value') + '&day=' + da + '&day_part=' + pa;
		}
		var win = window.open(baseUrl + "?r=" + Math.random() + "&act=print2" + getdataprint, '_blank');
		if(win){
			//Browser has allowed it to be opened
			win.focus();
		}else{
			//Broswer has blocked it
			alert('Please allow popups for this site');
		}
	});
	
	$('body').on('click', '#li-food-tabs-5', function () {
		if(firsttime_ready){
			firsttime_ready = false;
			getStatusReady();
		}
	});
	
	$('body').on('click', '#food_list_detail_btn', function () {
		var da = $('#date_food_ready_day').val();
		var pa  = $('#date_food_ready_part').find('option:selected').val();
		getListUserPlace($('#food_list_detail_select').find('option:selected').attr('value'),da ,pa);
	});

	var autoload = $('#food_list_detail').attr('auto');
	if(autoload){
		//alert('sfdf');
		baseUrl = "";
		parentUrl = "../../../"
		var da = $('#food_list_detail').attr('day');
		var pa = $('#food_list_detail').attr('part');
		getListUserPlace($('#food_list_detail_select').find('option:selected').attr('value'),da,pa);
	}
});