<?php
session_start();
include '../lib/permit-config.php';
include '../../../util/util.php';
include '../lib/permitUtil.php';

$putil = new permitUtil();
$roleid = $putil->getUserRoleID($_SESSION['userid']);
$roleid = $roleid[0];
if ($roleid != 5) exit;

$arrform = $_POST['dataforms'];
$permitid = $arrform['permitid'];
$nazerid = $_SESSION['userid'];


$messege = '0';
// 0: success
// 1: zamane taeed mojavez be payan reside hast
// 2: haghe taeed ya rad permit ro nadarid
// 3: sql error

$tcritical = $arrform['non_critical'];

if($tcritical == 'true'){
	include '../lib/new_request_class.php';
	$reqobj = new new_request_class();
	$cell = $reqobj->permit_sp_check_nazer_for_edit_permit($nazerid, $permitid);
	if (!is_null($cell) && ($cell['ptunps_issign'] == 1)) {		
		$messege = $reqobj->update_new_request($arrform);
	} else {
		$messege = '2';
	}
}
else if($tcritical == 'false')
{
	$deadtimereq = $putil->getPermitSetting('nazer_request_for_permit');
	date_default_timezone_set("Asia/Tehran");
	$hour = date('H');// - 1; //Returns IST
	if ($hour < $deadtimereq) {
		include '../lib/new_request_class.php';
		$reqobj = new new_request_class();
		$cell = $reqobj->permit_sp_check_nazer_for_edit_permit($nazerid, $permitid);
		if (!is_null($cell) && ($cell['ptunps_issign'] == 1)) {			
			$messege = $reqobj->update_new_request($arrform);
		} else {
			$messege = '2';
		}

	} else {
		$messege = '1';
	}
}
echo $messege;
?>