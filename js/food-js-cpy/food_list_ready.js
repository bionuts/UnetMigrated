$(function () {

	var respondGetListUserPlace = false;
	var baseUrl = "apps/food/ajax/ajaxfood.php";
	var listPlace = {};
	var respondGetStatus = false;
	var list = [];

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
					
					for (i in obj) {
						if(obj[i].user_id){
							var tmpobj = {};
							tmpobj.user_id = obj[i].user_id;
							tmpobj.users_lname = obj[i].users_lname;
							tmpobj.users_fname = obj[i].users_fname;
							tmpobj.items = obj[i].items;
							tmpobj.how_many = obj[i].how_many;
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
							+ '<td style="width:30%;">نام خانوادگی</td>'
							+ '<td style="width:30%;">نام</td>'
							+ '<td style="width:30%;">نوع غذا</td>'
							+ '<td style="width:10%;">تعداد</td>'
							+ '</tr>';
						for (i in list) {
							tbl += '<tr>'
								+ '<td>'+ list[i].users_lname +'</td>'
								+ '<td>'+ list[i].users_fname +'</td>'
								+ '<td>'+ list[i].items +'</td>'
								+ '<td>'+ list[i].how_many +'</td>'
								+ '</tr>';
						}
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
		return false;
	}
	
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
});