<?php
session_start();
// include '../lib/permit-config.php';
include_once($_SERVER['DOCUMENT_ROOT'].'/config/main_config.php');
include '../../../util/util.php';
include '../lib/permitUtil.php';
include '../lib/jdf.php';

$util = new UtilClass();
//echo $_SESSION['hashuser'].'<br/>';
//echo $util->hashuser($_SESSION["userid"] . $_SESSION["username"] . $_SERVER['REMOTE_ADDR'] . $_SERVER['HTTP_USER_AGENT']);

if ($_SESSION['hashuser'] != $util->hashuser($_SESSION["userid"] . $_SESSION["username"] . $_SERVER['REMOTE_ADDR'] . $_SERVER['HTTP_USER_AGENT'])) {
	exit();
}

$putil = new permitUtil();
$roleid = $putil->getUserRoleID($_SESSION['userid']);
$roleid = $roleid[0];
if ($roleid != 3 && $roleid != 4) exit;

// check if variable posted

if(!isset($_POST['startdate']) || !isset($_POST['enddate']) || !isset($_POST['repo_id']))
{
	echo 'date required, please enter nice date';
	exit;
}
$start_date       = explode( '/', trim( $_POST['startdate'] ) );
$arr_miladi_start = jalali_to_gregorian( $start_date[0], $start_date[1], $start_date[2] );
$end_date         = explode( '/', trim( $_POST['enddate'] ) );
$arr_miladi_end   = jalali_to_gregorian( $end_date[0], $end_date[1], $end_date[2] );


//$conn = mysqli_connect(PermitConfigClass::$dbserver, PermitConfigClass::$user, PermitConfigClass::$pass, PermitConfigClass::$dbname);
$conn = mysqli_connect(MainConfigClass::$dbserver, MainConfigClass::$user, MainConfigClass::$pass, MainConfigClass::$dbname);
if ($conn->connect_error) {
	return -1;
}
mysqli_set_charset($conn, "utf8");
$rows = null;

if($_POST['repo_id'] == 1)
	$result = mysqli_query($conn, "CALL permit_sp_report_green_permitcount_in_station('$arr_miladi_start[0]-$arr_miladi_start[1]-$arr_miladi_start[2]','$arr_miladi_end[0]-$arr_miladi_end[1]-$arr_miladi_end[2]',1);");
else if($_POST['repo_id'] == 2)
	$result = mysqli_query($conn, "CALL permit_sp_report_green_permitcount_in_scope2('$arr_miladi_start[0]-$arr_miladi_start[1]-$arr_miladi_start[2]','$arr_miladi_end[0]-$arr_miladi_end[1]-$arr_miladi_end[2]',1);");

	
	if (mysqli_num_rows($result) >= 1) {
	while ($row = mysqli_fetch_assoc($result)) {
		$rows[] = $row;
	}
}
mysqli_close($conn);
echo json_encode($rows);


