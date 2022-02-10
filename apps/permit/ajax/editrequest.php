<?php
session_start();
include '../lib/permit-config.php';
include '../../../util/util.php';
include '../lib/permitUtil.php';
include '../lib/new_request_class.php';

$permitid = trim($_POST['permitid']);
if (!ctype_digit($permitid)) exit;

$reqobj = new new_request_class();
$arrtextvalue = $reqobj->get_request_info($permitid);
$requester = $reqobj->get_requester_user($permitid);

$txtopt = false;
$train = false;
$cotrain = false;
$src = false;
$dst = false;

cmbx_disabled_enabled_device($arrtextvalue['fktype_mojavez_permit_main_id']);

function cmbx_disabled_enabled_device($pt)
{
    global $txtopt;
    global $train;
    global $cotrain;
    global $src;
    global $dst;

    if ($pt == 0 || $pt == 1)//no selection or piyade
    {
        $train = false;
        $cotrain = false;
        $src = false;
        $dst = false;
        $txtopt = false;
    } else if ($pt == 2)//viechle
    {
        $txtopt = true;
        $train = false;
        $cotrain = true;
        $src = true;
        $dst = true;
        //$('#permit_txtarea_opt_desc_edit').val('').prop( "disabled", false );
        //$('#permit_cmbx_train_list_edit').val(0).prop( "disabled", true );
        //$('#permit_cmbx_helper_vehicle_list_edit,#permit_cmbx_opt_start_edit,#permit_cmbx_opt_end_edit').val(0).prop( "disabled", false );
    } else if ($pt == 3)//garm
    {
        $txtopt = true;
        $train = true;
        $cotrain = false;
        $src = true;
        $dst = true;
        //$('#permit_cmbx_helper_vehicle_list_edit').val(0).prop( "disabled", true );
        //$('#permit_txtarea_opt_desc_edit').val('').prop( "disabled", false );
        //$('#permit_cmbx_train_list_edit,#permit_cmbx_opt_start_edit,#permit_cmbx_opt_end_edit').val(0).prop( "disabled", false );
    } else if ($pt == 4)//sard
    {
        $txtopt = true;
        $train = true;
        $cotrain = true;
        $src = true;
        $dst = true;
        //$('#permit_txtarea_opt_desc_edit').val('').prop( "disabled", false );
        //$('#permit_cmbx_train_list_edit,#permit_cmbx_helper_vehicle_list_edit,#permit_cmbx_opt_start_edit,#permit_cmbx_opt_end_edit').val(0).prop( "disabled", false );
    }
}

?>

