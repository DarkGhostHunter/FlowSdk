<?php

include_once '../_master/head.php';

$active = 'create';


if (isset($_POST['attributes'])) {

    /** @var \DarkGhostHunter\FlowSdk\Flow $flow */
    $flow = FlowInstance::getFlow();

    $coupon = $flow->coupon()->create($_POST['attributes']);

    if ($coupon->exists()) { ?>

        <div class="alert alert-success small">
            <p>Coupon Created with id <code><?php echo $coupon->id ?></code>.</p>
            No we can proceed to retrieve it:
            <div class="text-right">
                <a href="<?php echo currentUrlPath('retrieve.php?couponId=' . $coupon->id )?>"
                   class="btn btn-primary">
                    Go to retrieve &raquo;
                </a>
            </div>
        </div>

<?php } else { print_r($coupon); }

}
?>

<a href="<?php echo currentUrlPath('..') ?>" class="btn btn-link">
    &laquo; Go back to Examples
</a>

<h1>Coupon</h1>

<?php include_once __DIR__ . '/_common/nav.php'; ?>

<form method="POST" class="card card-body">
    <div class="form-row">

        <div class="form-group col-md-6">
            <label for="name">name</label>
            <input id="name" type="text" class="form-control" name="attributes[name]"
                   placeholder="My Super Coupon" required>
            <small class="input-text text-black-50">Name of the Coupon.</small>
        </div>

        <div class="col-12">
            <hr>
        </div>


        <div class="form-group col-md-4">
            <label for="percent_off">percent_off</label>
            <div class="input-group">
                <input id="percent_off" type="number" max="100" min="0" class="form-control" name="attributes[percent_off]"
                       value="<?php echo rand(0, 100) ?? null ?>">
                <div class="input-group-append">
                    <span class="input-group-text" id="basic-addon1">%</span>
                </div>
            </div>
            <small class="input-text text-black-50">Percent Off the plan/subscription amount. <span class="text-warning">Leave empty if using amount</span>.</small>
        </div>

        <div class="form-group col-md-4">
            <label for="currency">currency</label>
            <select id="currency" class="custom-select" name="attributes[currency]">
                <option value="" selected></option>
                <option value="CLP">CLP</option>
                <option value="UF">UF</option>
            </select>
            <small class="input-text text-black-50">Currency for the amount. <span class="text-warning">Leave empty if using percent</span></small>
        </div>

        <div class="form-group col-md-4">
            <label for="amount">amount</label>
            <div class="input-group">
                <div class="input-group-prepend">
                    <span class="input-group-text" id="basic-addon1">$</span>
                </div>
                <input id="amount" type="number" class="form-control" name="attributes[amount]"
                       value="" >
            </div>
            <small class="input-text text-black-50">Amount to be paid for the Coupon. <span class="text-warning">Leave empty if using percent</span> </small>
        </div>

        <div class="col-12">
            <hr>
        </div>

        <div class="form-group col-md-6">
            <label for="duration">duration</label>
            <select id="duration" name="attributes[duration]" class="custom-select">
                <option value="1">1 (finite)</option>
                <option value="0">0 (infinite)</option>
            </select>
            <small class="input-text text-black-50">Coupon duration</small>
        </div>

        <div class="form-group col-md-6">
            <label for="times">times</label>
            <input id="times" class="form-control" type="number" name="attributes[times]"
                   value="<?php echo rand(0,20) ?>">
            <small class="input-text text-black-50">If the duration is finite, times to use the coupon.</small>
        </div>

        <div class="form-group col-md-6">
            <label for="max_redemptions">max_redemptions</label>
            <input id="max_redemptions" class="form-control" type="number" name="attributes[max_redemptions]"
                   value="<?php echo rand(0,20) ?>">
            <small class="input-text text-black-50">Max times to apply the Coupon.</small>
        </div>

        <div class="form-group col-md-6">
            <label for="expires">expires</label>
            <input id="expires" class="form-control" type="date" min="<?php echo $date = date('Y-m-d', strtotime('tomorrow')) ?>" name="attributes[expires]"
                   value="<?php echo $date ?>">
            <small class="input-text text-black-50">When the Coupon will expire.</small>
        </div>

        <div class="col-12">
            <hr>
        </div>

        <div class="form-group col-md-12 text-right">
            <button class="btn btn-lg btn-primary mb-3">
                <i class="fas fa-check"></i> Create Coupon
            </button>
        </div>

    </div>
</form>

<?php include_once '../_master/footer.php' ?>