<?php

try
{
	include_once("captcha_class.php");

	$id = (isset($_REQUEST['id']) && !empty($_REQUEST['id'])) ? trim($_REQUEST['id']) : exit;
	$key = (isset($_REQUEST['key']) && !empty($_REQUEST['key'])) ? trim($_REQUEST['key']) : exit;

	$random_char = new random_char();
	$ConfirmString = strtolower($random_char -> md5_decrypt($id, $key));

	$confirm_image = new confirm_image($ConfirmString);
	$confirm_image -> ShowImage();
}
catch(Exception $ex)
{
	echo 'Caught exception: ',  $ex -> getMessage(), "<br />\n";
	exit;
}


?>