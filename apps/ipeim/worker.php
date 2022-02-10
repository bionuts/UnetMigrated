<?php
if(isset($_POST['save_ipeim_worker']))
{		
	$data = array(
		'peimid' => trim($_POST['peimid']),
		'pifname' => trim($_POST['pifname']),
		'pilname' => trim($_POST['pilname']),
		'pimelli' => trim($_POST['pimelli']),
		'pitel' => trim($_POST['pitel'])
	);
		
	if($data['pifname'] != '' && $data['pilname'] != '' && $data['pimelli'] != '' && $data['pitel'] != '' && $data['peimid'] != 0)
	{	
		$resworker = insertnewworker($data);
	}
	else
	{
		$resworker = '<p style="color:red;font-weight:bold;">Fill Data</p>';
	}
}
if(isset($_POST['peimid']))	$workerlist = get_peymankars_nafarat_list(@$_POST['peimid']);	

function get_peymankars_nafarat_list($pi)
{
	$res = '';
	$conn = mysqli_connect('localhost', 'root', 'hmmhmm', 'unetdb');
	if ($conn->connect_error) return 'failed to connect to db';
	mysqli_set_charset($conn, "utf8");

	$result = mysqli_query($conn, "CALL permit_sp_GetPeimankarTakeListOfp($pi);");
	$rows=null;
	if (mysqli_num_rows($result) > 0) 
	{	
		// peimankar_listnafarat_id	,peimankar_listnafarat_fname,peimankar_listnafarat_lname,peimankar_listnafarat_codemelli,peimankar_listnafarat_mobile
		while($row = mysqli_fetch_assoc($result))
		{
			$res .= '<tr>';
			$res .= '<td>'.$row['peimankar_listnafarat_fname'].'</td>';
			$res .= '<td>'.$row['peimankar_listnafarat_lname'].'</td>';
			$res .= '<td>'.$row['peimankar_listnafarat_codemelli'].'</td>';
			$res .= '<td>'.$row['peimankar_listnafarat_mobile'].'</td>';
			$res .= '<td><input type="button" wid="'.$row['peimankar_listnafarat_id'].'" class="peim_btn_edit" value="ویرایش"  style="font:13px tahoma;"/></td>';			
			$res .= '<td><input type="button" wid="'.$row['peimankar_listnafarat_id'].'" class="peim_btn_del" value="حذف"  style="font:13px tahoma;"/></td>';	
			$res .= '</tr>';
		}
	} 	
	mysqli_close($conn);
	return $res;
}
		
function insertnewworker($data)
{
	$res = '';
	$conn = mysqli_connect('localhost', 'root', 'hmmhmm', 'unetdb');
	if ($conn->connect_error) return 'failed to connect to db';
	mysqli_set_charset($conn, "utf8");
	$sql = "CALL permit_sp_insert_peimankar_nafar(".$data['peimid'].",'".$data['pifname']."','".$data['pilname']."','".$data['pimelli']."','".$data['pitel']."');";
	$res = mysqli_query($conn, $sql);
	echo $conn->error;
	if($res)
	{
		$res = '<p style="color:green;font-weight:bold;">SAVED</p>';
	}
	else
	{
		$res = '<p style="color:red;font-weight:bold;">ERROR</p>';
	}
	mysqli_close($conn);
	return $res;
}

function get_peymankars_list_holder($nezarat_id,$peimid)
{
	$conn = mysqli_connect('localhost', 'root', 'hmmhmm', 'unetdb');
	if ($conn->connect_error) return 'failed to connect to db';
	mysqli_set_charset($conn, "utf8");

	$result = mysqli_query($conn, " CALL permit_sp_GetUserNazerTakePeimankar(".$nezarat_id.",NULL);");
				
	$str='';
	
	$option_start = "<option value='";
	$option_end = "</option>";
	
	if (mysqli_num_rows($result) > 0) {
		// output data of each row
		while($row = mysqli_fetch_assoc($result)){
			$sel = (($row['fkusers_peimankar_info_id'] == $peimid) ? 'selected' : '');
			$str .= $option_start . $row['fkusers_peimankar_info_id'] ."' ".$sel." >" . $row['users_fname'] ." ". $row['users_lname'] . $option_end;
		}
	} else {
		echo $option_start."0'>اطلاعات مورد نظر یافت نشد".$option_end;
	}
	
	mysqli_close($conn);
	echo  ($str);
}

