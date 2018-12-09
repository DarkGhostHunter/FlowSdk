<?php

include_once '../_master/head.php';

$active = 'retrieve';

if ($_POST['invoiceId'] ?? null) {

    /** @var \DarkGhostHunter\FlowSdk\Flow $flow */
    $flow = FlowInstance::getFlow();

    $invoice = $flow->invoice()->get($_POST['invoiceId']);

}
?>

<a href="<?php echo currentUrlPath('..') ?>" class="btn btn-link">
&laquo; Go back to Examples
</a>

<h1>Invoice</h1>

<?php include_once __DIR__ . '/_common/nav.php' ?>


    <form method="POST" class="card card-body">
        <div class="form-row">

            <div class="form-group col-md-12">
                <label for="invoiceId">invoiceId</label>
                <input id="invoiceId" type="text" class="form-control" placeholder="1034"
                       value="<?php echo $_GET['invoiceId'] ?? null ?>"
                       name="invoiceId" required>
                <small class="input-text text-black-50">invoiceId to retrieve</small>
            </div>

            <div class="col-12">
                <hr>
            </div>

            <div class="form-group col-md-12 text-right">
                <button class="btn btn-lg btn-primary mb-3">
                    <i class="fas fa-check"></i> Retrieve Invoice
                </button>
            </div>
        </div>
    </form>

<?php if (isset($invoice)) { ?>
    <div class="card text-white bg-success mb-3 w-100">
        <h3 class="card-header">Invoice <?php echo $invoice->id ?> retrieved</h3>
        <div class="card-body">
            <?php if (!$invoice->exists()) { ?>
                <div class="alert alert-danger">
                    This invoice has been cancelled
                </div>
            <?php } ?>
            <pre><?php print_r($invoice->toArray()) ?></pre>
            <form action="<?php echo currentUrlPath('cancel.php') ?>" method="get" class="text-right">
                <input type="hidden" name="invoiceId" value="<?php echo $invoice->id ?>">
                <button type="submit" class="btn btn-danger btn-sm">
                    <i class="fas fa-times"></i> Cancel Invoice
                </button>
            </form>
        </div>
    </div>
<?php } ?>

<?php include_once '../_master/footer.php' ?>