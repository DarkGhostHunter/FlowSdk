<?php

include_once '../_master/head.php';

$active = 'attach';

if (isset($_POST['subscriptionId']) && isset($_POST['couponId'])) {

    /** @var \DarkGhostHunter\FlowSdk\Flow $flow */
    $flow = FlowInstance::getFlow();

    $subscription = $flow->subscription()->addCoupon(
        $_POST['subscriptionId'],
        $_POST['couponId']
    );
}
?>

<a href="<?php echo currentUrlPath('..') ?>" class="btn btn-link">
    &laquo; Go back to Examples
</a>

<h1>Subscription Coupon</h1>

<?php include_once '_common/nav.php' ?>

<form method="POST" class="card card-body mb-3">
    <div class="form-row align-items-center">

        <div class="form-group col-md-4">
            <label for="subscriptionId">subscriptionId</label>
            <input id="subscriptionId" type="text" class="form-control" name="subscriptionId"
                   placeholder="sus_azcyjj9ycd" required>
            <small class="input-text text-black-50">Subscription to add the Coupon.</small>
        </div>

        <div class="form-group col-md-4">
            <label for="couponId">couponId</label>
            <input id="couponId" type="tel" class="form-control" name="couponId"
                   placeholder="5797" required>
            <small class="input-text text-black-50">Coupon to attach</small>
        </div>

        <div class="form-group col-md-4 text-right">
            <button class="btn btn-lg btn-primary">
                <i class="fas fa-check"></i> Attach Coupon
            </button>
        </div>
    </div>
</form>

<?php if (isset($subscription) && $subscription->exists()) { ?>

    <div class="card text-white bg-success">
        <h3 class="card-header">Coupon added</h3>
        <div class="card-body">
            <pre><?php print_r($subscription->toArray()) ?></pre>
            <form action="<?php echo currentUrlPath('detach.php')?>" method="get" class="text-right">
                <input type="hidden" name="subscriptionId" value="<?php echo $_POST['subscriptionId'] ?>">
                <button type="submit" class="btn btn-danger btn-sm">
                    <i class="fas fa-trash-alt"></i> Remove Coupon
                </button>
            </form>
        </div>
    </div>

<?php }

include_once '../_master/footer.php' ?>