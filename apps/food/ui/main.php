<?php
// height: 400px;max-height: 400px;overflow: auto;

/*function ip_in_range( $ip, $range ) {
	if ( strpos( $range, '/' ) == false ) {
		$range .= '/32';
	}
	// $range is in IP/CIDR format eg 127.0.0.1/24
	list( $range, $netmask ) = explode( '/', $range, 2 );
	$range_decimal = ip2long( $range );
	$ip_decimal = ip2long( $ip );
	$wildcard_decimal = pow( 2, ( 32 - $netmask ) ) - 1;
	$netmask_decimal = ~ $wildcard_decimal;
	return ( ( $ip_decimal & $netmask_decimal ) == ( $range_decimal & $netmask_decimal ) );
}
$uip = $_SERVER['REMOTE_ADDR'];
if(!(ip_in_range( $uip, '10.20.0.0/16' ) || ip_in_range( $uip, '192.168.3.0/24' )))
{
	echo '<h1 style="color:red;">no access from internet</h1>';exit;
}
*/

session_start();
include '../../../util/util.php';
$util = new UtilClass();
if(!$util->haveAcces('food',$_SESSION["userid"]))exit;

if ($_SESSION['hashuser'] != $util->hashuser($_SESSION["userid"] . $_SESSION["username"] . $_SERVER['REMOTE_ADDR'] . $_SERVER['HTTP_USER_AGENT'])) {
    header("Location: logout.php");
    exit();
}
$user = $_SESSION["userid"];
//multifood  admin  supervisor
function getPermitMulti()
{
	global $user;
	$user = $_SESSION["userid"];
	$conn = connect();
	$sql = "SELECT `food_role_id` FROM `food_tbl_role` WHERE `user_id` = $user AND (`food_role`='multifood' OR `food_role`='admin')";
	$result = $conn->query($sql);
	if ($result == TRUE) {
		 if ($result->num_rows) {
			$conn->close();
			return '1';
		 }
	}
	$conn->close();
	return '0';
}

function getPermitAdmin()
{
	global $user;
	$conn = connect();
	$sql = "SELECT `food_role_id` FROM `food_tbl_role` WHERE `user_id` =$user AND `food_role`='admin'";
	$result = $conn->query($sql);
	if ($result == TRUE) {
		 if ($result->num_rows) {
			$conn->close();
			return '1';
		 }
	}
	$conn->close();
	return '0';
}

function getPermitSupervisor()
{
	global $user;
	$conn = connect();
	$sql = "SELECT `food_role_id` FROM `food_tbl_role` WHERE `user_id` =$user AND `food_role`='supervisor'";
	$result = $conn->query($sql);
	if ($result == TRUE) {
		 if ($result->num_rows) {
			$conn->close();
			return '1';
		 }
	}
	$conn->close();
	return '0';
}

include '../../../config/TreeConfigClass.php';
include '../../../config/food_config.php';
include '../../../lib/Node.php';
include '../../../lib/Tree.php';

