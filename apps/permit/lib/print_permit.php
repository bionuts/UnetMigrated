<?php

$rand = rand(1, 9999999999) * rand(1, 999);

$persian_digits = array('۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹');
$english_digits = array('0', '1', '2', '3', '4', '5', '6', '7', '8', '9');

if (!isset($_GET["id"])) exit;
$mojavez_id = trim($_GET["id"]);
if (!ctype_digit($mojavez_id)) exit;

session_start();
include '../../../util/util.php';
$util = new UtilClass();
if (@$_SESSION['hashuser'] != @$util->hashuser($_SESSION["userid"] . $_SESSION["username"] . $_SERVER['REMOTE_ADDR'] . $_SERVER['HTTP_USER_AGENT'])) 
{	
	echo 'please login';
	exit;
}

include 'print_permit_class.php';
$obj = new print_permit_class();

if(!$obj->doprint($mojavez_id,$_SESSION["userid"])) exit;

@$data_result = $obj->show_request($mojavez_id);

$activity_date_result = $obj->get_activity_date($mojavez_id);

@$peymankar_list_result = $obj->show_list_peimankar($mojavez_id);

@$nazer_list_result = $obj->show_list_nazer($mojavez_id);

@$username = $data_result['users_fname'] . " " . @$data_result['users_lname'];

include 'jdf.php';
$export_date = jdate('l , j F Y', strtotime($activity_date_result['tdate']));
?>
<!DOCTYPE html>
<html>
<head>
    <title></title>
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0"/>
    <link rel="stylesheet" href="dcss.css?r=<?php echo $rand ?>"/>
	<style type="text/css">
		div.WordSection1 div table
		{
			width: 100% !important;
		}
	</style>
