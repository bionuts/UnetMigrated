<?php
//sleep(1);
include '../lib/mas_ajax_handler.php';
$mas_handler = new MASAjaxManager();
?>
<div id="app_mas" class="windowpanel box_sized" style="width:92%;display:none;top:37px;left: 6%;right: 20px;"
     lastxpos="6%" lastypos="37px" lastwidth="92%" lastheight="" maximize="false" minimize="false">
<div class="windowpanel_header box_sized">
    <img class="windowpanel_header_icon" src="img/hamer1.png"/>

    <div class="windowpanel_header_title">نرم افزار اعلام خرابی</div>
    <div class="windowpanel_header_btns">
        <img class="windowpanel_header_btns_close imgwindowpanel" src="img/close1.png"/>
        <img class="windowpanel_header_btns_max imgwindowpanel" src="img/max3.png"/>
        <img class="windowpanel_header_btns_min imgwindowpanel" src="img/min1.png"/>
    </div>
</div>
<div class="windowpanel_body box_sized" style="direction:rtl;bottom:1px;position:absolute;top:31px;">
<div id="mas_tellbug_panel"
     style="position:absolute;width:0px;max-width:600px;z-index:9999;
							   background-color:#fdf4ff;border-right:3px solid #68217a;height:100%;left:0px;top:0px;">
    <div id="mas_tellbug_content" style="display:none;width:100%;padding:5px;height:100%;" class="box_sized">
        <table style="width: 100%;font-family:BYekanRegular !important;	font-size:13px;" class="box_sized">
            <tr>
                <td style="width: 40%;">
                    <span>شماره خط :</span><img id="mas-bug-loader-line"
                                                style="display:none;position: relative;top:3px;right:5px;margin: 0;padding: 0;"
                                                src="img/mas-ajax-loader.gif"/><br/>
                    <select id="mas_cmbx_bug_line" class="byekan-13" style="width: 100%;">
                        <?php
                        echo $mas_handler->get_metro_line_list();
                        ?>
                    </select>
                </td>
                <td rowspan="5" style="width: 60%;vertical-align: top;">
                    <span>شرح خرابی :</span><br/>
                    <textarea id="mas_txtarea_bug_desc" rows="5" class="byekan-13 box_sized fadir validate[required]" style="width: 100%;padding: 5px;"></textarea>
                </td>
            </tr>

            <tr>
                <td>
                    <span>سیستم : </span>
                    <img id="mas-bug-loader-sys"
                         style="display:none;position: relative;top:3px;right:5px;margin: 0;padding: 0;"
                         src="img/mas-ajax-loader.gif"/><br/>
                    <select class="byekan-13" style="width: 100%;" id="mas-cmbx-bug-sys">
                        <option value="0"></option>
                        <?php
                        echo $mas_handler->get_sys_from_line_list();
                        ?>
                    </select>
                </td>
            </tr>
            <tr>
                <td>
                    <span>زیر سیستم : </span><img id="mas-bug-loader-subsys"
                                                  style="display:none;position: relative;top:3px;right:5px;margin: 0;padding: 0;"
                                                  src="img/mas-ajax-loader.gif"/> <br/>
                    <select id="mas-cmbx-bug-subsys" class="byekan-13" style="width: 100%;" disabled="disabled">
                    </select>
                </td>
            </tr>
            <tr>
                <td>
                    <span>نام تجهیز :</span><img id="mas-bug-loader-tajhiz"
                                                 style="display:none;position: relative;top:3px;right:5px;margin: 0;padding: 0;"
                                                 src="img/mas-ajax-loader.gif"/><br/>
                    <select id="mas_cmbx_bug_equip" class="byekan-13" style="width: 100%;" disabled="disabled">
                    </select>
                </td>
            </tr>
            <tr>
                <td>
                    <span>محل/شماره خرابی :</span><img id="mas-bug-loader-nplace"
                                                       style="display:none;position: relative;top:3px;right:5px;margin: 0;padding: 0;"
                                                       src="img/mas-ajax-loader.gif"/><br/>
                    <select id="mas_cmbx_bug_nplace"  disabled="disabled" class="byekan-13" style="width: 100%;">
                    </select>
                </td>
            </tr>
            <tr>
                <td colspan="2" style="text-align: right;background-color: #e1ffe3;">
                    <span>شماره سریال تجهیز :</span><br/>
                    <select id="mas_cmbx_bug_tajhiz_serial"
                            style="width: 100%;font-family: tahoma;font-size: 16px;font-weight: bold;" class="cnt_cmbx">
                    </select>
                </td>
            </tr>
            <tr>
                <td colspan="2" style="text-align: center !important;">
                    <div
                        style="margin: 5px auto;background-color: #f3c2ff;height: 30px;width: 120px;color: black;direction: rtl;
                        text-align: center;line-height: 30px;vertical-align: middle;">
                        در حال ارسال...
                    </div>
                    <input type="button" value="ارسال" class="mas_btnico_style"
                           style="margin-left: 5px;width: 100px;"/>
                    <input type="button" value="انصراف" class="mas_btnico_style" style="width: 100px;"/>
                </td>
            </tr>
        </table>
    </div>
