<?php

include_once '../_master/head.php';

$active = 'update';

/** @var \DarkGhostHunter\FlowSdk\Flow $flow */
$flow = FlowInstance::getFlow();

if (isset($_GET['planId']) && !isset($_POST['attributes'])) {

    $plan = $flow->plan()->get($_GET['planId']);

} elseif (isset($_POST['planId']) && isset($_POST['attributes'])) {

    $plan = $flow->plan()->update(
        $_POST['planId'],
        $_POST['attributes']
    );

?>
    <form action="delete.php" method="POST" class="alert alert-success small">
        <input type="hidden" name="customerId" value="<?php echo $plan->planId ?>">
        Customer <code><?php echo $plan->planId ?></code> has been updated.
        <div class="text-right">
            <button class="btn btn-danger btn-sm">
                <i class="fas fa-trash-alt"></i> Delete customer?
            </button>
        </div>
    </form>
<?php
} else {
    $plan = $flow->plan()->make([]);
}
?>

<a href="<?php echo currentUrlPath('..') ?>" class="btn btn-link">
    &laquo; Go back to Examples
</a>

<h1>Customer</h1>

<?php include_once __DIR__ . '/_common/nav.php' ?>

    <form method="POST" class="card card-body">
        <div class="form-row">

            <div class="form-group col-md-6">
                <label for="planId">planId</label>
                <input id="planId" type="text" class="form-control" name="planId"
                       placeholder="planId-0fb12747"
                       value="<?php echo $_GET['planId'] ?? null ?>" required>
                <small class="input-text text-black-50">planId to update.</small>
            </div>

            <div class="col-12">
                <hr>
            </div>

            <div class="form-group col-md-6">
                <label for="name">name</label>
                <input id="name" type="text" class="form-control" name="attributes[name]"
                       value="<?php echo $plan->name ?>"
                       placeholder="My Super Plan" required>
                <small class="input-text text-black-50">Name of the Plan.</small>
            </div>


            <div class="col-12">
                <hr>
            </div>

            <div class="form-group col-md-6">
                <label for="currency">currency</label>
                <select id="currency" class="custom-select" name="attributes[currency]">
                    <option value="CLP" <?php echo $plan->currency === 'CLP' ? 'selected' : '' ?>>CLP</option>
                    <option value="UF" <?php echo $plan->currency === 'UF' ? 'selected' : '' ?>>UF</option>
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
                           value="<?php echo $plan->amount ?>" required>
                </div>
                <small class="input-text text-black-50">Amount to be paid for the Plan.</small>
            </div>

            <div class="col-12">
                <hr>
            </div>

            <div class="form-group col-md-6">
                <label for="interval">interval</label>
                <select id="interval" name="attributes[interval]" class="custom-select">
                    <option value="1" <?php echo $plan->value === 1 ? 'selected' : '' ?>>1 (daily)</option>
                    <option value="2" <?php echo $plan->value === 2 ? 'selected' : '' ?>>2 (weekly)</option>
                    <option value="3" <?php echo $plan->value === 3 ? 'selected' : '' ?>>3 (monthly)</option>
                    <option value="4" <?php echo $plan->value === 4 ? 'selected' : '' ?>>4 (yearly)</option>
                </select>
                <small class="input-text text-black-50">Cycle interval of the Plan</small>
            </div>

            <div class="form-group col-md-6">
                <label for="interval_count">interval_count</label>
                <select id="interval_count" name="attributes[interval_count]" class="custom-select">
                    <option value="1" <?php echo $plan->value === 1 ? 'selected' : '' ?>>1 (every cycle)</option>
                    <option value="2" <?php echo $plan->value === 2 ? 'selected' : '' ?>>2 (every 2 cycles)</option>
                    <option value="3" <?php echo $plan->value === 3 ? 'selected' : '' ?>>3 (every 3 cycles)</option>
                    <option value="4" <?php echo $plan->value === 4 ? 'selected' : '' ?>>4 (every 4 cycles)</option>
                </select>
                <small class="input-text text-black-50">Cycle frequency to be paid.</small>
            </div>

            <div class="col-12">
                <hr>
            </div>

            <div class="form-group col-md-6">
                <label for="trial_period_days">trial_period_days</label>
                <input id="trial_period_days" class="form-control" type="number" name="attributes[trial_period_days]"
                       value="<?php echo $plan->trial_period_days ?>">
                <small class="input-text text-black-50">Trial days for the Plan</small>
            </div>

            <div class="form-group col-md-6">
                <label for="days_until_due">days_until_due</label>
                <input id="days_until_due" class="form-control" type="number" name="attributes[days_until_due]"
                       value="<?php echo $plan->days_until_due ?>">
                <small class="input-text text-black-50">Days after the last cycle day to consider it unpaid.</small>
            </div>

            <div class="col-12">
                <hr>
            </div>


            <div class="form-group col-md-12">
                <label for="urlCallback">urlCallback</label>
                <input id="urlCallback" type="url" class="form-control" name="attributes[urlCallback]"
                       value="<?php echo $plan->urlCallback ?>">
                <small class="input-text text-black-50">Webhook for Flow. Must be publicly reachable through Internet.</small>
            </div>

            <div class="col-12">
                <hr>
            </div>

            <div class="form-group col-md-12 text-right">
                <button class="btn btn-lg btn-primary mb-3">
                    <i class="fas fa-check"></i> Update Plan
                </button>
            </div>

        </div>
    </form>

<?php include_once '../_master/footer.php' ?>