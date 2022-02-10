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
        echo $show_req_obj->get_today_request_peimankar($_SESSION['userid']);
        break;
    case 3: //OCC Signed
        echo $show_req_obj->get_today_request_signed_occ($_SESSION['userid']);
        break;
    case 4: //OCC Un-Signed
        echo $show_req_obj->get_today_request_unsigned_occ($_SESSION['userid']);
        break;
    case 5: //Nazer Bahrebardari
        echo $show_req_obj->get_today_request_nazer($_SESSION['userid']);
        break;
    /*case 6: //Nazer sakht
        break;*/
    case 7: //Green Nezartchi  for permits
        echo $show_req_obj->get_today_request_greenuser($_SESSION['userid']);
        break;
	case 11: //Green Nezartchi  for permits
        echo $show_req_obj->get_today_request_omooristgah_signeduser($_SESSION['userid']);
        break;
    /*case 8: //Nazer Sakht - Green
        break;*/
    case 9: //Karbare Darkhast Dahande Sakht
        echo $show_req_obj->get_today_request_karbare_sakhat($_SESSION['userid']);
        break;
}
?>