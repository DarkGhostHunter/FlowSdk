<?php

include_once '../_master/head.php';

/** @var \DarkGhostHunter\FlowSdk\Flow $flow */
$flow = FlowInstance::getFlow();

$payment = $flow->payment()->get($_POST['token']);

file_put_contents('webhook.txt', $payment);