<?php
session_start();
include '../lib/permit-config.php';
include '../../../util/util.php';
include '../lib/permitUtil.php';

$putil = new permitUtil();
$roleid = $putil->getUserRoleID($_SESSION['userid']);
$roleid = $roleid[0];
if ($roleid != 5) exit;

$nazerid = $_SESSION['userid'];
$permitid = $_POST['permitid'];
$whynazer = $_POST['whynazer'];

include '../lib/new_request_class.php';
$reqobj = new new_request_class();
$cell = $reqobj->permit_sp_check_nazer_for_edit_permit($nazerid, $permitid);
if (!is_null($cell) && ($cell['ptunps_issign'] == 1)) {
    echo $reqobj->radnazer($permitid, str_replace("\n", '<br/>', $whynazer), $nazerid);
}
?>

