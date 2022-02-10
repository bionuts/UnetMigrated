<?php
		$persian_digits    =    array('۰','۱','۲','۳','۴','۵','۶','۷','۸','۹');
		$english_digits    =    array('0','1','2','3','4','5','6','7','8','9');

		if(!isset($_GET["id"]))exit;
		$mojavez_id = trim($_GET["id"]);
		include 'print_permit_class.php';
		$obj = new print_permit_class();
		@$data_result = $obj->show_request( $mojavez_id );
		@$peymankar_list_result=$obj->show_list_peimankar($mojavez_id);
		@$nazer_list_result=$obj->show_list_nazer($mojavez_id);
		@$username= $data_result['users_fname']." ".@$data_result['users_lname'];
		
		include 'jdf.php';
		@$darkhast_date_tmp= explode(" ",$data_result['permit_main_tarikhsaat_darkhast_bypeimankar']);
		@$darkhast_date=explode("-",$darkhast_date_tmp[0]);
		//echo($darkhast_date_tmp[1]);
		@$darkhast_time = explode(":",$darkhast_date_tmp[1]);
		@$darkhast_date = (gregorian_to_jalali( $darkhast_date[0] , $darkhast_date[1] , $darkhast_date[2] , "/" ))."<br />". $darkhast_time[0].":".$darkhast_time[1];

		@$export_date_tmp= explode(" ",$data_result['permit_main_tarikhsaat_sodoor_byocc']);
		@$export_date=explode("-",$export_date_tmp[0]);
		//echo($darkhast_date_tmp[1]);
		@$export_time = explode(":",$export_date_tmp[1]);
		@$export_date = (gregorian_to_jalali( $export_date[0] , $export_date[1] , $export_date[2] , "/" ))."<br />". $export_time[0].":".$export_time[1];
