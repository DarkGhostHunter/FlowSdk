<?php

include_once '../_master/head.php';

if ($_POST['payment'] ?? null) {

    /** @var \DarkGhostHunter\FlowSdk\Flow $flow */
    $flow = FlowInstance::getFlow();

    // Null the forwarding if it's zero
    if ((int)$_POST['payment']['forward_days_after'] === 0) {
        unset($_POST['payment']['forward_days_after']);
    }
    if ((int)$_POST['payment']['forward_times'] === 0) {
        unset($_POST['payment']['forward_times']);
    }

    $response = $flow->payment()->commitByEmail($_POST['payment']);

    header('Location: '. $response->getUrl());

}

?>

<a href="//<?php echo "{$_SERVER['HTTP_HOST']}{$_SERVER['REQUEST_URI']}" ?>/.." class="btn btn-link">
    &laquo; Go back to Examples
</a>

<h1>Payment</h1>
<p>Let's create a payment through Email and commit it:</p>

<form method="POST" class="card card-body">
    <div class="form-row">

        <div class="form-group col-md-6">
            <label for="commerceOrder">commerceOrder</label>
            <input id="commerceOrder" type="text" class="form-control" name="payment[commerceOrder]"
                   value="<?php echo 'commerceOrder-' . bin2hex(random_bytes(4)) ?>" required>
            <small class="input-text text-black-50">We are making a random order string.</small>
        </div>

        <div class="form-group col-md-6">
            <label for="subject">subject</label>
            <input id="subject" type="text" class="form-control" name="payment[subject]"
                   value="Game Console Nº<?php echo rand(1, 9999) ?>" required>
            <small class="input-text text-black-50">The Order description.</small>
        </div>

        <div class="col-12">
            <hr>
        </div>

        <div class="form-group col-md-6">
            <label for="currency">currency</label>
            <select id="currency" class="custom-select" name="payment[currency]">
                <option value="CLP" selected>CLP</option>
                <option value="USD">USD</option>
            </select>
            <small class="input-text text-black-50">Currency for the amount.</small>
        </div>

        <div class="form-group col-md-6">
            <label for="amount">amount</label>
            <div class="input-group">
                <div class="input-group-prepend">
                    <span class="input-group-text" id="basic-addon1">$</span>
                </div>
                <input id="amount" type="number" class="form-control" name="payment[amount]"
                       value="<?php echo rand(1000, 100000)?>" required>
            </div>
            <small class="input-text text-black-50">Amount to be paid.</small>
        </div>

        <div class="col-12">
            <hr>
        </div>

        <div class="form-group col-md-12">
            <label for="email">email</label>
            <input id="email" type="text" class="form-control" placeholder="real@email.com"
                   name="payment[email]" required>
            <small class="input-text text-black-50"><strong>REAL</strong> payer's email, where Flow will send the receipt.</small>
        </div>

        <div class="col-12">
            <hr>
        </div>

        <div class="form-group col-md-12">
            <label for="urlConfirmation">urlConfirmation</label>
            <input id="urlConfirmation" type="url" class="form-control" name="payment[urlConfirmation]"
                   value="<?php echo currentUrlPath('webhook.php') ?>">
            <small class="input-text text-black-50">Webhook for Flow. Must be publicly reachable through Internet.</small>
        </div>

        <div class="form-group col-md-12">
            <label for="urlReturn">urlReturn</label>
            <input id="urlReturn" type="url" class="form-control" name="payment[urlReturn]"
                   value="<?php echo currentUrlPath('return.php') ?>">
            <small class="input-text text-warning">It's required, but the user won't return to your application. Instead, Flow will ask him to register. Crazy, isn't?</small>
        </div>

        <div class="col-12">
            <hr>
        </div>

        <div class="form-group col-md-6">
            <label for="forward_days_after">forward_days_after</label>
            <input id="forward_days_after" type="number" class="form-control" name="payment[forward_days_after]"
                   value="<?php echo rand(0,30) ?? null ?>">
            <small class="input-text text-black-50">Days to remind the payment after if is not completed.</small>
        </div>

        <div class="form-group col-md-6">
            <label for="forward_times">forward_times</label>
            <input id="forward_times" type="number" class="form-control" name="payment[forward_times]"
                   value="<?php echo rand(0,3) ?>">
            <small class="input-text text-black-50">Times to send the reminder.</small>
        </div>

        <div class="col-12">
            <hr>
        </div>

        <div class="form-group col-md-12">
            <label for="optional">optional (message)</label>
            <input id="optional" type="text" class="form-control" name="payment[optional][message]"
                   value="This is an example message for the payment.">
            <small class="input-text text-black-50">Will append this string as JSON, and show it as part of the Flow receipt.</small>
        </div>

        <div class="col-12">
            <hr>
        </div>

        <div class="form-group col-md-12 text-right">
            <button class="btn btn-lg btn-primary mb-3">
                <i class="fas fa-check"></i> Pay
            </button>
            <div class="small text-black-50">You will be redirected to Flow once the payment is created.</div>
        </div>

    </div>
</form>

<?php include_once '../_master/footer.php' ?>