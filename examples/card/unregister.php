<?php

include_once '../_master/head.php';

if ($_POST['customerId'] ?? null) {

    /** @var \DarkGhostHunter\FlowSdk\Flow $flow */
    $flow = FlowInstance::getFlow();

    $customer = $flow->customer()->unregisterCard($_POST['customerId']);

}
?>

<a href="<?php echo currentUrlPath('..') ?>" class="btn btn-link">
    &laquo; Go back to Examples
</a>

<h1>Credit Card</h1>

<div class="card">
    <h2 class="card-header">
        Card Unregistration Status
    </h2>
    <pre class="card-body"><?php print_r($customer->toArray()) ?></pre>
</div>

<?php include_once '../_master/footer.php' ?>