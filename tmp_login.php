<?php 
	session_start();	
	$chkvis=0;
	if(!isset($_SESSION["captcha_try"])) {$_SESSION["captcha_try"]=0;}
	else
	{
		@$cap = $_SESSION["captcha_try"]+1;
		@$_SESSION["captcha_try"]=$cap;
	}
	if(isset($_POST["login_submit"]))
	{
		@$captcha_c = strtolower(trim($_POST["captcha_code"]));
		$username = trim($_POST["username"]);
		$password = trim($_POST["password"]); 
		
		$tmp_username = $username;
		
		require "user_accounts.php";
		 $obj = new user_accounts_class();
		 if($obj->login($username, /*md5*/($password)) && @$captcha_c == @$_SESSION['cp_code'])
		 {
			header("Location: index.php");
		 }
		 else
		 {
			$wrong_pass="نام کاربری/رمز عبور اشتباه می باشد";
			 if($_SESSION["captcha_try"] >3) $wrong_pass="کد امنیتی اشتباه می باشد";
				if(!$obj->login($username, /*md5*/($password)))	$wrong_pass="نام کاربری/رمز عبور اشتباه می باشد";	
		 }			
	}
	
	if(@$_SESSION["captcha_try"] >=3)
	{
		$chkvis = 1;
		include_once("lib/captcha/captcha_class.php");
		try
		{
			$random_char = new random_char();
			$id = ($random_char -> get_id());
			$key = ($random_char -> get_key());
			$code = strtolower	( $random_char -> get_code() );
			$_SESSION['cp_code'] = $code;
		}
		catch(Exception $ex)
		{
			echo 'Caught exception: ',  $ex -> getMessage(), "<br /><br />"; exit;
		}
	}
?>

<!DOCTYPE html>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />		
		<script type = "text/javascript" src="js/jquery-1.11.1.min.js" ></script>
		<link rel="stylesheet" type="text/css" href="css/login.css" />
		<link rel="stylesheet" type="text/css" href="font/byekan.css" />	
		<style type = "text/css">
			
		</style>
		<script type="text/javascript">
			function myFunction() {
				window.location.href = 'login.php';
			}
		</script>
	</head>
	<body dir="rtl">	
		<form enctype="application/x-www-form-urlencoded" method="post" action="login.php">
			<div class="box_sized" style="width:400px;padding:10px;margin:150px auto;padding:0px;">		
				<fieldset class="login_feildset_style" style="background-color:white;margin:0px;">
					<legend align="center" class="login_legend_style">ورود به سیستم</legend>
					<div class="box_sized" style="width: 100%;padding:5px;">
						<div>
							<label class="fa_direction" for="username">نام کاربری : <span style="color:red;font-size:13px;font-family:tahoma;"><?php echo @$wrong_pass; ?></span></label>
							<span style="color: red;margin-right: 5px;font-family: tahoma;font-size: 12px;"></span><br>
							<input type="text" value="<?php echo @$tmp_username; ?>" class="en_dir box_sized" id="username" name="username">
						</div>
						<div>
							<label class="fa_direction" for="username">رمز عبور :</label><br>
							<input type="password" class="en_dir rounded_corners_5px box_sized" id="password" name="password">
						</div>
						<?php
							if ($chkvis == 1)
							{
						?>	
						<div style="text-align:center;" >
							<div style="position:relative;left:15px;top:-7px;display:inline-block;"> 
								<img src="img/reload_captch.png" onclick="myFunction();" style="width:30px;height:30px;cursor:pointer;"/> 
							</div>

							<div style="position:relative;left:15px; display:inline-block;">
								<img src="lib/captcha/?id=<?php echo $id; ?>&key=<?php echo $key; ?>" alt="Confirm Image" title="Confirm Image" name="ConfirmImage" border="1" id="ConfirmImage" 
								style="margin-top:10px;"/>
							</div>
							<br />
							<input name="captcha_code" type="text" id="captcha_code" value="" class="en_dir  box_sized" style="width:150px;text-align:center;"/>
						</div>
						<?php } ?>
						<div style="text-align: center;">
							<input type="submit" value="ورود" class="btn_login_style" id="login_submit" name="login_submit">
							
						</div>
					</div>
				</fieldset>
			</div>
		</form>
	</body>
</html>