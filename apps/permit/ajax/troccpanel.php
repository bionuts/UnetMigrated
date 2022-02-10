<?php
session_start();
include '../lib/permit-config.php';
include '../../../util/util.php';
include '../lib/permitUtil.php';

$util = new UtilClass();
if ($_SESSION['hashuser'] != $util->hashuser($_SESSION["userid"] . $_SESSION["username"] . $_SERVER['REMOTE_ADDR'] . $_SERVER['HTTP_USER_AGENT'])) {
    exit();
}
$putil = new permitUtil();
$roleid = $putil->getUserRoleID($_SESSION['userid']);
$roleid = $roleid[0];
if ($roleid != 3 and $roleid != 11) exit; //occ and omoor istgah
$permitid = trim($_POST['permitid']);
if (!ctype_digit($permitid)) exit;
include '../lib/new_request_class.php';
$reqobj = new new_request_class();
$row = $reqobj->show_new_request_2($permitid);
?>

<div id="user_more_info_main" class="taeedradocc box_sized radius5 user_more_info"
     style="display:none;position:absolute;z-index:99999;margin:0px auto;right:0;left:0;width:700px;">
    <?php //print_r($row); ?>
    <div id="user_more_info_header" class="box_sized user_more_info">
        <div id="user_more_info_title" style="font-family:BYekanRegular;font-size:13px;width:400px;text-align:right;">
            اطلاعات درخواست مجوز ، به شماره <?php echo $row['permit_main_id']; ?>
        </div>
        <div id="user_more_info_exit" class="box_sized user_more_info">
            <img id="permit_close_usermoreinfo" class="btnpointer" style="width:16px;height:16px;"
                 src="img/close1.png"/>
        </div>
    </div>

    <div id="user_more_info_form_info" class="box_sized user_more_info"
         style="background-color:white;max-height:590px;overflow:auto;">

        <div id="user_more_info_form_info_internal" class="box_sized user_more_info">
            <table style="width:100%;background-color:#ffffff;">
                <tr>
                    <td style="width:20%;background-color:lightgray;text-align:left;">
                        <span>درخواست دهنده : </span>
                    </td>
                    <td><?php echo $row['users_fname'] . ' ' . $row['users_lname']; ?></td>
                </tr>
                <tr>
                    <td style="width:20%;background-color:lightgray;text-align:left;">
                        <span>واحد نظارت : </span>
                    </td>
                    <td>
                        <?php echo $row['vahednezarat_name']; ?>
                    </td>
                </tr>
                <tr>
                    <td style="width:20%;background-color:lightgray;text-align:left;">
                        <span>نام پیمانکار : </span>
                    </td>
                    <td><?php
                        if(is_null(@$row['pfn']))
                        {
                            echo $row['users_fname'] . ' ' . $row['users_lname'];
                        }
                        else {
                            echo $row['pfn'] . ' ' . $row['pln'];
                        }
                        ?>

                    </td>
                </tr>
                <tr>
                    <td style="width:20%;background-color:lightgray;text-align:left;">
                        <span>تلفن کشیک : </span>
                    </td>
                    <td style="direction: ltr;text-align: right;">
                        <?php echo $util->format_phone($row['tel_vahedkeshik_permit_main']); ?>
                    </td>
                </tr>
                <tr>
                    <td style="width:20%;background-color:lightgray;text-align:left;vertical-align: top;">
                        <span>علت فعالیت : </span>
                    </td>
                    <td style="direction: rtl;text-align: right;">
                        <?php echo $row['permit_main_sharhamaliat']; ?>
                    </td>
                </tr>
				
				<tr style="display:;">
                    <td style="width:20%;background-color:lightgray;text-align:left;vertical-align: top;">
                        <span>توضیحات و احتیاطات از سمت ناظر : </span>
                    </td>
                    <td style="direction: rtl;text-align: right;">
                        <?php echo $row['supervisor_hint']; ?>
                    </td>
                </tr>
				
            </table>
            <hr style="margin: 0;"/>
            <table style="width:100%;background-color:#ffffff;">
                <tr>
                    <td style="width:20%;background-color:lightgray;text-align:left;">
                        <span>زمان انجام فعالیت : </span>
                    </td>
                    <td style="width:30%;">
                        <?php echo $row['do_activity_name']; ?>
                    </td>
                    <td style="width:20%;background-color:lightgray;text-align:left;">
                        <span>نوع مجوز : </span>
                    </td>
                    <td>
                        <?php echo $row['type_mojavez_name']; ?>
                    </td>
                </tr>
                <tr>
                    <td style="width:20%;background-color:lightgray;text-align:left;">
                        <span>شماره خط : </span>
                    </td>
                    <td>
                        <?php echo $row['linenumber_name']; ?>
                    </td>
                    <td style="width:20%;background-color:lightgray;text-align:left;">
                        <span>قطار : </span>
                    </td>
                    <td>
                        <?php if (is_null($row['trains_name'])) echo '-'; else echo $row['trains_name']; ?>
                    </td>
                </tr>
                <tr>
                    <td style="width:20%;background-color:lightgray;text-align:left;">
                        <span>حوزه کاری : </span>
                    </td>
                    <td>
                        <?php echo $row['hozekari_name']; ?>
                    </td>
                    <td style="width:20%;background-color:lightgray;text-align:left;">
                        <span>وسیله نقلیه کمکی : </span>
                    </td>
                    <td>
                        <?php if (is_null($row['komaki_trains_name'])) echo '-'; else  echo $row['komaki_trains_name']; ?>
                    </td>
                </tr>
                <tr>
                    <td style="width:20%;background-color:lightgray;text-align:left;vertical-align: top;">
                        <span>محل کار : </span>
                    </td>
                    <td style="">
                        <?php echo $row['mahal_kar_name']; ?>
                    </td>
                    <td style="width:20%;background-color:lightgray;text-align:left;">
                        <span>مبداء : </span>
                    </td>
                    <td>
                        <?php if (is_null($row['stpnt'])) echo '-'; else echo $row['stpnt']; ?>
                    </td>
                </tr>
                <tr>
                    <td style="width:20%;background-color:#d51818;text-align:left;color:white;">
                        نیاز به قطع برق (OCS)؟
                    </td>
                    <td style="font-weight: bold;">
                        <?php echo(($row['permit_main_niazbeghaatbargh'] == 1) ? '<span style="color:red;">دارد</span>' : '<span style="color:blue;">ندارد</span>'); ?>
                    </td>
                    <td style="width:20%;background-color:lightgray;text-align:left;">
                        <span>مقصد : </span>
                    </td>
                    <td>
                        <?php if (is_null($row['endpnt'])) echo '-'; else echo $row['endpnt']; ?>
                    </td>
                </tr>
				<tr>
                    <td style="width:25%;background-color:#d51818;text-align:left;color:white;">
                        فعالیت در اماکن غیر فنی ؟
                    </td>
                    <td style="font-weight: bold;">
                        <?php echo(($row['is_non_critical'] == 1) ? '<span style="color:blue;">بلی</span>' : '<span style="color:red;">خیر</span>'); ?>
                    </td>
                    <td style="width:20%;background-color:lightgray;text-align:left;">
                        
                    </td>
                    <td>
                        
                    </td>
                </tr>
				
				<?php if($row['non_critical_with_supervisor'] == 1){ ?>
				<tr>
                    <td colspan="4" style="width:25%;text-align:right;color:blue;font-weight:bold;">                        
                                <label class="permit_chkbx_lbl" style="width:70%;color:blue;">حضور ناظر در هنگام ورود ( شروع به کار ) الزامی نیست</label>
                    </td>                                        
                </tr>
				<?php } else { ?>
				
				<tr>
                    <td colspan="4" style="width:25%;text-align:right;color:blue;font-weight:bold;">                        
                                <label class="permit_chkbx_lbl" style="width:70%;color:red;">حضور ناظر الزامی می باشد</label>
                    </td>                                        
                </tr>
				
				<?php } ?>
                <tr>
                    <td colspan="4" style="background-color: #d3d3d3;">
                        <span style="font-weight:bold;">شرح مانور : </span><br/>
                        <?php if (is_null($row['permit_main_sharhmanovr']) || $row['permit_main_sharhmanovr']== '' ) echo '-'; else echo $row['permit_main_sharhmanovr']; ?>
                    </td>
                </tr>
            </table>
            <hr style="margin: 0;"/>
            <div
                style="text-align:center;display:block;background-color:#d6ffdb;padding:5px;width:100%;margin:5px auto;"
                class="box_sized">
                <div style="font-family: tahoma !important;font-size: 13px;text-align:right;margin-bottom:4px;"
                     class="fadir">شرح کاربر :
                </div>
                <textarea id="permit_txtarea_dalilradocc" rows="4" class="fadir box_sized"
                          style="margin-bottom:3px;width:100%;resize:none;padding:3px;"><?php if (is_null($row['permit_main_dalilradocc']) || $row['permit_main_dalilradocc']== '' ) echo ' '; else echo $row['permit_main_dalilradocc'];?></textarea><br>

                <input permitid="<?php echo $row['permit_main_id']; ?>" trp="1"
                       class="permit_btn_trp_occ_req_permit btnpointer" id="permit_btn_taeed_occ_req_permit"
                       type="button" value="تائید درخواست"
                       style="margin:0px auto;padding:5px;font-family: tahoma !important;font-size: 13px;	color:white;width:120px;background-color:#539f46;border:1px solid #539f46;"
                       class="btnpointer radius5"/>
                <input permitid="<?php echo $row['permit_main_id']; ?>" trp="0"
                       class="permit_btn_trp_occ_req_permit btnpointer" id="permit_btn_rad_occ_req_permit" type="button"
                       style="margin:0px auto;padding:5px;font-family: tahoma !important;font-size: 13px;	color:white;width:120px;background-color:#ef3203;border:1px solid #ef3203;"
                       class="radius5 btnpointer" value="رد درخواست"/>
            </div>
        </div>
    </div>
</div>