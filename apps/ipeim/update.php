<?php
	include 'lib/jdf.php';
	$data = null;
	session_start();
	if(isset($_GET['pid']))
	{		
		$pidupdate = addslashes(trim($_GET['pid']));
		if(!is_numeric($pidupdate)) exit;		
		$_SESSION['pidupdate'] = $pidupdate;
		$data = get_p_info($pidupdate);
	}
	
	if(isset($_POST['update_peim']))
	{
		$data = array(
			'puser' => trim($_POST['puser']),
			'ppass' => trim($_POST['ppass']),
			'pname' => trim($_POST['pname']),
			'pnum' => trim($_POST['pnum']),
			'pdates' => trim($_POST['pdates']),
			'pdatef' => trim($_POST['pdatef']),
			'unet_peim' => trim($_POST['unet_peim'])
		);
		if($data['puser'] != '' && $data['ppass'] != '' && $data['pname'] != '' && $data['pnum'] != '' && $data['pdates'] != '' && $data['pdatef'] != '' && $data['unet_peim'] != 0)
		{	
			$arr = explode('/',$data['pdates']);
			$data['pdates'] =  jalali_to_gregorian($arr[0],$arr[1],$arr[2],'-');
			
			$arr = explode('/',$data['pdatef']);
			$data['pdatef'] =  jalali_to_gregorian($arr[0],$arr[1],$arr[2],'-');
			
			$resupdate = updatepeim($data);
			
			$arr = explode('-',$data['pdates']); // 2014-02-21
			$data['pdates'] = gregorian_to_jalali($arr[0],$arr[1],$arr[2],'/');
			
			$arr = explode('-',$data['pdatef']); // 2014-02-21
			$data['pdatef'] = gregorian_to_jalali($arr[0],$arr[1],$arr[2],'/');
			
		}
		else
		{
			$resupdate = '<p style="color:red;font-weight:bold;">Fill Data</p>';
		}
	}

	function get_p_info($id)
	{
		$data = null;
		$res = null;
		$conn = mysqli_connect('localhost', 'root', 'hmmhmm', 'unetdb');
        if ($conn->connect_error) return 'failed to connect to db';
        mysqli_set_charset($conn, "utf8");
		$sql = "CALL permit_sp_get_peimankar_info($id);";
		$res = mysqli_query($conn, $sql);		
		if($res)
		{
			if(mysqli_num_rows($res) == 1)
			{
				$row = mysqli_fetch_assoc($res);
				
				$arr = explode('-',$row['peimankar_info_start_gharardad']);// 2014-02-21
				$sdate = gregorian_to_jalali($arr[0],$arr[1],$arr[2],'/');
				
				$arr = explode('-',$row['peimankar_info_finish_gharardad']);// 2014-02-21
				$fdate = gregorian_to_jalali($arr[0],$arr[1],$arr[2],'/');
				
				$data = array(
					'puser' => $row['users_username'],
					'ppass' => $row['users_password'],
					'pname' => $row['users_lname'],
					'pnum' => $row['peimankar_info_shomaregharardad'],
					'pdates' => $sdate,
					'pdatef' => $fdate,
					'unet_peim' => $row['vahednezarat_id']
				);				
			}
		}		
		mysqli_close($conn);
		return $data;
	}
	
	function updatepeim($data)
	{
		$res = '';
		$conn = mysqli_connect('localhost', 'root', 'hmmhmm', 'unetdb');
        if ($conn->connect_error) return 'failed to connect to db';
        mysqli_set_charset($conn, "utf8");
		$sql = "CALL permit_sp_update_peimankar('".$data['pname']."',".$_SESSION['pidupdate'].",'".$data['pnum']."',".$data['unet_peim'].",'".$data['pdates']."','".$data['pdatef']."');";
		$res = mysqli_query($conn, $sql);		
		if($res)
		{
			$res = '<p style="color:green;font-weight:bold;">UPDATED</p>';
		}
		else
		{
			$res = '<p style="color:red;font-weight:bold;">ERROR</p>';
		}
		mysqli_close($conn);
		return $res;
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
<!doctype html>
<html>
<head>
	<meta charset="utf-8">
	<link rel="stylesheet" type="text/css" media="all" href="js/facal/skins/aqua/theme.css" title="Aqua" />
	
	<script type="text/javascript" src="js/jquery-1.11.1.min.js"></script>
	<script type="text/javascript" src="js/facal/jalali.js"></script>
	<script type="text/javascript" src="js/facal/calendar.js"></script>
	<script type="text/javascript" src="js/facal/calendar-setup.js"></script>
	<script type="text/javascript" src="js/facal/lang/calendar-fa.js"></script>
	<script type="text/javascript">
		$(function(){
		});
	</script>
</head>
<body>
<div id="insertpeim" style="direction:rtl;">
	<form action="update.php" method="post" autocomplete="off" style="display:inline-block;">
		<fieldset style="display:inline-block;">
			<legend style="direction:rtl;font:13px tahoma;">ویرایش پیمانکار</legend>		
			<table style="direction:rtl;font:13px tahoma;">
				<tr>
					<td>نام کاربری : </td>
					<td><input type="text" readonly="readonly" name="puser" value = "<?php echo @$data['puser']; ?>" style="text-align:center;direction:ltr;font:13px tahoma;width:300px;"/></td>
				</tr>
				<tr>
					<td>رمز عبور : </td>
					<td><input type="text" required name="ppass" value = "<?php echo @$data['ppass']; ?>" style="text-align:center;direction:rtl;font:13px tahoma;width:300px;"/></td>
				</tr>
				<tr>
					<td>نام پیمانکار : </td>
					<td><input type="text" required name="pname" value = "<?php echo @$data['pname']; ?>" style="text-align:center;direction:rtl;font:13px tahoma;width:300px;"/></td>
				</tr>
				<tr>
					<td>شماره قرارداد : </td>
					<td><input type="text" required name="pnum" value = "<?php echo @$data['pnum']; ?>" style="text-align:center;direction:rtl;font:13px tahoma;width:300px;"/></td>
				</tr>
				<tr>
					<td>تاریخ شروع قرارداد : </td>
					<td>
						<input type="text" readonly="readonly" id="pdates" required name="pdates" value = "<?php echo @$data['pdates']; ?>" placeholder="1342/02/06" style="text-align:center;direction:rtl;font:13px tahoma;width:300px;"/>						
						<img id="date_btn_s" src="img/cal.png" style="vertical-align: top;">
					</td>
				</tr>
				<tr>
					<td>تاریخ پایان قرارداد : </td>
					<td>
						<input type="text" id="pdatef" readonly="readonly" required name="pdatef" value = "<?php echo @$data['pdatef']; ?>" placeholder="1342/08/28" style="text-align:center;direction:rtl;font:13px tahoma;width:300px;"/>
						<img id="date_btn_f" src="img/cal.png" style="vertical-align: top;">
					</td>
				</tr>
				<tr>
					<td>واحد نظارت : </td>
					<td>						
						<select name="unet_peim" style="width:300px;direction:rtl;font:13px tahoma;">
							<option value="0"></option>
							<?php
								echo get_nezarati_vahed_list(@$data['unet_peim']);
							?>
						</select>
					</td>
				</tr>
				<tr style="text-align:center;">
					<td colspan="2">
						<input type="submit" name="update_peim" value="ویرایش"  style="text-align:center;direction:rtl;font:13px tahoma;width:100px;"/>						
						<div style="text-align:center;direction:rtl;font:13px tahoma;width:100px;text-align:center;width:100%;" ><?php echo @$resupdate; ?></div>
					</td>
				</tr>
			</table>
		</fieldset>		
	</form>
</div>
<script type="text/javascript">
	Calendar.setup({
		inputField  : "pdates",         // ID of the input field
		ifFormat    : "%Y/%m/%d",    // the date format
		dateType	   :	'jalali',
		button      : "date_btn_s"       // ID of the button
    });
	Calendar.setup({
		inputField  : "pdatef",         // ID of the input field
		ifFormat    : "%Y/%m/%d",    // the date format
		dateType	   :	'jalali',
		button      : "date_btn_f"       // ID of the button
    });
</script>
</body>
</html>
<?php

?>
