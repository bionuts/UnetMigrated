<?php
    include_once($_SERVER['DOCUMENT_ROOT'].'/config/main_config.php');
	$res = '';
	include 'lib/jdf.php';
	if(isset($_POST['save_peim']))
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
			
			$res = insertpeim($data);
			$data = array(
				'puser' => '',
				'ppass' => trim($_POST['ppass']),
				'pname' => '',
				'pnum' => '',
				'pdates' => trim($_POST['pdates']),
				'pdatef' => trim($_POST['pdatef']),
				'unet_peim' => trim($_POST['unet_peim'])
			);
		}
		else
		{
			$res = '<p style="color:red;font-weight:bold;">Fill Data</p>';
		}
				
		
	}
	else if(isset($_POST['list_peim']))
	{
		$nezaratid =  addslashes(trim($_POST['unet_peim_list']));
		if($nezaratid != 0)
		{
			$peim_list_res = get_peymankars_list($nezaratid);
		}
	}
	else if(isset($_GET['piddel']))
	{
		$pi = addslashes(trim($_GET['piddel']));
		if(is_numeric($pi))
		{
			$delres = delpeim($pi);
		}		
	}
	function delpeim($id)
	{
		$deloutput = '';
		// $conn = mysqli_connect('localhost', 'root', 'hmmhmm', 'unetdb');
        $conn = mysqli_connect(MainConfigClass::$dbserver, MainConfigClass::$user, MainConfigClass::$pass, MainConfigClass::$dbname);
        if ($conn->connect_error) return 'failed to connect to db';
		//$sql = "CALL permit_sp_delete_peimankar($id);";
		$res = mysqli_query($conn, $sql);		
		if($res)
		{
			$deloutput = '<p style="color:green;font-weight:bold;">DELETED</p>';
		}
		else
		{
			$deloutput = '<p style="color:red;font-weight:bold;">ERROR</p>';
		}
		mysqli_close($conn);
		return $deloutput;
	}
	
	function insertpeim($data)
	{
		$res = '';
		$conn = mysqli_connect('localhost', 'root', 'hmmhmm', 'unetdb');
        if ($conn->connect_error) return 'failed to connect to db';
        mysqli_set_charset($conn, "utf8");
		$sql = "CALL permit_sp_insert_peimankar('".$data['puser']."','".$data['ppass']."','".$data['pname']."',".$data['unet_peim'].",'".$data['pnum']."','".$data['pdates']."','".$data['pdatef']."');";
		$res = mysqli_query($conn, $sql);		
		if($res)
		{
			$res = '<p style="color:green;font-weight:bold;">SAVED</p>';
			/*if(mysqli_affected_rows($conn))
			{
				$res = '<p style="color:green;font-weight:bold;">SAVED</p>';
			}
			else
			{
				$res = '<p style="color:red;font-weight:bold;">ERROR</p>';
			}*/
		}
		else
		{
			$res = '<p style="color:red;font-weight:bold;">ERROR-Duplicate User</p>';
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
	
	function get_peymankars_list($nezarat_id)
	{		
		$conn = mysqli_connect('localhost', 'root', 'hmmhmm', 'unetdb');
        if ($conn->connect_error) return 'failed to connect to db';
        mysqli_set_charset($conn, "utf8");
		$result = mysqli_query($conn, "CALL permit_sp_GetUserNazerTakePeimankar($nezarat_id,NULL);");		
		if (mysqli_num_rows($result) > 0) 
		{
			$tmp = '';
			while($row = mysqli_fetch_assoc($result))
			{
				$arr = explode('-',$row['peimankar_info_start_gharardad']); // 2014-02-21
				$ss = gregorian_to_jalali($arr[0],$arr[1],$arr[2],'/');
				
				$arr = explode('-',$row['peimankar_info_finish_gharardad']); // 2014-02-21
				$ff = gregorian_to_jalali($arr[0],$arr[1],$arr[2],'/');
				
				$tmp .= '<tr>';
				$tmp .= '<td>'.$row['users_username'].'</td>';
				$tmp .= '<td>'.$row['users_fname'].' '.$row['users_lname'].'</td>';
				$tmp .= '<td>'.$row['peimankar_info_shomaregharardad'].'</td>';
				$tmp .= '<td>'.$ss.'</td>';
				$tmp .= '<td>'.$ff.'</td>';
				$tmp .= '<td>'.$row['vahednezarat_name'].'</td>';			
				$tmp .= '<td><input type="button" pid="'.$row['users_id'].'" class="peim_btn_edit" value="ویرایش"  style="font:13px tahoma;"/></td>';			
				//$tmp .= '<td><input type="button" pid="'.$row['users_id'].'" class="peim_btn_del" value="حذف"  style="font:13px tahoma;"/></td>';			
				$tmp .= '</tr>';				
			}			
		} 
		else 
		{
			$tmp = '<tr><td colspan="8">موجود نمی باشد</td></tr>';
		}		
		mysqli_close($conn);
		return $tmp;
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
		$(function()
		{
			$('.peim_btn_del').click(function(){
				var conf = confirm('آیا مطمئن هستید؟');
				if(conf)
				{
					var pid = $(this).attr('pid');
					window.location = window.location + '?piddel=' + pid + '&r='+Math.random();
				}				
			});
			$('.peim_btn_edit').click(function()
			{
				var pid = $(this).attr('pid');
				var win = window.open('update.php?pid=' + pid + '&r=' + Math.random(), '_blank');
				if (win) 
				{
					win.focus();
				} 				
			});
		});
	</script>
</head>
<body>
<div id="insertpeim" style="direction:rtl;">
	<form action="index.php" method="post" autocomplete="off" style="display:inline-block;">
		<fieldset style="display:inline-block;">
			<legend style="direction:rtl;font:13px tahoma;">پیمانکار جدید</legend>		
			<table style="direction:rtl;font:13px tahoma;">
				<tr>
					<td>نام کاربری : </td>
					<td><input type="text" required name="puser" value = "<?php echo @$data['puser']; ?>" style="text-align:center;direction:ltr;font:13px tahoma;width:300px;"/></td>
				</tr>
				<tr>
					<td>رمز عبور : </td>
					<td><input type="text" required name="ppass" value = "<?php echo @$data['ppass']; ?>" style="text-align:center;direction:rtl;font:13px tahoma;width:300px;"/></td>
				</tr>
				<tr>
					<td>نام پیمانکار : </td>
					<td><input type="text" required name="pname" placeholder="بدون کلمه شرکت" value = "<?php echo @$data['pname']; ?>" style="text-align:center;direction:rtl;font:13px tahoma;width:300px;"/></td>
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
								echo get_nezarati_vahed_list();
							?>
						</select>
					</td>
				</tr>
				<tr style="text-align:center;">
					<td colspan="2">
						<input type="submit" name="save_peim" value="ذخیره"  style="text-align:center;direction:rtl;font:13px tahoma;width:100px;"/>						
						<div style="text-align:center;direction:rtl;font:13px tahoma;width:100px;text-align:center;width:100%;" ><?php echo @$res; ?></div>
					</td>
				</tr>
				<tr style="text-align:center;">
					<td colspan="2">
						<a href="worker.php" style="text-decoration:none;">اضافه کردن نفرات پیمانکار</a>
					</td>
				</tr>
			</table>
		</fieldset>		
	</form>
	<form action="index.php" method="post" style="display:inline-block;vertical-align:top;">
		<fieldset style="display:inline-block;">
			<legend style="direction:rtl;font:13px tahoma;">لیست پیمانکاران</legend>
			<select name="unet_peim_list" style="width:300px;direction:rtl;font:13px tahoma;">
				<option value="0"></option>
				<?php
					echo get_nezarati_vahed_list($_POST['unet_peim_list']);
				?>
			</select>
			<input type="submit" name="list_peim" value="لیست"  style="font:13px tahoma;"/><?php echo @$delres; ?>
			<table style="direction:rtl;font:13px tahoma;" border="1" style="border-collapse: collapse;">
				<tr style="background-color:black;color:white;text-align:center;">
					<td>نام کاربری</td>					
					<td>نام پیمانکار</td>
					<td>شماره قرارداد</td>
					<td>تاریخ شروع قرارداد</td>
					<td>تاریخ پایان قرارداد</td>
					<td>واحد نظارت</td>
					<td>ویرایش</td>					
				</tr>
				<?php echo @$peim_list_res; ?>
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