</div>

<div id="mas_track_panel" trackshow="false"
     style="position:absolute;width:0px;max-width:400px;z-index:9999;
			background-color:#fdf4ff;border-right:3px solid #68217a;height:100%;left:0px;top:0px;">
    <div id="mas_track_content" style="display:none;width:100%;padding:5px;height:100%;position:relative;"
         class="box_sized">
        <div id="mas_tbl_track_btnimg_panel"
             style="text-align:center;z-index:999;display:none;background-color:white;border-bottom:2px solid #68217a;position:absolute;top:0px;left:0px;width:100%;padding:5px;"
             class="box_sized">
            <textarea rows="6" style="width:100%;padding:5px;" class="box_sized"></textarea><br/>
            <input id="st1" type="radio" value="1" name="bug_status">
            <label for="st1">بدون اقدام</label>
            <input id="st2" type="radio" value="2" name="bug_status">
            <label for="st2">در دست اقدام</label>
            <input id="st3" type="radio" value="3" name="bug_status">
            <label for="st3">رفع شده</label>
            <br/>
            <input type="button" id="mas_tbl_track_btnimg_panel_send" class="mas_btn" style="width:50px;"
                   value="ارسال"/>
            <input type="button" id="mas_tbl_track_btnimg_panel_close" class="mas_btn" style="width:50px;"
                   value="انصراف"/>
        </div>
        <div id="mas_tbl_track_btnimg_panel_ref"
             style="text-align:center;z-index:999;display:none;background-color:white;
					 border-bottom:2px solid #68217a;position:absolute;top:0px;left:0px;width:100%;padding:5px;"
             class="box_sized">
            <table class="box_sized" style="width: 100%;">
                <tbody>
                <tr>
                    <td style="width: 50%;">
                        <label for="cmb_netcat">واحد :</label><br>
                        <select id="cmb_netcat_1" name="cmb_netcat" class="mas_cmb_style">
                            <option value="1">تاسیسات برقی</option>
                            <option value="2">مخابرات</option>
                            <option value="3">پست های برق فشار قوی</option>
                            <option value="4">OCS</option>
                            <option value="5">سیگنالینگ و کنترل</option>
                            <option value="6">تاسیسات مکانیکی</option>
                            <option value="7">تاسیسات تهویه</option>
                        </select>
                    </td>
                    <td style="width: 50%;">
                        <label for="cmb_naghscat">بخش خرابی :</label><br>
                        <select id="cmb_naghscat" name="cmb_naghscat" class="mas_cmb_style">
                            <option value="1">پله برقی و آسانسورها</option>
                            <option value="2">تاسیسات برقی عمومی ایستگاها و مسیر</option>
                            <option value="3">برق اضطراری و UPS ها</option>
                            <option value="4">ساختمان کنترل و توقفگاه</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                        <div class="box_sized" style="font: 13px tahoma; width: 100%;text-align: right;">شرح رجوع به
                            واحد دیگر :
                        </div>
                        <textarea rows="6" style="width:100%;padding:5px;" class="box_sized"></textarea>
                    </td>
                </tr>
                </tbody>
            </table>
            <input type="button" id="mas_tbl_track_btnimg_panel_refsend" style="width:50px;" class="mas_btn"
                   value="ارجاع"/>
            <input type="button" id="mas_tbl_track_btnimg_panel_close_ref" style="width:50px;" class="mas_btn"
                   value="انصراف"/>
        </div>
        <div style="height:30px;position:relative;margin-bottom:5px;border-bottom:1px solid black;">
            <img class="mas_tbl_track_btnimg" id="id_mas_tbl_track_btnimg" src="img/feed.png" title="درج پیام"
                 style="width:24px;height:24px;"/>
            <img class="mas_tbl_track_btnimg" id="id_mas_tbl_track_btnimg_ref" src="img/ref.png" title="ارجاع"
                 style="width:24px;height:24px;"/>
        </div>
        <div id="mas_tbl_pm_thread" style="max-height:90%;overflow:auto;">
            <div class="mas_tbl_pm_item box_sized radius5">
                <div style="overflow:auto;">
                    <div
                        style="float:left;color:red;direction:ltr;text-align:center;width:30%;font-family:tahoma;font-size:11px;">
                        1393/07/23<br/>13:21:32
                    </div>
                    <div style="float:right;width:64%;">محمد رضا جهانخواه - مسئول AFC و آنالیز برداری در بهره برداری
                    </div>
                </div>
                <hr/>
                <span style="color:blue;">عنوان : شکستگی سکوبانهای سکوی داوطلبی در حد </span><br/>
                محمد رضا جهانخواه
                محمد رضا جهانخواه محمد رضا جهانخواهمحمد رضا جهانخواه <br/>
                محمد رضا جهانخواه
                محمد رضا جهانخواه
                محمد رضا جهانخواه محمد رضا جهانخواهمحمد رضا جهانخواهمحمد رضا جهانخواه
                محمد رضا جهانخواه
                محمد رضا جهانخواه محمد رضا جهانخواهمحمد رضا جهانخواه
            </div>
            <div class="mas_tbl_pm_item box_sized radius5">
                <div style="overflow:auto;">
                    <div
                        style="float:left;color:red;direction:ltr;text-align:center;width:30%;font-family:tahoma;font-size:11px;">
                        1393/07/23<br/>13:21:32
                    </div>
                    <div style="float:right;width:70%;">محمد رضا جهانخواه - مسئول AFC و آنالیز برداری در بهره برداری
                    </div>
                </div>
                <hr/>
                محمد رضا جهانخواه
                محمد رضا جهانخواه محمد رضا جهانخواهمحمد رضا جهانخواه <br/>
                محمد رضا جهانخواه
                محمد رضا جهانخواه
                محمد رضا جهانخواه محمد رضا جهانخواهمحمد رضا جهانخواهمحمد رضا جهانخواه
                محمد رضا جهانخواه
                محمد رضا جهانخواه محمد رضا جهانخواهمحمد رضا جهانخواه
            </div>
            <div class="mas_tbl_pm_item box_sized radius5">
                <div style="overflow:auto;">
                    <div
                        style="float:left;color:red;direction:ltr;text-align:center;width:30%;font-family:tahoma;font-size:11px;">
                        1393/07/23<br/>13:21:32
                    </div>
                    <div style="float:right;width:70%;">محمد رضا جهانخواه - مسئول AFC و آنالیز برداری در بهره برداری
                    </div>
                </div>
                <hr/>
                محمد رضا جهانخواه
                محمد رضا جهانخواه محمد رضا جهانخواهمحمد رضا جهانخواه <br/>
                محمد رضا جهانخواه
                محمد رضا جهانخواه
                محمد رضا جهانخواه محمد رضا جهانخواهمحمد رضا جهانخواهمحمد رضا جهانخواه
                محمد رضا جهانخواه
                محمد رضا جهانخواه محمد رضا جهانخواهمحمد رضا جهانخواه
            </div>
            <div class="mas_tbl_pm_item box_sized radius5">
                <div style="overflow:auto;">
                    <div
                        style="float:left;color:red;direction:ltr;text-align:center;width:30%;font-family:tahoma;font-size:11px;">
                        1393/07/23<br/>13:21:32
                    </div>
                    <div style="float:right;width:70%;">محمد رضا جهانخواه - مسئول AFC و آنالیز برداری در بهره برداری
                    </div>
                </div>
                <hr/>
                محمد رضا جهانخواه
                محمد رضا جهانخواه محمد رضا جهانخواهمحمد رضا جهانخواه <br/>
                محمد رضا جهانخواه
                محمد رضا جهانخواه
                محمد رضا جهانخواه محمد رضا جهانخواهمحمد رضا جهانخواهمحمد رضا جهانخواه
                محمد رضا جهانخواه
                محمد رضا جهانخواه محمد رضا جهانخواهمحمد رضا جهانخواه
            </div>
            <div class="mas_tbl_pm_item box_sized radius5">
                <div style="overflow:auto;">
                    <div
                        style="float:left;color:red;direction:ltr;text-align:center;width:30%;font-family:tahoma;font-size:11px;">
                        1393/07/23<br/>13:21:32
                    </div>
                    <div style="float:right;width:70%;">محمد رضا جهانخواه - مسئول AFC و آنالیز برداری در بهره برداری
                    </div>
                </div>
                <hr/>
                محمد رضا جهانخواه<br/>
                محمحمحمحمحمحمحمحمحمحمحمحمحمحمحمحمحمحمحمحمحمحمحمحمحمحمحمحمحمحمحمحمحمحمحمحمحمحمحمحمحمحمحمحمحمحمحمحمحمحمحمحمحمحمحمحمحمح
            </div>
        </div>
    </div>
