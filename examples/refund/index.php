<?php

include_once '../_master/head.php';

if ($_POST['refund'] ?? null) {

    /** @var \DarkGhostHunter\FlowSdk\Flow $flow */
    $flow = FlowInstance::getFlow();

    $refund = $flow->refund()->create(
        array_merge($_POST['refund'], [
            $_POST['transactionType'] => $_POST['transactionId']
        ])
    );


    if ($refund->exists()) { ?>

        <div class="alert alert-success small">
            <p>The Refund was sent to <code><?php echo $_POST['refund']['receiverEmail'] ?></code>.</p>
            Once complete, Flow will hit the Webhook with the <code>token</code> identifying the refund.
        </div>

        <div class="mb-3" id="accordion">
            <div class="card">
                <div class="card-header" id="headingTwo">
                    <h5 class="mb-0">
                        <button class="btn btn-link collapsed" type="button" data-toggle="collapse"
                                data-target="#collapse">
                            Transaction Details
                        </button>
                    </h5>
                </div>
                <div id="collapse" class="collapse" data-parent="#accordion">
                    <div class="card-body pb-1">
                        <pre><?php print_r($refund->toArray()) ?></pre>
                    </div>
                </div>
            </div>
        </div>
    <?php }
} ?>

<a href="<?php echo currentUrlPath('..') ?>" class="btn btn-link">
    &laquo; Go back to Examples
</a>

<h1>Refund</h1>
<p>Let's refund a payment:</p>

<div class="alert alert-info small">
    If you didn't made a payment, welp.. <a href="<?php echo currentUrlPath('../payments') ?>">go and do one</a>. We will need its <code>commerceOrder</code> or <code>flowOrder</code>.

</div>

<form method="POST" class="card card-body">
    <div class="form-row">

        <div class="form-group col-md-6">
            <label for="refundCommerceOrder">refundCommerceOrder</label>
            <input id="refundCommerceOrder" type="text" class="form-control" name="refund[refundCommerceOrder]"
                   value="<?php echo 'commerceRefund-' . bin2hex(random_bytes(4)) ?>" required>
            <small class="input-text text-black-50">The name of this refund order.</small>
        </div>

        <div class="form-group col-md-6">
            <label for="receiverEmail">receiverEmail</label>
            <input id="receiverEmail" type="email" class="form-control" name="refund[receiverEmail]"
                   placeholder="real@email.com" required>
            <small class="input-text text-black-50">The email who will will receive the refund.</small>
        </div>

        <div class="col-12">
            <hr>
        </div>

        <div class="form-group col-md-6">
            <label for="amount">amount</label>
            <div class="input-group">
                <div class="input-group-prepend">
                    <span class="input-group-text" id="basic-addon1">$</span>
                </div>
                <input id="amount" type="number" class="form-control" name="refund[amount]"
                       placeholder="<?php echo rand(1000, 100000)?>" required>
            </div>
            <small class="input-text text-black-50">Amount to be paid.</small>
        </div>

        <div class="col-12">
            <hr>
        </div>

        <div class="form-group col-md-12">
            <label for="urlCallBack">urlCallBack</label>
            <input id="urlCallBack" type="url" class="form-control" name="refund[urlCallBack]"
                   value="<?php echo currentUrlPath('webhook.php') ?>">
            <small class="input-text text-black-50">Webhook for Flow. Must be publicly reachable through Internet.</small>
        </div>

        <div class="col-12">
            <hr>
        </div>

        <div class="form-group col-md-6">
            <label for="transactionType">Transaction ID Type</label>
            <select id="transactionType" class="custom-select" name="transactionType">
                <option value="commerceTrxId" selected>commerceTrxId (commerceOrder)</option>
                <option value="flowTrxId">flowTrxId (flowOrder)</option>
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
                <i class="fas fa-check"></i> Refund
            </button>
            <div class="small text-black-50">An email to the receiver will be dispatched to complete the refund.</div>
        </div>

    </div>
</form>

<?php include_once '../_master/footer.php' ?>