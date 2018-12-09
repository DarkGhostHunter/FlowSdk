<?php

include_once '../_master/head.php';

/** @var \DarkGhostHunter\FlowSdk\Flow $flow */
$flow = FlowInstance::getFlow();

$refund = $flow->refund()->get($_POST['token']);

file_put_contents('webhook.txt', $refund);