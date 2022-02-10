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
if ($roleid != 3) exit;

// $userid = $_SESSION['userid'];
// if (!ctype_digit($permitid)) exit;

$status = trim($_POST['status']);

include '../lib/new_request_class.php';
$reqobj = new new_request_class();
echo $reqobj->radtaeed_publish_occ($status);
?>

