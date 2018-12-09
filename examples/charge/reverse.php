<?php

include_once '../_master/head.php';

$active = 'reverse';

if (isset($_POST['transactionType']) && isset($_POST['transactionId'])) {

    /** @var \DarkGhostHunter\FlowSdk\Flow $flow */
    $flow = FlowInstance::getFlow();

    $response = $flow->customer()->reverseCharge(
        $_POST['transactionType'],
        $_POST['transactionId']
    );
?>
    <div class="card">
        <h3 class="card-header">Reverse Response</h3>
        <div class="card-body">
            <pre><?php print_r($response->toArray()) ?></pre>
        </div>
    </div>
<?php } ?>

<a href="<?php echo currentUrlPath('..') ?>" class="btn btn-link">
    &laquo; Go back to Examples
</a>
<h1>Charge</h1>

<?php include_once '_common/nav.php' ?>

    <form method="POST" class="card card-body">
        <div class="form-row">

            <div class="form-group col-md-6">
                <label for="transactionType">Transaction ID Type</label>
                <select id="transactionType" class="custom-select" name="transactionType">
                    <option value="commerceOrder" selected>commerceOrder</option>
                    <option value="flowOrder">flowOrder</option>
                </select>
                <small class="input-text text-black-50">The ID type of the transaction to refund.</small>
            </div>

            <div class="form-group col-md-6">
                <label for="transactionId">Transaction ID</label>
                <input id="transactionId" type="text" class="form-control" placeholder="Transaction ID"
                       name="transactionId" required>
                <small class="input-text text-black-50">Transaction identifier to refund</small>
            </div>

            <div class="col-12">
                <hr>
            </div>

            <div class="form-group col-md-12 text-right">
                <button class="btn btn-lg btn-primary mb-3">
                    <i class="fas fa-check"></i> Reverse
                </button>
            </div>

        </div>
    </form>

<?php include_once '../_master/footer.php' ?>