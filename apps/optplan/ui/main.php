<?php
session_start();
include '../lib/optplan_ajax_handler.php';
$optplan = new OPTPlanAjaxManager();
$isoccman = $optplan->canedit($_SESSION['userid']);
?>
<div id="optplan" occman="<?php echo (($isoccman)?'true':'false'); ?>"
     style="min-width: 700px;width:80%;top:37px;left: 10%;right: 20px;position: fixed;background-color: #d5d5d5;z-index: 2;border:1px solid #c3c3c3;padding-bottom:8px;"
     class="radius5">
<div style="position: absolute;left: 5px;top: 5px;text-align: center;">
    <input type="button" isocc="<?php echo (($isoccman)?'true':'false'); ?>" style="font:12px tahoma;" id="optplan_btn_refreshall_pm" value="بروز رسانی"/><br/>
    <img src="img/optplanajax.gif" id="optplan_btn_refreshall_pm_img" style="display:none ;"/>
    <span id="optplan_btn_refreshall_pm_lbl" style="display: none;font: 11px tahoma;">انجام شد</span>
</div>
<div
    style="position: relative;color:#062543;width: 80%;font-family: tahoma !important;font-size: 12px;max-height: 100px;overflow: auto;text-align: right;direction: rtl;margin: 5px auto;margin-bottom: 5px;"
    class="box_sized">    
    <?php
    if (!$isoccman) {
        echo '<div style="max-height:100px;border-right:3px solid black;padding-right:5px;" class="box_sized"><span style="font-weight:bold;width:300px;display:inline-block;margin-bottom:3px;">پنل برنامه ریزی بهره برداری (شماره داخلی مرکز فرمان : 2255 ، 2266 ، 2288):</span><br/>'.nl2br($optplan->get_hint_txt()).'</div>';
    } else {
        echo '<textarea id="optplan_txtarea_hints_txt" style="border:1px solid #2a5d8f;padding: 5px !important;width: 100%;padding: 3px;resize: none;font-family: tahoma, arial;font-size: 13px;color:#0c2c4b;background-color: #ffffff;" rows="3" class="box_sized fadir">' . $optplan->get_hint_txt() . '</textarea>';
    }

    if ($isoccman) {
        ?>
        <div style="display: inline-block;margin-top: 3px;">
            <input type="button" class="btnpointer" id="opt_plan_btn_send_daily"
                   style="border:1px solid #0d569e;background-color:#0d569e;padding:4px;color:white;font:13px tahoma;"
                   day="today"
                   value="ذخیره برنامه"/>
            <img id="optplan_img_dailyloader" src="img/mas-ajax-loader.gif" style="display: none;"/>
            <span id="span_optplan_img_dailyloader" style="font-family: tahoma, arial;font-size: 12px;"></span>
        </div>
    <?php
    }
    ?>
