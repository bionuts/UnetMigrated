<?php
session_start();
include 'util/util.php';
$util = new UtilClass();
/*if($util->haveAcces('optplan',$_SESSION["userid"]))
{}*/
if($_SESSION['hashuser'] != $util->hashuser($_SESSION["userid"].$_SESSION["username"].$_SERVER['REMOTE_ADDR'].$_SERVER['HTTP_USER_AGENT'])) 
{
    header("Location: logout.php");
    exit();
}

$rand = rand(1,9999999999) * rand (1,999);

?>
<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" type="text/css" href="font/byekan.css"/>
    <link rel="stylesheet" type="text/css" href="js/jqueryui/jquery-ui.min.css"/>
    <link rel="stylesheet" type="text/css" href="css/mas.css"/>
    <link rel="stylesheet" type="text/css" href="css/permit.css?r=2<?php /*echo $rand*/ ?>"/>
    
	<!--<link rel="stylesheet" type="text/css" href="css/food.css?r="/>
	<link rel="stylesheet" type="text/css" href="css/food_tree.css?r="/>
    <link rel="stylesheet" type="text/css" href="css/food_edit.css?r="/>
	<link rel="stylesheet" type="text/css" href="css/food_list_report.css?r="/>
	<link rel="stylesheet" type="text/css" href="css/food_list_ditail.css?r="/>-->
	
	<link rel="stylesheet" href="css/desktop.css?r=<?php echo $rand ?>"/>
    <link rel="stylesheet" href="css/dcss.css?r=<?php /*echo $rand*/ ?>" rel="stylesheet"/>
    <link rel="stylesheet" href="css/ucss.css?r=<?php /*echo $rand*/ ?>" rel="stylesheet"/>
	<link rel="stylesheet" href="apps/usersettings/css/style.css?r=<?php /*echo $rand*/ ?>" rel="stylesheet"/>
	<link rel="stylesheet" href="css/common.css?r=<?php /*echo $rand*/ ?>" rel="stylesheet"/>
	
    <script type="text/javascript" src="js/jquery-1.11.1.min.js"></script>
    <script type="text/javascript" src="js/jqueryui/jquery-ui.min.js"></script>
    
    <script type="text/javascript" src="js/mas.js"></script>
    <script type="text/javascript" src="js/permit.js?ver=46"></script>
    
	<!--<script type="text/javascript" src="js/food_tree.js?r="></script>
    <script type="text/javascript" src="js/food_edit.js?r="></script>
	<script type="text/javascript" src="js/food_hafte_user.js?r="></script>
    <script type="text/javascript" src="js/food_hafte_edit.js?r="></script>
	<script type="text/javascript" src="js/food_list_report.js?r="></script>
	<script type="text/javascript" src="js/food_list_ready.js?r="></script>-->
	
	
    <script type="text/javascript" src="js/optplan.js?r=<?php /*echo $rand*/ ?>"></script>    
    <script type="text/javascript" src="js/desktop.js?r=5"></script>
	<script type="text/javascript" src="apps/usersettings/js/script.js?r=<?php /*echo $rand*/ ?>"></script>
	<script src="apps/optplan/js/tinymce/tinymce.min.js"></script>
	<script type="text/javascript">
	
		$(function(){		
			tinyMCE.init({
				selector: ".optplan_txtarea_txteditor",
				theme: "modern",				
				plugins: [
                "advlist autolink image lists charmap hr",
                "searchreplace wordcount visualblocks code fullscreen",
                "table contextmenu directionality textcolor paste fullpage textcolor colorpicker textpattern"
				],

				toolbar1: "fullpage | bold underline | alignleft aligncenter alignright alignjustify | fontselect fontsizeselect | searchreplace | bullist numlist | image code | forecolor backcolor | table | hr | charmap | fullscreen | ltr rtl | restoredraft",
				//toolbar2: "searchreplace | bullist numlist | image code | forecolor backcolor",
				//toolbar2: "searchreplace | bullist numlist | image code | forecolor backcolor | table | hr | charmap | fullscreen | ltr rtl | restoredraft",
				//toolbar3: "table | hr | charmap | fullscreen | ltr rtl | restoredraft",


					menubar: false,
					toolbar_items_size: 'small',
					style_formats: [
							{title: 'Bold text', inline: 'b'},
							{title: 'Red text', inline: 'span', styles: {color: '#ff0000'}},
							{title: 'Red header', block: 'h1', styles: {color: '#ff0000'}},
							{title: 'Example 1', inline: 'span', classes: 'example1'},
							{title: 'Example 2', inline: 'span', classes: 'example2'},
							{title: 'Table styles'},
							{title: 'Table row 1', selector: 'tr', classes: 'tablerow1'}
					]
			});	
		});		
	</script>
	
	
    <style type="text/css" rel="stylesheet">
		.MsoNormalTable,.WordSection1
		{
			width:100%;
			margin:0;
		}
        .ui-tabs {
            direction: rtl;
        }

        .ui-tabs .ui-tabs-nav li.ui-tabs-selected,
        .ui-tabs .ui-tabs-nav li.ui-state-default {
            float: right;
        }

        .ui-tabs .ui-tabs-nav li a {
            float: right;
            font-family: BYekanRegular;
            font-size: 13px;
            font-weight: normal;
            outline: none;
        }

        .ui-selectmenu-button {
            direction: rtl;
        }

        #centeredmenu {
            width: 100%;
            /*background: #fff;*/
            border-bottom: 2px solid #5b5b5b;
            overflow: hidden;
            position: relative;
        }

        #centeredmenu ul {
            clear: left;
            float: left;
            list-style: none;
            margin: 0;
            padding: 0;
            position: relative;
            left: 50%;
            text-align: center;
        }

        #centeredmenu ul li {
            display: block;
            float: left;
            list-style: none;
            margin: 0;
            padding: 0;
            position: relative;
            right: 50%;
        }

        #centeredmenu ul li a {
            display: block;
            margin: 0 0 0 1px;
            padding: 8px 10px;
            background: #e2e2e2;
            color: #000;
            text-decoration: none;
            line-height: 1.3em;
            width: 150px;
        }

        #centeredmenu ul li a:hover {
            background: #b8b8b8;
            color: #fff;
        }

        #centeredmenu ul li a.active,
        #centeredmenu ul li a.active:hover {
            color: #fff;
            background: #484848;
        }
		.f-basic,#optplan_optplanes
		{
			background-color:white !important;
		}
    </style>

    <link rel="stylesheet" href="js/jalalijscalendar/skins/aqua/theme.css">
    <script src="js/jalalijscalendar/jalali.js"></script>
    <script src="js/jalalijscalendar/calendar.js"></script>
    <script src="js/jalalijscalendar/calendar-setup.js"></script>
    <script src="js/jalalijscalendar/lang/calendar-fa.js"></script>


