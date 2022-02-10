<?php
session_start();
include '../lib/permit-config.php';
include '../../../util/util.php';
include '../lib/permitUtil.php';

$util = new UtilClass();
if ($_SESSION['hashuser'] != $util->hashuser($_SESSION["userid"] . $_SESSION["username"] . $_SERVER['REMOTE_ADDR'] . $_SERVER['HTTP_USER_AGENT'])) {
    exit();
}
$putil = new permitUtil();
$roleid = $putil->getUserRoleID($_SESSION['userid']);
$roleid = $roleid[0];
if ($roleid != 3 and $roleid != 11) exit;

$userid = $_SESSION['userid'];
$permitid = trim($_POST['permitid']);
if (!ctype_digit($permitid)) exit;
$texttip = addslashes(str_replace("\n",'<br/>',trim($_POST['texttip'])));
$trp = trim($_POST['trp']);

include '../lib/new_request_class.php';
$reqobj = new new_request_class();
echo $reqobj->radtaeed_occ($permitid,$trp,$texttip,$userid,$roleid);
?>

