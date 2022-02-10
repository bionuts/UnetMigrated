<?php
session_start();
include '../lib/optplan_ajax_handler.php';
$optplan = new OPTPlanAjaxManager();
$isoccman = $optplan->canedit($_SESSION['userid']);

$jsondata = array(
	'commontxt' => $optplan->get_hint_txt(),
	'today_note' => $optplan->get_note_for_today(),
	'today_opt' => $optplan->get_opt_for_today(),
	'tomorrow_note' => $optplan->get_note_for_tomorrow(),
	'tomorrow_opt' => $optplan->get_opt_for_tomorrow()
);
echo json_encode($jsondata);