</div>

<table class="mas_tbl">
<tr class="permit_header_row" style="background-color: #68217a;">
    <td style="text-align:right;" colspan="13">
        <div style="display:inline-block;">
            <input bugteller="false" id="btn" type="button" value="new"/>
        </div>
        <div
            style="margin-left:0px;display:inline-block;text-align:center;color:white;background-color:green;width:30px;height:25px;line-height:25px"
            id="permit_btn_refresh_rows" class="btnpointer">R
        </div>
        <div style="display:none;padding-left:5px;padding-right:7px;line-height:25px;height:25px;
									text-align:right;direction:rtl;background-color:#008000;"
             id="permit_tbl_refresh_ajax_laoder">
            <img style="padding:0px;margin:0;margin-left:5px;position:relative;top:4px;" src="img/tblajax.gif">در حال
            بروز رسانی اطلاعات جدول
        </div>
    </td>
</tr>
<tr class="header_row">
<td style="width:7%;vertical-align:bottom;">
    <div>شماره خرابی</div>
    <div>
        <input class="box_sized" style="color:#68217a;padding:3px;text-align:center;
						border:1px solid #68217a;width:100%;" type="text" id="txt_bugid"/>
    </div>
</td>
<td style="width:10%;vertical-align:bottom;">
    <div>وضیعت</div>
    <div style="position:relative;" id="mas_tbl_header_bugstatus">
        <div class="tbl_search_ddl_chkbx box_sized"
             style="border:1px solid #68217a;color:#68217a;background-color:white;" vis="false">
            همه موارد
        </div>
        <div class="tbl_search_ddl_chkbx_content"
             style="border:1px solid #68217a;background-color:#f8ebfc;color:black;width:150px;">
            <ul style="margin:0px;padding:0px;list-style-type:none;">
                <li>
                    <input checked="checked" class="st_chkbx_group st_chkbx_all" id="chk1" type="checkbox"
                           value="0"/>
                    <label for="chk1">همه موارد</label>
                </li>
                <li>
                    <input checked="checked" class="st_chkbx_group" id="chk2" lbl="بدون اقدام" type="checkbox"
                           value="1"/>
                    <label for="chk2">بدون اقدام</label>
                </li>
                <li>
                    <input checked="checked" class="st_chkbx_group" id="chk3" lbl="در دست اقدام" type="checkbox"
                           value="2"/>
                    <label for="chk3">در دست اقدام</label>
                </li>
                <li>
                    <input checked="checked" class="st_chkbx_group" id="chk4" lbl="رفع شده" type="checkbox"
                           value="3"/>
                    <label for="chk4">رفع شده</label>
                </li>
            </ul>
        </div>
    </div>
