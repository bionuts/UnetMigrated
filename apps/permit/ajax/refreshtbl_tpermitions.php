<?php
session_start();
include '../lib/permit-config.php';
include '../../../util/util.php';
include '../lib/permitUtil.php';
include '../lib/showrequest.php';

$util = new UtilClass();
if ($_SESSION['hashuser'] != $util->hashuser($_SESSION["userid"] . $_SESSION["username"] . $_SERVER['REMOTE_ADDR'] . $_SERVER['HTTP_USER_AGENT'])) {
    exit();
}


$putil = new permitUtil();
$roleid = $putil->getUserRoleID($_SESSION['userid']);
$roleid = $roleid[0];
$show_req_obj = new show_request();
switch ($roleid) {
    case 1: // peimankar sakht
    case 2: //peimankar Bahrebardari
        echo $show_req_obj->get_today_permitions_forpeimankar($_SESSION['userid']);
        break;
    case 3: //OCC Signed
    case 4: //OCC Un-Signed
    case 7: //Green Nezartchi  for permits
	case 11:
        echo $show_req_obj->get_today_permitions();
        break;
    case 5: //Nazer Bahrebardari
        echo $show_req_obj->get_today_permitions_for_nazer($_SESSION['userid']);
        break;
    /*case 6: //Nazer sakht
        break;*/
    /*case 8: //Nazer Sakht - Green
        break;*/
    case 9: //Karbare Darkhast Dahande Sakht
        echo $show_req_obj->get_today_permitions_sakhtuser($_SESSION['userid']);
        break;
}