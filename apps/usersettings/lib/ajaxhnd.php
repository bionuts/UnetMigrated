<?php
//sleep(1);
usleep(600000);
include 'setuser_ajax_handler.php';
$obj = new SetUserAjaxManager();
$obj->ProcReq();