</td>
<td style="width:15%;vertical-align:bottom;">
    <div>واحد</div>
    <div style="position:relative;" id="mas_tbl_header_unitcat">
        <div class="tbl_search_ddl_chkbx box_sized"
             style="border:1px solid #68217a;color:#68217a;background-color:white;" vis="false">
            همه موارد
        </div>
        <div class="tbl_search_ddl_chkbx_content"
             style="border:1px solid #68217a;background-color:#f8ebfc;color:black;width:150px;">
            <ul style="margin:0px;padding:0px;list-style-type:none;">
                <li>
                    <input checked="checked" class="st_chkbx_group st_chkbx_all" id="chkk1" type="checkbox"
                           value="0"/>
                    <label for="chkk1">همه موارد</label>
                </li>
                <li>
                    <input checked="checked" class="st_chkbx_group" id="chkk2" lbl="سیگنالینگ" type="checkbox"
                           value="1"/>
                    <label for="chkk2">سیگنالینگ</label>
                </li>
                <li>
                    <input checked="checked" class="st_chkbx_group" id="chkk3" lbl="تاسیسات برقی" type="checkbox"
                           value="2"/>
                    <label for="chkk3">تاسیسات برقی</label>
                </li>
                <li>
                    <input checked="checked" class="st_chkbx_group" id="chkk4" lbl="تاسیسات مکانیکی" type="checkbox"
                           value="3"/>
                    <label for="chkk4">تاسیسات مکانیکی</label>
                </li>
                <li>
                    <input checked="checked" class="st_chkbx_group" id="chkk5" lbl="مخابرات" type="checkbox"
                           value="3"/>
                    <label for="chkk5">مخابرات</label>
                </li>
                <li>
                    <input checked="checked" class="st_chkbx_group" id="chkk6" lbl="ناوگان" type="checkbox"
                           value="3"/>
                    <label for="chkk5">ناوگان</label>
                </li>
            </ul>
        </div>
    </div>
