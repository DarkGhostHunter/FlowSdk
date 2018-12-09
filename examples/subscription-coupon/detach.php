<?php

include_once '../_master/head.php';

$active = 'detach';

if (isset($_POST['subscriptionId'])) {

    /** @var \DarkGhostHunter\FlowSdk\Flow $flow */
    $flow = FlowInstance::getFlow();

    $subscription = $flow->subscription()->removeCoupon($_POST['subscriptionId']);
}
?>

<a href="<?php echo currentUrlPath('..') ?>" class="btn btn-link">
    &laquo; Go back to Examples
</a>

<h1>Subscription Coupon</h1>

<?php include_once '_common/nav.php' ?>

<form method="POST" class="card card-body mb-3">
    <div class="form-row align-items-center">

        <div class="form-group col-md">
            <label for="subscriptionId">subscriptionId</label>
            <input id="subscriptionId" type="text" class="form-control" name="subscriptionId"
                   value="<?php echo $_GET['subscriptionId'] ?? null ?>"
                   placeholder="sus_azcyjj9ycd" required>
            <small class="input-text text-black-50">Subscription remove Coupon.</small>
        </div>

        <div class="form-group col-md-auto text-right">
            <button class="btn btn-lg btn-danger">
                <i class="fas fa-times"></i> Detach Coupons
            </button>
        </div>
    </div>
</form>

<?php if (isset($subscription) && $subscription->exists()) { ?>

    <div class="card text-white bg-success">
        <h3 class="card-header">Coupon removed</h3>
        <div class="card-body">
            <pre><?php print_r($subscription->toArray()) ?></pre>
        </div>
    </div>

<?php }

include_once '../_master/footer.php' ?>