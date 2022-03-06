<?php
session_start();
include '../../../util/util.php';
$util = new UtilClass();
if ( ! $util->haveAcces( 'permit', $_SESSION["userid"] ) ) {
	exit;
}

if ( $_SESSION['hashuser'] != $util->hashuser( $_SESSION["userid"] . $_SESSION["username"] . $_SERVER['REMOTE_ADDR'] . $_SERVER['HTTP_USER_AGENT'] ) ) {
	header( "Location: logout.php" );
	exit();
}

// include '../lib/permit-config.php';
include_once($_SERVER['DOCUMENT_ROOT'].'/config/main_config.php');
include '../lib/permitUtil.php';
$putil  = new permitUtil();
$roleid = $putil->getUserRoleID( $_SESSION['userid'] );
$roleid = $roleid[0];
include '../lib/showrequest.php';
$show_req_obj = new show_request();

?>
<div id="app_permit" class="windowpanel box_sized" style="width:92%;display:none;top:37px;left: 6%;right: 20px;"
     lastxpos="6%" lastypos="37px" lastwidth="92%" lastheight="" maximize="false" minimize="false">
    <div class="windowpanel_header box_sized">
        <img class="windowpanel_header_icon" src="img/permit.png"/>

        <div class="windowpanel_header_title">درخواست مجوز کار و تردد در اماکن سازمان قطار شهری شیراز</div>
        <div class="windowpanel_header_btns">
            <img class="windowpanel_header_btns_close imgwindowpanel" src="img/close1.png"/>
            <img class="windowpanel_header_btns_max imgwindowpanel" src="img/max3.png"/>
            <img class="windowpanel_header_btns_min imgwindowpanel" src="img/min1.png"/>
        </div>
    </div>
    <div class="windowpanel_body box_sized" id="permit_body_panel"
         style="direction:rtl;bottom:1px;position:absolute;top:31px;">
        <div id="permit_confirm_req" style="position:absolute;width:0px;z-index:9999;
							   background-color:#fdf4ff;border-right:3px solid #0158c1;height:100%;left:0px;top:0px;">
            <div id="permit_confirm_req_content" style="display:none;width:100%;padding:5px;height:100%;overflow:auto;"
                 class="box_sized">
                <div
                        style="position:relative;color:white;background-color:#c83c23;width:100%;padding:10px;margin:5px auto;font-family:BYekanRegular;font-size:13px;"
                        class="box_sized radius5 fadir ">
                    نکته : لطفا تمام اطلاعات وارد شده مربوط به درخواست مجوز را بررسی کنید و پس از حاصل شدن اطمینان از
                    صحت اطلاعات ، اقدام به ارسال فرم نمایید
                </div>
                <div id="print_main" class="box_sized print" style="margin-bottom:5px;">
                    <div id="print_form_info" class="box_sized print">
                        <table border="0" style="width:100%;" class="print box_sized">
                            <tr style="font-weight:bold;background-color:#f2f2f2;">
                                <td style="min-width:35%;width:40%;">واحد نظارت بهره برداری : <span
                                            id="permit_summary_nezaratunit" style="font-weight:normal;"></span></td>
                                <td style="font-weight:bold;background-color:#f0f0f0;">نام پیمانکار : <span
                                            id="permit_summary_peimankar_name" style="font-weight:normal;"></span></td>
                            </tr>
                            <tr>
                                <td style="font-weight:bold;background-color:#f0f0f0;">ناظر گروه</td>
                                <td style="width:59%;font-weight:bold;background-color:#f0f0f0;">لیست نفرات پیمانکار
                                </td>

                            </tr>
                            <tr>
                                <td class="print_wrap_text" style="line-height:20px;word-spacing:2px;font-size:11px;"
                                    id="permit_summary_nazer_list">

                                </td>
                                <td id="permit_summary_listof_worker" valign="top" rowspan="3" class="print_wrap_text"
                                    style="line-height:20px;word-spacing:5px;font-size:11px;">

                                </td>
                            </tr>
                        </table>
                        <p class="print_reset" id="permit_summary_print_tel"></p>
                    </div>

                    <div id="print_accept_text" class="print">
                        <table border="0" style="width:100%;border-color:#5373ea" class="print">
                            <tr style="background-color:#f2f2f2;text-align:center;">
                                <td style="width:50%;font-weight:bold;">محدوده مجوز</td>
                                <td style="width:50%;font-weight:bold;">مجوز و مانور</td>
                            </tr>
                            <tr>
                                <td class="print_table_hide_rows" style="font-weight:bold;">زمان انجام فعالیت : <span
                                            id="permit_summary_activityname" style="font-weight:normal;"></span></td>
                                <td class="print_table_hide_rows" style="font-weight:bold;">نوع مجوز : <span
                                            id="permit_summary_permittype" style="font-weight:normal;"></span></td>
                            </tr>
                            <tr>
                                <td class="print_table_hide_rows" style="font-weight:bold;">شماره خط : <span
                                            id="permit_summary_linenum" style="font-weight:normal;"></span></td>
                                <td class="print_table_hide_rows" style="font-weight:bold;">قطار : <span
                                            id="permit_summary_trainnum" style="font-weight:normal;"></span></td>
                            </tr>
                            <tr>
                                <td class="print_table_hide_rows" style="font-weight:bold;">حوزه کاری : <span
                                            id="permit_summary_wscope" style="font-weight:normal;"></span></td>
                                <td class="print_table_hide_rows" style="font-weight:bold;">وسیله نقلیه کمکی : <span
                                            id="permit_summary_vhelper" style="font-weight:normal;"></span></td>
                            </tr>
                            <tr>
                                <td class="print_table_hide_rows" style="font-weight:bold;">محل کار : <span
                                            id="permit_summary_placekar" style="font-weight:normal;"></span></td>
                                <td class="print_table_hide_rows" style="font-weight:bold;">مبدا : <span
                                            style="font-weight:normal;" id="permit_summary_stplace"></span></td>
                            </tr>
                            <tr>
                                <td class="print_table_hide_rows" style="font-weight:bold;"></td>
                                <td class="print_table_hide_rows" style="font-weight:bold;">مقصد : <span
                                            id="permit_summary_enplace" style="font-weight:normal;"></span></td>
                            </tr>
                            <tr>
                                <td class="print_table_hide_rows" style="font-weight:bold;"></td>
                                <td class="print_table_hide_rows" style="font-weight:bold;">شرح مانور:<br/> <span
                                            id="permit_summary_manover" style="font-weight:normal;"></span></td>
                            </tr>
                        </table>

                        <p>شرح و علت فعالیت : </p>

                        <p id="permit_summary_descpermit" style="word-wrap: break-word;">
                        </p>
                        <hr/>
                        <p class="print_reset" style="color:red;">نیاز به قطع برق شبکه بالاسری (OCS) دارد ؟ <span
                                    id="permit_summary_powercut" style="font-weight:bold;"></span></p>
						<hr/>
                        <p class="print_reset" style="color:red;">فعالیت در اماکن غیر فنی ایستگاه ؟<span
                                    id="permit_summary_non_critical" style="font-weight:bold;"></span></p>
                    </div>

                    <div id="print_important_text" class="print">
                        <p>توضیحات و نکات مهم</p>

                        <p class="print_reset"></p>
                    </div>
                </div>
                <div
                        style="text-align:center;position:relative;color:white;background-color:#b9e9a7;width:100%;padding:10px;margin:5px auto;font-family:BYekanRegular;font-size:13px;"
                        class="box_sized radius5 fadir ">
                    <lable id="lbl_permit_btn_confirm_go_ajaxgif"
                           style="color:#306b1a;direction:ltr;text-align:left;position:relative;top:2px;display: none;">
                        ... Loading
                    </lable>
                    <img src="img/ajj.gif" id="permit_btn_confirm_go_ajaxgif"
                         style="position: relative;top:6px;display: none;"/><br/><br/>
                    <input type="button" id="permit_btn_confirm_go" class="permit_btn_style" style=""
                           value="ارسال درخواست"/>
                    <input type="button" id="permit_btn_correct_req" class="permit_btn_style"
                           style="background-color:#c62e2e;border:1px solid #c62e2e;" value="تصحیح درخواست"/>
                </div>
            </div>
        </div>
        <div id="permit_overlay_for_usermoreinfo"
             style="display:none;position:absolute;left:0px;right:0px;top:0px;bottom:0px;opacity:0.6;background-color:black;width:100%;height:100%;z-index:99998;"></div>
        <div id="user_more_info_main" class="box_sized radius5 user_more_info"
             style="display:none;direction:rtl;position:absolute;z-index:99999;margin:30px auto;right:0;left:0;">
            <div id="user_more_info_header" class="box_sized user_more_info">
                <div id="user_more_info_title">
                    <img style="width:25px;height:25px;" src="img/profile7.png"/>

                    <span style="position:relative;top:-7px;">مشخصات ناظر</span>
                </div>

                <div id="user_more_info_exit" class="box_sized user_more_info">
                    <img id="permit_close_usermoreinfo" class="btnpointer" style="width:16px;height:16px;"
                         src="img/close1.png"/>
                </div>
            </div>

            <div id="user_more_info_form_info" class="box_sized user_more_info" style="background-color:white;">

                <div id="user_more_info_form_info_internal" class="box_sized user_more_info">

                    <img style="float:left;width:110px;height:130px;" src="img/nazera.png"/>

                    <p>نام : user</p>

                    <p>نام خانوادگی : user </p>

                    <p>واحد نظارت : unit</p>

                    <p>سمت : cons IT</p>

                    <p>شماره موبایل : 0102932190</p>

                    <p>شماره تلفن : 332113</p>

                </div>
            </div>


        </div>
        <div id="permit_tabs" style="width:100%;height:100%;" class="box_sized">
            <ul>
                <li><a href="#tabs-1">درخواست های امروز</a></li>
                <li><a href="#tabs-2">فعالیت های امروز</a></li>
                <li><a href="#tabs-3">بایگانی</a></li>
				<?php
				$deadtimereq = 11;
				//peimankar_request_for_permit
				switch ( $roleid ) {
					case 1: // peimankar sakht
					case 2: //peimankar Bahrebardari
					case 7: //Green Nezartchi  for permits
					case 9: //Karbare Darkhast Dahande Sakht
						$deadtimereq = $putil->getPermitSetting( 'peimankar_request_for_permit' );
						break;
					case 3: //OCC Signed
					case 4: //OCC Un-Signed
					case 5: //Nazer Bahrebardari
						$deadtimereq = $putil->getPermitSetting( 'nazer_request_for_permit' );
						break;

					/*case 6: //Nazer sakht
						break;*/

					/*case 8: //Nazer Sakht - Green
						break;*/
				}
				date_default_timezone_set( "Asia/Tehran" );
				$hour   = date( 'H' );// - 1; //Returns IST
				// echo 'hourxxx:'.$hour;
				$canreq = false;
				if ( ( $hour < $deadtimereq ) && $roleid != 1 ) {
					$canreq = true;
				}
				// echo $hour;
				if ( true ) { //if ( $canreq ) {
					?>
                    <li><a href="#tabs-4">درخواست مجوز ( <?php echo $hour; ?> )</a></li>
					<?php
				}
				?>

				<?php

				switch ( $roleid ) {
					case 3: //OCC Signed
					case 4: //OCC Un-Signed
						?>
                        <li><a href="#tabs-5">گزارشات</a></li>
						<?php
				}
				?>
            </ul>
            <div id="tabs-1" style="padding:0px;padding-top:5px;min-width:800px;height: 94%;width: 100%;">
                <div style="height: 100%;width: 100%;" id="permit_tab1_content">
                    <table class="permit_tbl" id="permit_tbl_today_permits"
                           style="width: 100%;height: 100%;table-layout: fixed;">
                        <thead id="permit_tabl_thead" style="display:block;"><!---->
                        <tr class="permit_header_row">
                            <td colspan="<?php echo( $roleid == 4 ? 12 : 13 ); ?>" style="text-align:right;">
							
								<?php if($roleid==3){ ?>
								<div class="btnpointer" id="permit_btn_publish_occ" data-mode="<?php ?>unpublished"
                                     style="margin-left:0px;display:inline-block;text-align:center;padding-right:5px;padding-left:5px;
                                     color:white;background-color:red;width:auto;height:25px;line-height:25px">
                                    اعلام مجوز به واحد ها
                                </div>								
								<?php } ?>
                                <div class="btnpointer" id="permit_btn_refresh_rows"
                                     style="margin-left:0px;display:inline-block;text-align:center;
                                     color:white;background-color:green;width:30px;height:25px;line-height:25px">
                                    R
                                </div>
                                <div id="permit_tbl_refresh_ajax_laoder"
                                     style="display:inline;padding-left:5px;padding-right:7px;line-height:25px;height:25px;visibility: hidden;
									text-align:right;direction:rtl;background-color:#008000;">
                                    <img src="img/tblajax.gif"
                                         style="padding:0px;margin:0;margin-left:5px;position:relative;top:4px;"/>در حال
                                    بروز رسانی اطلاعات جدول
                                </div>
                            </td>
                        </tr>
                        <tr class="permit_row_green" style="visibility:collapse;">
                            <td><img title="پرینت مجوز" style="width:26px;" src="img/p3.png"
                                     class="permit_img_show_details show_printable_green" permit_id="11641"></td>
                            <td style="text-align:center;">۱۱۶۴۱</td>
                            <td style="text-align:center;position:relative;z-index:999;padding:0px;padding-bottom:1px;">
                                <img title="مشاهده توضیحات مرکز فرمان" style="width:35px;height:35px;margin:0px auto;"
                                     src="img/warn.png" class="permit_img_show_occ_cmnt btnpointer" vhid="true">
                                <div style="position:absolute;top:5px;display:none;" class="permit_occ_hidden">
                                    <div style="float:right;position:absolute;top:10px;right:-9px;">
                                        <div class="rightarrow"></div>
                                    </div>
                                    <div class="cmnt_occ radius5" style="float:left;width:400px;">
                                        <div style="overflow:auto;" class="cmnt_occ_header box_sized">
                                            <div style="float:right;margin-left:5px;margin-right:3px;"><img
                                                        style="width:20px;height:20px;" src="img/warn.png"
                                                        class="permit_img_show_details"></div>
                                            <div style="position:relative;display:inline-block;width:80%;">شرح شرايط
                                                انجام كار
                                            </div>
                                        </div>
                                        <div class="cmnt_occ_body box_sized">"این مجوز جهت تردد درزین 961 در خط B و در
                                            محدوده خط 4 پایانه تا ایستگاه احسان و کراس اور احسان از ساعت 00:01 تا ساعت
                                            7:00 صادر شده است. انجام هر گونه حرکت و مانور در مسیر رفت و برگشت و شانت از
                                            سوزنها، منوط به استعلام آزادی خط از طریق بیسیم از بخش کنترل ترافیک مرکز
                                            کنترل و فرمان میباشد.<br>شروع و پایان فعالیت با مرکز فرمان هماهنگ نمایید و
                                            استعلام قطع برق شبکه بالاسری گرفته شود.<br>رعایت کلیه نکات ایمنی و حضور ناظر
                                            الزامی می باشد.<br>دستگاه نظارت موظف است که قبل از اقدام به تایید مجوز و
                                            همچنین در زمان شروع عملیات، هماهنگی های لازم را با بخش مانور عملیات انجام
                                            دهد."
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td style="text-align:center;position:relative;z-index:998;padding:0px;padding-bottom:1px;">
                                <img title="مشاهده شرح عملیات" src="img/mail.png" style="width:28px;height:28px;"
                                     class="permit_img_show_info_req_cmnt btnpointer" vhid="true">
                                <div style="position:absolute;top:0px;display:none;" class="permit_info_req_hidden">
                                    <div style="float:right;position:absolute;top:10px;right:-9px;">
                                        <div class="rightarrowblue"></div>
                                    </div>
                                    <div class="cmnt_desc_req radius5 box_sized" style="float:left;width:400px;">
                                        <div style="overflow:auto;" class="cmnt_desc_req_header box_sized">
                                            <div style="display:inline-block;margin-left:5px;margin-right:3px;"><img
                                                        style="width:20px;height:20px;" src="img/mail.png"
                                                        class="permit_img_show_details"></div>
                                            <div style="position:relative;top:-6px;display:inline-block;width:80%;">شرح
                                                عملیات
                                            </div>
                                        </div>
                                        <div class="cmnt_desc_req_body box_sized">انجام PM ، خط 4 توقفگاه و محدوده بین
                                            توقفگاه و ایستگاه احسان خط B و کراس اورهای مسیر از ساعت 00:01 الی 7:30
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td style="text-align:center;">برق</td>
                            <td style="text-align:center;direction:ltr;">2254</td>
                            <td style="text-align:center;">شرکت سامان ساخت برنا تارا</td>
                            <td style="text-align:center;">00:01 بامداد تا شروع بهره برداری</td>
                            <td style="text-align:center;">حریم ریلی پایانه</td>
                            <td style="text-align:center;position:relative;z-index:999;"><img
                                        title="مشاهده محل کار فعالیت"
                                        style="margin:0px;width:30px;height:30px;position: relative;top:3px;"
                                        src="img/places6.png" class="permit_img_show_nazer_cmnt btnpointer" vhid="true">
                                <div style="position:absolute;top:5px;left:60px;display:none;"
                                     class="permit_nazer_hidden">
                                    <div style="float:left;position:absolute;top:10px;left:-9px;">
                                        <div class="leftarrow"></div>
                                    </div>
                                    <div class="cmnt_nazer radius5 box_sized" style="float:right;width:400px;">
                                        <div style="overflow:auto;" class="cmnt_nazer_header box_sized">
                                            <div style="float:right;margin-left:5px;margin-right:3px;"><img
                                                        style="width:20px;height:20px;" src="img/places6.png"
                                                        class="permit_img_show_details"></div>
                                            <div style="position:relative;display:inline-block;width:80%;">لیست محل
                                                کار
                                            </div>
                                        </div>
                                        <div class="cmnt_nazer_body box_sized">پایانه احسان</div>
                                    </div>
                                </div>
                            </td>
                            <td style="text-align:center;">وسیله نقلیه ریلی کمکی</td>
                            <td style="text-align:center;">دارد</td>
                            <td><img vhid="true" class="permit_img_trpermit_req btnpointer"
                                     style="width:34px;height:34px;" src="img/trp6.png" title="تائید یا رد درخواست"
                                     permitid="11641"></td>
                        </tr>
						<?php
						echo $putil->getTblHeader( $roleid );
						echo '</thead>';
						echo '<tbody id="tbodynum_one"
                                style="display:block ;overflow-y: auto;overflow-x:hidden;">';
						//echo $putil->getTblHeader($roleid);
						echo $putil->getTblHeader_shaghol( $roleid );
						$total_page = 0;
						switch ( $roleid ) {
							case 1: // peimankar sakht
							case 2: //peimankar Bahrebardari
								echo $show_req_obj->get_today_request_peimankar( $_SESSION['userid'] );
								break;
							case 3: //OCC Signed										
								echo $show_req_obj->get_today_request_signed_occ( $_SESSION['userid'] );
								//$total_page = $show_req_obj->get_total_page_occ($_SESSION['userid']);
								break;
							case 4: //OCC Un-Signed
								echo $show_req_obj->get_today_request_unsigned_occ( $_SESSION['userid'] );
								break;
							case 5: //Nazer Bahrebardari
								echo $show_req_obj->get_today_request_nazer( $_SESSION['userid'] );
								break;
							case 7: //امور ایستگاه
								echo $show_req_obj->get_today_request_greenuser( $_SESSION['userid'] );
								break;
							case 11: //امور ایستگاه حق تایید								
								echo $show_req_obj->get_today_request_omooristgah_signeduser( $_SESSION['userid'] );
								break;
							case 9: //Karbare Darkhast Dahande Sakht
								echo $show_req_obj->get_today_request_karbare_sakhat( $_SESSION['userid'] );
								break;
							/*case 6: //Nazer sakht
								break;*/
							/*case 8: //Nazer Sakht - Green
								break;*/
						}
						?>
                        </tbody>
                    </table>
                </div>
            </div>
            <div id="tabs-2"
                 style="padding:0px;padding-top:5px;min-width:800px;height: 94%;width: 100%;">
                <div id="permit_tab2_content" style="height: 100%;width: 100%;">
                    <table class="permit_tbl" id="permit_tbl_today_permits_today_permitions"
                           style="width: 100%;height: 100%;">
                        <thead id="permit_tabl_thead" style="display:block;"><!---->
                        <tr class="permit_header_row">
                            <td colspan="<?php echo( $roleid == 4 ? 13 : 13 ); ?>" style="text-align:right;">
                                <div class="btnpointer" id="permit_btn_refresh_rows_today_permitions"
                                     style="margin-left:0px;display:inline-block;text-align:center;color:white;background-color:green;width:30px;height:25px;line-height:25px">
                                    R
                                </div>
                                <div id="permit_tbl_refresh_ajax_laoder_tp"
                                     style="display: inline;;padding-left:5px;padding-right:7px;line-height:25px;height:25px;
									text-align:right;direction:rtl;background-color:#008000;visibility: hidden;">
                                    <img src="img/tblajax.gif"
                                         style="padding:0px;margin:0;margin-left:5px;position:relative;top:4px;"/>در حال
                                    بروز رسانی اطلاعات جدول
                                </div>
                            </td>
                        </tr>
                        <tr class="permit_row_green" style="visibility: collapse;">
                            <td><img title="پرینت مجوز" style="width:26px;" src="img/p3.png"
                                     class="permit_img_show_details show_printable_green" permit_id="11583"></td>
                            <td style="text-align:center;">۱۱۵۸۳</td>
                            <td style="text-align:center;position:relative;z-index:999;padding:0px;padding-bottom:1px;">
                                <img title="مشاهده توضیحات مرکز فرمان" style="width:35px;height:35px;margin:0px auto;"
                                     src="img/warn.png" class="permit_img_show_occ_cmnt btnpointer" vhid="true">
                                <div style="position:absolute;top:5px;display:none;" class="permit_occ_hidden">
                                    <div style="float:right;position:absolute;top:10px;right:-9px;">
                                        <div class="rightarrow"></div>
                                    </div>
                                    <div class="cmnt_occ radius5" style="float:left;width:400px;">
                                        <div style="overflow:auto;" class="cmnt_occ_header box_sized">
                                            <div style="float:right;margin-left:5px;margin-right:3px;"><img
                                                        style="width:20px;height:20px;" src="img/warn.png"
                                                        class="permit_img_show_details"></div>
                                            <div style="position:relative;display:inline-block;width:80%;">شرح شرايط
                                                انجام كار
                                            </div>
                                        </div>
                                        <div class="cmnt_occ_body box_sized">"ورود به حریم ریلی اکیدا ممنوع میباشد.<br>این
                                            مجوز جهت تردد در ایستگاه صادر شده است. انجام هر گونه فعالیت در اتاقهای فنی
                                            منوط به حضور ناظر و هماهنگی با دستگاه نظارت مربوطه میباشد.<br>هر گونه فعالیت
                                            منجر به قطع برق تجهیزات یا منتج به ناپایداری و از دسترس خارج شدن و قطع نمایش
                                            اطلاعات ترافیکی سیستم های کنترل ترافیک که باعث اختلال در مسیر سازی و
                                            مانیتورینگ وسایل نقلیه ریلی گردد ممنوع میباشد."
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td style="text-align:center;position:relative;z-index:998;padding:0px;padding-bottom:1px;">
                                <img title="مشاهده شرح عملیات" src="img/mail.png" style="width:28px;height:28px;"
                                     class="permit_img_show_info_req_cmnt btnpointer" vhid="true">
                                <div style="position:absolute;top:0px;display:none;" class="permit_info_req_hidden">
                                    <div style="float:right;position:absolute;top:10px;right:-9px;">
                                        <div class="rightarrowblue"></div>
                                    </div>
                                    <div class="cmnt_desc_req radius5 box_sized" style="float:left;width:400px;">
                                        <div style="overflow:auto;" class="cmnt_desc_req_header box_sized">
                                            <div style="display:inline-block;margin-left:5px;margin-right:3px;"><img
                                                        style="width:20px;height:20px;" src="img/mail.png"
                                                        class="permit_img_show_details"></div>
                                            <div style="position:relative;top:-6px;display:inline-block;width:80%;">شرح
                                                عملیات
                                            </div>
                                        </div>
                                        <div class="cmnt_desc_req_body box_sized">نگهداری و تعمیرات سیستم های مخابراتی و
                                            گیت های AFC
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td style="text-align:center;">سیگنالینگ ، کنترل و مخابرات</td>
                            <td style="text-align:center;direction:ltr;">0938 650 9296</td>
                            <td style="text-align:center;">شرکت آریا همراه سامانه</td>
                            <td style="text-align:center;">اتمام بهره برداری تا 23:59</td>
                            <td style="text-align:center;">اماکن ایستگاهی</td>
                            <td style="text-align:center;position:relative;z-index:999;"><img
                                        title="مشاهده محل کار فعالیت"
                                        style="margin:0px;width:30px;height:30px;position: relative;top:3px;"
                                        src="img/places6.png" class="permit_img_show_nazer_cmnt btnpointer" vhid="true">
                                <div style="position:absolute;top:5px;left:60px;display:none;"
                                     class="permit_nazer_hidden">
                                    <div style="float:left;position:absolute;top:10px;left:-9px;">
                                        <div class="leftarrow"></div>
                                    </div>
                                    <div class="cmnt_nazer radius5 box_sized" style="float:right;width:400px;">
                                        <div style="overflow:auto;" class="cmnt_nazer_header box_sized">
                                            <div style="float:right;margin-left:5px;margin-right:3px;"><img
                                                        style="width:20px;height:20px;" src="img/places6.png"
                                                        class="permit_img_show_details"></div>
                                            <div style="position:relative;display:inline-block;width:80%;">لیست محل
                                                کار
                                            </div>
                                        </div>
                                        <div class="cmnt_nazer_body box_sized">ایستگاه احسان - ایستگاه شریعتی - ایستگاه
                                            میرزا شیرازی - ایستگاه شاهد - ایستگاه قصردشت - ایستگاه مطهری - ایستگاه آوینی
                                            - ایستگاه نمازی - پایانه احسان
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td style="text-align:center;">گروه پیاده</td>
                            <td style="text-align:center;">ندارد</td>
                            <td style="direction: ltr;text-align: center;">۱۳۹۳/۱۱/۲۰</td>
                        </tr>
						<?php
						echo $putil->getTblHeader_greenToday_permits();
						echo '</thead>';
						echo '<tbody id="tbodynum_two"
                                style="display:block ;overflow-y: auto;overflow-x:hidden;">';
						echo $putil->getTblHeader_greenToday_permits_shaghol();
						$total_page   = 0;
						$show_req_obj = new show_request();
						switch ( $roleid ) {
							case 1: // peimankar sakht
							case 2: //peimankar Bahrebardari
								echo $show_req_obj->get_today_permitions_forpeimankar( $_SESSION['userid'] );
								break;
							case 3: //OCC Signed
							case 4: //OCC Un-Signed
							case 7: //Green Nezartchi  for permits
							case 11:
								echo $show_req_obj->get_today_permitions();
								break;
							case 5: //Nazer Bahrebardari
								echo $show_req_obj->get_today_permitions_for_nazer( $_SESSION['userid'] );
								break;
							/*case 6: //Nazer sakht
								break;*/
							/*case 8: //Nazer Sakht - Green
								break;*/
							case 9: //Karbare Darkhast Dahande Sakht
								echo $show_req_obj->get_today_permitions_sakhtuser( $_SESSION['userid'] );
								break;
						}
						?>
                        </tbody>
                    </table>
                </div>
            </div>
            <div id="tabs-3" style="padding:0px;padding-top:5px;height: 94%;width: 100%;">
				<?php
				switch ( $roleid ) {
					case 1: // peimankar sakht
					case 2: //peimankar Bahrebardari
						$total_page = $show_req_obj->get_total_page_peimankar( $_SESSION['userid'] );
						break;
					case 3: //OCC Signed
					case 4: //OCC Un-Signed
						$total_page = $show_req_obj->get_total_page_occ( $_SESSION['userid'] );
						break;
					case 5: //Nazer Bahrebardari
						$total_page = $show_req_obj->get_total_page_nazer( $_SESSION['userid'] );
						break;
					/*case 6: //Nazer sakht
					break;*/
					case 7: //Green Nezartchi  for permits
					case 11:
						$total_page = $show_req_obj->get_request_greenuser_bayegani_total_page( $_SESSION['userid'] );
						break;
					/*case 8: //Nazer Sakht - Green
					break;*/
					case 9: //Karbare Darkhast Dahande Sakht
						$total_page = $show_req_obj->get_total_page_sakhtuser( $_SESSION['userid'] );
						break;
				}
				?>
                <div id="permit_tab3_content" style="height: 100%;width: 100%;">
                    <table class="permit_tbl" id="permit_tbl_bayegani_permits" style="width: 100%;height: 100%;">
                        <thead id="permit_tabl_thead" style="display:block;"><!---->
                        <tr class="footer_row">
                            <td colspan="<?php if ( $roleid == 3 || $roleid == 4 ) {
								echo '13';
							} else {
								echo '14';
							} ?>"
                                style="padding:0px;"
                                class="box_sized">
                                <div class="byekan_13" style="text-align: right;direction: rtl;border:0px solid #68217a;background-color:#dddddd;
												padding:5px;margin:0px;">
                                    <input type="button" title="اول" id="permit_btn_byegani_first"
                                           class="permit_pindex permit_btn_nav" navtxt="first"
                                           style="font-family:BYekanRegular;font-size:13px;" value="«"/>
                                    <input type="button" title="قبلی" id="permit_btn_byegani_pre"
                                           class="permit_pindex permit_btn_nav" navtxt="pre"
                                           style="font-family:BYekanRegular;font-size:13px;" value="‹"/>

                                    <span style="font-family:BYekanRegular;font-size:13px;">صفحه</span>
                                    <input type="text" value="1" id="permit_txtbx_beyegani_pageindex" class="justnumber"
                                           style="color:red;font-family:BYekanRegular;font-size:13px;border:1px solid #0158c1;width:35px;text-align:center;"/>
                                    <span
                                            style="padding:0px 2px;display:inline-block;font-family:BYekanRegular;font-size:13px;">از</span>
                                    <span
                                            style="padding:0px 2px;display:inline-block;"><?php echo $total_page; ?></span>

                                    <input type="button" title="بعدی" id="permit_btn_byegani_nxt"
                                           class="permit_pindex permit_btn_nav" navtxt="nxt"
                                           style="font-family:BYekanRegular;font-size:13px;" value="›"/>
                                    <input type="button" title="آخر" id="permit_btn_byegani_last"
                                           class="permit_pindex permit_btn_nav" navtxt="last"
                                           style="font-family:BYekanRegular;font-size:13px;" value="»"/>
                                </div>
                            </td>
                        </tr>
                        <tr class="permit_header_row">
                            <td colspan="<?php if ( $roleid == 3 || $roleid == 4 ) {
								echo '13';
							} else {
								echo '14';
							} ?>"
                                style="text-align:right;">
                                <div class="btnpointer" id="permit_btn_refresh_rows_bayegani"
                                     style="margin-left:0px;display:inline-block;text-align:center;color:white;background-color:green;width:30px;height:25px;line-height:25px">
                                    R
                                </div>
                                <div id="permit_tbl_refresh_ajax_laoder_bayegani"
                                     style="display:inline;padding-left:5px;padding-right:7px;line-height:25px;height:25px;
										text-align:right;direction:rtl;background-color:#008000;visibility: hidden;">
                                    <img src="img/tblajax.gif"
                                         style="padding:0px;margin:0;margin-left:5px;position:relative;top:4px;"/>در حال
                                    بروز رسانی اطلاعات جدول
                                </div>
                            </td>
                        </tr>
                        <tr class="permit_row_yellow" style="visibility: collapse;">
                            <td><img title="جزئیات" src="img/info2.png" style="width:24px;"
                                     class="permit_img_show_details permit_img_show_details_noedit_info"
                                     permit_id="11701"></td>
                            <td style="text-align:center;">۱۱۷۰۱</td>
                            <td style="text-align:center;position:relative;z-index:999;padding:0px;padding-bottom:1px;">
                                مجوز در حال بررسی می باشد
                            </td>
                            <td style="text-align:center;position:relative;z-index:998;padding:0px;padding-bottom:1px;">
                                <img title="مشاهده شرح عملیات" src="img/mail.png" style="width:28px;height:28px;"
                                     class="permit_img_show_info_req_cmnt btnpointer" vhid="true">
                                <div style="position:absolute;top:0px;display:none;" class="permit_info_req_hidden">
                                    <div style="float:right;position:absolute;top:10px;right:-9px;">
                                        <div class="rightarrowblue"></div>
                                    </div>
                                    <div class="cmnt_desc_req radius5 box_sized" style="float:left;width:400px;">
                                        <div style="overflow:auto;" class="cmnt_desc_req_header box_sized">
                                            <div style="display:inline-block;margin-left:5px;margin-right:3px;"><img
                                                        style="width:20px;height:20px;" src="img/mail.png"
                                                        class="permit_img_show_details"></div>
                                            <div style="position:relative;top:-6px;display:inline-block;width:80%;">شرح
                                                عملیات
                                            </div>
                                        </div>
                                        <div class="cmnt_desc_req_body box_sized">فیکس و تنظیم نمودن cw خط سه توقفگاه .
                                            محدوده : خط ۳ پایانه و محدوده ی بین توقفگاه و ایستگاه احسان هر دو خط درزین
                                            های مورد نیاز ۹۶۰ و ۹۶1 (بسته به شرایط از یکی از درزین ها استفاده خواهد شد).
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td style="text-align:center;">برق</td>
                            <td style="text-align:center;direction:ltr;">2254</td>
                            <td style="text-align:center;">شرکت سامان ساخت برنا تارا</td>
                            <td style="text-align:center;">00:01 بامداد تا شروع بهره برداری</td>
                            <td style="text-align:center;">حریم ریلی پایانه</td>
                            <td style="text-align:center;position:relative;z-index:999;"><img
                                        title="مشاهده محل کار فعالیت"
                                        style="margin:0px;width:30px;height:30px;position: relative;top:3px;"
                                        src="img/places6.png" class="permit_img_show_nazer_cmnt btnpointer" vhid="true">
                                <div style="position:absolute;top:5px;left:60px;display:none;"
                                     class="permit_nazer_hidden">
                                    <div style="float:left;position:absolute;top:10px;left:-9px;">
                                        <div class="leftarrow"></div>
                                    </div>
                                    <div class="cmnt_nazer radius5 box_sized" style="float:right;width:400px;">
                                        <div style="overflow:auto;" class="cmnt_nazer_header box_sized">
                                            <div style="float:right;margin-left:5px;margin-right:3px;"><img
                                                        style="width:20px;height:20px;" src="img/places6.png"
                                                        class="permit_img_show_details"></div>
                                            <div style="position:relative;display:inline-block;width:80%;">لیست محل
                                                کار
                                            </div>
                                        </div>
                                        <div class="cmnt_nazer_body box_sized">پایانه احسان</div>
                                    </div>
                                </div>
                            </td>
                            <td style="text-align:center;">وسیله نقلیه ریلی کمکی</td>
                            <td style="text-align:center;">دارد</td>
                            <td style="direction: ltr;text-align: center;">-</td>
                        </tr>
						<?php
						echo $putil->getTblHeader( $roleid, true );//bayegani header
						echo '</thead>';
						echo '<tbody id="tbodynum_three"
                                style="display:block ;overflow-y: auto;overflow-x:hidden;">';
						echo $putil->getTblHeader_shaghol( $roleid, true );
						switch ( $roleid ) {
							case 1: // peimankar sakht
							case 2: //peimankar Bahrebardari
								echo $show_req_obj->get_request_peimankar_bayegani( $_SESSION['userid'], 1 );
								break;
							case 3: //OCC Signed
							case 4: //OCC Un-Signed
								echo $show_req_obj->get_request_occ_bayegani( $_SESSION['userid'], 1 );
								break;
							case 5: //Nazer Bahrebardari
								echo $show_req_obj->get_request_nazer_bayegani( $_SESSION['userid'], 1 );
								break;
							/*case 6: //Nazer sakht
							break;*/
							case 7: //Green Nezartchi  for permits
							case 11:
								echo $show_req_obj->get_today_request_greenuser_bayegani( $_SESSION['userid'], 1 );
								break;
							/*case 8: //Nazer Sakht - Green
							break;*/
							case 9: //Karbare Darkhast Dahande Sakht
								echo $show_req_obj->get_request_sakhtuser_bayegani( $_SESSION['userid'], 1 );
								break;
						}
						?>
                        </tbody>
                    </table>
                </div>
            </div>
			<?php
			if ( true ) { //if ( $canreq ) {
				?>
                <div id="tabs-4" style="padding:0px !important;padding-top:5px !important;overflow-y: auto;">
					<?php
					include '../lib/new_request_class.php';
					$reqobj = new new_request_class();					
					$peim_can_permit = true;
					if ( $roleid == 1 || $roleid == 2 )
					{
						$ptemp = $reqobj->permit_check_peim_can_request_permit($_SESSION['userid']);
						if($ptemp == 0)
						{
							$peim_can_permit = false;
						}	
					}
					
					if( $peim_can_permit )
					{
					?>
					
                    <div class="box_sized radius5" id="permit_tab4_content"
                         style="border:1px solid #e0e0e0;background-color:white;width:100%;margin:0px auto;margin-bottom:5px;padding-top:10px;overflow-y: auto;">
                        <div class="box_sized radius5"
                             style="max-width:1200px;border:1px solid #dddddd;background-color:#f2f2f2;padding:5px;text-align:center;width:98%;margin:0px auto;margin-bottom:5px;">
                            <div style="margin-top:5px;margin-bottom:5px;outline:0px solid red;">
                                <div style="display:inline-block;direction:rtl;text-align:right;vertical-align: top;">
                                    <fieldset class="permit_fieldset_style radius5"
                                              style="text-align:right;height:auto;width:506px;">
                                        <legend align="" class="radius3">شرح عملیات</legend>
                                        <label
                                                style="margin:0px;margin-bottom:6px;outline:0px solid red;padding:0px;display:block;">شرح
                                            و علت فعالیت:</label>
                                        <textarea id="permit_txtbx_activity_desc" rows="8" maxlength="900"
                                                  class="permit_text_style fadir radius5 box_sized"
                                                  placeholder="انجام فعالیت............. در محدوده ..........."
                                                  style="resize:none;font-family:tahoma !important;font-weight:bold;font-size:15px;width:100%;padding:1px !important;"></textarea>
												  
										<div class="box_sized" id="permit_cmbx_safty_hints_div" style="display:;padding:10px;position:relative;width:100%;border:1px solid #f9f9f9;background-color:#C2D1E8;">
											
											<p style="color:red;background-color:#f2f2f2;padding:10px;">لطفا وضعیت فعالیت را مشخص کنید : <br style="margin:0px;"/> ( استفاده از وسایل حفاظت فردی الزامی می باشد )</p>
											<?php
												$hints_rows = $reqobj->permit_get_safty_act_list();
												foreach ($hints_rows as $hrow) {
											?>													
													<input type="checkbox" name="safty_checkbox" id="permit_chkbx_safty_<?php echo $hrow['hint_id']; ?>" value="<?php echo $hrow['hint_id']; ?>">
													<label class="permit_chkbx_lbl" style="width:70%;" for="permit_chkbx_safty_<?php echo $hrow['hint_id']; ?>"><?php echo $hrow['hint_value']; ?></label>
													<br style="margin:0px;"/>
													
											<?php 
												} 
											?>
										</div>
										
                                    </fieldset>
                                </div>
                                <div style="display:inline-block;direction:rtl;text-align:right;vertical-align: top;">
                                    <fieldset class="permit_fieldset_style radius5" style="height:250px;width:506px;">
                                        <legend align="" class="radius3">شرایط انجام کار</legend>
                                        <div style="position:relative;top:5px;width:100%;display:block;padding-top:0px;">
										
											<input type="checkbox" name="checkbox" id="permit_chkbx_cut_power"
                                                   value="value">
                                            <label class="permit_chkbx_lbl" style="width:70%;"
                                                   for="permit_chkbx_cut_power">نیاز به قطع برق شبکه بالاسری (OCS)
                                                دارد</label>
												<hr/>
												<input type="checkbox" name="checkbox" id="permit_chkbx_no_critical_place"
                                                   value="value">
                                            <label class="permit_chkbx_lbl2" style="width:70%;"
                                                   for="permit_chkbx_no_critical_place">فعالیت در اماکن غیر فنی ایستگاه <a href="tmpfiles/test.pdf" style="color:red;" target="_blank">( دانلود دستورالعمل اماکن غیر فنی )</a></label>
                                        </div>
                                    </fieldset>
                                </div>
                            </div>
                            <div style="margin-top:5px;outline:0px solid red;">
                                <div style="display:inline-block;direction:rtl;text-align:right;vertical-align: top;">
                                    <fieldset class="permit_fieldset_style radius5" id="fieldset_nezarat"
                                              style="height:400px;width:506px;">
                                        <legend align="" class="radius3">دستگاه نظارت</legend>
                                        <div>
                                            <table style="width:100%;padding:0px;margin:0px;">
                                                <tr>
                                                    <td style="width:65%;">
                                                        <div style="margin-bottom:3px;">واحد نظارت بهره برداری :</div>
                                                        <div>
                                                            <select class="permit_cmbox_style"
                                                                    id="permit_cmbx_unit_nezarat" style="width:80%;">
                                                                <option value="0"></option>
																<?php
																echo $reqobj->get_nezarati_vahed_list( $_SESSION['userid'] );
																?>
                                                            </select>
                                                            <img src="img/ajax-loader.gif"
                                                                 id="permit_ajax_loader_unit_nezarat"
                                                                 style="display:none;position:relative;top:5px;"/>
                                                        </div>
                                                    </td>
													<td style="padding:3px;width:34%;" class="box_sized">
                                                        <div style="margin-bottom:3px;">تلفن کشیک:</div>
                                                        <div class="box_sized">
                                                            <input id="permit_txtbx_keshik_tell" type="text"
                                                                   class="radius5 permit_text_style" style="width:80%;"
                                                                   value=""/>
                                                        </div>
                                                    </td>
                                                </tr>
												<?php if($roleid == 1 || $roleid == 2){ ?>
												<tr>
                                                    <td colspan="2" style="color:blue;font-weight:bold;">انتخاب ناظران توسط دستگاه نظارت انجام می شود</td>
												</tr>
												<?php }else{ ?>
                                                <tr>
                                                    <td colspan="2">
                                                        <div style="margin-bottom:3px;">ناظران : <span
                                                                    id="permit_selected_num"
                                                                    style="color:blue;">0 انتخاب</span></div>
                                                        <div
                                                                style="width:500px;max-height:185px;overflow:auto;border:1px solid #dddddd;padding:5px;background-color:#f1f1f1;"
                                                                class="box_sized">
                                                            <table id="permit_tbl_nazer_of_nezarat"
                                                                   style="width:100%;text-align:center;position:relative;"
                                                                   class="permit_nazer_mans box_sized">

                                                                <tr class="permit_tbl_nazer_of_nezarat_header"
                                                                    style="background-color:#898989;color:white;text-align:center;">
                                                                    <td>ناظر</td>
                                                                    <td>نام خانوادگی</td>
                                                                    <td>نام</td>
                                                                    <td>شماره تماس</td>
                                                                    <td>کد ملی</td>
                                                                </tr>


                                                            </table>
                                                        </div>
                                                    </td>
                                                </tr>
												<?php } ?>
                                            </table>
                                        </div>
                                    </fieldset>
                                </div>
                                <div style="display:inline-block;direction:rtl;text-align:right;vertical-align: top;">
                                    <fieldset class="permit_fieldset_style radius5" id="fieldset_peimankar"
                                              style="height:400px;width:506px;">
                                        <legend align="" class="radius3">پیمانکار</legend>
                                        <div>
                                            <table style="width:100%;padding:0px;margin:0px;">
                                                <tr>
                                                    <td colspan="2" style="width:65%;">
                                                        <div style="margin-bottom:3px;">پیمانکار:</div>
                                                        <div>
                                                            <select class="permit_cmbox_style"
                                                                    id="permit_cmbx_peimankar_of_unitnezarat"
                                                                    style="width:80%;">

                                                            </select>
                                                            <img id="permit_ajax_loader_peimankar_of_unitnezarat"
                                                                src="img/ajax-loader.gif"
                                                                style="display:none;position:relative;top:5px;"/>
                                                        </div>
                                                    </td>													
                                                </tr>
												<tr>
                                                    <td colspan="2" style="width:100%;">
                                                        <div style="margin-bottom:3px;">سرپرست گروه کاری:</div>
                                                        <div>
                                                            <select class="permit_cmbox_style"
                                                                    id="permit_cmbx_supervisor_peimankar"
                                                                    style="width:80%;">

                                                            </select>                                                            
                                                        </div>
                                                    </td>																										
                                                </tr>
												<tr>
                                                    <td colspan="2" style="width:100%;">
                                                        <div style="margin-bottom:3px;">جانشین سرپرست گروه کاری:</div>
                                                        <div>
                                                            <select class="permit_cmbox_style"
                                                                    id="permit_cmbx_supervisor_peimankar2"
                                                                    style="width:80%;">

                                                            </select>                                                            
                                                        </div>
                                                    </td>																										
                                                </tr>
												
                                                <tr>
                                                    <td colspan="2">
                                                        <div style="margin-bottom:3px;">لیست نفرات : <span
                                                                    id="permit_selected_num_of_worker"
                                                                    style="color:blue;">0 انتخاب</span>
                                                        </div>
                                                        <div
                                                                style="width:500px;max-height:190px;overflow:auto;border:1px solid #dddddd;padding:5px;background-color:#f1f1f1;"
                                                                class="box_sized">
                                                            <table id="permit_tbl_listof_worker_peimankar"
                                                                   style="margin:0px;width:100%;">
                                                                <tr>
                                                                    <td valign="top" style="width:50%;">
                                                                        <table id="permit_tbl_listof_worker1"
                                                                               style="width:100%;text-align:center;"
                                                                               class="permit_nazer_mans box_sized">
                                                                            <tr class="permit_tbl_listof_worker_header"
                                                                                style="background-color:#898989;color:white;text-align:center;">
                                                                                <td>نام خانوادگی</td>
                                                                                <td>نام</td>
                                                                                <td>کد ملی</td>
                                                                            </tr>

                                                                        </table>
                                                                    </td>
                                                                    <td valign="top" style="width:50%;">
                                                                        <table id="permit_tbl_listof_worker2"
                                                                               style="width:100%;text-align:center;"
                                                                               class="permit_nazer_mans box_sized">
                                                                            <tr class="permit_tbl_listof_worker_header"
                                                                                style="background-color:#898989;color:white;text-align:center;">
                                                                                <td>نام خانوادگی</td>
                                                                                <td>نام</td>
                                                                                <td>کد ملی</td>
                                                                            </tr>

                                                                        </table>
                                                                    </td>
                                                                </tr>
                                                            </table>
                                                        </div>
                                                    </td>
                                                </tr>
                                            </table>
                                        </div>
                                    </fieldset>
                                </div>
                            </div>
                            <div style="margin-top:5px;outline:0px solid red;">
                                <div style="display:inline-block;direction:rtl;text-align:right;vertical-align: top;">
                                    <fieldset class="permit_fieldset_style radius5" style="width:506px;height:;">
                                        <legend align="" class="radius3">محدوده مجوز</legend>
                                        <div>
                                            <table style="width:100%;padding:0px;margin:0px;">
                                                <tr>
                                                    <td>
                                                        <div style="margin-bottom:3px;">زمان انجام فعالیت:</div>
                                                        <div>
                                                            <select id="permit_cmbx_activity_time"
                                                                    class="permit_cmbox_style" style="width:90%;">
                                                                <option value="0"></option>
																<?php
																echo $reqobj->get_activity_do_time_list();
																?>
                                                            </select>
                                                            <img src="img/ajax-loader.gif"
                                                                 id="permit_cmbx_activity_time_ajaxloader"
                                                                 style="display:none;position:relative;top:5px;"/>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <div style="margin-bottom:3px;">شماره خط:</div>
                                                        <div>
                                                            <select id="permit_cmbx_metroline_number"
                                                                    class="permit_cmbox_style" style="width:90%;">
                                                                <option value="0"></option>
																<?php
																echo $reqobj->get_Lines_list();
																?>
                                                            </select>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <div style="margin-bottom:3px;">حوزه کاری:</div>
                                                        <div>
                                                            <select id="permit_cmbx_working_scope"
                                                                    class="permit_cmbox_style" disabled="disabled"
                                                                    style="width:90%;">
                                                            </select>
                                                            <img id="permit_cmbx_working_scope_ajaxloader"
                                                                 src="img/ajax-loader.gif"
                                                                 style="display:none;position:relative;top:5px;"/>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <div style="margin-bottom:7px;position:relative;">محل کار:
                                                            <button id="btn_sall" title="MHM Bugfixes"
                                                                    style="position:absolute;right:60px;top:-1px;">All
                                                            </button>
                                                        </div>
                                                        <div>
                                                            <!--<select id="permit_cmbx_working_place" disabled="disabled"  class="permit_cmbox_style" style="width:90%;"></select>-->
                                                            <div id="permit_cmbx_working_place_div"
                                                                 style="max-height:400px;overflow-y: scroll;width:90%;padding:5px;background-color:#dee0ff;position:relative;"
                                                                 class="box_sized">

                                                            </div>
                                                        </div>
                                                    </td>
                                                </tr>
                                            </table>
                                        </div>
                                    </fieldset>
                                </div>
                                <div style="display:inline-block;direction:rtl;text-align:right;vertical-align: top;">
                                    <fieldset class="permit_fieldset_style radius5" style="height:280px;width:506px;">
                                        <legend align="" class="radius3">نوع مجوز و مانور</legend>
                                        <div>
                                            <table style="width:100%;padding:0px;margin:0px;">
                                                <tr>
                                                    <td style="width:45%;">
                                                        <div style="margin-bottom:3px;">نوع مجوز:</div>
                                                        <div>
                                                            <select id="permit_cmbx_permit_type"
                                                                    class="permit_cmbox_style" style="width:90%;">
                                                                <option value="0"></option>
																<?php
																echo $reqobj->get_mojavez_type_list();
																?>
                                                            </select>
                                                        </div>
                                                    </td>
                                                    <td rowspan="5" valign="top" style="height: 230px;">
                                                        <label
                                                                style="margin:0px;margin-bottom:3px;outline:0px solid red;padding:0px;">شرح
                                                            مانور:</label>
                                                        <span style="font-family:tahoma;font-size:11px;color:red;">
												لطفا شرح کامل و خلاصه ی از مسیر مانور، سوزنهای شانت و حوزه فعالیت ارائه نمایید
												</span>
                                                        <textarea id="permit_txtarea_opt_desc" disabled="disabled"
                                                                  class="permit_txtarea_style radius5 box_sized"
                                                                  style="margin-top:3px;width:100%;height:50%;"></textarea>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <div style="margin-bottom:3px;">قطار:</div>
                                                        <div>
                                                            <select id="permit_cmbx_train_list" disabled="disabled"
                                                                    class="permit_cmbox_style" style="width:90%;">
                                                                <option value="0"></option>
																<?php
																echo $reqobj->get_trains_list();
																?>
                                                            </select>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <div style="margin-bottom:3px;">وسیله نقلیه کمکی / کشنده:</div>
                                                        <div>
                                                            <select id="permit_cmbx_helper_vehicle_list"
                                                                    disabled="disabled" class="permit_cmbox_style"
                                                                    style="width:90%;">
                                                                <option value="0"></option>
																<?php
																echo $reqobj->get_komaki_trains_list();
																?>
                                                            </select>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <div style="margin-bottom:3px;">مبداء:</div>
                                                        <div>
                                                            <select id="permit_cmbx_opt_start" disabled="disabled"
                                                                    class="permit_cmbox_style" style="width:90%;">
                                                                <option value="0"></option>
																<?php
																echo $reqobj->get_mabda_maghsad();
																?>
                                                            </select>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <div style="margin-bottom:3px;">مقصد: ( محل پارک )</div>
                                                        <div>
                                                            <select id="permit_cmbx_opt_end" disabled="disabled"
                                                                    class="permit_cmbox_style" style="width:90%;">
                                                                <option value="0"></option>
																<?php
																echo $reqobj->get_mabda_maghsad();
																?>
                                                            </select>
                                                        </div>
                                                    </td>
                                                </tr>
                                            </table>
                                        </div>
                                    </fieldset>
                                </div>
                            </div>
                            <div style="margin-top:5px;">
                                <fieldset class="permit_fieldset_style radius5 box_sized"
                                          style="width:100%;background-color:#ffefef;border:1px solid red;">
                                    <legend align="center" class="radius3"
                                            style="background-color:red;border:1px solid red;padding:5px 10px;width:200px;">
                                        دستور العمل : توضیحات و نکات مهم
                                    </legend>
                                    <div>
                                        <div style="width:300px;color:black;display:none;">
                                            <ul style="padding:0px;margin:0px;list-style-position: inside;width:100%;">
                                                <li>لطفا اطلاعات وارد شده را کامل کنید.</li>
                                                <li>لطفا اطلاعات وارد شده را کامل کنید.</li>
                                                <li>لطفا اطلاعات وارد شده را کامل کنید.</li>
                                                <li>لطفا اطلاعات وارد شده را کامل کنید.</li>
                                            </ul>
                                        </div>
                                        <div style="text-align:center;">
                                            <div id="permit_formalarm" class="radius5"
                                                 style="width:300px;margin:5px auto;padding:5px;border:1px solid red;display:none;color:red;font-family:tahoma;font-size:13px;">
                                                لطفا اطلاعات فرم را کامل وارد کنید
                                            </div>
											<div style="width:300px;margin:5px auto;padding:5px;border:1px solid red;color:red;font-family:tahoma;font-size:13px;">
                                                <input type="checkbox" name="checkbox" id="agreementrules"
                                                   value="value">
												<label class="permit_chkbx_lbl2" style="width:70%;color:blue;"
													   for="agreementrules">قبل از ارسال درخواست ، تمام موارد و بند های دستورالعمل اخذ مجوز را با دقت خوانده و قبول دارم. <a href="tmpfiles/test.pdf" style="color:red;" target="_blank">( دانلود دستورالعمل اخذ مجوز کار و تردد )</a></label>
                                            </div>
                                            <input id="permit_btn_req_permit" type="button" 
                                                   class="permit_btn_style radius5" disabled
                                                   style="width:120px;background-color:#539f46;border:1px solid #539f46;"
                                                   value="ارسال درخواست"/>
                                        </div>
                                    </div>
                                </fieldset>
                            </div>
                        </div>
                    </div>
                
				    <?php } ?>
				</div>
				<?php
			}
			?>
			<?php

			switch ( $roleid ) {
				case 3: //OCC Signed
				case 4: //OCC Un-Signed
					?>
                <div id="tabs-5" style="padding:0px;padding-top:5px;height: 94%;width: 100%;z-index: 999">
                    <input id="date_btn_1" value="از" type="button"/>
                    <input type="text" readonly="readonly" id="txt_start_date"
                               style="font:13px tahoma;direction: ltr;text-align: center;padding: 5px;"/>


                    <input id="date_btn_2" value="تا" type="button"/>
                    <input type="text" readonly="readonly" id="txt_end_date"
                               style="font:13px tahoma;direction: ltr;text-align: center;padding: 5px;"/>


                        <select id="repostslist"
                                style="font:13px tahoma;direction: rtl;text-align: right;padding: 5px;">
                            <option value="1">گزارش براساس ایستگاه/زمان فعالیت</option>
							<option value="2">گزارش براساس حوزه کاری/زمان فعالیت</option>
                        </select>
                        <input type="button" value="گزارش" id="btn_go" style="font:13px tahoma;padding: 5px 10px;"/>
                        <label id="lbl_report_ajax_loader" style="font: 12px tahoma;"></label>
                        <hr/>
                        <div style="border: 1px solid black;max-height: 500px;overflow-x: hidden;overflow-y: scroll">
                            <table id="tbl_report1" style="width: 600px;">

                            </table>
                        </div>
                    </div>
					<?php
			}
			?>


        </div>
    </div>
    <!-- end of body window -->
</div>