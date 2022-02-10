<?php
$persian_digits    =    array('۰','۱','۲','۳','۴','۵','۶','۷','۸','۹');
$english_digits    =    array('0','1','2','3','4','5','6','7','8','9');
?>
<div id="app_meeting" class="windowpanel box_sized" style="display:none;width:70%;top:35px;left: 13%;right: 20px;"
	lastxpos="6%" lastypos="37px" lastwidth="92%" lastheight="" maximize="false" minimize="false">
	<div class="windowpanel_header box_sized">
		<img class="windowpanel_header_icon" src="img/meeting.png"/>
		<div class="windowpanel_header_title">نرم افزار ثبت جلسات سازمان قطار شهري شيراز</div>
		<div class="windowpanel_header_btns">
			<img class="windowpanel_header_btns_close imgwindowpanel" src="img/close1.png"/>
			<img class="windowpanel_header_btns_max imgwindowpanel" src="img/max3.png"/>
			<img class="windowpanel_header_btns_min imgwindowpanel" src="img/min1.png"/>
		</div>
	</div>
	<div class="windowpanel_body box_sized" style="direction:rtl;bottom:1px;position:absolute;top:31px;">
		<div id="meeting_overlay_for_conflict" 
			style="display:;position:absolute;left:0px;right:0px;top:0px;bottom:0px;opacity:0.6;background-color:black;width:100%;height:100%;z-index:99998;"></div>
		<div id="meeting_conflict_content_id" class="box_sized radius5 meeting_conflict_content" 
			style="width:80%;max-height:400px;height:50%;overflow:auto;border:3px solid #e94331;display:;direction:rtl;position:absolute;z-index:99999;
			margin:30px auto;right:0;left:0;background-color:white;">
			<table id="meeting_tbl_conflict" style="outline:1px solid red;">
				<tr class="meeting_tbl_conflict_header">
					<td colspan="8" style="padding:10px 5px;font-weight:bold;position:relative;text-align:right;direction:rtl;">
						<div style="display:inline-block;">تنظیم جلسه با جلسات زیر تداخل دارد ، آیا مایل به ذخیره جلسه جدید دارید ؟</div>
						<div style="display:inline-block;"><input type="button" style="font-family:tahoma;font-size:11px;" id="meeting_btn_save_conflict_session" value="ذخیره"/></div>
						<img id="meeting_img_conflict_close" style="width:16px;height:16px;position:absolute;left:5px;top:9px;" class="btnpointer" src="./img/close1.png" />
					</td>					
				</tr>
				<tr class="meeting_tbl_conflict_header">
					<td>شماره جلسه</td>
					<td>متقاضی</td>
					<td>موضوع</td>
					<td>تاریخ</td>
					<td>ساعت شروع</td>
					<td>ساعت پایان</td>
					<td>شرکت</td>
					<td>اعضای جلسه</td>
				</tr>
				<tr class="meeting_tbl_conflict_row">
					<td>459</td>
					<td>مهندس حسن مرادی</td>
					<td>سیستم جامع نت</td>
					<td>1393/06/26</td>
					<td>10:56</td>
					<td>14:00</td>
					<td>شرکت پتسا دکور</td>
					<td>آقای حسنلی ، مهندس موغلی</td>
				</tr>
				<?php for($i=0;$i<10;$i++){ ?>
				<tr class="meeting_tbl_conflict_row">
					<td>459</td>
					<td>مهندس حسن مرادی ، مهندس حسن مرادی ، مهندس حسن مرادی ، مهندس حسن مرادی ، مهندس حسن مرادی ، مهندس حسن مرادی ، </td>
					<td>سیستم جامع نت</td>
					<td>1393/06/26</td>
					<td>10:56</td>
					<td>14:00</td>
					<td>شرکت پتسا دکور</td>
					<td>آقای حسنلی ، مهندس موغلی ، آقای حسنلی ، مهندس موغلیمهندس حسن مرادی ، مهندس حسن مرادی ، مهندس حسن مرادی ، مهندس حسن مرادی ، </td>
				</tr>
				<?php } ?>
			</table>
		</div>
		<div id="meeting_new_seesion" 
			style="position:absolute;width:0px;z-index:9999;
			background-color:#fdf4ff;border-right:3px solid #fab136;height:100%;left:0px;top:0px;">
			<div id="meeting_new_seesion_content" style="display:none;width:100%;padding:5px;height:100%;overflow:auto;" class="box_sized">
				<table class="meeting_tbl box_sized">
					<tr>
						<td colspan="2">
							<div style="margin-bottom:3px;">متقاضی جلسه:</div>
							<input type="text" class="meeting_text_style box_sized fadir txt_required" style="width:100%;" id="meeting_txt_body_needer"/>
						</td>
					</tr>
					<tr>
						<td colspan="2">
							<div style="margin-bottom:3px;">موضوع جلسه:</div>
							<input type="text" class="meeting_text_style box_sized fadir txt_required" style="width:100%;" id="meeting_txt_meeting_subject"/>
						</td>
					</tr>
					<tr>
						<td colspan="2" style="text-align:center;">
							<div style="padding:10px;background-color:#f1f1f1;display:inline-block;margin:2px;" class="box_sized">
								<table>
									<tr>
										<td colspan="3">
											<span>تاریخ جلسه : </span>
											<span style="font-family:tahoma;font-size:10px;color:blue;text-align:left;direction:rtl;">
												<?php echo str_replace($english_digits, $persian_digits, '09 / 05 / 1393');  ?>
											</span>
										</td>
									</tr>
									<tr style="text-align:center;direction:ltr;">
										<td>
											/ <input placeholder="روز" jumptoid="meeting_txt_mah" maxvalue="31"  tabindex="1001" type="text" id="meeting_txt_rooz" 
												class="meeting_text_style box_sized fadir justnumber len2jump txt_required" 
												style="text-align:center;" size="2" id="meeting_body_demond" maxlength="2" />
										</td>
										<td>
											/ <input placeholder="ماه" maxvalue="12" jumptoid="meeting_txt_hour"  tabindex="1002" type="text" id="meeting_txt_mah" 
												class="meeting_text_style box_sized fadir justnumber len2jump txt_required" 
												style="text-align:center;" size="2" id="meeting_body_demond" maxlength="2" />
										</td>
										<td>
											<script>
												//alert(new Date().getFullYear());
											</script>
											<input placeholder="سال" maxvalue="99" tabindex="1003" value="1393" type="text" id="meeting_txt_sal" 
												class="meeting_text_style box_sized fadir justnumber len2jump txt_required" 
												style="text-align:center;" size="4" id="meeting_body_demond" maxlength="4" />
										</td>
									</tr>
								</table>
							</div>
							<div style="padding:10px;background-color:#f1f1f1;display:inline-block;margin:2px;" class="box_sized">
								<table>
									<tr>
										<td colspan="3">
											<span>ساعت شروع : </span>
											<span style="font-family:tahoma;font-size:10px;color:blue;text-align:left;direction:rtl;">
												<?php echo str_replace($english_digits, $persian_digits, '23 : 10');  ?>
											</span>
										</td>
									</tr>
									<tr style="text-align:center;direction:ltr;">
										<td>
											&nbsp;:&nbsp;<input jumptoid="meeting_txt_phour" maxvalue="59"  placeholder="دقیقه" tabindex="1004" type="text" id="meeting_txt_min" 
											class="meeting_text_style box_sized fadir justnumber len2jump txt_required" 
											style="text-align:center;" size="4" id="meeting_body_demond" maxlength="2" />
										</td>
										<td>
											<input placeholder="ساعت" maxvalue="24" jumptoid="meeting_txt_min" tabindex="1005" type="text" id="meeting_txt_hour" 
											class="meeting_text_style box_sized fadir justnumber len2jump txt_required" 
											style="text-align:center;" size="4" id="meeting_body_demond" maxlength="2" />
										</td>
									</tr>
								</table>
							</div>
							<div style="padding:10px;background-color:#f1f1f1;display:inline-block;margin:2px;" class="box_sized">
								<table>
									<tr>
										<td colspan="3">
											<span>ساعت پایان : </span>
											<span style="font-family:tahoma;font-size:10px;color:blue;text-align:left;direction:rtl;">
												<?php echo str_replace($english_digits, $persian_digits, '56 : 13');  ?>
											</span>
										</td>
									</tr>
									<tr style="text-align:center;direction:ltr;">
										<td>
											&nbsp;:&nbsp;<input placeholder="دقیقه" maxvalue="59"  tabindex="1006" type="text" id="meeting_txt_pmin" 
											class="meeting_text_style box_sized fadir justnumber len2jump txt_required" 
											style="text-align:center;" size="4" id="meeting_body_demond" maxlength="2" />
										</td>
										<td>
											<input placeholder="ساعت" jumptoid="meeting_txt_pmin" maxvalue="24" tabindex="1007" type="text" id="meeting_txt_phour" 
											class="meeting_text_style box_sized fadir justnumber len2jump txt_required" 
											style="text-align:center;" size="4" id="meeting_body_demond" maxlength="2" />
										</td>
									</tr>
								</table>
							</div>
						</td>
					</tr>
					<tr>
						
					</tr>
					<tr>
						<td colspan="2">
							<div style="margin-bottom:3px;">نام شرکت:</div>
							<input type="text" class="meeting_text_style box_sized fadir" style="width:100%;" id="meeting_txt_coname"/>
						</td>
					</tr>
					<tr>
						<td colspan="2">
							<div style="margin-bottom:3px;">اعضای جلسه:</div>
							<textarea rows="4" class="meeting_txtarea_style fadir box_sized" id="meeting_txtarea_members"></textarea>
						</td>
					</tr>
					
					<tr>
						<td colspan="2" style="text-align:center;">
							<div style="width:80px;margin:0px auto;text-align:center;display:inline-block;" class="meeting_btn_style" id="meeting_btn_newmetting_add">ذخیره</div>
							<div style="width:80px;margin:0px auto;text-align:center;display:inline-block;" class="meeting_btn_style" id="meeting_btn_newmetting_cancel">انصراف</div>
						</td>
					</tr>
				</table>
			</div>
		</div>
		<input type="button" value="ثبت جلسه جدید" id="meeting_btn_newmetting"/>
	</div>
</div>