</head>
<body>

<div class="unet_panel_load_app" class="box_sized"
     style="display:none;padding:30px 50px;width:240px;text-align:center;position:fixed;margin:0px auto;
		left:0;right:0;top:200px;background-color:#f5f5f5;border:3px solid #000ED6;z-index:1000;">
    <div style="color:#09236c;margin-bottom:5px;">Loading ...</div>
    <img src="img/aj.gif"/>
</div>
<div id="user_pm" vis="false" style="position:fixed;width:0px;height:100%;right:0;top:35px;
			background-color:white;border-bottom:1px solid black;border-left:3px solid black;">
    <div id="user_pm_content">hello</div>
</div>
<div style="position:fixed;top:0;left:0;right:0;background-color:black;width:100%;height:35px;color:white;z-index:1;">
    <img class="btn_img_profile" id="img_profile" src="img/profile7.png" style="width:26px;height:26px;top:5px;"/>

    <div id="pm_div">
        <img class="btn_img_profile" src="img/pm9.png" style="width:20px;height:20px;"/>

        <div class="radius3"
             style="font-family:tahoma;font-size:11px;
						font-weight:bold;position:absolute;right:20px;top:-5px;background-color:red;color:white;padding:1px 4px;">
            0
        </div>
    </div>
    <div
        style="text-align:right;width:40%;float:right;position:relative;right:50px;top:9px;font-family:tahoma;font-size:13px;color:white;direction:rtl;">
        کاربری
        : <?php echo $_SESSION['fname'] . ' ' . $_SESSION['lname'] . '<span style="color:red;"> | </span>' . 'سمت : ' . $_SESSION["semat_name"]; ?>
    </div>
    <div style="position:absolute;left:5px;top:8px;font-family:tahoma;font-size:13px;">&gt; User: <span
            style="text-decoration:underline;"><?php echo $_SESSION["username"]; ?></span></div>
</div>
<div class="radius3" id="profile_menu" vis="false"
     style="font-family:BYekanRegular;font-size:13px;width:200px;border:1px solid #cccccc;direction:rtl;text-align:right;
						display:none;-moz-box-shadow: 0px 0px 5px #c4c4c4;z-index:999;
						-webkit-box-shadow: 0px 0px 5px #c4c4c4;box-shadow: 0px 0px 5px #c4c4c4;
						position:fixed;right:2px;top:37px;background-color:white;color:black;padding:10px 0px;">
    <ul class="ul_profile_desktop" style="">
        <li>
            <img src="img/home.png" style="float:right;margin-left:10px;"/>

            <div style="float:;">مشخصات کاربر</div>
        </li>
        <li task="user_settings">
            <img src="img/bug.png" style="float:right;margin-left:10px;"/>

            <div style="float:;">تغییر رمز عبور</div>
        </li>
        <li>
            <img src="img/contact.png" style="float:right;margin-left:10px;"/>

            <div style="">پیشنهادات و انتقادات</div>
        </li>
        <li id="idforclose">
            <img src="img/logout.png" style="float:right;margin-left:10px;"/>

            <div style="float:;"><a id="aforlinkcloze" href="logout.php">خروج</a></div>
        </li>
    </ul>
</div>
<?php

