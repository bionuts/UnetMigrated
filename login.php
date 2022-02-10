<?php 
if(isset($_POST["login_submit"]))
{
	$username = addslashes(trim($_POST["username"]));
	$password = addslashes(trim($_POST["password"])); 
	include "user_accounts.php";
	$obj = new user_accounts_class();
	if($obj->login($username,$password))
	{
		header("Location: index.php");
	}
	else
	{
		$wrong_pass = "نام کاربری / رمز عبور اشتباه می باشد";
	}	
}
?>
<!DOCTYPE html>
<!--[if lt IE 7]> <html class="lt-ie9 lt-ie8 lt-ie7" lang="en"> <![endif]-->
<!--[if IE 7]> <html class="lt-ie9 lt-ie8" lang="en"> <![endif]-->
<!--[if IE 8]> <html class="lt-ie9" lang="en"> <![endif]-->
<!--[if gt IE 8]><!-->
<html lang="en">
<!--<![endif]-->
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
<title>ورود به سیستم</title>
<link rel="stylesheet" href="loginstyle/css/style.css" />
<link rel="stylesheet" href="font/byekan.css" />
<!--[if lt IE 9]><script src="//html5shim.googlecode.com/svn/trunk/html5.js"></script><![endif]-->
<script type="text/javascript">
	function myFunction() {
		window.location.href = 'login.php';
	}
  </script>
  <style type "text/css">
<!--
/* @group Blink */
.blink {
	-webkit-animation: blink .75s linear infinite;
	-moz-animation: blink .75s linear infinite;
	-ms-animation: blink .75s linear infinite;
	-o-animation: blink .75s linear infinite;
	 animation: blink .75s linear infinite;
}
@-webkit-keyframes blink {
	0% { opacity: 1; }
	50% { opacity: 1; }
	50.01% { opacity: 0; }
	100% { opacity: 0; }
}
@-moz-keyframes blink {
	0% { opacity: 1; }
	50% { opacity: 1; }
	50.01% { opacity: 0; }
	100% { opacity: 0; }
}
@-ms-keyframes blink {
	0% { opacity: 1; }
	50% { opacity: 1; }
	50.01% { opacity: 0; }
	100% { opacity: 0; }
}
@-o-keyframes blink {
	0% { opacity: 1; }
	50% { opacity: 1; }
	50.01% { opacity: 0; }
	100% { opacity: 0; }
}
@keyframes blink {
	0% { opacity: 1; }
	50% { opacity: 1; }
	50.01% { opacity: 0; }
	100% { opacity: 0; }
}
/* @end */
-->
</style>
</head>
<body>
<p style="text-align:center;direction:rtl;background-color:white;padding:10px;">
کاربران محترم جهت دسترسی به سامانه درخواست مجوز توسط شبکه خارج از سازمان از طریق آدرس زیر اقدام فرمایید : <br/>
<a style="color:blue;" href="https://unet.shirazmetro.ir">https://unet.shirazmetro.ir</a>
</p>

<section class="container">	
  <div class="login">	
    <h1><img src="img/logo.jpg" style="position:relative;top:20px;width:70px;height:70px;"/>
      <p style="margin:8px 0px 0px 0px;font:13px BYekanRegular;">سیستم جامع بهره برداری قطار شهری شیراز و حومه</p>	  
    </h1>
    <form method="post" action="login.php">
      <p style="margin:0px;padding:0px;font:12px tahoma;color:red;text-align:center;"><?php echo @$wrong_pass; ?></p>
      <p>
        <input type="text" name="username" value="" placeholder="Username">
      </p>
      <p>
        <input type="password" name="password" value="" placeholder="Password">
      </p>
      <p class="submit" style="text-align:center;">
        <input type="submit" name="login_submit" value="ورود" style="font:13px tahoma;">
      </p>
	  <p>
		<?php
			date_default_timezone_set("Asia/Tehran");
			$hour = date('H');
			echo 'Hour: '.$hour;
		?>
	  </p>
    </form>
  </div>
  <div class="login-help" style="display:none;">
    <p>Forgot your password? <a href="index.html">Click here to reset it</a>.</p>
  </div>
</section>
</body>
</html>
