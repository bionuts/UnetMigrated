<?php

if (!isset($_GET["id"])) exit;
$mojavez_id = trim($_GET["id"]);
if (!ctype_digit($mojavez_id)) exit;

session_start();
include '../../../util/util.php';
$util = new UtilClass();
if (@$_SESSION['hashuser'] != @$util->hashuser($_SESSION["userid"] . $_SESSION["username"] . $_SERVER['REMOTE_ADDR'] . $_SERVER['HTTP_USER_AGENT'])) exit;

include '../lib/permit-config.php';
include '../lib/permitUtil.php';
$putil  = new permitUtil();
$roleid = $putil->getUserRoleID($_SESSION['userid']);
$roleid = $roleid[0];

// check request has sent from peimankars
if($roleid == 1 || $roleid == 2)
{	
	// check designated permit belongs to owner
	if(!$putil->OwnerPermit($mojavez_id,$roleid,$_SESSION["userid"])) exit;	

	$deadtimereq = 11;
	date_default_timezone_set("Asia/Tehran");
	$hour = date('H');

	if($hour < $deadtimereq)
	{
		if($putil->delete_permit($mojavez_id))
			echo 'done';
		else
			echo 'failed';
	}
	else
	{
		echo 'limit';
	}
}
?>