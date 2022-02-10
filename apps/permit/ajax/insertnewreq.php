<?php
session_start();
include '../lib/permit-config.php';
include '../../../util/util.php';
include '../lib/permitUtil.php';


$util = new UtilClass();
if ($_SESSION['hashuser'] != $util->hashuser($_SESSION["userid"] . $_SESSION["username"] . $_SERVER['REMOTE_ADDR'] . $_SERVER['HTTP_USER_AGENT'])) {
return 'hello';
    exit();
}

$putil = new permitUtil();
$roleid = $putil->getUserRoleID($_SESSION['userid']);
$roleid = $roleid[0];

$deadtimereq = 11;
//peimankar_request_for_permit
switch ($roleid) {
    case 1: // peimankar sakht
    case 2: //peimankar Bahrebardari    
    case 7: //Nezartchi Bahrebardari - Green
	case 11:
    case 8: //Nezartchi Sakht - Green
    case 9: //Karbare Darkhast Dahande Sakht
        $deadtimereq = $putil->getPermitSetting('peimankar_request_for_permit');
    case 3: //OCC Signed
    case 4: //OCC Un-Signed
	case 5: //Nazer Bahrebardari
    case 6: //Nazer sakht
        $deadtimereq = $putil->getPermitSetting('nazer_request_for_permit');
        break;
}

$messege = '0';
// 0: success
// 1: peimankar haghe darkhaste mojavez nadarad
// 2: zamane gereftan mojavez be payan reside hast
// 3: sql error

$arrform = $_POST['dataforms'];
$tcritical = $arrform['non_critical'];

if($tcritical == 'true'){
	include '../lib/new_request_class.php';
    $reqobj = new new_request_class();    
	
	$peim_can_permit = true;
	if ( $roleid == 1 || $roleid == 2 )
	{
		$ptemp = $reqobj->permit_check_peim_can_request_permit($_SESSION['userid']);
		if( $ptemp == 0 )
		{
			$peim_can_permit = false;
		}	
	}
	
    if( $peim_can_permit )
		$messege=$reqobj->insert_new_request($arrform);
	else
		$messege = '1';
}
else if($tcritical == 'false')
{
	date_default_timezone_set("Asia/Tehran");
	$hour = date('H');// - 1;
	if (($hour < $deadtimereq) && $roleid != 1) {
		include '../lib/new_request_class.php';
		$reqobj = new new_request_class();		
		
		$peim_can_permit = true;
		if ( $roleid == 1 || $roleid == 2 )
		{
			$ptemp = $reqobj->permit_check_peim_can_request_permit($_SESSION['userid']);
			if( $ptemp == 0 )
			{
				$peim_can_permit = false;
			}	
		}
		
		if( $peim_can_permit )
			$messege=$reqobj->insert_new_request($arrform);
		else 
			$messege = '1';			
	} 
	else {
		$messege = '2';
	}
}
echo $messege;

?>