<?php
//usleep(100000);
include '../../../config/TreeConfigClass.php';
include '../../../lib/Node.php';
include '../../../lib/Tree.php';
$tree = new Tree();
$tree->handleRquest($_POST['func']);