function get_nezarati_vahed_list($iactive = 0)
{
	if($iactive != 0) $iactive = addslashes(trim($iactive));
	$conn = mysqli_connect('localhost', 'root', 'hmmhmm', 'unetdb');
	if ($conn->connect_error) return 'failed to connect to db';
	mysqli_set_charset($conn, "utf8");
	$result = mysqli_query($conn, "CALL permit_sp_Show_nazer(NULL);");
	$str = '';
	
	$option_start = "<option value='";
	$option_end = "</option>";
	
	if (mysqli_num_rows($result) > 0) {
		// output data of each row
		while($row = mysqli_fetch_assoc($result))
		{
			$sel = (($row['vahednezarat_id'] == $iactive) ? 'selected' : '');
			$str .= $option_start . $row['vahednezarat_id'] ."' ".$sel." >" . $row['vahednezarat_name'] . $option_end;
		}
	}		
	mysqli_close($conn);
	return $str;
}
?>
<!DOCTYPE html>
<html>
	<head>
		<title></title>
		<meta charset="UTF-8"/>
		<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0"/>
		<script type="text/javascript" src="js/jquery-1.11.1.min.js"></script>
		<script type="text/javascript">
			$(function(){
				$('body').on('change', '#permit_cmbx_unit_nezarat', function () {
					var nezaratid = $(this).val();
					if (nezaratid != 0) {
						$('#permit_cmbx_peimankar_of_unitnezarat option').remove();						
						GetPeimankarOfNezaratUnit(nezaratid);
					}
				});
				
				function GetPeimankarOfNezaratUnit(nezaratid) {
					$.ajax({
						url: 'getipeim.php',
						type: 'POST',
						data: {'nezaratid': nezaratid},
						success: function (data) {
							$('#permit_cmbx_peimankar_of_unitnezarat option').remove();
							var strtmp = '<option value="0"></option>' + data;
							$('#permit_ajax_loader_peimankar_of_unitnezarat').fadeOut('fast');
							$('#permit_cmbx_peimankar_of_unitnezarat').append(strtmp);
							$('#permit_cmbx_peimankar_of_unitnezarat').prop("disabled", false);
						},
						beforeSend: function () {
							$('#permit_ajax_loader_peimankar_of_unitnezarat').fadeIn('fast');
						}
					});
				}
			});
		</script>
	</head>
	<body style="direction:rtl;">
		<form action="worker.php" method="post" autocomplete="off" style="display:inline-block;">
		<div  style="font-family:tahoma !important;font-size:13px;text-align:right;vertical-align: top;">
			<div >
				<table>
					<tr>
						<td>واحد نظارت:</td>
						<td>
							<div>
								<select class="permit_cmbox_style" name="unet_id" id="permit_cmbx_unit_nezarat" style="width:80%;">
									<option value="0"></option>
									<?php
										echo get_nezarati_vahed_list(@$_POST['unet_id']);
									?>
								</select>
								<img src="../../../img/ajax-loader.gif" id="permit_ajax_loader_unit_nezarat" style="display:none;position:relative;top:5px;"/>
							</div>
						</td>				
					</tr>
					<tr>
						<td>نام پیمانکار:</td>
						<td>
							<div>
								<select class="permit_cmbox_style" name="peimid" id="permit_cmbx_peimankar_of_unitnezarat" style="width:80%;">
									<option value="0"></option>
									<?php if (isset($_POST['unet_id']) && isset($_POST['peimid'])) get_peymankars_list_holder($_POST['unet_id'],$_POST['peimid']); ?>
								</select>
								
							</div>
						</td>
					</tr>
					<tr>
						<td>وارد کردن فایل اکسل:</td>
						<td>
							<input  type="file" />
						</td>
						<td>
							<button type="button" style="width:65px;">ذخیره</button>
						</td>
					</tr>
				</table>				
			</div>
			<div >
					<fieldset id="fieldset_nezarat" style="display:inline-block;" >
						<legend class="radius3" align="">افراد جدید</legend>
						<table style="padding:0px;margin:0px;">
							<tbody>
								<tr>
									<td style="vertical-align:top;">
										<div style="font-family:tahoma !important;font-size:13px;border:1px solid #898989;padding:5px;width:100%;">
											<label  style="margin:0px;margin-bottom:6px;outline:0px solid red;padding:0px;display:block;">نام:</label>
											<input id="permit_txtbx_activity_desc" value = "<?php echo @$data['pifname']; ?>"  class="permit_text_style fadir radius5 box_sized" type="text" name="pifname" style="font-family:tahoma !important;font-size:13px;width:80%;padding:4px !important;"  maxlength="120">

											<label style="margin:0px;margin-bottom:6px;outline:0px solid red;padding:0px;display:block;">نام خانوادگی:</label>
											<input id="permit_txtbx_activity_desc" class="permit_text_style fadir radius5 box_sized" value = "<?php echo @$data['pilname']; ?>" type="text" name="pilname" style="font-family:tahoma !important;font-size:13px;width:80%;padding:4px !important;"  maxlength="120">

											<label style="margin:0px;margin-bottom:6px;outline:0px solid red;padding:0px;display:block;">شماره ملی:</label>
											<input id="permit_txtbx_activity_desc" value = "<?php echo @$data['pimelli']; ?>" class="permit_text_style fadir radius5 box_sized" type="text" name="pimelli" style="font-family:tahoma !important;font-size:13px;width:80%;padding:4px !important;"  maxlength="120">

											<label style="margin:0px;margin-bottom:6px;outline:0px solid red;padding:0px;display:block;">شماره تماس:</label>
											<input id="permit_txtbx_activity_desc" value = "<?php echo @$data['pitel']; ?>" class="permit_text_style fadir radius5 box_sized" type="text" name="pitel" style="font-family:tahoma !important;font-size:13px;width:80%;padding:4px !important;"  maxlength="120">
											
											<input type="submit" name="save_ipeim_worker" value="اضافه"  style="text-align:center;direction:rtl;font:13px tahoma;width:100px;"/>
											<?php echo @$resworker; ?>
											<p><a href="index.php" style="text-decoration:underline;color:red;">بازگشت به صفحه قبل</a></p>
										</div>
									
									</td>
									
									
									<td style="vertical-align:top;">
							
										<div class="box_sized" style="margin-right:15px;border:1px solid #dddddd;background-color:#f1f1f1;">
										<table id="permit_tbl_nazer_of_nezarat" class="permit_nazer_mans box_sized" style="width:100%;" border="1">
											<tr class="permit_tbl_nazer_of_nezarat_header" style="background-color:#898989;color:white;text-align:center;">
												<td>نام</td>
												<td>نام خانوادگی</td>
												<td>شماره ملی</td>
												<td>شماره تماس</td>
												<td>ویرایش</td>
												<td>حذف</td>
											</tr>
											<?php echo @$workerlist; ?>
										</table>
										</div>
									</td>
								</tr>
							</tbody>
						</table>
					</fieldset>
				
			</div>
		</div>
		</form>
	</body>
</html>