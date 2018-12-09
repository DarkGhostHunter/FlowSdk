<?php

include_once '../_master/head.php';

$active = 'create';

if (isset($_POST['attributes'])) {

    /** @var \DarkGhostHunter\FlowSdk\Flow $flow */
    $flow = FlowInstance::getFlow();

    $charge = $flow->customer()->createCharge($_POST['attributes']);

}

?>

<a href="<?php echo currentUrlPath('..') ?>" class="btn btn-link">
    &laquo; Go back to Examples
</a>

<h1>Charge</h1>

<?php include_once '_common/nav.php' ?>

<?php if (isset($charge)) { ?>
    <div class="card mb-3 text-white bg-success">
        <h3 class="card-header">Charge Status</h3>
        <div class="card-body">
            <pre><?php print_r($charge->toArray()) ?></pre>
            <form action="<?php echo currentUrlPath('reverse.php') ?>" method="POST" class="text-right">
                <input type="hidden" name="transactionType" value="commerceOrder">
                <input type="hidden" name="transactionId" value="<?php echo $charge->commerceOrder ?>">
                <button type="submit" class="btn btn-primary btn-sm">
                    <i class="fas fa-redo"></i> Reverse Charge
                </button>
            </form>
        </div>
    </div>
<?php } ?>

<form method="POST" class="card card-body">
    <div class="form-row">

        <div class="form-group col-md-6">
            <label for="customerId">customerId</label>
            <input id="customerId" type="text" class="form-control" name="attributes[customerId]"
                   value="<?php echo $_GET['customerId'] ?? null ?>" required>
            <small class="input-text text-black-50">The Customer to charge. Must have a Credit Card Registered, otherwise it will fallback to an Email Payment.</small>
        </div>

        <div class="col-12">
            <hr>
        </div>

        <div class="form-group col-md-6">
            <label for="commerceOrder">commerceOrder (for the charge)</label>
            <input id="commerceOrder" type="text" class="form-control" name="attributes[commerceOrder]"
                   value="<?php echo 'commerceCharge#' . bin2hex(random_bytes(4)) ?>" required>
            <small class="input-text text-black-50">We are making a random order string.</small>
        </div>

        <div class="form-group col-md-6">
            <label for="subject">subject</label>
            <input id="subject" type="text" class="form-control" name="attributes[subject]"
                   value="Game Console Nº<?php echo rand(1, 9999) ?>" required>
            <small class="input-text text-black-50">The Order description.</small>
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
            <small class="input-text text-black-50">Currency for the amount. Can be in UF.</small>
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
            <small class="input-text text-black-50">Amount to be paid.</small>
        </div>

        <div class="col-12">
            <hr>
        </div>

        <div class="form-group col-md-12">
            <label for="optional">optional (message)</label>
            <input id="optional" type="text" class="form-control" name="attributes[optional][message]"
                   value="This is an example message for the payment.">
            <small class="input-text text-black-50">Will append this string as JSON, and show it as part of the Flow receipt.</small>
        </div>

        <div class="col-12">
            <hr>
        </div>

        <div class="form-group col-md-12 text-right">
            <button class="btn btn-lg btn-primary mb-3">
                <i class="fas fa-check"></i> Charge
            </button>
            <div class="small text-black-50">You will be redirected to Flow once the payment is created.</div>
        </div>

    </div>
</form>

<?php include_once '../_master/footer.php' ?>