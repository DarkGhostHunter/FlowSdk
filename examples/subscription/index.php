<?php

include_once '../_master/head.php';

$active = 'create';

if ($_POST['subscription'] ?? null) {

    /** @var \DarkGhostHunter\FlowSdk\Flow $flow */
    $flow = FlowInstance::getFlow();

    $subscription = $flow->subscription()->create($_POST['subscription']);

?>

<div class="alert alert-success small">
    <p>Subscription Created with id <code><?php echo $subscription->subscriptionId ?></code>.</p>
    No we can proceed to retrieve it:
    <div class="text-right">
        <a href="<?php echo currentUrlPath('retrieve.php?subscriptionId=' . $subscription->subscriptionId)?>"
           class="btn btn-primary">
            Go to retrieve &raquo;
        </a>
    </div>
</div>

<?php } ?>

<a href="<?php echo currentUrlPath('..') ?>" class="btn btn-link">
    &laquo; Go back to Examples
</a>

<h1>Subscription</h1>

<?php include_once __DIR__ . '/_common/nav.php'; ?>

<form method="POST" class="card card-body">
    <div class="form-row">

        <div class="form-group col-md-6">
            <label for="planId">planId</label>
            <input id="planId" type="text" class="form-control" name="subscription[planId]"
                   placeholder="planid-954wd2" required>
            <small class="input-text text-black-50">Plan to Subscribe.</small>
        </div>

        <div class="form-group col-md-6">
            <label for="customerId">customerId</label>
            <input id="customerId" type="text" class="form-control" name="subscription[customerId]"
                   placeholder="cus_julcghzhbp" required>
            <small class="input-text text-black-50">Customer to Subscribe.</small>
        </div>

        <div class="col-12">
            <hr>
        </div>

        <div class="form-group col-md-4">
            <label for="subscription_start">subscription_start</label>
            <input id="subscription_start" type="date" min="<?php echo $date = date('Y-m-d', strtotime('tomorrow')) ?>" class="form-control" name="subscription[subscription_start]"
                   value="<?php echo $date ?>">
            <small class="input-text text-black-50">When will the subscription start.</small>
        </div>

        <div class="form-group col-md-4">
            <label for="couponId">couponId</label>
            <input id="couponId" type="number" class="form-control" name="subscription[couponId]"
                   placeholder="879543">
            <small class="input-text text-black-50">Coupon Id to add to the Subscription.</small>
        </div>

        <div class="form-group col-md-4">
            <label for="trial_period_days">trial_period_days</label>
            <input id="trial_period_days" type="number" class="form-control" name="subscription[trial_period_days]"
                   placeholder="3">
            <small class="input-text text-black-50">Days for trial period. Overrides the Plan Id Trial days.</small>
        </div>

        <div class="col-12">
            <hr>
        </div>

        <div class="form-group col-md-12 text-right">
            <button class="btn btn-lg btn-primary mb-3">
                <i class="fas fa-check"></i> Create Subscription
            </button>
        </div>

    </div>
</form>

<?php include_once '../_master/footer.php' ?>