</td>
<td style="width:20%;vertical-align:bottom;">
    <div>بخش خرابی</div>
    <div style="position:relative;" id="mas_tbl_header_naghscat">
        <div class="tbl_search_ddl_chkbx box_sized"
             style="border:1px solid #68217a;color:#68217a;background-color:white;" vis="false">
            همه موارد
        </div>
        <div class="tbl_search_ddl_chkbx_content"
             style="border:1px solid #68217a;background-color:#f8ebfc;color:black;width:150px;">
            <ul style="margin:0px;padding:0px;list-style-type:none;">
                <li>
                    <input checked="checked" class="st_chkbx_group st_chkbx_all" id="chk111" type="checkbox"
                           value="0"/>
                    <label for="chk111">همه موارد</label>
                </li>
                <li>
                    <input checked="checked" class="st_chkbx_group" id="chk222" lbl="AFC" type="checkbox"
                           value="1"/>
                    <label for="chk222">AFC</label>
                </li>
                <li>
                    <input checked="checked" class="st_chkbx_group" id="chk333" lbl="مدار راه" type="checkbox"
                           value="2"/>
                    <label for="chk333">مدار راه</label>
                </li>
                <li>
                    <input checked="checked" class="st_chkbx_group" id="chk444" lbl="سوزن" type="checkbox"
                           value="3"/>
                    <label for="chk444">سوزن</label>
                </li>
                <li>
                    <input checked="checked" class="st_chkbx_group" id="chk555" lbl="اینترلاکینگ" type="checkbox"
                           value="3"/>
                    <label for="chk555">اینترلاکینگ</label>
                </li>
                <li>
                    <input checked="checked" class="st_chkbx_group" id="chk666" lbl="سیگنالینگ" type="checkbox"
                           value="3"/>
                    <label for="chk666">سیگنالینگ</label>
                </li>
            </ul>
        </div>
    </div>