if($util->haveAcces('optplan',$_SESSION["userid"]))
{

include 'apps/optplan/lib/optplan_ajax_handler.php';
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
    style="position: relative;color:#062543;width: 80%;font-family: tahoma !important;
	font-size: 12px;max-height:100px;overflow: auto;text-align: right;direction: rtl;margin: 5px auto;margin-bottom: 5px;"
    class="box_sized">
    <?php
    if (!$isoccman) {
        echo '<div style="max-height:100px;border-right:3px solid black;padding-right:5px;" class="box_sized"><span style="font-weight:bold;width:600px;display:inline-block;margin-bottom:3px;">پنل برنامه ریزی بهره برداری<br/>شماره تلفن واحدهای مرکز فرمان : برنامه ریزی(2255-2244)،انرژی(2266-2277)،ترافیک(2288-2299)</span><br/>'.nl2br($optplan->get_hint_txt()).'</div>';
    } else {
        echo '<textarea class="box_sized fadir" id="optplan_txtarea_hints_txt" style="border:1px solid #2a5d8f;padding: 5px !important;width: 100%;padding: 3px;resize: none;font-family: tahoma, arial;font-size: 13px;color:#0c2c4b;background-color: #ffffff;" rows="3" >' . $optplan->get_hint_txt() . '</textarea>';
    }

    if ($isoccman) {
        ?>
        <div style="display: inline-block;margin-top: 3px;">
            <input type="button" class="btnpointer" id="opt_plan_btn_send_daily"
                   style="border:1px solid #0d569e;background-color:#0d569e;padding:4px;color:white;font:13px tahoma;"
                   day="today"
                   value="ذخیره برنامه روزانه"/>
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
            <img id="optplan_tomorrow_img_bib_tomorrow"
                 src="apps/optplan/img/<?php echo $optplan->get_saved_img_tomorrow(); ?>"
                 style="height: 60%;width: 40%;background-size:100%;margin: 0px auto;display:block;"
                 class="box_sized radius5 blackshadow">
            
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
                echo '<textarea class="box_sized fadir optplan_txtarea_txteditor" id="optplan_txtarea_notes_tomorrow" style="border:1px solid #f7d6ff;width: 100%;padding: 3px;resize: none;font-family: tahoma, arial;font-size: 13px;color:#52305a;" rows="4" >' . $optplan->get_note_for_tomorrow() . '</textarea>';
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
                style="font-weight:bold;width:300px;display:inline-block;margin-bottom:3px;font-family: tahoma, arial;font-size: 12px;">برنامه عملیات فردا : </span><hr/>
            <?php
            if (!$isoccman) {
                echo ($optplan->get_opt_for_tomorrow());
            } else {
                echo '<textarea class="box_sized fadir optplan_txtarea_txteditor" id="optplan_txtarea_optplan_for_tomorrow" style="height:400px;border:1px solid #a9db80;width: 100%;padding: 3px;resize: none;font-family: tahoma, arial;font-size: 13px;color:#0d3112;" rows="4" >' . $optplan->get_opt_for_tomorrow() . '</textarea>';
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
            } 
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
                style="font-weight:bold;width:300px;display:inline-block;margin-bottom:3px;font-family: tahoma, arial;font-size: 12px;">برنامه عملیات امروز  :</span><hr/>
            <?php
            if(!$isoccman)
            {
                echo '<div style="display:block;margin:0px auto;width:98%;" class="box_sized">'.$optplan->get_opt_for_today().'</div>';
            }
            else
            {
            echo '<textarea id="optplan_txtarea_optplan_for_today"
                            style="height:400px;border:1px solid #a9db80;width: 100%;padding: 3px;resize: none;font-family: tahoma, arial;font-size: 13px;color: #1b2f0a;background-color:white;"
                            class="box_sized fadir optplan_txtarea_txteditor" rows="4">'.$optplan->get_opt_for_today().'</textarea>';
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

<?php
}
?>
<div style="position:relative;top:35px;" id="desktop-main">
    <div id="divnum"></div>
    <!--<div class="desktop-icon">
        <img class="icon-img" src="img/mas_ico.png" app="mas"/>
        <div class="icon-label">
            اعلام خرابی
        </div>
    </div>-->
	<?php

	if($util->haveAcces('permit',$_SESSION["userid"]))
	{
?>
	
    <div class="desktop-icon">
        <img class="icon-img" src="img/permit.png" app="permit"/>
        <div class="icon-label">درخواست مجوز</div>
    </div>
	<?php
}	
	if($util->haveAcces('food',$_SESSION["userid"]))
	{		
	?>
	<!--<div class="desktop-icon">
		<img id="div_app_food" class="icon-img" src="img/food11.png" app="food"/>
		<div class="icon-label">رزرواسیون غذا</div>
	</div>-->
	<?php
	}	
	?>		
</div>

<input type="hidden" id="rcheck" value="<?php
    include_once 'apps/permit/lib/permit-config.php';
	include_once 'apps/permit/lib/permitUtil.php';
	$putil = new permitUtil();
	$roleid = $putil->getUserRoleID($_SESSION['userid']);
	$roleid = $roleid[0];
	echo $roleid;
	// echo $_SESSION['userid'];
?>" />

</body>
</html>