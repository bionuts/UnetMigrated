<?php 
	//sleep(1);
	include '../lib/meeting.php';
	$reqobj = new meeting_class();
	$arrvalues = $_POST['arrvalues'];
	echo json_encode($reqobj->insert_new_meeting($arrvalues));
?>