</head>
<body style="padding:0;margin:0;">
<div id="print_main" class="box_sized print" style="padding:0;margin:0;position:relative;">
    <div style="position:absolute;left:80px;top:25px;z-index:101 !important;">
        <img src="img/done.png"
             style="width:80px;height:80px;-ms-transform: rotate(-36deg);-webkit-transform: rotate(-36deg);transform: rotate(-36deg);"/>
    </div>
    <div
        style="position:fixed;left:0px;top:0px;z-index:10000 !important;font-size:12px;direction:rtl;
				border:0px solid black;padding:0px;
				background-color:gray;color:white;width:250px;text-align:center;" class="box_sized">
        مجوز : <?php echo @$mojavez_id; ?> - تاریخ
        : <?php if (@$data_result['is_non_critical'] == 1) echo 'بدون محدودیت زمانی'; else echo @str_replace($english_digits, $persian_digits, $export_date); ?>
    </div>
    <div id="print_header" class="box_sized print" style="height:70px;position:relative;">
        <div id="print_mojavez_num"
             style="text-align:right;direction:rtl;position:absolute;top:15px;right:15px;width:200px;font-weight:normal;">
            شماره مجوز : <?php echo @$mojavez_id; ?><br/>
            تاریخ فعالیت : <?php if (@$data_result['is_non_critical'] == 1) echo 'بدون محدودیت زمانی'; else echo @str_replace($english_digits, $persian_digits, $export_date); ?>
        </div>
        <div id="print_content_header" class="box_sized print">
            <img src="img/logo.jpg" style="width:40px;height:40px;display:block;margin:0px auto;"/>

            <p class="print_reset">سازمان قطار شهری شیراز و حومه</p>
        </div>
    </div>

    <div id="print_user_info" class="print">
        <p class="print_reset"><span
                style="font-weight:bold;">نام کاربری درخواست کننده : </span><?php echo @$username; ?> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span><span style="font-weight:bold;">شماره تماس : </span><?php echo @str_replace($english_digits, $persian_digits, $data_result['peimankar_info_cellphone']); ?></span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            <span style="font-weight:bold;">تلفن کشیک</span>
            : <?php echo @str_replace($english_digits, $persian_digits, $data_result['tel_vahedkeshik_permit_main']); ?>
        </p>
    </div>

    <div class="tmr_plan" style="width:100%;font-size:9px !important;">
        <?php
        $print_permit_date = $activity_date_result['tdate'];
        include '../../optplan/lib/optplan_ajax_handler.php';
        $optplan = new OPTPlanAjaxManager();

        echo '<div style="background-color:white;padding:5px;" class="box_sized">' .
            str_replace("apps/optplan/img/", "../../optplan/img/", $optplan->get_note_for_print_permit($print_permit_date)) . '</div><hr/>';
        echo str_replace("apps/optplan/img/", "../../optplan/img/", $optplan->get_opt_for_print_permit($print_permit_date)) . '<hr/>';

        /*get_note_for_tomorrow,get_opt_for_tomorrow*/

        /*echo '<div style="background-color:white;padding:5px;" class="box_sized">'.
        str_replace("apps/optplan/img/","../../optplan/img/" ,$optplan->get_note_for_today()).'</div><hr/>';
        echo str_replace("apps/optplan/img/","../../optplan/img/" ,$optplan->get_opt_for_today()).'<hr/>';*/
        ?>
    </div>

    <div id="print_form_info" class="box_sized print">
        <table border="0" style="width:100%;" class="print">
            <tr style="font-weight:bold;background-color:#f2f2f2; /*background:url('img/bk.jpg') top right;*/">
                <td style="min-width:35%;width:40%">واحد نظارت : <span
                        style="font-weight:normal;"><?php echo @str_replace($english_digits, $persian_digits, $data_result['vahednezarat_name']); ?></span>
                </td>
                <td style="text-align:center;">لیست نفرات پیمانکار</td>
            </tr>
			<tr>
                <td style="font-weight:bold;background-color:#f0f0f0;">نام
                    پیمانکار : <span style="font-weight:normal;"><?php
                        if (is_null(@$data_result['pfn'])) {
                            echo @$username;
                        } else {
                            echo $data_result['pfn'] . " " . @$data_result['pln'];
                        }
                        ?></span>
				</td>
                <td valign="top" class="print_wrap_text" style="padding:7x;line-height:20px;word-spacing:5px;font-size:11px;font-weight:bold;">
					سرپرست گروه کاری :<?php echo @$data_result['first_peim_supervisor_tot']; ?><br/>جانشین سرپرست گروه :<?php echo @$data_result['second_peim_supervisor_tot']; ?>
                </td>
            </tr>
            <tr>
                <td style="font-weight:bold;background-color:#f0f0f0;">
				</td>
                <td valign="top" rowspan="2" class="print_wrap_text"
                    style="padding:7x;line-height:20px;word-spacing:5px;font-size:11px;">
					
                    <?php echo @str_replace($english_digits, $persian_digits, $peymankar_list_result); ?>
                </td>
            </tr>
            <tr>
                <td style="font-weight:bold;background-color:#f0f0f0; /*background:url('img/bk.jpg') top right;*/">ناظر
                    گروه
                </td>
            </tr>
            <tr>
                <td class="print_wrap_text" style="line-height:20px;word-spacing:2px;font-size:11px;">
                    <?php echo @str_replace($english_digits, $persian_digits, $nazer_list_result); ?> <br/>
                </td>
            </tr>
        </table>
    </div>

    <div id="print_accept_text" class="print">
        <table border="0" style="width:100%;border-color:#5373ea;background-color:white;" class="print">
            <tr style="background-color:#f2f2f2;text-align:center; /*background:url('img/bk.jpg') top right;*/">
                <td style="width:50%;font-weight:bold;">محدوده مجوز</td>
                <td style="width:50%;font-weight:bold;">مجوز و مانور</td>
            </tr>
            <tr>
                <td class="print_table_hide_rows" style="font-weight:bold;">زمان انجام فعالیت : <span
                        style="font-weight:normal;"><?php echo @str_replace($english_digits, $persian_digits, $data_result['do_activity_name']); ?></span>
                </td>
                <td class="print_table_hide_rows" style="font-weight:bold;">نوع مجوز : <span
                        style="font-weight:normal;"><?php echo @str_replace($english_digits, $persian_digits, $data_result['type_mojavez_name']); ?></span>
                </td>
            </tr>
            <tr>
                <td class="print_table_hide_rows" style="font-weight:bold;">شماره خط : <span
                        style="font-weight:normal;"><?php echo @str_replace($english_digits, $persian_digits, $data_result['linenumber_name']); ?></span>
                </td>
                <td class="print_table_hide_rows" style="font-weight:bold;">قطار : <span
                        style="font-weight:normal;"><?php echo @str_replace($english_digits, $persian_digits, $data_result['trains_name']); ?></span>
                </td>
            </tr>
            <tr>
                <td class="print_table_hide_rows" style="font-weight:bold;">حوزه کاری : <span
                        style="font-weight:normal;"><?php echo @str_replace($english_digits, $persian_digits, $data_result['hozekari_name']); ?></span>
                </td>
                <td class="print_table_hide_rows" style="font-weight:bold;">وسیله نقلیه کمکی : <span
                        style="font-weight:normal;"><?php echo @str_replace($english_digits, $persian_digits, $data_result['komaki_trains_name']); ?></span>
                </td>
            </tr>
            <tr>
                <td class="print_table_hide_rows" style="font-weight:bold;">محل کار : <span
                        style="font-weight:normal;"><?php echo @str_replace($english_digits, $persian_digits, $data_result['mahal_kar_name']); ?></span>
                </td>
                <td class="print_table_hide_rows" style="font-weight:bold;">مبدا : <span
                        style="font-weight:normal;"><?php echo @str_replace($english_digits, $persian_digits, $data_result['stpnt']); ?></span>
                </td>
            </tr>
            <tr>
                <td class="print_table_hide_rows" style="font-weight:bold;"></td>
                <td class="print_table_hide_rows" style="font-weight:bold;">مقصد : <span
                        style="font-weight:normal;"><?php echo @str_replace($english_digits, $persian_digits, $data_result['endpnt']); ?></span>
                </td>
            </tr>
            <tr>
                <td class="print_table_hide_rows" style="font-weight:bold;"></td>
                <td class="print_table_hide_rows" style="font-weight:bold;">شرح مانور : <span
                        style="font-weight:normal;"><?php echo @str_replace($english_digits, $persian_digits, $data_result['permit_main_sharhmanovr']); ?></span>
                </td>
            </tr>
        </table>

        <p style="font:15px tahoma;font-weight:bold;color:blue;">شرح و علت فعالیت : </p>

        <p style="word-wrap: break-word;font:15px tahoma;font-weight:bold;">
            <?php echo @str_replace($english_digits, $persian_digits, $data_result['permit_main_sharhamaliat']); ?>
        </p>
        <hr/>
		
		<?php
			$selected_hints_rows = $obj->print_permit_get_safty_act_list($mojavez_id);
			foreach ($selected_hints_rows as $hrow) {
		?>		
				<label class="permit_chkbx_lbl" style="width:70%;color:orange;" for="permit_chkbx_safty_<?php echo $hrow['hint_id']; ?>"><?php echo $hrow['hint_value']; ?></label>
				<br style="margin:0px;"/>
				
		<?php 
			} 
		?>
		
		<hr/>
		
		<p style="display:;font:15px tahoma;font-weight:bold;color:blue;">توضیحات و احتیاطات لازم از سمت ناظر: </p>

        <p style="word-wrap: break-word;font:15px tahoma;font-weight:bold;margin-bottom:15px;">
            <?php echo @str_replace($english_digits, $persian_digits, $data_result['supervisor_hint']); ?>
        </p>
        <hr/>
		
        <p class="print_reset" style="color:red;font-size:20px;">نیاز به قطع برق شبکه بالاسری (OCS) دارد ؟ <label
                style="font-weight:bold;"> <?php if (@$data_result['permit_main_niazbeghaatbargh'] == 1) echo 'بلی'; else echo 'خیر'; ?></label>
        </p>
		<hr/>
        <p class="print_reset" style="color:red;font-size:20px;">فعالیت در اماکن غیر فنی ایستگاه ؟ <label
                style="font-weight:bold;"> <?php if (@$data_result['is_non_critical'] == 1) echo 'بلی'; else echo 'خیر'; ?></label>
        </p>
		<hr/>
		<?php if(@$data_result['non_critical_with_supervisor'] == 1){ ?>
				                  
            <p class="permit_chkbx_lbl" style="font-size:20px;color:blue;">حضور ناظر در هنگام ورود ( شروع به کار ) الزامی نیست</p>
                
		<?php }else{  ?>							
			<p class="permit_chkbx_lbl" style="font-size:20px;color:red;">حضور ناظر الزامی می باشد</p>
		<?php } ?>
        
    </div>
    <div id="print_accept_text" class="print" style="position:relative !important;">
        <div
            style="position:absolute;left:0px;top:-20px;z-index:10000 !important;font-size:12px;direction:rtl;
					border:0px solid black;padding:0px;
					background-color:gray;color:white;width:250px;text-align:center;" class="box_sized">
            مجوز : <?php echo @$mojavez_id; ?> - تاریخ
            : <?php if (@$data_result['is_non_critical'] == 1) echo 'بدون محدودیت زمانی'; else echo @str_replace($english_digits, $persian_digits, $export_date); ?>
        </div>
        <img src="img/done.png"
             style="position:absolute;left:10px;top:0px;width:80px;height:80px;-ms-transform: rotate(-36deg);-webkit-transform: rotate(-36deg);transform: rotate(-36deg);"/>

			 <?php if (@$data_result['is_non_critical'] == 1){?>
        <p style="font:15px tahoma;font-weight:bold;margin:0px;">متن تاییدیه امور ایستگاه : </p>
		<?php }else{ ?>
		<p style="font:15px tahoma;font-weight:bold;margin:0px;">متن تائیدیه مجوز مرکز فرمان : </p>
		<?php }?>

        <p class="print_reset box_sized"
           style="font:13px tahoma;font-weight:bold;color:red;padding-left:100px !important;"><?php
            echo @str_replace($english_digits, $persian_digits, $data_result['permit_main_dalilradocc']) . '<br/>';
            ?></p>
    </div>
    <div id="print_important_text" class="print" style="display:;">
        <p>توضیحات و نکات مهم</p>

        <p class="print_reset"></p>
    </div>
</div>
</body>
</html>