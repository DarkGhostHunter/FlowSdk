<?php

include_once '../_master/head.php';

$active = 'create';

if ($_POST['attributes'] ?? null) {

    /** @var \DarkGhostHunter\FlowSdk\Flow $flow */
    $flow = FlowInstance::getFlow();

    $plan = $flow->plan()->create($_POST['attributes']);

    if ($plan->exists()) { ?>

        <div class="alert alert-success small">
            <p>Plan Created with id <code><?php echo $plan->planId ?></code>.</p>
            No we can proceed to retrieve it:
            <div class="text-right">
                <a href="<?php echo currentUrlPath('retrieve.php?planId=' . $plan->planId )?>"
                   class="btn btn-primary">
                    Go to retrieve &raquo;
                </a>
            </div>
        </div>

<?php } else { print_r($plan); }

}
?>

<a href="<?php echo currentUrlPath('..') ?>" class="btn btn-link">
    &laquo; Go back to Examples
</a>

<h1>Plan</h1>

<?php include_once __DIR__ . '/_common/nav.php'; ?>

<form method="POST" class="card card-body">
    <div class="form-row">

        <div class="form-group col-md-6">
            <label for="name">name</label>
            <input id="name" type="text" class="form-control" name="attributes[name]"
                   placeholder="My Super Plan" required>
            <small class="input-text text-black-50">Name of the Plan.</small>
        </div>

        <div class="form-group col-md-6">
            <label for="planId">planId</label>
            <input id="planId" type="tel" class="form-control" name="attributes[planId]"
                   value="<?php echo 'planId-' . bin2hex(random_bytes(4)) ?>" required>
            <small class="input-text text-black-50">Id of the Plan</small>
        </div>

        <div class="col-12">
            <hr>
        </div>

        <div class="form-group col-md-6">
            <label for="currency">currency</label>
            <select id="currency" class="custom-select" name="attributes[currency]">
                <option value="CLP" selected>CLP</option>
                <option value="UF">UF</option>
            </select>
            <small class="input-text text-black-50">Currency for the amount.</small>
        </div>

        <div class="form-group col-md-6">
            <label for="amount">amount</label>
            <div class="input-group">
                <div class="input-group-prepend">
                    <span class="input-group-text" id="basic-addon1">$</span>
                </div>
                <input id="amount" type="number" class="form-control" name="attributes[amount]"
                       value="<?php echo rand(1000, 100000)?>" required>
            </div>
            <small class="input-text text-black-50">Amount to be paid for the Plan.</small>
        </div>

        <div class="col-12">
            <hr>
        </div>

        <div class="form-group col-md-6">
            <label for="interval">interval</label>
            <select id="interval" name="attributes[interval]" class="custom-select">
                <option value="1">1 (daily)</option>
                <option value="2">2 (weekly)</option>
                <option value="3">3 (monthly)</option>
                <option value="4">4 (yearly)</option>
            </select>
            <small class="input-text text-black-50">Cycle interval of the Plan</small>
        </div>

        <div class="form-group col-md-6">
            <label for="interval_count">interval_count</label>
            <select id="interval_count" name="attributes[interval_count]" class="custom-select">
                <option value="1">1 (every cycle)</option>
                <option value="2">2 (every 2 cycles)</option>
                <option value="3">3 (every 3 cycles)</option>
                <option value="4">4 (every 4 cycles)</option>
            </select>
            <small class="input-text text-black-50">Cycle frequency to be paid.</small>
        </div>

        <div class="col-12">
            <hr>
        </div>

        <div class="form-group col-md-6">
            <label for="trial_period_days">trial_period_days</label>
            <input id="trial_period_days" class="form-control" type="number" name="attributes[trial_period_days]"
                   value="<?php echo rand(0,20) ?>">
            <small class="input-text text-black-50">Trial days for the Plan</small>
        </div>

        <div class="form-group col-md-6">
            <label for="days_until_due">days_until_due</label>
            <input id="days_until_due" class="form-control" type="number" name="attributes[days_until_due]"
                   value="<?php echo rand(1,3) ?>">
            <small class="input-text text-black-50">Days after the last cycle day to consider it unpaid.</small>
        </div>

        <div class="col-12">
            <hr>
        </div>

        <div class="form-group col-md-12">
            <label for="urlCallback">urlCallback</label>
            <input id="urlCallback" type="url" class="form-control" name="attributes[urlCallback]"
                   value="<?php echo currentUrlPath('webhook.php') ?>">
            <small class="input-text text-black-50">Webhook for Flow. Must be publicly reachable through Internet.</small>
        </div>

        <div class="col-12">
            <hr>
        </div>

        <div class="form-group col-md-12 text-right">
            <button class="btn btn-lg btn-primary mb-3">
                <i class="fas fa-check"></i> Create Plan
            </button>
        </div>

    </div>
</form>

<?php include_once '../_master/footer.php' ?>