</td>
<td style="width:20%;vertical-align:bottom;">
    <div>عنوان</div>
    <div>
        <input class="box_sized"
               style="color:red;padding:3px;font-family:tahoma;font-size:12px;text-align:right;direction:rtl;border:1px solid #68217a;width:100%;"
               type="text" id="txt_bugid"/>
    </div>
</td>
<td style="width:10%;vertical-align:bottom;">
    <div>مکان</div>
    <div style="position:relative;" id="mas_tbl_header_np">
        <div class="tbl_search_ddl_chkbx box_sized"
             style="border:1px solid #68217a;color:#68217a;background-color:white;" vis="false">
            همه موارد
        </div>
        <div class="tbl_search_ddl_chkbx_content"
             style="border:1px solid #68217a;background-color:#f8ebfc;color:black;width:150px;">
            <ul style="margin:0px;padding:0px;list-style-type:none;">
                <li>
                    <input checked="checked" class="st_chkbx_group st_chkbx_all" id="chk11" type="checkbox"
                           value="0"/>
                    <label for="chk11">همه موارد</label>
                </li>
                <li>
                    <input checked="checked" class="st_chkbx_group" id="chk22" lbl="ایستگاه نمازی" type="checkbox"
                           value="1"/>
                    <label for="chk22">ایستگاه نمازی</label>
                </li>
                <li>
                    <input checked="checked" class="st_chkbx_group" id="chk33" lbl="ایستگاه قصرالدشت"
                           type="checkbox" value="2"/>
                    <label for="chk33">ایستگاه قصرالدشت</label>
                </li>
            </ul>
        </div>
    </div>
</td>
<td style="width:10%;vertical-align:bottom;">
    <div>تاریخ</div>
    <div style="position:relative;" id="mas_tbl_header_np">
        <div class="mass_date_fromto box_sized" style="font-size:11px;
									width:100%;text-align:center;color:red;" vis="false">
            ----/--/--<br/>----/--/--
        </div>
        <div class="mass_date_fromto_content"
             style="display:none;border:1px solid #68217a;position:absolute;z-index:999;width:150px;
										background-color:#f8ebfc;color:black;text-align:right;padding:5px;">
            از <input id="pcal1" type="text"
                      style="text-align:center;font-family: BYekanRegular;font-size: 13px;width: 100px;border:1px solid #68217a;margin-bottom:5px;"/>
            تا <input id="pcal2" type="text"
                      style="text-align:center;font-family: BYekanRegular;font-size: 13px;width: 100px;border:1px solid #68217a;"/>
        </div>
    </div>
</td>
<td style="width:10%;vertical-align:bottom;">
    <div>مدت زمان رفع</div>
    <div style="position:relative;" id="mas_tbl_header_np">
        <div class="mass_range box_sized" style="font-size:10px;
									width:100%;text-align:center;color:red;direction:ltr;padding:2px;text" vis="false">
            a <= x <= b
        </div>
        <div class="mass_range_content"
             style="display:none;border:1px solid #68217a;position:absolute;z-index:999;width:220px;
										left:0px;
										background-color:#f8ebfc;color:black;text-align:right;padding:5px;">
            <table style="width:100%;">
                <tr>
                    <td style="text-align:left;">
													<span
                                                        style="font-family:tahoma;font-size:12px;display:inline;direction:rtl;padding:3px;">
													بزرگتر مساوی
													</span>
                    </td>
                    <td>
                        <input type="text" id="mas_gtrange" style="direction:ltr;border:1px solid #68217a;display:inline;text-align:center;
														color:red;padding:3px;font-family:tahoma;font-size:12px;"
                               size="5"/>
                        <span style="font-family:tahoma;font-size:12px;">ساعت</span>
                    </td>
                </tr>
                <tr>
                    <td style="text-align:left;">
													<span
                                                        style="font-family:tahoma;	font-size:12px;display:inline;direction:rtl;padding:3px;">
														کوچکتر مساوی
													</span>
                    </td>
                    <td>
                        <input type="text" id="mas_ltrange" style="direction:ltr;border:1px solid #68217a;display:inline;text-align:center;
														color:red;padding:3px;font-family:tahoma;font-size:12px;"
                               size="5"/>
                        <span style="font-family:tahoma;font-size:12px;">ساعت</span>
                    </td>
                </tr>
            </table>
        </div>
    </div>
