<?php

include_once '../_master/head.php';

$active = 'delete';

/** @var \DarkGhostHunter\FlowSdk\Flow $flow */
$flow = FlowInstance::getFlow();

if (isset($_POST['customerId']) && isset($_POST['delete']) && (bool)$_POST['delete'] === true) {

    $customer = $flow->customer()->delete($_POST['customerId']);

?>
    <form action="retrieve.php" method="post" class="alert alert-success small">
        <input type="hidden" name="customerId" value="<?php echo $customer->customerId ?>">
        Customer <code><?php echo $customer->customerId ?></code> has been deleted.
        <div class="text-right">
            <button type="submit" class="btn btn-sm btn-primary">
                See Customer (Retrieve) &raquo;
            </button>
        </div>
    </form>
<?php

} elseif (isset($_POST['customerId'])) {
    $customer = $flow->customer()->get($_POST['customerId']);
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
        <input type="hidden" name="delete" value="true">
        <div class="form-row align-items-center">

            <div class="form-group col-md-6">
                <label for="customerId">customerId</label>
                <input id="customerId" type="text" class="form-control" name="customerId"
                       placeholder="cus_1v577va23b"
                       value="<?php echo $customer->customerId ?>" required>
                <small class="input-text text-black-50">customerId to delete.</small>
            </div>

            <div class="col-12">
                <hr>
            </div>

            <div class="form-group col-md-12 text-right">
                <button class="btn btn-lg btn-danger mb-3">
                    <i class="fas fa-trash"></i> Confirm Customer Delete
                </button>
            </div>

        </div>
    </form>

<?php include_once '../_master/footer.php' ?>