?>
<!DOCTYPE html>
<html>
	<head>
		<title></title>
		<meta charset="UTF-8"/>
		<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0"/>
		<link rel="stylesheet" href="dcss.css" />
	</head>
	<body style="padding:0;margin:0;">
		<div id="print_main" class="box_sized print" style="padding:0;margin:0;">
			<div id="print_header" class="box_sized print" style="height:130px;position:relative;">
				<div id="print_mojavez_num" style="text-align:right;direction:rtl;position:absolute;top:15px;right:15px;">
					شماره مجوز : <?php echo @$mojavez_id;?><br/><br/>
					تاریخ مجوز :
				</div>	
				<div id="print_content_header" class="box_sized print">
					<img src="img/logo.jpg" style="width:50px;height:50px;display:block;margin:0px auto;"/>
					<p class="print_reset">سازمان قطار شهری شیراز و حومه</p>
					<p class="print_reset">مرکز کنترل و فرمان</p>
				</div>
				<!--<div id="print_content_desc" class="box_sized print" >
					<table id="print_customers" class="print box_sized">
						  <tr >
							<td >درخواست مجوز: </td>
							<td style="text-align:center;"><?php echo @str_replace($english_digits, $persian_digits,$darkhast_date );?></td> 
						  </tr >
						  <tr style="display:;"	>
							<td >صدور مجوز: </td>
							<td style="text-align:center;" ><?php echo @str_replace($english_digits, $persian_digits,$export_date);?></td> 
						  </tr>
					</table>
				</div>-->
			</div>
			
			<div  id="print_user_info" class="print">
				<p class="print_reset">نام کاربری درخواست کننده : <?php echo @$username;?> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span>شماره تماس : <?php echo @str_replace($english_digits, $persian_digits,$data_result['peimankar_info_cellphone']);?></span></p>
			</div>	
			
			<div class="tmr_plan" style="width:100%;">
				<?php
					include '../../optplan/lib/optplan_ajax_handler.php';
					$optplan = new OPTPlanAjaxManager();
					
					echo '<div style="background-color:white;padding:5px;" class="box_sized">'.str_replace("apps/optplan/img/","../../optplan/img/" ,$optplan->get_note_for_tomorrow()).'</div><hr/>';
					echo str_replace("apps/optplan/img/","../../optplan/img/" ,$optplan->get_opt_for_tomorrow()).'<hr/>';
				?>				
			</div>

			<div id="print_form_info" class="box_sized print">
				<table border="0" style="width:100%;" class="print">
					<tr style="font-weight:bold;background-color:#f2f2f2; background:url('img/bk.jpg') top right;">
						<td style="min-width:35%;width:40%" >واحد نظارت : <span style="font-weight:normal;"><?php echo @str_replace($english_digits, $persian_digits,$data_result['vahednezarat_name']);?></span></td>
						<td style="text-align:center;">لیست نفرات پیمانکار</td> 
					</tr>
					<tr>
						<td style="font-weight:bold;background-color:#f0f0f0; background:url('img/bk.jpg') top right;">نام پیمانکار : <span style="font-weight:normal;"><?php echo @$data_result['users_fname']." ".@$data_result['users_lname'];?></span></td>
						<td  valign="top" rowspan="3" class="print_wrap_text" style="padding:7x;line-height:20px;word-spacing:5px;font-size:11px;">
							<?php echo @str_replace($english_digits, $persian_digits,$peymankar_list_result);?>
						</td>
					</tr>
					<tr>
						<td style="font-weight:bold;background-color:#f0f0f0; background:url('img/bk.jpg') top right;">ناظر گروه</td>
					</tr>
					<tr>
						<td class="print_wrap_text" style="line-height:20px;word-spacing:2px;font-size:11px;">
							<?php  echo @str_replace($english_digits, $persian_digits,$nazer_list_result); ?> <br />
						</td>
					</tr>
				</table>
				<p class="print_reset" id="print_tel">تلفن کشیک : <?php echo @str_replace($english_digits, $persian_digits,$data_result['tel_vahedkeshik_permit_main']);?></p>
			</div>
			
			<div id="print_accept_text" class="print">
				<table  border="0" style="width:100%;border-color:#5373ea;background-color:white;" class="print">
						<tr style="background-color:#f2f2f2;text-align:center; background:url('img/bk.jpg') top right;">
							<td style="width:50%;font-weight:bold;">محدوده مجوز</td>
							<td style="width:50%;font-weight:bold;">مجوز و مانور</td> 	
						</tr>
						<tr>
							<td  class="print_table_hide_rows" style="font-weight:bold;">زمان انجام فعالیت : <span style="font-weight:normal;"><?php echo @str_replace($english_digits, $persian_digits,$data_result['do_activity_name']);?></span></td>
							<td class="print_table_hide_rows" style="font-weight:bold;">نوع مجوز : <span style="font-weight:normal;"><?php echo @str_replace($english_digits, $persian_digits,$data_result['type_mojavez_name']);?></span></td>
						</tr>
						<tr>
							<td class="print_table_hide_rows" style="font-weight:bold;">شماره خط : <span style="font-weight:normal;"><?php echo @str_replace($english_digits, $persian_digits,$data_result['linenumber_name']);?></span></td>
							<td class="print_table_hide_rows" style="font-weight:bold;">قطار : <span style="font-weight:normal;"><?php echo @str_replace($english_digits, $persian_digits,$data_result['trains_name']);?></span></td>
						</tr>
						<tr>
							<td class="print_table_hide_rows" style="font-weight:bold;">حوزه کاری : <span style="font-weight:normal;"><?php echo @str_replace($english_digits, $persian_digits,$data_result['hozekari_name']);?></span></td>
							<td class="print_table_hide_rows" style="font-weight:bold;">وسیله نقلیه کمکی : <span style="font-weight:normal;"><?php echo @str_replace($english_digits, $persian_digits,$data_result['komaki_trains_name']);?></span></td>
						</tr>
						<tr>
							<td class="print_table_hide_rows" style="font-weight:bold;">محل کار : <span style="font-weight:normal;"><?php echo @str_replace($english_digits, $persian_digits,$data_result['mahal_kar_name']);?></span></td>
							<td class="print_table_hide_rows" style="font-weight:bold;">مبدا : <span style="font-weight:normal;"><?php echo @str_replace($english_digits, $persian_digits,$data_result['stpnt']);?></span></td>
						</tr>
						<tr>
							<td class="print_table_hide_rows" style="font-weight:bold;"></td>
							<td class="print_table_hide_rows" style="font-weight:bold;">مقصد : <span style="font-weight:normal;"><?php echo @str_replace($english_digits, $persian_digits,$data_result['endpnt']);?></span></td>
						</tr>
						<tr>
							<td class="print_table_hide_rows" style="font-weight:bold;"></td>
							<td class="print_table_hide_rows" style="font-weight:bold;">شرح مانور : <span style="font-weight:normal;"><?php echo @str_replace($english_digits, $persian_digits,$data_result['permit_main_sharhmanovr']);?></span></td>
						</tr>
				</table>
				
				<p>شرح و علت فعالیت</p>
				<p>
					<?php echo @str_replace($english_digits, $persian_digits,$data_result['permit_main_sharhamaliat']);?>
				</p>
				<hr />
				<p >قطع برق</p>
				<p class="print_reset" style="color:red;">نیاز به قطع برق شبکه بالاسری (OCS) دارد ؟  <span style="font-weight:bold;"> <?php if(@$data_result['permit_main_niazbeghaatbargh']==1) echo 'بلی'; else echo 'خیر';?></span> </p>
			</div>
			<div id="print_accept_text" class="print" style="display:;" >
				<p style="font:15px tahoma;font-weight:bold;" >متن تائیدیه مجوز مرکز فرمان :	</p>
				<p class="print_reset" style="font:15px tahoma;font-weight:bold;"><?php echo @str_replace($english_digits, $persian_digits,$data_result['permit_main_dalilradocc']);?></p>
			</div>
			<div id="print_important_text" class="print" style="display:;" >
				<p >توضیحات و نکات مهم</p>
				<p class="print_reset"></p>
			</div>
		</div>
	</body>
</html>