</div>
<div style="width: 100%;" class="box_sized">
    <div id="centeredmenu">
        <ul class="byekan-13 " style="font-size: 16px;">
            <li><a class="optplan-tablinker" relink="optplan_panel1" href="#">برنامه فردا</a></li>
            <li><a class="optplan-tablinker active" relink="optplan_panel2" href="#">برنامه جاری امروز</a></li>
            <li><a class="optplan-tablinker" relink="optplan_panel3" href="#">گزارش عملیات دیروز</a></li>
        </ul>
    </div>
    <div id="optplan_panel1" class="optplan_tab_panel box_sized"
         style="display:none ;width: 100%;overflow: auto;">
        <?php
		if ($isoccman) 
		{
		?>
        <div id="optplan_graph" style="padding:5px 0px;width: 100%;margin: 0px auto;position: relative;height: 180px;" class="box_sized">
            <img id="optplan_tomorrow_img_bib_tomorrow" src="apps/optplan/img/<?php echo $optplan->get_saved_img_tomorrow(); ?>" 
				style="height: 60%;width: 40%;background-size:100%;margin: 0px auto;display:block;"
                 class="box_sized radius5 blackshadow" />
            
                <div
                    style="direction: rtl;text-align: center;display: block;padding: 5px;margin: 0px auto;">
                    <input type="file" id="opt_plan_map_file_tomorrow"/>
                    <input type="button" id="opt_plan_btn_sendimg_tomorrow" style="font:13px tahoma;" day="today"
                           value="ارسال"/>
                    <img id="optplan_img_fuploadloader_tomorrow" src="img/mas-ajax-loader.gif"
                         style="display: none;"/>
                </div>            
        </div>
		<?php
            }
            /*else
            {
                echo '<div style="text-align:center;padding-top: 5px;" class="byekan-13">';
                echo 'نقشه حرکتی وسایل نقلیه در حریم ریلی';
                echo '</div>';
            }*/
		?>
        <div id="optplan_optnotes" class="box_sized"
             style="position:relative;font-family: tahoma, arial;font-size: 13px;width: 100%;
                 overflow: none;text-align: right;direction: rtl;padding: 10px;margin: 0px auto;"> 
            <?php
            if (!$isoccman) {
				echo '<div style="border-right:8px solid #ff5a5a;background-color:white;padding:5px;width:100%;" class="box_sized"><span style="font-weight:bold;width:300px;display:inline-block;margin-bottom:3px;">نکات مهم فردا :</span><br/>'.($optplan->get_note_for_tomorrow()).'</div>';
            } else {
                echo '<textarea class="box_sized fadir optplan_txtarea_txteditor" id="optplan_txtarea_notes_tomorrow" style="border:1px solid #f7d6ff;width: 100%;padding: 3px;resize: none;font-family: tahoma, arial;font-size: 13px;color:#52305a;" >' . $optplan->get_note_for_tomorrow() . '</textarea>';
            }

            if ($isoccman) {
                ?>
                <div style="display: inline-block;margin-top: 3px;">
                    <input type="button" class="btnpointer" id="opt_plan_btn_send_notes_tomorrow"
                           style="border:1px solid #9e56af;background-color:#9e56af;padding:4px;color:white;font:13px tahoma;"
                           day="today"
                           value="ذخیره نکات مهم"/>
                    <img id="optplan_img_noteloader_tomorrow" src="img/mas-ajax-loader.gif" style="display: none;"/>
                    <span id="span_optplan_img_noteloader_tomorrow"
                          style="font-family: tahoma, arial;font-size: 12px;"></span>
                </div>
            <?php
            }
            ?>
        </div>
        <div id="optplan_optplanes"
             style="padding:10px;text-align: right;direction: rtl;font-family: tahoma, arial;font-size: 13px;width: 100%;margin: 0px auto;overflow:auto;"
             class="box_sized">
            <span
                style="font-weight:bold;width:300px;display:inline-block;margin-bottom:3px;font-family: tahoma, arial;font-size: 12px;">برنامه عملیات فردا : </span>
            <hr/>
            <?php
            if (!$isoccman) {
                echo ($optplan->get_opt_for_tomorrow());
            } else {
                echo '<textarea class="box_sized fadir optplan_txtarea_txteditor" id="optplan_txtarea_optplan_for_tomorrow" style="border:1px solid #a9db80;width: 100%;padding: 3px;resize: none;font-family: tahoma, arial;font-size: 13px;color:#0d3112;" rows="4" >' . $optplan->get_opt_for_tomorrow() . '</textarea>';
            }
            if ($isoccman) {
                ?>
                <div style="display: inline-block;margin-top: 3px;">
                    <input type="button" id="opt_plan_btn_send_opttask_tomorrow"
                           style="border:1px solid #416f1b;background-color:#416f1b;padding:4px;color:white;font:13px tahoma;"
                           day="today"
                           value="ذخیره برنامه عملیات"/>
                    <img id="optplan_img_opt_loadertomorrow" src="img/mas-ajax-loader.gif" style="display: none;"/>
                    <span id="span_optplan_img_opt_loadertomorrow"
                          style="font-family: tahoma, arial;font-size: 12px;"></span>
                </div>
            <?php
            }
            ?>
        </div>
    </div>
    <div id="optplan_panel2" class="optplan_tab_panel box_sized"
         style="width: 100%;padding: 0px;overflow: auto;">
		 
		<?php
            if ($isoccman) {
        ?>
        <div id="optplan_graph"
             style="padding:5px 0px;width: 100%;margin: 0px auto;position: relative;height: 180px;"
             class="box_sized">
            <img id="optplan_today_img_bib" src="apps/optplan/img/<?php echo $optplan->get_saved_img_today(); ?>"
                 style="height: 60%;width: 40%;background-size:100%;margin: 0px auto;display:block;"
                 class="box_sized radius5 blackshadow">

            
                <div
                    style="direction: rtl;text-align: center;display: block;padding: 5px;margin: 0px auto;">
                    <input type="file" id="opt_plan_map_file"/>
                    <input type="button" id="opt_plan_btn_sendimg_today" style="font:13px tahoma;" day="today"
                           value="ارسال"/>
                    <img id="optplan_img_fuploadloader" src="img/mas-ajax-loader.gif" style="display: none;"/>
                </div>            
        </div>
		<?php
            } /*else {
                echo '<div style="text-align:center;padding-top: 5px;" class="byekan-13">';
                echo 'نقشه حرکتی وسایل نقلیه در حریم ریلی';
                echo '</div>';
            }*/
		?>
        <div id="optplan_optnotes" class="box_sized"
             style="position:relative;font-family: tahoma, arial;font-size: 13px;width: 100%;
                 overflow: none;text-align: right;direction: rtl;padding: 10px;margin: 0px auto;">
            <?php
            if (!$isoccman) {
                //echo '<div style="display:inline-block;margin:0px auto;">'.$optplan->get_note_for_today().'</div>';
				echo '<div style="border-right:8px solid #ff5a5a;background-color:white;padding:5px;width:100%;" class="box_sized"><span style="font-weight:bold;width:300px;display:inline-block;margin-bottom:3px;">نکات مهم امروز :</span><br/>'.($optplan->get_note_for_today()).'</div>';
            } else {
                echo '<textarea class="box_sized fadir optplan_txtarea_txteditor" id="optplan_txtarea_notes_today" style="border:1px solid #f7d6ff;width: 100%;padding: 3px;resize: none;font-family: tahoma, arial;font-size: 13px;color:#52305a;" >' . $optplan->get_note_for_today() . '</textarea>';
            }
            if ($isoccman) {
                ?>
                <div style="display: inline-block;margin-top: 3px;">
                    <input type="button" class="btnpointer" id="opt_plan_btn_send_notes"
                           style="border:1px solid #9e56af;background-color:#9e56af;padding:4px;color:white;font:13px tahoma;"
                           day="today"
                           value="ذخیره نکات مهم"/>
                    <img id="optplan_img_noteloader" src="img/mas-ajax-loader.gif" style="display: none;"/>
                        <span id="span_optplan_img_noteloader"
                              style="font-family: tahoma, arial;font-size: 12px;"></span>
                </div>
            <?php
            }
            ?>
        </div>
        <div id="optplan_optplanes"
             style="padding:10px;text-align: right;direction: rtl;font-family: tahoma, arial;font-size: 13px;width: 100%;margin: 0px auto;overflow:auto;"
             class="box_sized">
            <span
                style="font-weight:bold;width:300px;display:inline-block;margin-bottom:3px;font-family: tahoma, arial;font-size: 12px;">برنامه عملیات امروز  :</span>
            <hr/>
            <?php
            if (!$isoccman) {
                echo '<div style="display:block;margin:0px auto;width:98%;" class="box_sized">'.$optplan->get_opt_for_today().'</div>';
            } else {
                echo '<textarea id="optplan_txtarea_optplan_for_today"
                            style="border:1px solid #a9db80;width: 100%;padding: 3px;resize: none;font-family: tahoma, arial;font-size: 13px;color: #1b2f0a;"
                            rows="4" class="box_sized fadir optplan_txtarea_txteditor" rows="4">' . $optplan->get_opt_for_today() . '</textarea>';
            }

            if ($isoccman) {
                ?>
                <div style="display: inline-block;margin-top: 3px;">
                    <input type="button" id="opt_plan_btn_send_opttask"
                           style="border:1px solid #416f1b;background-color:#416f1b;padding:4px;color:white;font:13px tahoma;"
                           day="today"
                           value="ذخیره برنامه عملیات"/>
                    <img id="optplan_img_opt_loader" src="img/mas-ajax-loader.gif" style="display: none;"/>
                        <span id="span_optplan_img_opt_loader"
                              style="font-family: tahoma, arial;font-size: 12px;"></span>
                </div>
            <?php
            }
            ?>
        </div>
    </div>
    <div id="optplan_panel3" class="optplan_tab_panel box_sized"
         style="display:none ;width: 100%;padding: 3px;background-color: #fae6ff;height: 100px;">
        panel3
    </div>
</div>
</div>