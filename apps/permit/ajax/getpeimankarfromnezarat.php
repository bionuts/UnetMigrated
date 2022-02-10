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
$id_nezarat = trim($_POST['nezaratid']);
if (!ctype_digit($id_nezarat)) exit;
include '../lib/new_request_class.php';
$reqobj = new new_request_class();
echo $reqobj->get_peymankars_list_rolebase($roleid,$id_nezarat, $_SESSION['userid']);