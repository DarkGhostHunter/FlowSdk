<?php

include_once '../_master/head.php';

$active = 'update';

/** @var \DarkGhostHunter\FlowSdk\Flow $flow */
$flow = FlowInstance::getFlow();

if (isset($_POST['customerId']) && !isset($_POST['attributes'])) {

    $customer = $flow->customer()->get($_POST['customerId']);

} elseif (isset($_POST['customerId']) && isset($_POST['attributes'])) {

    $customer = $flow->customer()->update(
        $_POST['customerId'],
        $_POST['attributes']
    );

?>
    <form action="delete.php" method="POST" class="alert alert-success small">
        <input type="hidden" name="customerId" value="<?php echo $customer->customerId ?>">
        Customer <code><?php echo $customer->customerId ?></code> has been updated.
        <div class="text-right">
            <button class="btn btn-danger btn-sm">
                <i class="fas fa-trash-alt"></i> Delete customer?
            </button>
        </div>
    </form>
<?php
} else {
    $customer = $flow->customer()->make([]);
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
                <label for="customerId">customerId</label>
                <input id="customerId" type="text" class="form-control" name="customerId"
                       placeholder="cus_1v577va23b"
                       value="<?php echo $customer->customerId ?? null ?>" required>
                <small class="input-text text-black-50">customerId to update.</small>
            </div>

            <div class="col-12">
                <hr>
            </div>

            <div class="form-group col-md-4">
                <label for="name">name</label>
                <input id="name" type="text" class="form-control" name="attributes[name]"
                       value="<?php echo $customer->name ?>" required>
                <small class="input-text text-black-50">Name of the Customer.</small>
            </div>

            <div class="form-group col-md-4">
                <label for="email">email</label>
                <input id="email" type="email" class="form-control" name="attributes[email]"
                       value="<?php echo $customer->email ?>" required>
                <small class="input-text text-black-50"><strong>REAL</strong> email of the customer.</small>
            </div>

            <div class="form-group col-md-4">
                <label for="externalId">externalId</label>
                <input id="externalId" type="text" class="form-control" name="attributes[externalId]"
                       value="<?php echo $customer->externalId ?>" required>
                <small class="input-text text-black-50">Customer ID for your application.</small>
            </div>

            <div class="col-12">
                <hr>
            </div>

            <div class="form-group col-md-12 text-right">
                <button class="btn btn-lg btn-primary mb-3">
                    <i class="fas fa-edit"></i> Update Customer
                </button>
            </div>

        </div>
    </form>

<?php include_once '../_master/footer.php' ?>