<div id="permit_edit_fromnazer_req"
     style="position:absolute;width:100%;z-index:9999;background-color:#fdf4ff;height:100%;left:0px;top:0px;">
    <div id="permit_confirm_req_content" style="width:100%;padding:5px;height:100%;overflow:auto;"
         class="box_sized">
        <div
            style="position:relative;color:white;background-color:#f7b527;width:100%;padding:10px;margin:5px auto;font-family:BYekanRegular;font-size:13px;"
            class="box_sized radius5 fadir ">
            نکته : فرم ویرایش درخواست
            <img id="permit_img_close_editpanel" class="btnpointer" src="img/close1.png"
                 style="width:20px;height:20px;position:absolute;left:5px;top:10px;"/>
        </div>

        <div class="box_sized radius5"
             style="border:1px solid #e0e0e0;background-color:white;width:100%;margin:0px auto;margin-bottom:5px;padding-top:10px;">
            <div class="box_sized radius5"
                 style="max-width:1200px;border:1px solid #dddddd;background-color:#ffedc6;padding:5px;text-align:center;width:98%;margin:0px auto;margin-bottom:5px;">
                <div style="margin-top:5px;margin-bottom:5px;outline:0px solid red;">
                    <div style="display:inline-block;direction:rtl;text-align:right;vertical-align: top;">
                        <fieldset class="permit_fieldset_style radius5"
                                  style="text-align:right;height:auto;width:506px;">
                            <legend align="" class="radius3">شرح عملیات</legend>
                            <label
                                style="margin:0px;margin-bottom:6px;outline:0px solid red;padding:0px;display:block;">شرح
                                و علت فعالیت:</label>
							<textarea id="permit_txtbx_activity_desc_edit" rows="8" maxlength="900"
                                      class="permit_text_style fadir radius5 box_sized"
                                      placeholder="انجام فعالیت............. در محدوده ..........."
                                      style="resize:none;font-family:tahoma !important;font-weight:bold;font-size:15px;width:100%;padding:1px !important;"><?php echo $arrtextvalue['permit_main_sharhamaliat']; ?></textarea>
									  
							<div class="box_sized" id="permit_cmbx_safty_hints_div" style="display:;padding:10px;position:relative;width:100%;border:1px solid #f9f9f9;background-color:#C2D1E8;">
											
								<p style="color:red;background-color:#f2f2f2;padding:10px;">لطفا وضعیت فعالیت را مشخص کنید : <br style="margin:0px;"/> ( استفاده از وسایل حفاظت فردی الزامی می باشد )</p>
								<?php
									echo $reqobj->get_safty_hint_list_multicheckbox($permitid);
								?>
							</div>
							<hr/>
							<p style="display:;direction:rtl;text-align:right;font-family:tahoma;font-size:13px;font-weight:bold;">توضیحات و احتیاطات لازم از سمت ناظر :</P>
							<p style="display:;direction:rtl;text-align:right;font-family:tahoma;font-size:13px;"><?php echo $arrtextvalue['supervisor_hint']; ?></P>
                        </fieldset>
                    </div>
                    <div style="display:inline-block;direction:rtl;text-align:right;vertical-align: top;">
                        <fieldset class="permit_fieldset_style radius5" style="height:220px;width:506px;">
                            <legend align="" class="radius3">شرایط انجام کار</legend>
                            <div style="position:relative;top:20px;width:100%;">
                                <input type="checkbox" name="checkbox"
                                       id="permit_chkbx_cut_power_edit" <?php echo(($arrtextvalue['permit_main_niazbeghaatbargh'] == 1 ? 'checked' : '')); ?> />
                                <label class="permit_chkbx_lbl" style="width:70%;" for="permit_chkbx_cut_power">نیاز به
                                    قطع برق شبکه بالاسری (OCS) دارد</label>
								<hr/>
								<input type="checkbox" name="checkbox" 
                                       id="permit_chkbx_non_critical_edit" <?php echo(($arrtextvalue['is_non_critical'] == 1 ? 'checked' : '')); ?> />
                                <label class="permit_chkbx_lbl" style="width:70%;" for="permit_chkbx_non_critical_edit">فعالیت در اماکن غیر فنی ایستگاه</label>
								
								<hr/>
								<input type="checkbox" name="checkbox" 
                                       id="permit_chkbx_with_supervisor_edit" <?php echo(($arrtextvalue['non_critical_with_supervisor'] == 1 ? 'checked' : '')); ?> />
                                <label class="permit_chkbx_lbl" style="width:70%;color:red;" for="permit_chkbx_with_supervisor_edit">حضور ناظر در هنگام ورود ( شروع به کار ) الزامی نیست</label>
								
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
                                        <td colspan="2">
                                            <div style="margin-bottom:3px;">
                                                <span>درخواست کننده مجوز : </span><span
                                                    style="color:red;"><?php echo $requester['users_fname'] . ' ' . $requester['users_lname']; ?></span><br/>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="width:65%;">
                                            <div style="margin-bottom:3px;">
                                                <span>واحد نظارت : </span><span
                                                    style="color:red;"><?php echo $arrtextvalue['vahednezarat_name']; ?></span>
                                            </div>
                                        </td>
                                        <td style="padding:3px;width:34%;" class="box_sized">
                                            <div style="margin-bottom:3px;">تلفن کشیک:</div>
                                            <div class="box_sized">
                                                <input id="permit_txtbx_keshik_tell_edit" type="text"
                                                       class="radius5 permit_text_style" style="width:80%;"
                                                       value="<?php echo $arrtextvalue['tel_vahedkeshik_permit_main']; ?>"/>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="2">
                                            <div style="margin-bottom:3px;">ناظر گروه : <span id="permit_selected_num"
                                                                                              style="color:blue;">0 انتخاب</span>
                                            </div>
                                            <div
                                                style="width:500px;max-height:185px;overflow:auto;border:1px solid #dddddd;padding:5px;background-color:#f1f1f1;"
                                                class="box_sized">
                                                <table id="permit_tbl_nazer_of_nezarat_edit"
                                                       style="width:100%;text-align:center;position:relative;"
                                                       class="permit_nazer_mans box_sized">
                                                    <tr class="permit_tbl_nazer_of_nezarat_header_edit"
                                                        style="background-color:#898989;color:white;text-align:center;">
                                                        <td>ناظر</td>
                                                        <td>نام خانوادگی</td>
                                                        <td>نام</td>
                                                        <td>کد ملی</td>
                                                        <td>شماره تماس</td>
                                                    </tr>
                                                    <?php
                                                    $rows = $reqobj->get_nazers_list_edit($arrtextvalue['vahednezarat_id']);
                                                    $selrows = $reqobj->get_selected_nazers_list($arrtextvalue['permit_main_id']);
                                                    $trstr = '';
                                                    //selected
                                                    foreach ($rows as $r) {
                                                        $id = $r['users_id'];
                                                        $fn = $r['users_fname'];
                                                        $ln = $r['users_lname'];
                                                        $mob = $r['userdetail_mobile'];
                                                        $code = $r['userdetail_codemelli'];

                                                        $select_tmp = '';
                                                        if (!is_null($selrows)) {
                                                            if (in_array($id, $selrows)) {
                                                                $select_tmp = 'selected';
                                                            } else {
                                                                $select_tmp = '';
                                                            }
                                                        }

                                                        $trstr .= '<tr class="permit_tbl_nazer_of_nezarat_row ' . $select_tmp . '" nazer_id="' . $id . '">';
                                                        $trstr .= '<td><img src = "img/ddetails.png" style="position:relative;top:2px;" class="img_get_moreinfo btnpointer" /></td>';
                                                        $trstr .= '<td>' . $ln . '</td>';
                                                        $trstr .= '<td>' . $fn . '</td>';
                                                        $trstr .= '<td>' . $mob . '</td>';
                                                        $trstr .= '<td>' . $code . '</td></tr>';
                                                    }
                                                    echo $trstr;
                                                    ?>
                                                </table>
                                            </div>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </fieldset>
                    </div>
                    <div style="display:inline-block;direction:rtl;text-align:right;vertical-align: top;">
                        <fieldset class="permit_fieldset_style radius5" id="fieldset_peimankar"
                                  style="height:400px;width:506px;">
                            <legend align="" class="radius3">ناظر پیمانکار</legend>
                            <div>
                                <table style="width:100%;padding:0px;margin:0px;">
                                    <tr>
                                        <td>
                                            <div style="margin-bottom:3px;"><span>پیمانکار : </span><span
                                                    style="color:red;"><?php echo $arrtextvalue['users_fname'] . ' ' . $arrtextvalue['users_lname']; ?></span>
                                            </div>
                                        </td>
                                    </tr>
									<tr>
                                        <td>
                                            <div style="margin-bottom:3px;">سرپرست گروه کاری:</div>
                                            <div>
                                                <select id="permit_cmbx_supervisor_peimankar_edit" class="permit_cmbox_style"
                                                        style="width:90%;">                                                    
                                                    <?php
                                                    echo $reqobj->get_peim_supervisor_list($arrtextvalue['fkpeimankar_permit_main_id'],$arrtextvalue['first_peim_supervisor']);
                                                    ?>
                                                </select>                                                
                                            </div>
                                        </td>
                                    </tr>
									<tr>
                                        <td>
                                            <div style="margin-bottom:3px;">جانشین سرپرست گروه کاری:</div>
                                            <div>
                                                <select id="permit_cmbx_supervisor_peimankar2_edit" class="permit_cmbox_style"
                                                        style="width:90%;">                                                    
                                                    <?php
                                                    echo $reqobj->get_peim_supervisor_list($arrtextvalue['fkpeimankar_permit_main_id'],$arrtextvalue['second_peim_supervisor']);
                                                    ?>
                                                </select>                                                
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <div style="margin-bottom:3px;">لیست نفرات : <span
                                                    id="permit_selected_num_of_worker"
                                                    style="color:blue;">0 انتخاب</span></div>
                                            <div
                                                style="width:500px;max-height:190px;overflow:auto;border:1px solid #dddddd;padding:5px;background-color:#f1f1f1;"
                                                class="box_sized">
                                                <?php
                                                $rows = $reqobj->get_peymankars_nafarat_list_edit($arrtextvalue['fkpeimankar_permit_main_id']);
                                                $selrows = $reqobj->get_selected_peimankar_list($arrtextvalue['permit_main_id']); // peimankar_listnafarat_id
                                                $tbl1 = '';
                                                $tbl2 = '';
                                                $turn = true;
                                                foreach ($rows as $r) {
                                                    $id = $r['peimankar_listnafarat_id'];
                                                    $fn = $r['peimankar_listnafarat_fname'];
                                                    $ln = $r['peimankar_listnafarat_lname'];
                                                    $code = $r['peimankar_listnafarat_codemelli'];

                                                    if ($turn) {
                                                        $select_tmp = '';
                                                        if (!is_null($selrows)) {
                                                            if (in_array($id, $selrows)) {
                                                                $select_tmp = 'selected';
                                                            } else {
                                                                $select_tmp = '';
                                                            }
                                                        }
                                                        $tbl1 .= '<tr class="permit_tbl_listof_worker_row ' . $select_tmp . '" peimankar_worker_id="' . $id . '">';
                                                        $tbl1 .= '<td>' . $ln . '</td>';
                                                        $tbl1 .= '<td>' . $fn . '</td>';
                                                        $tbl1 .= '<td>' . $code . '</td></tr>';
                                                        $turn = false;
                                                    } else {
                                                        $select_tmp = '';
                                                        if (!is_null($selrows)) {
                                                            if (in_array($id, $selrows)) {
                                                                $select_tmp = 'selected';
                                                            } else {
                                                                $select_tmp = '';
                                                            }
                                                        }

                                                        $tbl2 .= '<tr class="permit_tbl_listof_worker_row ' . $select_tmp . '" peimankar_worker_id="' . $id . '">';
                                                        $tbl2 .= '<td>' . $ln . '</td>';
                                                        $tbl2 .= '<td>' . $fn . '</td>';
                                                        $tbl2 .= '<td>' . $code . '</td></tr>';
                                                        $turn = true;
                                                    }
                                                }

                                                ?>
                                                <table id="permit_tbl_listof_worker_peimankar"
                                                       style="margin:0px;width:100%;">
                                                    <tr>
                                                        <td valign="top" style="width:50%;">
                                                            <table id="permit_tbl_listof_worker1_edit"
                                                                   style="width:100%;text-align:center;"
                                                                   class="permit_nazer_mans box_sized">
                                                                <tr class="permit_tbl_listof_worker_header_edit"
                                                                    style="background-color:#898989;color:white;text-align:center;">
                                                                    <td>نام خانوادگی</td>
                                                                    <td>نام</td>
                                                                    <td>کد ملی</td>
                                                                </tr>
                                                                <?php
                                                                echo $tbl1;
                                                                ?>
                                                            </table>
                                                        </td>
                                                        <td valign="top" style="width:50%;">
                                                            <table id="permit_tbl_listof_worker2_edit"
                                                                   style="width:100%;text-align:center;"
                                                                   class="permit_nazer_mans box_sized">
                                                                <tr class="permit_tbl_listof_worker_header_edit"
                                                                    style="background-color:#898989;color:white;text-align:center;">
                                                                    <td>نام خانوادگی</td>
                                                                    <td>نام</td>
                                                                    <td>کد ملی</td>
                                                                </tr>
                                                                <?php
                                                                echo $tbl2;
                                                                ?>
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
                                                <select id="permit_cmbx_activity_time_edit" class="permit_cmbox_style"
                                                        style="width:90%;">
                                                    <option value="0"></option>
                                                    <?php
                                                    echo $reqobj->get_activity_do_time_list($arrtextvalue['fkdo_activity_permit_main_id']);
                                                    ?>
                                                </select>
                                                <img src="img/ajax-loader.gif"
                                                     id="permit_cmbx_activity_time_ajaxloader_edit"
                                                     style="display:none;position:relative;top:5px;"/>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <div style="margin-bottom:3px;">شماره خط:</div>
                                            <div>
                                                <select id="permit_cmbx_metroline_number_edit"
                                                        class="permit_cmbox_style" style="width:90%;">
                                                    <option value="0"></option>
                                                    <?php
                                                    echo $reqobj->get_Lines_list($arrtextvalue['fklinenumber_permit_main_id']);
                                                    ?>
                                                </select>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <div style="margin-bottom:3px;">حوزه کاری:</div>
                                            <div>
                                                <select id="permit_cmbx_working_scope_edit" class="permit_cmbox_style"
                                                        style="width:90%;">
                                                    <?php
                                                    echo $reqobj->get_kari_hoze_list($arrtextvalue['fkdo_activity_permit_main_id'], $arrtextvalue['fkhozekari_permit_main_id']);
                                                    ?>
                                                </select>
                                                <img id="permit_cmbx_working_scope_ajaxloader_edit"
                                                     src="img/ajax-loader.gif"
                                                     style="display:none;position:relative;top:5px;"/>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <div style="margin-bottom:3px;">محل کار:</div>
                                            <div>
                                                <!--<select id="permit_cmbx_working_place_edit"   class="permit_cmbox_style" style="width:90%;">
													<?php
                                                echo $reqobj->get_kari_place_list($arrtextvalue['fkhozekari_permit_main_id'],
                                                    $arrtextvalue['fklinenumber_permit_main_id'], $arrtextvalue['fkmahal_kar_permit_main_id']);
                                                ?>
												</select>-->
                                                <div id="permit_cmbx_working_place_div_edit"
                                                     style="width:90%;padding:5px;background-color:#ffe6e6;"
                                                     class="box_sized">
                                                    <!--fkmahal_kar_permit_main_id_multi-->
                                                    <?php
                                                    echo $reqobj->get_kari_place_list_multicheckbox($arrtextvalue['fkhozekari_permit_main_id'],
                                                        $arrtextvalue['fklinenumber_permit_main_id'], $arrtextvalue['fkmahal_kar_permit_main_id_multi']);
                                                    ?>
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
                                                <select id="permit_cmbx_permit_type_edit" class="permit_cmbox_style"
                                                        style="width:90%;">
                                                    <option value="0"></option>
                                                    <?php
                                                    echo $reqobj->get_mojavez_type_list($arrtextvalue['fktype_mojavez_permit_main_id']);
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
											<textarea
                                                id="permit_txtarea_opt_desc_edit" <?php if (!$train) echo 'disabled="disabled"'; ?>
                                                class="permit_txtarea_style radius5 box_sized"
                                                style="margin-top:3px;width:100%;height:50%;"><?php echo $arrtextvalue['permit_main_sharhmanovr']; ?></textarea>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <div style="margin-bottom:3px;">قطار:</div>
                                            <div>
                                                <select
                                                    id="permit_cmbx_train_list_edit" <?php if (!$train) echo 'disabled="disabled"'; ?>
                                                    class="permit_cmbox_style" style="width:90%;">
                                                    <option value="0"></option>
                                                    <?php
                                                    echo $reqobj->get_trains_list($arrtextvalue['fktrains_permit_main_id']);
                                                    ?>
                                                </select>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <div style="margin-bottom:3px;">وسیله نقلیه کمکی / کشنده:</div>
                                            <div>
                                                <select
                                                    id="permit_cmbx_helper_vehicle_list_edit" <?php if (!$cotrain) echo 'disabled="disabled"'; ?>
                                                    class="permit_cmbox_style" style="width:90%;">
                                                    <option value="0"></option>
                                                    <?php
                                                    echo $reqobj->get_komaki_trains_list($arrtextvalue['fkkomaki_trains_permit_main_id']);
                                                    ?>
                                                </select>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <div style="margin-bottom:3px;">مبداء:</div>
                                            <div>
                                                <select
                                                    id="permit_cmbx_opt_start_edit" <?php if (!$src) echo 'disabled="disabled"'; ?>
                                                    class="permit_cmbox_style" style="width:90%;">
                                                    <option value="0"></option>
                                                    <?php
                                                    echo $reqobj->get_mabda_maghsad($arrtextvalue['fkmabdae_maghsad_mabdae_id']);
                                                    ?>
                                                </select>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <div style="margin-bottom:3px;">مقصد:</div>
                                            <div>
                                                <select
                                                    id="permit_cmbx_opt_end_edit" <?php if (!$dst) echo 'disabled="disabled"'; ?>
                                                    class="permit_cmbox_style" style="width:90%;">
                                                    <option value="0"></option>
                                                    <?php
                                                    echo $reqobj->get_mabda_maghsad($arrtextvalue['fkmabdae_maghsad_maghsad_id']);
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
                                style="background-color:red;border:1px solid red;padding:5px 10px;width:200px;">دستور
                            العمل : توضیحات و نکات مهم
                        </legend>
                        <div>
                            <div style="width:300px;color:black;">
                                <ul style="padding:0px;margin:0px;list-style-position: inside;width:100%;">
                                    <li>لطفا اطلاعات وارد شده را کامل کنید.</li>
                                    <li>لطفا اطلاعات وارد شده را کامل کنید.</li>
                                    <li>لطفا اطلاعات وارد شده را کامل کنید.</li>
                                    <li>لطفا اطلاعات وارد شده را کامل کنید.</li>
                                </ul>
                            </div>
                            <div style="text-align:center;position:relative;">
                                <div id="permit_formalarm" class="radius5"
                                     style="width:300px;margin:5px auto;padding:5px;border:1px solid red;display:none;color:red;font-family:tahoma;font-size:13px;">
                                    لطفا اطلاعات فرم را کامل وارد کنید
                                </div>
                                <lable id="lbl_permit_btn_taeed_req_permit_ajaxgif"
                                       style="color:#306b1a;direction:ltr;text-align:left;position:relative;top:2px;display:none;">
                                    ... Loading
                                </lable>
                                <img src="img/allll.gif" id="permit_btn_taeed_req_permit_ajaxgif"
                                     style="position: relative;top:6px;display:none ;"/><br/><br/>
                                <input id="permit_btn_taeed_req_permit" permitid="<?php echo $permitid; ?>"
                                       type="button" class="permit_btn_style radius5"
                                       style="width:120px;background-color:#539f46;border:1px solid #539f46;"
                                       value="تائید درخواست (<?php date_default_timezone_set("Asia/Tehran");echo date('H'); ?>)"/>
                                <input id="permit_btn_rad_by_nazer" type="button" class="permit_btn_style radius5"
                                       style="width:120px;background-color:#ef3203;border:1px solid #ef3203;"
                                       value="رد درخواست"/>

                                <div id="permit_why_rad_nazer"
                                     style="width:400px;padding:5px;background-color:white;display:none;position:absolute;bottom:43px;right:0;left:0;margin:0px auto;border:2px solid #ef3203;"
                                     class="radius5 box_sized">
                                    <div class="fadir"
                                         style="font-family: tahoma !important;font-size: 13px;text-align:right;margin-bottom:4px;">
                                        دلیل رد درخواست :
                                    </div>
                                    <textarea id="permit_txtarea_dalilradnazer"
                                              style="margin-bottom:3px;width:100%;resize:none;padding:3px;"
                                              class="fadir box_sized" rows="5"></textarea><br/>
                                    <lable id="lbl_permit_btn_dalil_rad_nazer_ajaxgif"
                                           style="color:#ef3203;direction:ltr;text-align:left;position:relative;top:2px;display:none;">
                                        ... Loading
                                    </lable>
                                    <img src="img/ajaxjj.gif" id="permit_btn_dalil_rad_nazer_ajaxgif"
                                         style="position: relative;top:6px;display: none;"/><br/><br/>
                                    <input type="button" permitid="<?php echo $permitid; ?>" value="ارسال"
                                           id="permit_btn_dalil_rad_nazer" class="radius5 btnpointer"
                                           style="margin:0px auto;padding:5px;font-family: tahoma !important;font-size: 13px;	color:white;width:120px;background-color:#ef3203;border:1px solid #ef3203;"/>

                                    <div class="bottomarrow" style="position:absolute;bottom:-11px;left:100px;"></div>
                                </div>
								
								<div id="permit_hint_nazer"
                                     style="width:400px;padding:5px;background-color:white;display:none;position:absolute;bottom:43px;right:0;left:0;margin:0px auto;border:2px solid #234BED;"
                                     class="radius5 box_sized">
                                    <div class="fadir"
                                         style="font-family: tahoma !important;font-size: 13px;text-align:right;margin-bottom:4px;">
                                        توضیحات و احتیاطات لازم از سمت ناظر :
                                    </div>
                                    <textarea id="permit_txtarea_hint_nazer"
                                              style="margin-bottom:3px;width:100%;resize:none;padding:3px;font-family: tahoma !important;font-size: 13px;text-align:right;"
                                              class="fadir box_sized" rows="5"><?php echo $arrtextvalue['supervisor_hint']; ?></textarea><br/>
                                    <lable id="lbl_permit_btn_dalil_hint_nazer_ajaxgif"
                                           style="color:#234BED;direction:ltr;text-align:left;position:relative;top:2px;display:none;">
                                        ... Loading
                                    </lable>
                                    <img src="img/ajaxjj.gif" id="permit_btn_dalil_hint_nazer_ajaxgif"
                                         style="position: relative;top:6px;display: none;"/><br/><br/>
                                    <input type="button" permitid="<?php echo $permitid; ?>" value="تایید درخواست"
                                           id="permit_btn_dalil_hint_nazer" class="radius5 btnpointer"
                                           style="margin:0px auto;padding:5px;font-family: tahoma !important;font-size: 13px;	color:white;width:120px;background-color:#234BED;border:1px solid #234BED;"/>

                                    <div class="blue_bottomarrow" style="position:absolute;bottom:-11px;right:100px;"></div>
                                </div>
								
                            </div>
                        </div>
                    </fieldset>
                </div>
            </div>
        </div>
    </div>
</div>