<?php
//sleep(1);
usleep(600000);
include 'optplan_ajax_handler.php';
$obj = new OPTPlanAjaxManager();
$obj->ProcReq();