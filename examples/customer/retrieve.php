<?php

include_once '../_master/head.php';

$active = 'retrieve';

if ($_GET['customerId'] ?? null) {

    /** @var \DarkGhostHunter\FlowSdk\Flow $flow */
    $flow = FlowInstance::getFlow();

    $customer = $flow->customer()->get($_GET['customerId']);

}
?>

<a href="<?php echo currentUrlPath('..') ?>" class="btn btn-link">
    &laquo; Go back to Examples
</a>

<h1>Customer</h1>

<?php include_once __DIR__ . '/_common/nav.php' ?>


<?php if (isset($customer)) { ?>
    <div class="card text-white bg-success mb-3 w-100">
        <h3 class="card-header">Customer <?php echo $customer->customerId ?> retrieved</h3>
        <div class="card-body">
            <?php if (!$customer->exists()) { ?>
                <div class="alert alert-danger">
                    This customer has been deleted
                </div>
            <?php } ?>
            <pre><?php print_r($customer->toArray()) ?></pre>
            <form action="update.php" method="POST" class="text-right">
                <input type="hidden" name="customerId" value="<?php echo $customer->customerId ?>">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-edit"></i> Update Customer
                </button>
            </form>
        </div>
    </div>
<?php } ?>

<form method="GET" action="<?php echo currentUrlPath('retrieve.php')?>" class="card card-body">
    <div class="form-row">

        <div class="form-group col-md-12">
            <label for="customerId">customerId</label>
            <input id="customerId" type="text" class="form-control" placeholder="cus_1v577va23b"
                   value="<?php echo $_GET['customerId'] ?? null ?>"
                   name="customerId" required>
            <small class="input-text text-black-50">customerId to retrieve</small>
        </div>

        <div class="col-12">
            <hr>
        </div>

        <div class="form-group col-md-12 text-right">
            <button class="btn btn-lg btn-primary mb-3">
                <i class="fas fa-check"></i> Retrieve Customer
            </button>
        </div>
    </div>
</form>

<?php include_once '../_master/footer.php' ?>