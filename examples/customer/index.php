<?php

include_once '../_master/head.php';

$active = 'create';

if ($_POST['customer'] ?? null) {

    /** @var \DarkGhostHunter\FlowSdk\Flow $flow */
    $flow = FlowInstance::getFlow();

    $customer = $flow->customer()->create($_POST['customer']);

    if ($customer->exists()) { ?>

        <div class="alert alert-success small">
            <p>Customer Created with id <code><?php echo $customer->customerId ?></code>.</p>
            No we can proceed to retrieve it:
            <div class="text-right">
                <a href="<?php echo currentUrlPath('retrieve.php?customerId=' . $customer->customerId)?>"
                   class="btn btn-primary">
                    Go to retrieve &raquo;
                </a>
            </div>
        </div>

<?php } else { print_r($customer); }

}
?>

<a href="<?php echo currentUrlPath('..') ?>" class="btn btn-link">
    &laquo; Go back to Examples
</a>

<h1>Customer</h1>

<?php include_once __DIR__ . '/_common/nav.php'; ?>

<form method="POST" class="card card-body">
    <div class="form-row">

        <div class="form-group col-md-4">
            <label for="name">name</label>
            <input id="name" type="text" class="form-control" name="customer[name]"
                   placeholder="John Doe" required>
            <small class="input-text text-black-50">Name of the Customer.</small>
        </div>

        <div class="form-group col-md-4">
            <label for="email">email</label>
            <input id="email" type="email" class="form-control" name="customer[email]"
                   placeholder="real@email.com" required>
            <small class="input-text text-black-50"><strong>REAL</strong> email of the customer.</small>
        </div>

        <div class="form-group col-md-4">
            <label for="externalId">externalId</label>
            <input id="externalId" type="text" class="form-control" name="customer[externalId]"
                   value="<?php echo 'customerId-' . bin2hex(random_bytes(4)) ?>" required>
            <small class="input-text text-black-50">Customer ID for your application.</small>
        </div>

        <div class="col-12">
            <hr>
        </div>

        <div class="form-group col-md-12 text-right">
            <button class="btn btn-lg btn-primary mb-3">
                <i class="fas fa-check"></i> Create Customer
            </button>
        </div>

    </div>
</form>

<?php include_once '../_master/footer.php' ?>