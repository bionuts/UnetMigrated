$(function () {
	var vahdecopydrag = null;
	var obj_dragdrop = null;
    var listTablecloth = {};
	var timeUpdateTablecloth = 1;
	var listMealInWeek = {};
	var timeUpdateMealInWeek = 0;
	var lastIdListMealInWeek = 0;
	var countUpdateMealInWeek = 0;
	var afterdrag = false;
	var afterdragContent = '';
	var baseUrl = "apps/food/ajax/ajaxfood.php";
	var waitdrop = false;
	var waitdropthis;
	var currentWeek = 0;
	var respondGetListTablecloth = false;
	var respondCreateMealInWeek = false;
	var respondMoveMealInWeek = false;
	var respondDeleteMealInWeek = false;
	var respondLoadListMealInWeek = false;
	var respondLoadNextListMealInWeek = false;
	var dateGetListTablecloth = 0;

	function getListTablecloth(id){
		var dt = new Date();
		if(dt.getTime() < dateGetListTablecloth + 10000)
			return;
		respondGetListTablecloth = true;
		$(".food_vade_items").draggable({ disabled: true });
		$(".fd_vade_hafte").draggable({ disabled: true });
		$("#loading1").fadeIn(0);
		$("#loading1").find('span').html("در حال بارگذاری ...");
		$.ajax({
            url: baseUrl,
            type: 'POST',
            data: {
                'act': 'get_list_tablecloth',
                'time': timeUpdateTablecloth
            },
            success: function (d) {
                try {
					//alert(d);
					var find = ',';
					var re = new RegExp(find, 'g');
					var dt = new Date();
					dateGetListTablecloth = dt.getTime();
					if(!id)
						loadListMealInWeek();
					var obj = JSON.parse(d);
                    var i;
					var changeListTablecloth = false;
					var countent = "";
					for (i in obj) {
						if(obj[i].id){
							if(id && id==obj[i].id && listTablecloth[obj[i].id].items == obj[i].items  && obj[i].isvis == 1)
								afterdrag = true;
							else
								afterdrag = false;
							changeListTablecloth = true;
							listTablecloth[obj[i].id] = {};
							listTablecloth[obj[i].id].id = obj[i].id;
							listTablecloth[obj[i].id].items = obj[i].items;
							listTablecloth[obj[i].id].isvis = obj[i].isvis;
							listTablecloth[obj[i].id].price = obj[i].price;
						}
					}
					if(changeListTablecloth){
						//alert('change');
						for (i in listTablecloth) {
							if(listTablecloth[i] && listTablecloth[i].isvis == 1){

								var items = listTablecloth[i].items.replace(re, '<br/>');
								countent += '<div idtablecloth="'+listTablecloth[i].id+'" id="tablecloth_'+listTablecloth[i].id+'" class="food_vade_items box_sized radius5">'
											+'<img class="food_img_vade_hico" src="img/vade.png"/>'
											+'<p style="margin: 0;padding: 0px;">'
											+ items
											+'</p>'
											+ '<div class="pricemeal" style="color: red; text-align: left;">' + listTablecloth[i].price + ' تومان'
											+'</div></div>';
							}
						}
						if(!id){
							$("#ListTablecloth").html(countent);
							dragfood_vade_items();
						}else{
							if(afterdrag){
								afterdragContent = countent;
							}else{
								$("#ListTablecloth").html(countent);
								dragfood_vade_items();
							}
						}
						timeUpdateTablecloth = obj.time;
					}
                }
                catch (err) {
                    alert( d + "\r\n" + err);
                }
                finally {
					respondGetListTablecloth = false;
					if(waitdrop){
						waitdrop = false;
						dropAll(waitdropthis, false);
					}
					if(!enableDrag())
						$("#loading1").fadeOut('fast');
                }
            }
        });
	}
	
	function dragfood_vade_items() {
		$(".food_vade_items").draggable({
			cursor: 'move',
			helper: "clone",
			revert: "invalid",
			start: function () {	
				getListTablecloth($(this).attr('idtablecloth'));
				vahdecopydrag = $(this);
			}
		});	
		if(enableDrag()) {
			$(".food_vade_items").draggable({ disabled: true });
			$(".fd_vade_hafte").draggable({ disabled: true });
		}
	}
		
	function dropAll(e, f) {
		if (vahdecopydrag != null) {
			if(f) {
				var regex = /<br\s*[\/]?>/gi;
				var divtmp = '<div idmealweak="temp" style="opacity: 0.5;" class="box_sized fd_vade_hafte ui-draggable ui-draggable-handle"><div class="box_sized fd_dv_clz_plf radius5">x</div>';
				divtmp += vahdecopydrag.find('p').html();
				divtmp += '</div>';
				divtmp = divtmp.replace(regex, "،");
				$(e).append(divtmp);
			}
			if(respondGetListTablecloth){	
				waitdrop = true;
				waitdropthis = e;
			} else {
				createMealInWeek(e, false);
			}			
		}
		else if (obj_dragdrop != null) {
			obj_dragdrop.removeAttr('style');
			var idmeal = obj_dragdrop.parent().attr('idmeal');
			var id = obj_dragdrop.attr('idmealweak');
			$(e).append(obj_dragdrop);
			
			if($(e).attr("idmeal") != idmeal){
				obj_dragdrop.css("opacity","0.5");
				moveMealInWeek(e, id);
			}
		}
		else
		{
			alert('ali');
		}
	}
	
	function createMealInWeek(e){
		respondCreateMealInWeek = true;
		$(".food_vade_items").draggable({ disabled: true });
		$(".fd_vade_hafte").draggable({ disabled: true });
		$("#loading1").fadeIn(0);
		$("#loading1").find('span').html("درحال ذخیره ...");
		$.ajax({
            url: baseUrl,
            type: 'POST',
            data: {
                'act': 'create_meal_weeks',
                'week': currentWeek,
				'day_part': $(e).attr('idmeal'),
				'meal_id': vahdecopydrag.attr('idtablecloth'),
				'time': timeUpdateMealInWeek,
				'lastid': lastIdListMealInWeek,
				'count': countUpdateMealInWeek
            },
            success: function (d) {
                try {
					//alert(""+ week + "\n" +  dayPart + "\n" +  mealId);
					//$("#mealweaktemp").css("opacity","1");
					//$("#mealweaktemp").attr("id","mealweaktemp2");
					var obj = JSON.parse(d);
					getListMealInWeek(obj);
					vahdecopydrag = null;
					if(afterdrag){
						afterdrag = false;
						$("#ListTablecloth").html(afterdragContent);
						afterdragContent = '';
						dragfood_vade_items();
					}
					
					timeUpdateMealInWeek = obj.time;
					lastIdListMealInWeek =  obj.lastid;
					countUpdateMealInWeek =  obj.count;
                }
                catch (err) {
                    alert( d + "\r\n" + err);
                }
                finally {
					respondCreateMealInWeek = false;
					if(!enableDrag())
						$("#loading1").fadeOut('fast');
                }
            }
        });
	}
	
	function moveMealInWeek(e ,id){
		respondMoveMealInWeek = true;
		$(".food_vade_items").draggable({ disabled: true });
		$(".fd_vade_hafte").draggable({ disabled: true });
		$("#loading1").fadeIn(0);
		$("#loading1").find('span').html("درحال ذخیره ...");
		$.ajax({
            url: baseUrl,
            type: 'POST',
            data: {
                'act': 'move_meal_weeks',
                'week': currentWeek,
				'day_part': $(e).attr('idmeal'),
				'meal_id': listMealInWeek[id].meal_id,
				'meal_week_id': id,
				'time': timeUpdateMealInWeek,
				'lastid': lastIdListMealInWeek,
				'count': countUpdateMealInWeek
            },
            success: function (d) {
                try {
					var obj = JSON.parse(d);
					getListMealInWeek(obj);
					timeUpdateMealInWeek = obj.time;
					lastIdListMealInWeek =  obj.lastid;
					countUpdateMealInWeek =  obj.count;					
                }
                catch (err) {
                    alert( d + "\r\n" + err);
                }
                finally {
					respondMoveMealInWeek = false;
					if(!enableDrag())
						$("#loading1").fadeOut('fast');
                }
            }
        });
	}
	
	function deleteMealInWeek(id){
		//countUpdateMealInWeek--;
		$(".food_vade_items").draggable({ disabled: true });
		$(".fd_vade_hafte").draggable({ disabled: true });
		respondDeleteMealInWeek = true;
		$("#loading1").fadeIn(0);
		$("#loading1").find('span').html("درحال ذخیره ...");
		$.ajax({
            url: baseUrl,
            type: 'POST',
            data: {
                'act': 'delete_meal_weeks',
                'meal_week_id': id,
				'week': currentWeek,
				'time': timeUpdateMealInWeek,
				'lastid': lastIdListMealInWeek,
				'count': countUpdateMealInWeek
            },
            success: function (d) {
                try {
					var obj = JSON.parse(d);
					getListMealInWeek(obj);
					timeUpdateMealInWeek = obj.time;
					lastIdListMealInWeek =  obj.lastid;
					countUpdateMealInWeek =  obj.count;	
                }
                catch (err) {
                    alert( d + "\r\n" + err);
                }
                finally {
					respondDeleteMealInWeek = false;
					if(!enableDrag())
						$("#loading1").fadeOut('fast');
                }
            }
        });
	}
	
	function loadListMealInWeek(){
		$(".food_vade_items").draggable({ disabled: true });
		$(".fd_vade_hafte").draggable({ disabled: true });
		respondLoadListMealInWeek = true;
		$("#loading1").fadeIn(0);
		$("#loading1").find('span').html("در حال بارگذاری ...");
		$.ajax({
            url: baseUrl,
            type: 'POST',
            data: {
                'act': 'get_list_meal_weeks',
                'week': currentWeek
            },
            success: function (d) {
                try {
					var obj = JSON.parse(d);
					getListMealInWeek(obj);
					timeUpdateMealInWeek = obj.time;
					lastIdListMealInWeek =  obj.lastid;
					countUpdateMealInWeek =  obj.count;	
                }
                catch (err) {
                    alert( d + "\r\n" + err);
                }
                finally {
					respondLoadListMealInWeek = false;
					if(!enableDrag())
						$("#loading1").fadeOut('fast');
                }
            }
        });
	}
	
	function getListMealInWeek(obj) {
		var find = ',';
		var re = new RegExp(find, 'g');
		var regex = /<br\s*[\/]?>/gi;
		var i;
		var countent = "";
		if(typeof obj.lastid != 'undefined')
			if(lastIdListMealInWeek < obj.lastid || countUpdateMealInWeek != obj.count) 
				listMealInWeek = {};
			
		for (i in obj) {
			if(typeof obj[i].id != 'undefined'){
				listMealInWeek[obj[i].id] = {};
				listMealInWeek[obj[i].id].id = obj[i].id;
				listMealInWeek[obj[i].id].week = obj[i].week;
				listMealInWeek[obj[i].id].day_part = obj[i].day_part;	
				listMealInWeek[obj[i].id].meal_id = obj[i].meal_id;	
			}
		}
		
		for(i = 0; i<21; i++)
		{
			$("#meal_" + i).html("");
		}
		
		for (i in listMealInWeek) {
			if(listMealInWeek[i]){
				if(listMealInWeek[i].week == currentWeek){
					if(listTablecloth[listMealInWeek[i].meal_id] && listTablecloth[listMealInWeek[i].meal_id].isvis == 1)
					{
						var divtmp = '<div idmealweak="' + listMealInWeek[i].id + '" class="box_sized fd_vade_hafte ui-draggable ui-draggable-handle"><div class="box_sized fd_dv_clz_plf radius5">x</div>';
						divtmp += listTablecloth[listMealInWeek[i].meal_id].items;//.replace(re, '<br/>');
						divtmp += '<div style="color: red; text-align: center;">' + listTablecloth[listMealInWeek[i].meal_id].price + ' تومان';
						divtmp += '</div></div>';
						//divtmp = divtmp.replace(regex, "،");
						$("#meal_"+listMealInWeek[i].day_part).append(divtmp);
					}
				}
			}
		}
		
		$('.fd_vade_hafte').draggable({
			cursor: 'move',
			revert: "invalid",
			start: function () {
				obj_dragdrop = $(this);
			}
		});
	}
	
	function enableDrag() {
		if(respondGetListTablecloth)
			return true;
		if(respondLoadListMealInWeek)
			return true;
		if(respondCreateMealInWeek)
			return true;
		if(respondMoveMealInWeek)
			return true;
		if(respondDeleteMealInWeek)
			return true;
		if(respondLoadNextListMealInWeek)
			return true;
			
		$(".food_vade_items").draggable({ disabled: false });
		$(".fd_vade_hafte").draggable({ disabled: false });
		return false;
	}

	function loadNextListMealInWeek(nxt){
		$(".food_vade_items").draggable({ disabled: true });
		$(".fd_vade_hafte").draggable({ disabled: true });
		respondLoadNextListMealInWeek = true;
		$("#loading1").fadeIn(0);
		$("#loading1").find('span').html("در حال بارگذاری ...");
		$.ajax({
            url: baseUrl,
            type: 'POST',
            data: {
                'act': 'get_next_list_meal_weeks',
                'week': currentWeek, 
				'next': nxt
            },
            success: function (d) {
                try {
					var obj = JSON.parse(d);
					currentWeek = obj.week;
					//alert(currentWeek);
					switch(currentWeek){
						case 0:
							$('#haftehid1').html('هفته اول');
							break;
						case 1:
							$('#haftehid1').html('هفته دوم');
							break;
						case 2:
							$('#haftehid1').html('هفته سوم');
							break;
						default:
							$('#haftehid1').html('هفته' +' '+ (currentWeek + 1));
					}
					getListMealInWeek(obj);
					timeUpdateMealInWeek = obj.time;
					lastIdListMealInWeek =  obj.lastid;
					countUpdateMealInWeek =  obj.count;	
                }
                catch (err) {
                    alert( d + "\r\n" + err);
                }
                finally {
					respondLoadNextListMealInWeek = false;
					if(!enableDrag())
						$("#loading1").fadeOut('fast');
                }
            }
        });
	}
	
	$('body').on('click', '#preImage1', function () {
		if(!enableDrag()) {
			loadNextListMealInWeek(0);
		}
	});
	

	$('body').on('click', '#nextImage1', function () {
		if(!enableDrag()) {
			loadNextListMealInWeek(1);
		}
	});
	
	$('body').on('click', '.fd_dv_clz_plf', function () { 
		if(!enableDrag()) {
			$(this).parent().css({opacity: '0.5'});
			deleteMealInWeek($(this).parent().attr('idmealweak'));
		}
		//$(this).parent().animate({opacity: '0.5'});
    });
	
	$('body').on('click', '#li-food-tabs-3', function () {
		$(".fd_drop_vade").droppable({
			accept: '.fd_vade_hafte,.food_vade_items',
			hoverClass: 'whenwantdrop',
			drop: function () {
				dropAll(this, true);
			}
		});
		if(firsttime_hafte_edit){
			vahdecopydrag = null;
			obj_dragdrop = null;
			listTablecloth = {};
			timeUpdateTablecloth = 1;
			listMealInWeek = {};
			timeUpdateMealInWeek = 0;
			lastIdListMealInWeek = 0;
			countUpdateMealInWeek = 0;
			afterdrag = false;
			afterdragContent = '';
			waitdrop = false;
			waitdropthis;
			currentWeek = 0;
			respondGetListTablecloth = false;
			respondCreateMealInWeek = false;
			respondMoveMealInWeek = false;
			respondDeleteMealInWeek = false;
			respondLoadListMealInWeek = false;
			respondLoadNextListMealInWeek = false;
			dateGetListTablecloth = 0;
		}
		firsttime_hafte_edit = false;
		getListTablecloth();
	});
	
});