</td>
</tr>
<tr class="data_row" style="background-color:#b1ffae;">
    <td style="text-align:center;">525</td>
    <td><img class="img_show_details" src="img/solve.png" title="رفع شده"/></td>
    <td style="text-align:center;">سیگنالینگ</td>
    <td style="text-align:center;">مدار سوزن</td>
    <td style="text-align:center;">قطعی در مدار راه 17.0</td>
    <td style="text-align:center;">ایستگاه نمازی</td>
    <td style="text-align:center;">1393/02/23 15:23:45</td>
    <td style="text-align:center;">24.89 ساعت</td>
</tr>
<tr class="data_row" style="background-color:#6aabed;">
    <td style="text-align:center;">525</td>
    <td><img class="img_show_details" src="img/progress.png" title="در دست اقدام"/></td>
    <td style="text-align:center;">سیگنالینگ</td>
    <td style="text-align:center;">مدار سوزن</td>
    <td style="text-align:center;">قطعی در مدار راه 17.0</td>
    <td style="text-align:center;">ایستگاه نمازی</td>
    <td style="text-align:center;">1393/02/23 15:23:45</td>
    <td style="text-align:center;">24.89 ساعت</td>
</tr>
<tr class="data_row" style="background-color:#ffaeae;">
    <td style="text-align:center;">525</td>
    <td><img class="img_show_details" src="img/nothing.png" title="بدون اقدام"/></td>
    <td style="text-align:center;">سیگنالینگ</td>
    <td style="text-align:center;">مدار سوزن</td>
    <td style="text-align:center;">قطعی در مدار راه 17.0</td>
    <td style="text-align:center;">ایستگاه نمازی</td>
    <td style="text-align:center;">1393/02/23 15:23:45</td>
    <td style="text-align:center;">24.89 ساعت</td>
</tr>
<tr class="footer_row">
    <td colspan="8" style="padding:0px;" class="box_sized">
        <div class="byekan_13" style="text-align: right;direction: rtl;border:0px solid #68217a;background-color:#d3d3d3;
									padding:5px;margin:0px;">
            <input type="button" title="اول" id="mas_tbl_refresh" class="pindex" value="«"/>
            <input type="button" title="قبلی" id="mas_tbl_refresh" class="pindex" value="‹"/>

            <span style="font-family:tahoma;font-size:12px;">صفحه</span>
            <input type="text" value="1" id="txt_page"
                   style="color:red;font-family:tahoma;font-size:11px;border:1px solid #68217a;width:30px;text-align:center;"/>
            <span style="padding:0px 2px;display:inline-block;">از</span>
            <span style="padding:0px 2px;display:inline-block;">35</span>

            <input type="button" title="بعدی" id="mas_tbl_refresh" class="pindex" value="›"/>
            <input type="button" title="آخر" id="mas_tbl_refresh" class="pindex" value="»"/>
            <img id="btn_tbl_refresh" src="img/fixloader.png" style=""/>
            <img src="img/loader3.gif" style="position:relative;top:3px;display:none;"/>
            <span id="mas_loading_lable" style="color:#68217a;font-family:tahoma;font-size:12px;display:none;">در حال بارگذاری ...</span>
        </div>
    </td>
</tr>
</table>
<script type="text/javascript">
    var objCal1 = new AMIB.persianCalendar('pcal1');
    var objCal2 = new AMIB.persianCalendar('pcal2');
</script>
</div>
<!-- end of body window -->
</div> <!-- end of mass window -->