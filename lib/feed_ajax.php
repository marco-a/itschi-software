<?php

header('cache-control: no-cache');
header('pragma: no-cache');

require '../base.php';
include '../includes/feed.php';

if (empty($_POST['limit']))
{
	exit;
}

feed(min(200, (int)$_POST['limit']+5));

$tpl->display('feed.tpl');

?>