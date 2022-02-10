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
$pindex = $_POST['pindex'];
switch ($roleid) {
    case 1: // peimankar sakht
    case 2: //peimankar Bahrebardari
        echo $show_req_obj->get_request_peimankar_bayegani($_SESSION['userid'], $pindex);
        break;
    case 3: //OCC Signed
    case 4: //OCC Un-Signed
        echo $show_req_obj->get_request_occ_bayegani($_SESSION['userid'], $pindex);
        break;
    case 5: //Nazer Bahrebardari
        echo $show_req_obj->get_request_nazer_bayegani($_SESSION['userid'], $pindex);
        break;
    /*case 6: //Nazer sakht
        break;*/
    case 7: //Green Nezartchi  for permits
        echo $show_req_obj->get_today_request_greenuser_bayegani($_SESSION['userid'], $pindex);
        break;
	case 11: //Green Nezartchi  for permits (signed)
        echo $show_req_obj->get_today_request_greenuser_bayegani($_SESSION['userid'], $pindex);
        break;
    /*case 8: //Nazer Sakht - Green
        break;*/
    case 9: //Karbare Darkhast Dahande Sakht
        echo $show_req_obj->get_request_sakhtuser_bayegani($_SESSION['userid'], $pindex);
        break;
}

?>