$tree = new Tree();
$permit_multi = getPermitMulti();
$permit_supervisor = getPermitSupervisor();
$permit_admin = getPermitAdmin();
?>
<div id="app_food" userid="<?php echo($_SESSION["userid"]);?>" permit_multi="<?php echo $permit_multi;?>" permit_admin="<?php echo $permit_admin;?>" class="windowpanel box_sized" style="width:92%;display:none;top:37px;left: 6%;right: 20px;"
     lastxpos="6%" lastypos="37px" lastwidth="92%" lastheight="" maximize="false" minimize="false">
    <div class="windowpanel_header box_sized">
        <img class="windowpanel_header_icon" src="img/food11.png"/>

        <div class="windowpanel_header_title">رزواسيون غذا</div>
        <div class="windowpanel_header_btns">
            <img class="windowpanel_header_btns_close imgwindowpanel" src="img/close1.png"/>
            <img class="windowpanel_header_btns_max imgwindowpanel" src="img/max3.png"/>
            <img class="windowpanel_header_btns_min imgwindowpanel" src="img/min1.png"/>
        </div>
    </div>
    <div class="windowpanel_body box_sized" id="permit_body_panel"
         style="direction:rtl;bottom:1px;position:absolute;top:31px;">
       
        <div id="food_tabs" style="width:100%;height:100%;" class="box_sized">
            <ul>
                <li><a id="li-food-tabs-1" href="#food-tabs-1">رزرو غذا</a></li>
				 <?php if($permit_admin == '1'){?>
                <li><a id="li-food-tabs-2" href="#food-tabs-2">ویرایش سفره</a></li>
                <li><a id="li-food-tabs-3" href="#food-tabs-3">چیدمان سفره</a></li>
                <?php 
					}
					if($permit_admin == '1' || $permit_supervisor == '1'){
				?>
				<li><a id="li-food-tabs-4" href="#food-tabs-4" permit="<?php echo $permit_admin; ?>">آمار غذا</a></li>
				<li><a id="li-food-tabs-5" href="#food-tabs-5">لیست غذا</a></li>
				<?php
					}
					if($permit_admin == '1'){
				?>
				<li><a id="li-food-tabs-6" href="#food-tabs-6">گزارشات</a></li>
				<li><a id="li-food-tabs-7" href="#food-tabs-7">تنظیمات</a></li>
				<?php
					}
				?>
            </ul>
            <div id="food-tabs-1" style="padding:0px;padding-top:5px;min-width:800px;height: 94%;width: 100%;">
			<div style="height: 96%;width: 100%;overflow:auto;">
				<div id="food_main_ebadi" style="width: 100%;margin-bottom: 10px;">

				<div
					style="vertical-align:top;padding:5px;width:603px;background-color:white;display: inline-block;background-color: #d9f1ff;"
					class="box_sized radius5">
					<div style="width: 100%;height: 100%;border: 1px solid #6fb7ff;background-color: white;"
						 class="box_sized radius5">
						<div
							style="height: 30px;font:13px BYekanRegular;text-align:center; width: 100%;background-color: #6fb7ff;color: white;line-height:28px;"
							class="box_sized">ایام هفتگی
						</div>
						<div class="food_list_content box_sized foodlistscroll"
							 style="width: 100%;padding: 5px;min-height: 500px;max-height: 800px;overflow: auto;">							 
							 <div style="text-align:center;color:black;font-family:tahoma;font-size:12px;margin-bottom:3px;background-color:#FFCCD2;border:1px solid red;padding:5px;">
							 با توجه به تصمیمات اخذ شده ، از تاریخ 95/02/11 ، سامانه رزرواسیون غذا غیر فعال بوده و ثبت سفارش غذا امکان پذیر نمی باشد . جهت کسب اطلاعات بیشتر در خصوص نحوه پرداخت هزینه خوراک با مدیریت اداری تماس حاصل نمائید .
							 </div>							 
							 <?php 
							 
							 ?>
							<!--<table style="width:100%;font:13px BYekanRegular;" class="food_tbl_orders">
								<tr class="food_h_headertbl">
									<td style="width:16%;background-color:#515151;"><img src="img/tbl_haftegi.png"
																						 style="width:40px;padding:0px;margin:0px;position:relative;top:4px;"/>
									</td>
									<td style="width:28%;">صبحانه</td>
									<td style="width:28%;">نهار</td>
									<td style="width:28%;">شام</td>
								</tr>
								<tr class="food_rw_haftegi">
									<td class="food_td_date">شنبه<br/><span id="day0"></span></td>
									<td idmeal2="0" id="meal2_0" class="fd_select_user_td"></td>
									<td idmeal2="1" id="meal2_1" class="fd_select_user_td">
										<div class="box_sized fd_vade_hafte_users_eat radius5">
											<div class="box_sized fd_dv_cmnt_plf radius5"><img title="نظر" src="img/fd_cmnt.png"/></div>
											<div class="box_sized fd_dv_chkbx_plf radius5"><img style="display:block;"
																								src="img/chkfooduser.png"/></div>
											خورشت قیمه،نوشابه و ماست خیار
										</div>
									</td>
									<td idmeal2="2" id="meal2_2" class="fd_select_user_td"></td>
								</tr>
								<tr class="food_rw_haftegi">
									<td class="food_td_date">
										یکشنبه<br/><span id="day1"></span>
									</td>
									<td idmeal2="3" id="meal2_3" class="fd_select_user_td"></td>
									<td idmeal2="4" id="meal2_4" class="fd_select_user_td">
										<div class="box_sized fd_vade_hafte_users radius5">
											<div class="box_sized fd_dv_chkbx_plf radius5"><img style="display:block;"
																								src="img/chkfooduser.png"/></div>
											خورشت قیمه،نوشابه و ماست خیار
										</div>
										<div class="box_sized fd_vade_hafte_users radius5">
											<div class="box_sized fd_dv_chkbx_plf radius5"><img style="display:block;"
																								src="img/chkfooduser.png"/></div>
											خورشت قیمه،نوشابه و ماست خیار
										</div>
									</td>
									<td idmeal2="5" id="meal2_5" class="fd_select_user_td"></td>
								</tr>
								<tr class="food_rw_haftegi">
									<td class="food_td_date">دوشنبه<br/><span id="day2"></span>
									</td>
									<td idmeal2="6" id="meal2_6" class="fd_select_user_td"></td>
									<td idmeal2="7" id="meal2_7" class="fd_select_user_td"></td>
									<td idmeal2="8" id="meal2_8" class="fd_select_user_td"></td>
								</tr>
								<tr class="food_rw_haftegi">
									<td class="food_td_date">سه شنبه<br/><span id="day3"></span></td>
									<td idmeal2="9" id="meal2_9" class="fd_select_user_td"></td>
									<td idmeal2="10" id="meal2_10" class="fd_select_user_td"></td>
									<td idmeal2="11" id="meal2_11" class="fd_select_user_td"></td>
								</tr>
								<tr class="food_rw_haftegi">
									<td class="food_td_date">چهارشنبه<br/><span id="day4"></span></td>
									<td idmeal2="12" id="meal2_12" class="fd_select_user_td"></td>
									<td idmeal2="13" id="meal2_13" class="fd_select_user_td"></td>
									<td idmeal2="14" id="meal2_14" class="fd_select_user_td"></td>
								</tr>
								<tr class="food_rw_haftegi">
									<td class="food_td_date">پنجشنبه<br/><span id="day5"></span></td>
									<td idmeal2="15" id="meal2_15" class="fd_select_user_td"></td>
									<td idmeal2="16" id="meal2_16" class="fd_select_user_td">  </td>
									<td idmeal2="17" id="meal2_17" class="fd_select_user_td"></td>
								</tr>
								<tr class="food_rw_haftegi">
									<td class="food_td_date">جمعه<br/><span id="day6"></span></td>
									<td idmeal2="18" id="meal2_18" class="fd_select_user_td"></td>
									<td idmeal2="19" id="meal2_19" class="fd_select_user_td"></td>
									<td idmeal2="20" id="meal2_20" class="fd_select_user_td">
									</td>
								</tr>
								<tr class="food_f_footertbl" style="border: 1px solid #727272;">
									<td style="text-align: center;font: 13px BYekanRegular;border: none;position:relative;">
										<div id="loading2" style="right:4px; top:10px;width: 200px;text-align: right;direction: rtl;">
											<div style="vertical-align: top;display: inline-block;padding: 0px;">
											<img style="margin: 0;padding: 0;display: block;" src="img/savehafteebadi.gif"/></div>
											<div
												style="vertical-align: top;line-height:30px;display: inline-block;direction: rtl;text-align: right;padding-right: 5px;">
												<span>درحال ذخیره ...</span>
											</div>
										</div>
									</td>
									<td style="text-align: center;font: 13px BYekanRegular;text-align: left;border: none;">
										<img id="preImage2" style="position: relative; top:4px;cursor: pointer;" src="img/pre.png"/>						
									</td>
									<td id="haftehid2" style="text-align: center;font: 13px BYekanRegular;border: none;">هفته اول</td>
									<td style="text-align: center;font: 13px BYekanRegular;border: none;text-align: right;">
										<img id="nextImage2" style="position: relative; top:4px;cursor: pointer;" src="img/nxt.png"/>
									</td>
								</tr>
							</table>-->
						</div>
					</div>
				</div>
				</div>
				<div id="selectPlace" style="display: none;">
				<div class="food_opacityblack" id="food_res_maat" style="display: ;"></div>
				<div class="food_overlayrate radius5" id="food_res_dlg" style="display:;width:300px;">
					<div id="food_selectplace_title"
						style="position:relative;color:white;width: 100%;height: 40px;background-color: #3c58a8;
						font:13px BYekanRegular;direction: rtl;text-align: right;padding-right: 10px;line-height: 40px;"
						class="box_sized">
						غذا : خورشت سبزی ، تاریخ : دوشنبه 26 مهر 1393
						<div id="food_close_resdls" 
						style="position:absolute;left:14px;top:14px;
						background:url('img/1420322024_close_delete.png');width:12px;height:12px;"></div>
					</div>
					<div style="width: 100%;background-color: #fdfdfd;padding:4px;" class="box_sized">
						<div style="width: 100%;border:1px solid #5577d7;padding:5px;height:100%;" class="box_sized">
							<table style="width:100%;">
								<tr>
									<td>
										<div style="font:13px tahoma;margin-bottom:3px;">مکان تحویل غذا :</div>
										<select class="box_sized" style="font:13px tahoma;border:1px solid #5577d7;padding:3px;width:100%;">
											<option value="1">سازمان مرکزی</option>
											<option value="2">توقفگاه احسان</option>
										</select>
									</td>									
								</tr>
								<tr>
									<td>
										<div style="font:13px tahoma;margin-bottom:3px;">تعداد غذا :</div>
										<input type="text" id="food_teddad_vahde_res" placeholder="لطفا تعداد را مشخص کنید" value="" 
										class="box_sized" style="font:13px tahoma;border:1px solid #5577d7;padding:3px;width:100%;"  />
									</td>									
								</tr>
								<tr>
									<td>
										<div style="font:13px tahoma;margin-bottom:3px;">اسامی مصرف کننده :</div>
										<textarea rows="3" id="food_teddad_vahde_reason" placeholder="بدلیل سفارش بیش از یک غذا ، الزاما نام مصرف کننده غذا را وارد کنید" 
										class="box_sized" style="font:13px tahoma;border:1px solid #5577d7;padding:3px;width:100%;resize:none;"></textarea>
									</td>									
								</tr>
								<tr>
									<td style="text-align:center;">
										<input id="btnSelectPlace" style="font:13px tahoma;width:80px;" type="button" value="ذخیره" />
									</td>
								</tr>
							</table>
						</div>
					</div>
				</div>
				</div>
			</div>
            </div>
            <?php if($permit_admin == '1'){?>
			<div id="food-tabs-2" style="padding:0px;padding-top:5px;min-width:800px;height: 94%;width: 100%;">	
			<div style="height: 96%;width: 100%;overflow:auto;">			
				<div id="food_main_ebadi" style="width: 100%;">
					<div
						style="padding:5px;width:300px;background-color:#d9f1ff;display: inline-block;vertical-align:top;"
						class="box_sized radius5">
						<div style="width: 100%;height: 100%;border: 1px solid #6fb7ff;background-color: white;"
							 class="box_sized radius5 box_resize1">
							<div
								style="height: 30px;font:13px BYekanRegular;text-align:center; width: 100%;
								background-color: #6fb7ff;color: white;line-height:28px;"
								class="box_sized">لیست غذاهای سازمان
							</div>
							<div class="rightclickdisable food_list_content box_sized box_resize2"
								 style="min-height:400px;height:400px;width: 100%;padding: 5px;overflow: auto;">
								<ul id="treemenu" class="contextMenu" style="z-index: 200000;background-color: #F1F1F1;">
									<li><a id="treeedit" href="javascript:void(0)">ويرايش</a></li>
									<li style="margin: 0;"><a id="treenew" href="javascript:void(0)">اضافه</a></li>
								</ul>
								<?php
								$node = $tree->getRootNode();
								?>
								<ul id="unettree" class="ulcat">
									<li lastnode="true" nodeid="<?php echo $node->getNodeID(); ?>">
										<div>
											<span class="tree_node max_bottom"></span><!--
								--><span class="tree_node parent_icon_node_close"></span>
											<span class="lbl_node"><?php echo $node->getNodeName(); ?></span>
										</div>
									</li>
								</ul>
							</div>
						</div>
					</div>
					<div
						style="vertical-align:top;padding:5px;width:300px;background-color:white;display: inline-block;background-color: #d9f1ff;"
						class="box_sized radius5">
						<div style="width: 100%;height: 100%;border: 1px solid #6fb7ff;background-color: white;"
							 class="box_sized radius5">
							<div
								style="height: 30px;font:13px BYekanRegular;text-align:center; width: 100%;background-color: #6fb7ff;color: white;line-height:28px;"
								class="box_sized">اقلام غذا مخصوص وعده جدید
							</div>
							<div class="food_list_content box_sized foodlistscroll samancss">
								
								<div style="outline:1px solid lightgray;margin-bottom:2px;background-color:#f7f7f7;padding:3px;padding-bottom:5px;" class="box_sized">
								<table style="font:12px tahoma;direction:rtl;width:100%;" class="box_sized">
									<tr>										
										<td>
											انتخاب پیمانکار:<br/>
											<select id="peymankarid" style="font:12px tahoma;width:100%;padding:2px;" class="box_sized">
												<?php
												$con = connect();
												$res = mysqli_query($con,"select * from  `food_tbl_peymankar` ");
												if (mysqli_num_rows($res) > 0)
												{
													while($row = mysqli_fetch_assoc($res))
													{
														echo "<option style='font:12px tahoma;' value='$row[peymankar_id]'>$row[name]</option>";
													}
												}
												?>
											</select>
										</td>
									</tr>
									<tr>										
										<td>
											هزینه سفره:<br/>
											<input type="text" name="cost" id="meal_price" style="font:12px tahoma;width:100%;padding:2px;" class="box_sized"/>
										</td>
									</tr>
								</table>
								</div>
								
							  <div style="margin-top:3px;height: 100%;height:300px;max-height: 300px;overflow: auto;outline:1px solid lightgray;margin-bottom:2px;background-color:#f5fff5;padding:3px;padding-bottom:5px;" class="box_sized">
								  <ul id="foods_ul_sortable"
									  style="list-style:  none;margin: 0;padding: 0;direction: rtl;text-align: right;width: 100%; ">
								  </ul>
							  </div>


								<div class="box_sized" style="width: 100%;text-align:center" id="meal_action_btn" >
									<input type="button" name="addfood"   class="meal_btn"  id="addmeal" value="ثبت سفره"  />
									<input type="button" name="cancel"  class="meal_btn"  id="cancelmeal" value="انصراف"  />
								</div>
							</div>

						</div>
					</div>
					<div
						style="vertical-align:top;padding:5px;width:300px;background-color:white;display: inline-block;background-color: #d9f1ff;"
						class="box_sized radius5">
						<div style="width: 100%;border: 1px solid #6fb7ff;background-color: white;"
							 class="box_sized radius5 box_resize1">
							<div
								style="height: 30px;font:13px BYekanRegular;text-align:center; width: 100%;background-color: #6fb7ff;color: white;line-height:28px;"
								class="box_sized">لیست وعده های غذا
							</div>
							<div class="food_list_content box_sized meal_lists box_resize2" style="height:350px;min-height:350px;overflow:auto;">

							</div>
						</div>
					</div>
				</div>
			</div>
            </div>
            <div id="food-tabs-3" style="padding:0px;padding-top:5px;height: 94%;width: 100%;">
				<div style="height: 96%;width: 100%;overflow:auto;">
				<div class="food_overlayrate radius5" style="display: none;">
					<div
						style="position:relative;color:white;width: 100%;height: 40px;background-color: #3c58a8;font:13px BYekanRegular;direction: rtl;text-align: right;padding-right: 10px;line-height: 40px;"
						class="box_sized">
						پنجشنبه 1393/02/23
						<div style="position:absolute;left:14px;top:14px;background:url('img/1420322024_close_delete.png');width:12px;height:12px;"></div>
					</div>
					<div style="width: 100%;height: 400px;background-color: #fdfdfd;padding:4px;" class="box_sized">
						<div style="width: 100%;border:1px solid #5577d7;padding:5px;height:100%;" class="box_sized">
							<div class="fd_tbl_ratetbl">
								<div style="text-align: right;background-color:#f1f1f1;border-bottom:1px solid #dedede;padding:3px;" class="box_sized">
									<div style="display:inline-block;vertical-align:top;vertical-align:top;font:13px BYekanRegular;">
										<div class="food_img_show_report"></div>
										<div style="vertical-align:top;display:inline-block;position:relative;top:6px;right:5px;">خورشت سبزی</div>
									</div>
									<div class="parent_rating_panel" ischk="0" >
										<div sval="1"></div><!--
										--><div sval="2"></div><!--
										--><div sval="3"></div><!--
										--><div sval="4"></div><!--
										--><div sval="5"></div><!--
										--><div sval="6"></div><!--
										--><div sval="7"></div><!--
										--><div sval="8"></div><!--
										--><div sval="9"></div><!--
										--><div sval="10"></div>
									</div>
									<div style="display:inline-block;float:left;position:relative;left:15px;top:7px;">
										<img src="img/fd_ajx_rate.gif" />
									</div>
								</div>
								<div class="fd_chart_content" style="margin-top:5px;width:90%;margin:5px auto;display:none;">
									<div style="text-align:right;direction:rtl;margin-bottom:3px;font:11px tahoma;">اخیر : ( تعداد نظرات  : 21 , <img src="img/fdup.png" /> 1.7+ )</div>
									<div
										style="background-color:#f6f6f6;width:100%;margin: 0px auto;border: 1px solid #5577d7;height: 15px;position: relative;font: 11px tahoma;">
										<div
											style="line-height:14px;width:60%;position: absolute;left: 0;top: 0px;height: 100%;
											background-color: #5577d7;color: white;text-align: center;" class="">
										   7.3
										</div>
									</div>
									<div style="text-align:right;direction:rtl;margin-bottom:3px;font:11px tahoma;">کل : ( تعداد نظرات  : 21 , <img src="img/fddown.png" /> 2.6- )</div>
									<div
										style="background-color:#f6f6f6;width:100%;margin: 0px auto;border: 1px solid #5577d7;height: 15px;position: relative;font: 11px tahoma;" >
										<div
											style="line-height:14px;width:60%;position: absolute;left: 0;top: 0px;height: 100%;
											background-color: #5577d7;color: white;text-align: center;" class="">
										   7.3
										</div>
									</div>
								</div>
							</div>
							<div class="fd_tbl_ratetbl">
								<div style="text-align: right;background-color:#f1f1f1;border-bottom:1px solid #dedede;padding:3px;" class="box_sized">
									<div style="display:inline-block;vertical-align:top;vertical-align:top;font:13px BYekanRegular;">
										<div class="food_img_show_report"></div>
										<div style="vertical-align:top;display:inline-block;position:relative;top:6px;right:5px;">خورشت سبزی</div>
									</div>
									<div class="parent_rating_panel" ischk="0" >
										<div sval="1"></div><!--
										--><div sval="2"></div><!--
										--><div sval="3"></div><!--
										--><div sval="4"></div><!--
										--><div sval="5"></div><!--
										--><div sval="6"></div><!--
										--><div sval="7"></div><!--
										--><div sval="8"></div><!--
										--><div sval="9"></div><!--
										--><div sval="10"></div>
									</div>
								</div>
								<div class="fd_chart_content" style="margin-top:5px;width:90%;margin:5px auto;display:none;">
									<div style="text-align:right;direction:rtl;margin-bottom:3px;font:11px tahoma;">اخیر : ( تعداد نظرات  : 21 , <img src="img/fdup.png" /> 1.7+ )</div>
									<div
										style="background-color:#f6f6f6;width:100%;margin: 0px auto;border: 1px solid #5577d7;height: 15px;position: relative;font: 11px tahoma;">
										<div
											style="line-height:14px;width:60%;position: absolute;left: 0;top: 0px;height: 100%;
											background-color: #5577d7;color: white;text-align: center;" class="">
										   7.3
										</div>
									</div>
									<div style="text-align:right;direction:rtl;margin-bottom:3px;font:11px tahoma;">کل : ( تعداد نظرات  : 21 , <img src="img/fddown.png" /> 2.6- )</div>
									<div
										style="background-color:#f6f6f6;width:100%;margin: 0px auto;border: 1px solid #5577d7;height: 15px;position: relative;font: 11px tahoma;" >
										<div
											style="line-height:14px;width:60%;position: absolute;left: 0;top: 0px;height: 100%;
											background-color: #5577d7;color: white;text-align: center;" class="">
										   7.3
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>

				<div id="food_main_ebadi" style="width: 100%;margin-bottom: 10px;">
				<div
					style="vertical-align:top;padding:5px;width:300px;background-color:white;display: inline-block;background-color: #d9f1ff;"
					class="box_sized radius5">
					<div style="width: 100%;height: 100%;border: 1px solid #6fb7ff;background-color: white;"
						 class="box_sized radius5">
						<div
							style="height: 30px;font:13px BYekanRegular;text-align:center; width: 100%;background-color: #6fb7ff;color: white;line-height:28px;"
							class="box_sized">لیست وعده های غذا
						</div>
					   <div id="ListTablecloth" style="height: 400px;max-height: 400px;overflow: auto;" class="food_list_content box_sized">
						   <!--<div id="tablecloth_1" class="food_vade_items box_sized radius5">
							<img class="food_img_vade_hico" src="img/vade.png">
							<p style="margin: 0;padding: 0px;">
								پلو<br>dfdgf<br>نوشابه
							</p>
						</div>-->
						</div>
					</div>
				</div>
				<div
					style="vertical-align:top;padding:5px;width:603px;background-color:white;display: inline-block;background-color: #d9f1ff;"
					class="box_sized radius5">
					<div style="width: 100%;height: 100%;border: 1px solid #6fb7ff;background-color: white;"
						 class="box_sized radius5">
						<div
							style="height: 30px;font:13px BYekanRegular;text-align:center; width: 100%;background-color: #6fb7ff;color: white;line-height:28px;"
							class="box_sized">ایام هفتگی
						</div>
						<div class="food_list_content box_sized foodlistscroll"
							 style="width: 100%;padding: 5px;min-height: 500px;max-height: 800px;overflow: auto;">
							<table style="width:100%;font:13px BYekanRegular;" class="food_tbl_orders">
								<tr class="food_h_headertbl">
									<td style="width:16%;background-color:#515151;"><img src="img/tbl_haftegi.png"
																						 style="width:40px;padding:0px;margin:0px;position:relative;top:4px;"/>
									</td>
									<td style="width:28%;">صبحانه</td>
									<td style="width:28%;">نهار</td>
									<td style="width:28%;">شام</td>
								</tr>
								<tr class="food_rw_haftegi">
									<td class="food_td_date">شنبه</td>
									<td id="meal_0" idmeal="0" class="fd_drop_vade">
										<div class="box_sized fd_vade_hafte radius5" style="display: none;">
											<div class="box_sized fd_dv_clz_plf radius5" >x</div>
											خورشت قیمه،پلو،نوشابه
										</div>
										
									</td>
									<td id="meal_1" idmeal="1" class="fd_drop_vade"></td>
									<td id="meal_2" idmeal="2" class="fd_drop_vade"></td>
								</tr>
								<tr class="food_rw_haftegi">
									<td	class="food_td_date">یک شنبه</td>
									<td id="meal_3" idmeal="3" class="fd_drop_vade"></td>
									<td id="meal_4" idmeal="4" class="fd_drop_vade"></td>
									<td id="meal_5" idmeal="5" class="fd_drop_vade"></td>
								</tr>
								<tr class="food_rw_haftegi">
									<td class="food_td_date">دوشنبه</td>
									<td id="meal_6" idmeal="6" class="fd_drop_vade"></td>
									<td id="meal_7" idmeal="7" class="fd_drop_vade"></td>
									<td id="meal_8" idmeal="8" class="fd_drop_vade"></td>
								</tr>
								<tr class="food_rw_haftegi">
									<td class="food_td_date">سه شنبه</td>
									<td id="meal_9" idmeal="9" class="fd_drop_vade"></td>
									<td id="meal_10" idmeal="10" class="fd_drop_vade"></td>
									<td id="meal_11" idmeal="11" class="fd_drop_vade"></td>
								</tr>
								<tr class="food_rw_haftegi">
									<td class="food_td_date">چهار شنبه</td>
									<td id="meal_12" idmeal="12" class="fd_drop_vade"></td>
									<td id="meal_13" idmeal="13" class="fd_drop_vade"></td>
									<td id="meal_14" idmeal="14" class="fd_drop_vade"></td>
								</tr>
								<tr class="food_rw_haftegi">
									<td class="food_td_date">پنجشنبه</td>
									<td id="meal_15" idmeal="15" class="fd_drop_vade"></td>
									<td id="meal_16" idmeal="16" class="fd_drop_vade"></td>
									<td id="meal_17" idmeal="17" class="fd_drop_vade"></td>
								</tr>
								<tr class="food_rw_haftegi">
									<td class="food_td_date">جمعه</td>
									<td id="meal_18" idmeal="18" class="fd_drop_vade"></td>
									<td id="meal_19" idmeal="19" class="fd_drop_vade"></td>
									<td id="meal_20" idmeal="20" class="fd_drop_vade"></td>
								</tr>
								<tr class="food_f_footertbl" style="border: 1px solid #727272;">
									<td style="text-align: center;font: 13px BYekanRegular;border: none;position:relative;">
										<div id="loading1" style="right:4px; top:10px;width: 200px;text-align: right;direction: rtl;">
											<div style="vertical-align: top;display: inline-block;padding: 0px;">
											<img style="margin: 0;padding: 0;display: block;" src="img/savehafteebadi.gif"/></div>
											<div
												style="vertical-align: top;line-height:30px;display: inline-block;direction: rtl;text-align: right;padding-right: 5px;">
												<span>درحال ذخیره ...</span>
											</div>
										</div>
									</td>
									<td style="text-align: center;font: 13px BYekanRegular;text-align: left;border: none;">
										<img id="preImage1" style="position: relative; top:4px;cursor: pointer;" src="img/pre.png"/>						
									</td>
									<td id="haftehid1" style="text-align: center;font: 13px BYekanRegular;border: none;">هفته اول</td>
									<td style="text-align: center;font: 13px BYekanRegular;border: none;text-align: right;">
										<img id="nextImage1" style="position: relative; top:4px;cursor: pointer;" src="img/nxt.png"/>
									</td>
								</tr>
							</table>
						</div>
					</div>
				</div>
				</div>
			</div>
            </div>
            <?php 
				}
				if($permit_admin == '1' || $permit_supervisor == '1'){
			?>
			<div id="food-tabs-4" style="padding:0px;padding-top:5px;min-width:800px;height: 94%;width: 100%;">    
				<div style="height: 96%;width: 100%;overflow:auto;">
				<div
					style="vertical-align:top;padding:5px;width:500px;
					background-color:white;display: inline-block;background-color: #d9f1ff;"
					class="box_sized radius5">
					<div style="width: 100%;height: 100%;border: 1px solid #6fb7ff;background-color: white;"
						 class="box_sized radius5">
						<div
							style="height: 30px;font:13px BYekanRegular;text-align:center; width: 100%;background-color: #6fb7ff;color: white;line-height:28px;"
							class="box_sized">آمار
						</div>
						<div class="food_list_content box_sized foodlistscroll"
							 style="width: 100%;padding: 5px;min-height: 400px;max-height: 800px;overflow: auto;">
							<div style="width:100%;text-align:center;margin-bottom:5px;" class="box_sized">
								<input id="date_food_report_day" type="text" class="box_sized" style="text-align:center;font:12px tahoma;padding:2px;" value="1393/03/24"/>
								<select id="date_food_report_part" class="box_sized" style="vertical-align:top;text-align:center;font:12px tahoma;padding:2px;" >
									<option value="0">صبحانه</option>
									<option value="1" selected>نهار</option>
									<option value="2">شام</option>
								</select> 
								<input id="btn_food_report_day" type="button" value="گزارش" style="vertical-align:top;font:12px tahoma;width:70px;" />
								<img id="print_report" style="vertical-align:top;width:24px ;cursor: pointer;display:inline-block;" src="img/p3.png"/>
							</div>
							<table id = "food_report_table" style="width:100%;font:13px tahoma;" class="food_tbl_orders">
								
							</table>
						</div>
					</div>
				</div>    
				</div>
			</div>			
			<div id="food-tabs-5" style="padding:0px;padding-top:5px;min-width:800px;height: 94%;width: 100%;">    
				<div style="height: 96%;width: 100%;overflow:auto;">
					<div style="vertical-align:top;padding:5px;width:700px;margin:0px 0px !important;
						background-color:white;background-color: #d9f1ff;"
						class="box_sized radius5">
						<div style="width: 100%;height: 100%;border: 1px solid #6fb7ff;background-color: white;"
							 class="box_sized radius5">
							<div
								style="height: 30px;font:13px BYekanRegular;text-align:center; width: 100%;background-color: #6fb7ff;color: white;line-height:28px;"
								class="box_sized">لیست
							</div>
							<div class="food_list_content box_sized foodlistscroll"
								 style="width: 100%;padding: 5px;min-height: 400px;max-height: 400px;overflow: auto;">
								<div id="food_list_detail_div_select" style="width:100%;text-align:center;margin-bottom:5px;" class="box_sized">	
									<input id="date_food_ready_day" type="text" class="box_sized" style="text-align:center;font:12px tahoma;padding:2px;" value=""/>
									<select id="date_food_ready_part" class="box_sized" style="text-align:center;font:12px tahoma;padding:2px;" >
										<option value="0">صبحانه</option>
										<option value="1" selected>نهار</option>
										<option value="2">شام</option>
									</select> 
									<select id="food_list_detail_select" class="box_sized" style="text-align:center;font:12px tahoma;padding:2px;">
									</select>
									<input id="food_list_detail_btn" style="font:13px tahoma;width:60px;" type="button" value="گزارش" />
									<img id="print_ready" style="vertical-align:top;width:24px ;cursor: pointer;display:inline-block;" src="img/p3.png"/>
								</div>
								<div id="loading4"  style="width:100%;text-align:center;font:12px tahoma;padding:2px;">در حال بارگذاري ...</div>
								<table id="food_list_detail"style="width:100%;font:13px tahoma;text-align:center;" class="tbl_print_food">
									<!--<tr style="background-color:gray;color:white;">
										<td style="width:30%;">نام خانوادگی</td>
										<td style="width:30%;">نام</td>
										<td style="width:30%;">نوع غذا</td>
										<td style="width:10%;">تعداد</td>
									</tr>
									<tr>
										<td>جهانخواه</td>
										<td>محمد رضا</td>
										<td>خورشت سبزی</td>
										<td>1</td>
									</tr>
									<tr>
										<td>جهانخواه</td>
										<td>محمد رضا</td>
										<td>خورشت سبزی</td>
										<td>1</td>
									</tr>
									<tr>
										<td>جهانخواه</td>
										<td>محمد رضا</td>
										<td>خورشت قیمه</td>
										<td>1</td>
									</tr>-->
								</table>
							</div>
						</div>
					</div>				
				</div>
			</div>
			<?php 
				}
				if($permit_admin == '1'){
			?>
			<div id="food-tabs-6" style="padding:0px;padding-top:5px;min-width:800px;height: 94%;width: 100%;">    
				<div style="height: 96%;width: 100%;overflow:auto;">
					<div style="vertical-align:top;padding:5px;width:700px;margin:0px 0px !important;
						background-color:white;background-color: #d9f1ff;"
						class="box_sized radius5">
						<div style="width: 100%;height: 100%;border: 1px solid #6fb7ff;background-color: white;"
							 class="box_sized radius5">
							<div
								style="height: 30px;font:13px BYekanRegular;text-align:center; width: 100%;background-color: #6fb7ff;color: white;line-height:28px;"
								class="box_sized">گزارشات
							</div>
							<div class="food_list_content box_sized foodlistscroll"
								 style="width: 100%;padding: 5px;min-height: 400px;max-height: 400px;overflow: auto;">
								 <div style="outline:1px solid lightgray;margin-bottom:2px;background-color:#f7f7f7;padding:3px;width:100%;text-align:right;" class="box_sized">
									پرسنل 
									<br/><select id="food_report_user_select" class="box_sized" style="width:100%;text-align:right;font:12px tahoma;padding:2px;" >
										<!--<option value="0">محمد رضا جهانخواه</option>-->
									</select> <br/>
									از تاریخ 
									<br/><input id="food_report_user_date1" type="text" class="box_sized" style="width:100%;text-align:right;font:12px tahoma;padding:2px;" value="1394/01/01"/>
									<br/>
									تا
									<br/><input id="food_report_user_date2" type="text" class="box_sized" style="width:100%;text-align:right;font:12px tahoma;padding:2px;" value="1394/01/31"/>
									<br/>
									<div style="margin-top:3px;text-align:center;">
									<input id="btn_food_report_user" type="button" value="گزارش" style="font:12px tahoma;width:70px;" />
									</div>
									<div id="loading5"  style="width:100%;text-align:center;font:12px tahoma;padding:2px;">در حال بارگذاري ...</div>
								</div>
								<table id="food_user_detail"style="width:100%;font:13px tahoma;text-align:center;" class="tbl_print_food">
									
								</table>
							</div>
						</div>
					</div>				
				</div>
			</div>
			<div id="food-tabs-7" style="padding:0px;padding-top:5px;min-width:800px;height: 94%;width: 100%;">    
				<div style="height: 96%;width: 100%;overflow:auto;">
					<div style="vertical-align:top;padding:5px;width:500px;margin:0px 0px !important;
						background-color:white;background-color: #d9f1ff;"
						class="box_sized radius5">
						<div style="width: 100%;height: 100%;border: 1px solid #6fb7ff;background-color: white;"
							 class="box_sized radius5">
							<div
								style="height: 30px;font:13px BYekanRegular;text-align:center; width: 100%;background-color: #6fb7ff;color: white;line-height:28px;"
								class="box_sized">تنظیمات
							</div>
							<div class="food_list_content box_sized foodlistscroll"
								 style="width: 100%;padding: 5px;">
								<div></div>
								<table id="setting_table" style="width:100%;font:13px BYekanRegular;margin-top:10px;">
									<tr>
										<td style="width: 20%;">تعداد هفته: </td>
										<td style="width: 40%;"><input id="setting_weeks"  type="text" class="box_sized setting numberic" name="weeks" saved="" /></td>
										<td style="width: 10%;">هفته</td>
									</tr>
									<tr>
										<td>تعداد روز (رزرو غذا): </td>
										<td><input id="setting_numberDayForSelction"  type="text" class="box_sized setting numberic" name="numberDayForSelction" saved="" /></td>
										<td>روز</td>
									</tr>
									<tr>
										<td>نمایش مبلغ: </td>
										<td><input id="setting_ShowMoney" type="checkbox" class="box_sized" name="ShowMoney" saved="" /></td>
										<td></td>
									</tr>
								</table>
								<div style="margin-top:25px;text-align:center;">
									<input id="save_setting" type="button" value="ذخیره" style="font:12px tahoma;width:70px;" />
								</div>
								<div id="loading6"  style="width:100%;text-align:center;font:12px tahoma;padding:2px;">در حال بارگذاري ...</div>
							</div>
						</div>
					</div>				
				</div>
			</div>
			<?php 
				}
			?>
	   </div>
    </div>
    <!-- end of body window -->
</div>