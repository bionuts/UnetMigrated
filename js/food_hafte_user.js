$(function () {
	var permit = 0;
    var listTablecloth = {};
    var timeUpdateTablecloth = 1;
    var listplace = {};
    var listMealInWeek = {};
    var listMealInWeekParts = {};
    var timeUpdateMealInWeek = 0;
    var lastIdListMealInWeek = 0;
    var countUpdateMealInWeek = 0;
    var listUserSelectedMealInWeek = {};
    var afterdrag = false;
    var afterdragContent = '';
    var baseUrl = "apps/food/ajax/ajaxfood.php";
    var waitdrop = false;
    var waitdropthis;
    var currentWeek = 0;
    var oldWeek = 0;
    var respondGetListTablecloth = false;
    var respondLoadListMealInWeek = false;
    var respondgetUserSelectedFood = false;
    var respondSaveListMealInWeek = false;
    var changeData = false;
    var user = 0;
    var todayPersian;
    var todayInt;
    var todayDays;
	var iscanreserve = {};
	var user_vade_obj;
    var daypass = 0;
    var refresh = true;
    var cdayweek;
    var cweek;
    var selctionDay;
	var showmoney;
   

    var saturdayInt;//int forign  date
    var saturdayDays;
    var lasttimeSelectDays;

    function getListTablecloth(f) {
        firsttime_hafte_user = false;
        respondGetListTablecloth = true;
        $("#loading2").fadeIn(0);
        $("#loading2").find('span').html("در حال بارگذاری ...");

        var data = {
            'act': 'get_list_tablecloth_user',
            'time': timeUpdateTablecloth
        }
        if (f) {
            var dt = new Date();
            data.ctime = dt.getTime();
        }
        //alert(data.ctime);
        //alert(data.act);
        $.ajax({
            url: baseUrl,
            type: 'POST',
            data: data,
            success: function (d) {
                try {
                    //alert('data: ' + d);
                    var obj = JSON.parse(d);
                    var i;
                    var countent = "";

                    for (i in obj) {
                        if (obj[i].id) {
                            if (obj[i].items) {
                                changeData = true;
                                listTablecloth[obj[i].id] = {};
                                listTablecloth[obj[i].id].id = obj[i].id;
                                listTablecloth[obj[i].id].items = obj[i].items;
                                listTablecloth[obj[i].id].isvis = obj[i].isvis;
                                listTablecloth[obj[i].id].price = obj[i].price;
                                listTablecloth[obj[i].id].time = parseInt(obj[i].t1);
                                if (listTablecloth[obj[i].id].time < parseInt(obj[i].t2))
                                    listTablecloth[obj[i].id].time = parseInt(obj[i].t2);
                            }
                            if (obj[i].place) {
                                listplace[obj[i].id] = {};
                                listplace[obj[i].id].id = obj[i].id;
                                listplace[obj[i].id].place = obj[i].place;
                            }
                        }
                    }
                    var select_obj = $('#selectPlace').find('select');
                    select_obj.html("");

                    for (i in listplace) {
                        select_obj.append('<option value="' + listplace[i].id + '">' + listplace[i].place + '</option>');
                    }
                    timeUpdateTablecloth = obj.time;
                    if (f) {
                        currentWeek = obj.cweek;						
                        todayPersian = obj.ctime;
                        todayInt = obj.time * 1000;//no use
                        todayDays = shamsi_to_milady_days(todayPersian);
						$('#date_food_report_day').val(todayPersian);
						$('#date_food_ready_day').val(todayPersian);
                        cweek = obj.cweek;

						showmoney = obj.showmoney;
                        cdayweek = obj.cdayweek;
                        saturdayDays = todayDays - cdayweek;
                        saturdayInt = saturdayDays * 86400000;
                        selctionDay = obj.selction;						                       
                        lasttimeSelectDays = todayDays + selctionDay;


                        loadListMealInWeek();
                    }
                    else {
						showmoney = obj.showmoney;
                        if (todayPersian != obj.ctime) {
                            todayPersian = obj.ctime;
                            todayInt = timeUpdateTablecloth * 1000;
                            todayDays = shamsi_to_milady_days(todayPersian);
							lasttimeSelectDays = todayDays + selctionDay;
                        }
                        if (obj.cweek && cweek != obj.cweek)cweek = obj.cweek;
                        if (obj.cdayweek && cdayweek != obj.cdayweek) {
                            cdayweek = obj.cdayweek;
                            saturdayDays = todayDays - cdayweek;
                            saturdayInt = saturdayDays * 86400000;
                        }

                        if (obj.selction && obj.selction != selctionDay) {
                            selctionDay = obj.selction;
                            lasttimeSelectDays = todayDays + selctionDay;
                        }
                        loadListMealInWeek();
                    }

                }
                catch (err) {
                    alert('getListTablecloth ' + d + "\r\n" + err);
                }
                finally {
                    respondGetListTablecloth = false;
                    if (!enableDrag())
                        $("#loading2").fadeOut('fast');
                }
            }
        });
    }

    function loadListMealInWeek() {
        respondLoadListMealInWeek = true;
        $("#loading2").fadeIn(0);
        $("#loading2").find('span').html("در حال بارگذاری ...");
        var data = {
            'act': 'get_list_meal_weeks',
            'week': currentWeek
        };
        if (currentWeek == oldWeek) {
            data.lastid = lastIdListMealInWeek;
            data.count = countUpdateMealInWeek;
            data.time = timeUpdateMealInWeek;
        } else {
            //alert('ali');
        }
        refresh = false;
        $.ajax({
            url: baseUrl,
            type: 'POST',
            data: data,
            success: function (d) {
                try {
                    if (currentWeek != oldWeek) {
                        listMealInWeek = {};
                        listMealInWeekParts = {};
                        //alert(d);
                    }
                    oldWeek = currentWeek;
                    var obj = JSON.parse(d);

                    getListMealInWeek(obj);
                    timeUpdateMealInWeek = obj.time;
                    lastIdListMealInWeek = obj.lastid;
                    countUpdateMealInWeek = obj.count;
                }
                catch (err) {
                    alert('loadListMealInWeek ' + d + "\r\n" + err);
                }
                finally {
                    respondLoadListMealInWeek = false;
                    if (!enableDrag())
                        $("#loading2").fadeOut('fast');
                }
            }
        });
    }

    function getListMealInWeek(obj) {
        var i;
        var countent = "";
        if (obj.lastid)
            if (lastIdListMealInWeek < obj.lastid || countUpdateMealInWeek != obj.count) {
                listMealInWeek = {};
                listMealInWeekParts = {};
            }
        for (i in obj) {
            if (obj[i].id) {
                listMealInWeek[obj[i].id] = {};
                listMealInWeek[obj[i].id].id = obj[i].id;
                listMealInWeek[obj[i].id].week = obj[i].week;
                listMealInWeek[obj[i].id].day_part = obj[i].day_part;
                listMealInWeek[obj[i].id].meal_id = obj[i].meal_id;
                if (!listMealInWeekParts[obj[i].day_part]) {
                    //alert("1");
                    listMealInWeekParts[obj[i].day_part] = [listMealInWeek[obj[i].id]];
                } else {
                    //alert("2");
                    listMealInWeekParts[obj[i].day_part].push(listMealInWeek[obj[i].id]);
                }
            }
        }
        getUserSelectedFood();
    }

    function getUserSelectedFood() {
        respondgetUserSelectedFood = true;
        $("#loading2").fadeIn(0);
        $("#loading2").find('span').html("در حال بارگذاری ...");
        $.ajax({
            url: baseUrl,
            type: 'POST',
            data: {
                'act': 'get_selected_food_user',
                'user': user,
                'saturday': (saturdayDays + daypass)
            },
            success: function (d) {
                try {
                    //alert(saturdayInt);
                    //var ddt = new Date(saturdayInt);
                    //alert(ddt);
                    var obj = JSON.parse(d);
                    //alert('data: ' + d);
                    var i;
                    listUserSelectedMealInWeek = {};
					iscanreserve = {};
                    for (i in obj) {
						if(obj[i].code){
							iscanreserve[obj[i].code] = {};
							iscanreserve[obj[i].code].id = obj[i].code;
						} else if (obj[i].id) {
                            listUserSelectedMealInWeek[obj[i].id] = {};
                            listUserSelectedMealInWeek[obj[i].id].id = obj[i].id;
                            listUserSelectedMealInWeek[obj[i].id].how_many = obj[i].how_many;
                            listUserSelectedMealInWeek[obj[i].id].placeid = obj[i].placeid;
                            listUserSelectedMealInWeek[obj[i].id].status = obj[i].status;
                            listUserSelectedMealInWeek[obj[i].id].extra = obj[i].ext;
							listUserSelectedMealInWeek[obj[i].id].time = obj[i].timeupdate;
                        }
                    }
                    //alert('ddfdf');
                    loadTable();
                    respondgetUserSelectedFood = false;
                    if (!enableDrag())
                        $("#loading2").fadeOut('fast');
                }
                catch (err) {
                    alert('getUserSelectedFood ' + d + "\r\n" + err);
                }
                finally {
                    respondgetUserSelectedFood = false;
                    if (!enableDrag())
                        $("#loading2").fadeOut('fast');
                }
            }
        });
    }

    function saveListMealInWeek(jsonData, placeid, extra, dt) {
        //alert(jsonData);
        respondSaveListMealInWeek = true;
        $("#loading2").fadeIn(0);
        $("#loading2").find('span').html("درحال ذخیره ...");
        $.ajax({
            url: baseUrl,
            type: 'POST',
            data: {
                'act': 'save_list_meal_weeks',
                'user': user,
                'placeid': placeid,
				'extra': extra,
                'json': jsonData,
                'saturday': (saturdayDays + daypass),
				'permit': permit,
				'dt': dt
            },
            success: function (d) {
                try {
                    if(d =="dont save") 
						alert("مهلت رزو غذا در این تاریخ به اتمام رسیده");
					else if (d==""){
						
					}else{
						alert('saveListMealInWeek ' + d + "\r\n" + err);
					}
                    getListTablecloth();
                }
                catch (err) {
                    alert('saveListMealInWeek ' + d + "\r\n" + err);
                }
                finally {
                    respondSaveListMealInWeek = false;
                    if (!enableDrag())
                        $("#loading2").fadeOut('fast');
                }
            }
        });
    }

    function oldfood(i, j,tday) {
        try {
            var content = '';
			var HaveContent = false;
            for (k in listMealInWeekParts[(i - daypass) * 3 + j]) {
                var tmp = listMealInWeekParts[(i - daypass) * 3 + j][k];
                var tmpb = false;
				
				var vote = '';
				var numberfood = '';
				var stringfd_selgreenuser = ' fd_vade_hafte_users_eat';
                if (listTablecloth[tmp.meal_id] && listTablecloth[tmp.meal_id].isvis == 1) {
					var items = listTablecloth[tmp.meal_id].items;//.replace(re, '<br/>');
                        //items = items.replace(regex, "،");
                    if (listUserSelectedMealInWeek) {
                        if (listUserSelectedMealInWeek[tmp.id]) {
                            if (listUserSelectedMealInWeek[tmp.id].how_many > 0 &&
                                listUserSelectedMealInWeek[tmp.id].time > listTablecloth[tmp.meal_id].time) {
                                tmpb = true;
								if(listUserSelectedMealInWeek[tmp.id].status == 1 ){
									vote ='<div class="box_sized fd_dv_cmnt_plf radius5"><img title="نظر" src="img/fd_cmnt.png"/></div>';
								}/*else if(listUserSelectedMealInWeek[tmp.id].status == 0 ){
									if (permit == 1){
										numberfood = '  <div style="display:inline;color: green; text-align: center;">' 
										+ listUserSelectedMealInWeek[tmp.id].how_many +'</div>';
									}
									stringfd_selgreenuser = ' fd_vade_hafte_users_noeat';
									items = '<div style="color: blue; text-align: center;">در دست اقدام(' + numberfood +' دست)</div>' + items;
									
								}*/
                            } else {
                                //how_many != 1
                            }
                        }
                    }
                    if (tmpb) {
                        HaveContent = true;
                        content += '<div class="box_sized' + stringfd_selgreenuser + ' radius5">'
                        + vote
                        + '<div class="box_sized fd_dv_chkbx_plf radius5"><img style="display:block;"'
                        + 'src="img/chkfooduser.png"/></div>' + items
                        + '</div>';
                    }else{
						/*if(tday && !HaveContent){
							content += '<div class="box_sized radius5" style="background-color: #ffd8d8;border:1px dashed #fdb5b5;color:red;">'
								+ '<div class="box_sized fd_dv_chkbx_plf radius5"><img style="display:block;"'
								+ 'src="img/chkfooduser.png"/></div> مهلت انتخاب غذا به اتمام رسیده'
								+ '</div>';
						}*/
						if(!HaveContent){
							//HaveContent = true;
							content += '<div class="box_sized fd_vade_hafte_users_eat radius5 timeout_hafte_users_food">'
							+ '<div class="box_sized fd_dv_chkbx_plf radius5"><img style="display:block;"'
							+ 'src="img/chkfooduser.png"/></div>' + items
							+ '</div>';
						}
					}
                    /*else {
                     if (listUserSelectedMealInWeek[tmp.id]) {
                     if (listUserSelectedMealInWeek[tmp.id].how_many == 1) {
                     //alert(listUserSelectedMealInWeek[tmp.id].time + '  ' + listTablecloth[tmp.meal_id].time);
                     }
                     }
                     }*/
                }
            }
            $("#meal2_" + ((i - daypass) * 3 + j)).html(content);
        }
        catch (err) {
            $("#meal2_" + ((i - daypass) * 3 + j)).html('');
            alert('oldfood: ' + err);
        }

    }

    function loadTable() {
        try {
            var find = ',';
            var re = new RegExp(find, 'g');
            var regex = /<br\s*[\/]?>/gi;
            var sdayint = saturdayInt;
            //alert('956 ' + sdayint);
            var sdays = saturdayDays;
            var i = 0;
            //alert(todayDays);
            for (i = daypass; i < daypass + 7; i++) {
                var persianDate = getPersianDate(sdayint + i * 86400000);
                $('#day' + (i - daypass)).html(persianDate);
                if (persianDate == todayPersian)
                    $('#day' + (i - daypass)).parent().css('background-color', '#A2A2A2');
                else
                    $('#day' + (i - daypass)).parent().css('background-color', '#515151');
                if (sdays + i < todayDays) {
                    var j;
                    for (j = 0; j < 3; j++) {
                        oldfood(i, j);
                    }
                    //oldselected food
                    //alert("oldselected  " + persianDate);

                } else if (sdays + i < lasttimeSelectDays) {
                    var j;
                    for (j = 0; j < 3; j++) {

                        var content = '';
                        var k = 0;
						var t1 = (sdays + i)*3 +j;
						var t2 = true;
						if(iscanreserve[t1]){
							if(iscanreserve[t1].id){
								t2 = false;
							}
						}
                        if (sdays + i >= todayDays && t2) {
                            try {
                                for (k in listMealInWeekParts[(i - daypass) * 3 + j]) {
                                    var tmp = listMealInWeekParts[(i - daypass) * 3 + j][k];
                                    var stringfd_selgreenuser = '';
                                    var stringDisplayBlock = '';
                                    var location = '';
                                    var placeidstring = '';
									var numberfood = '';
                                    if (listTablecloth[tmp.meal_id] && listTablecloth[tmp.meal_id].isvis == 1) {
                                        if (listUserSelectedMealInWeek) {
                                            if (listUserSelectedMealInWeek[tmp.id]) {
                                                if (listUserSelectedMealInWeek[tmp.id]) {
                                                    if (listUserSelectedMealInWeek[tmp.id].how_many > 0) {
                                                        stringfd_selgreenuser = ' fd_selgreenuser';
                                                        stringDisplayBlock = ' style="display:block;"';
                                                        location = '<div style="color: blue; text-align: center;">'
                                                        + listplace[listUserSelectedMealInWeek[tmp.id].placeid].place
                                                        + '</div>';
														if (permit == 1){
															numberfood = '<div style="color: green; text-align: center;" class="how_many">' 
															+ listUserSelectedMealInWeek[tmp.id].how_many +'</div>';
														}
                                                    } else {
                                                        //how_many != 1
                                                    }
                                                }
                                                placeidstring = ' placeid="'+  listUserSelectedMealInWeek[tmp.id].placeid + '"';
                                            }
                                        }
                                        var items = listTablecloth[tmp.meal_id].items;//.replace(re, '<br/>');
                                        //items = items.replace(regex, "،");
                                        content += '<div'+placeidstring +' idparts="' + tmp.id + '" '
											+ 'class="box_sized fd_vade_hafte_users radius5'
											+ stringfd_selgreenuser + '">'
											+ '<div class="box_sized fd_dv_chkbx_plf radius5"' + stringDisplayBlock
											+ '><img style="display:block;"'
											+ 'src="img/chkfooduser.png"/>' + numberfood + '</div>' + location + items;
                                        if(showmoney == 1)
											content += '<div style="color: red; text-align: center;">' + listTablecloth[tmp.meal_id].price + ' تومان' + '</div>';
										content += '</div>';
                                    }
                                }
                                $("#meal2_" + ((i - daypass) * 3 + j)).html(content);
                            }
                            catch (err) {
                                $("#meal2_" + ((i - daypass) * 3 + j)).html('');
                                alert('loadTable 1: ' + err);
                            }

                        }
                        else {
							if(!t2)
								oldfood(i, j,true);
							else 
								oldfood(i, j,false);
                        }
                    }
                } else {
                    for (j = 0; j < 3; j++) {
                        var content = '';
                        $("#meal2_" + ((i - daypass) * 3 + j)).html(content);
                    }
                    //alert("" + (sdays + i) + "  " + lasttimeDays + "  " + lasttimeSelectDays);
                }
            }

            switch (currentWeek) {
                case 0:
                    $('#haftehid2').html('هفته اول');
                    break;
                case 1:
                    $('#haftehid2').html('هفته دوم');
                    break;
                case 2:
                    $('#haftehid2').html('هفته سوم');
                    break;
                default:
                    $('#haftehid2').html('هفته' + ' ' + (currentWeek + 1));
            }
        }
        catch (err) {
            var i;
            for (i = 0; i < 7; i++) {
                var j;
                for (j = 0; j < 3; j++) {
                    var content = '';
                    var k = 0;
                    if (j > lastpart) {
                        $("#meal2_" + (i * 3 + j)).html(content);
                    }
                }
            }
            alert('loadTable base: ' + err);
        }
    }

    function enableDrag() {
        if (respondGetListTablecloth)
            return true;
        if (respondLoadListMealInWeek)
            return true;
        if (respondgetUserSelectedFood)
            return true;
        if (respondSaveListMealInWeek)
            return true;
        return false;
    }

    function loadNextListMealInWeek(nxt) {
        $(".food_vade_items").draggable({disabled: true});
        $(".fd_vade_hafte").draggable({disabled: true});
        respondLoadNextListMealInWeek = true;
        $("#loading2").fadeIn(0);
        $("#loading2").find('span').html("در حال بارگذاری ...");
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
                    if (nxt == 1) {
                        daypass += 7;
                    }
                    else {
                        daypass -= 7;
                    }
                    refresh = true;
                    getListTablecloth();
                    timeUpdateMealInWeek = obj.time;
                    lastIdListMealInWeek = obj.lastid;
                    countUpdateMealInWeek = obj.count;
                }
                catch (err) {
                    alert('loadNextListMealInWeek ' + d + "\r\n" + err);
                }
                finally {
                    respondLoadNextListMealInWeek = false;
                    if (!enableDrag())
                        $("#loading2").fadeOut('fast');
                }
            }
        });
    }

	$('body').on('click', '#preImage2', function () {
        if (!enableDrag()) {
            loadNextListMealInWeek(0);
        }
    });

	$('body').on('click', '#nextImage2', function () {
		//alert('');
        if (!enableDrag()) {
            loadNextListMealInWeek(1);
        }
    });

    //step 2
    function getPersianDate(intdate, days) {
        var s5 = 0;
        var s1 = 0;
        if (!days) {
            s1 = Math.floor((intdate + 12600000) / 86400000 + 2112);//12600000 = +3300 GMT // 86400000 = 1day //1343 1 1
        } else {
            s1 = days + 2112;
        }
        var s2 = s1 % 12053;
        s1 = s1 - s2;
        var Y33 = s1 / 12053;
        var s3 = s2 % 1461;
        s2 = s2 - s3;
        var Y4 = s2 / 1461;
        if (Y4 == 8) s5++;
        if (Y4 == 7 && s3 == 1460) {
            Y4 = 8;
            s3 = 0;
        }
        var s4 = s3 % 365;
        s3 = s3 - s4;
        var Y1 = s3 / 365;
        if (Y1 == 4) {
            Y1 = 3;
            s4 = 365;
        }
        s4 += s5;
        if (s4 > 185) {
            s4 = s4 - 186;
            var D = s4 % 30;
            s4 = s4 - D;
            var M = 6 + s4 / 30;
        } else {
            var D = s4 % 31;
            s4 = s4 - D;
            var M = s4 / 31;
        }
        return ("" + (1343 + Y33 * 33 + Y4 * 4 + Y1) + "/" + (M + 1) + "/" + (D + 1));
    }

    function shamsi_to_milady_days(str) {
        var tmp = str.split('/');
        var Y = parseInt(tmp[0]) - 1343;
        var s1 = Y % 33;
        Y -= s1;
        var days = Y / 33 * 12053;
        var s2 = Math.floor(s1 / 4);
        if (s2 > 7)
            s2 = 7;
        days += s1 * 365 + s2;
        var M = parseInt(tmp[1]);
        days += (M - 1) * 30;
        if (M > 6)
            days += 6;
        else
            days += (M - 1);
        D = parseInt(tmp[2]);
        days += D - 2113;
        return days;
    }

	$('body').on('click', '.timeout_hafte_users_food', function () {
		alert("مهلت رزو غذا در این تاریخ به اتمام رسیده");
	});
	
	
    $('body').on('click', '#btnSelectPlace', function () {
		var num = parseInt($('#food_teddad_vahde_res').val());		
		if($('#food_teddad_vahde_reason').val().trim().length > 0 || num < 2 || permit == 0)
		{
			if (!enableDrag()) {
				$("#selectPlace").fadeOut('fast',function(){
					 clickvade(user_vade_obj);
				});
			}
		}
		else
		{
			alert('بدلیل سفارش بیش از یک غذا ، الزاما نام مصرف کننده غذا را وارد کنید');
		}
    });
	
    $('body').on('click', '.fd_vade_hafte_users', function () {
        if (!enableDrag()) {
            user_vade_obj = $(this);
			var daystring = user_vade_obj.parent().parent().find("td:first").html().split('<')[0];
			var daysel = user_vade_obj.parent().parent().find("td:first").find('span:first').html();
			var idpartsCilcked = user_vade_obj.attr('idparts');
			var title = "<span>" +  "" + listTablecloth[listMealInWeek[idpartsCilcked].meal_id].items + " - " + daysel;
			title += '</span><div id="food_close_resdls"'
				+ 'style="position:absolute;left:14px;top:14px;'
				+ 'background:url(\'img/1420322024_close_delete.png\');width:12px;height:12px;"></div>'
			//alert(title);
			
            if (user_vade_obj.hasClass('fd_selgreenuser')) {
				if(permit == 0){
					clickvade(user_vade_obj);
				}
				else{
					var placeid = user_vade_obj.attr('placeid');
					$("#selectPlace").find('option').each(function(){
						if ($(this).attr('value') == placeid ){
							$(this).attr('selected','selected')
						}
					});
					$("#food_selectplace_title").html(title);
					$("#food_teddad_vahde_res").parent().parent().css('display','table-row');
					$("#food_teddad_vahde_res").val(user_vade_obj.find(".how_many").html());
					var num = parseInt(user_vade_obj.find(".how_many").html());
					if(num>1){
						$("#food_teddad_vahde_reason").parent().parent().css('display','table-row');
						$("#food_teddad_vahde_reason").val(listUserSelectedMealInWeek[idpartsCilcked].extra);
					}
					else{
						$("#food_teddad_vahde_reason").parent().parent().css('display','none');
					}
					//*********
					$("#selectPlace").fadeIn('fast',function(){$("#food_teddad_vahde_res").focus();$("#food_teddad_vahde_res").select();});
				}
            } else {
				
				var placeid = user_vade_obj.attr('placeid');
				$("#selectPlace").find('option').each(function(){
					if ($(this).attr('value') == placeid ){
						$(this).attr('selected','selected')
					}
				});
				if(permit == 0){
					$("#food_teddad_vahde_res").parent().parent().css('display','none');
					$("#food_teddad_vahde_reason").parent().parent().css('display','none');
				}
				else{ 
					$("#food_teddad_vahde_res").parent().parent().css('display','table-row');
					$("#food_teddad_vahde_reason").parent().parent().css('display','none');
				}
				$("#food_selectplace_title").html(title);
				$("#food_teddad_vahde_res").val("");
				$("#food_teddad_vahde_reason").val("");
				$("#selectPlace").fadeIn('fast',function(){$("#food_teddad_vahde_res").focus();});	
							
            }
        }
    });

    function clickvade(thisobj) {
        var idpartsCilcked = thisobj.attr('idparts');
        var jason = {};
        var idmeal2 = thisobj.parent().attr('idmeal2');
		var part = idmeal2%3;
		var dt = thisobj.parent().parent().find('span').html() + "," + part;
		var daysel = thisobj.parent().parent().find("td:first").find('span:first').html();
		var extra = "";
        if (idpartsCilcked) {
			if(permit == 0){
				if (thisobj.hasClass('fd_selgreenuser')) {
					jason[idpartsCilcked] = 0;
				}
				else {
					jason[idpartsCilcked] = 1;
				}			
				if (thisobj.hasClass('fd_selgreenuser')) {
					thisobj.removeClass('fd_selgreenuser');

				}
				else {
					thisobj.addClass('fd_selgreenuser');
				}
			}else if(permit == 1){
				var num = parseInt($('#food_teddad_vahde_res').val());
				if(num > 0){
					if(num>1)
						extra = $('#food_teddad_vahde_reason').val();
					jason[idpartsCilcked] = num;
					if (!thisobj.hasClass('fd_selgreenuser'))
						thisobj.addClass('fd_selgreenuser');
				}else{
					jason[idpartsCilcked] = 0;
					if (thisobj.hasClass('fd_selgreenuser'))
						thisobj.removeClass('fd_selgreenuser');
				}
			}
			
            thisobj.css({opacity: '0.5'});
            var id_selected = 0;
            $('#selectPlace').find('select option:selected').each(function () {
                id_selected = $(this).attr('value');
            });
			saveListMealInWeek(JSON.stringify(jason), id_selected, extra, dt);
        }
    }	
	
	$('body').on('click', '#li-food-tabs-1', function () {
		if(firsttime_hafte_user){
			$("#food_teddad_vahde_res" ).keyup(function () {
				var num = parseInt($('#food_teddad_vahde_res').val());
				if(num>1){
					$("#food_teddad_vahde_reason").parent().parent().css('display','table-row');
				}
				else{
					$("#food_teddad_vahde_reason").parent().parent().css('display','none');
				}
			});
			listTablecloth = {};
			timeUpdateTablecloth = 1;
			listplace = {};
			listMealInWeek = {};
			listMealInWeekParts = {};
			timeUpdateMealInWeek = 0;
			lastIdListMealInWeek = 0;
			countUpdateMealInWeek = 0;
			listUserSelectedMealInWeek = {};
			afterdrag = false;
			afterdragContent = '';
			waitdrop = false;
			currentWeek = 0;
			daypass = 0;
			oldWeek = 0;
			respondGetListTablecloth = false;
			respondLoadListMealInWeek = false;
			respondgetUserSelectedFood = false;
			respondSaveListMealInWeek = false;
			changeData = false;
		}
		user = $('#app_food').attr('userid');
		permit = parseInt($('#app_food').attr('permit_multi'));
		//alert(permit);
		getListTablecloth(firsttime_hafte_user);
	});
});