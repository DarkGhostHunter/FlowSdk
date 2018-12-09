<?php

include_once '../_master/head.php';

$active = 'cancel';

/** @var \DarkGhostHunter\FlowSdk\Flow $flow */
$flow = FlowInstance::getFlow();

if (isset($_POST['invoiceId'])) {

    $invoice = $flow->invoice()->cancel($_POST['invoiceId']);

    ?>
    <form action="<?php echo currentUrlPath('index.php') ?>" method="get" class="alert alert-success small">
        <input type="hidden" name="invoiceId" value="<?php echo $invoice->invoiceId ?>">
        Invoice <code><?php echo $invoice->id ?></code> has been deleted.
        <div class="text-right">
            <button type="submit" class="btn btn-sm btn-primary">
                See Invoice (Retrieve) &raquo;
            </button>
        </div>
    </form>
    <?php

} elseif (isset($_GET['invoiceId'])) {
    $invoice = $flow->invoice()->get($_GET['invoiceId']);
} else {
    $invoice = $flow->invoice()->make([]);
}
?>

    <a href="<?php echo currentUrlPath('..') ?>" class="btn btn-link">
        &laquo; Go back to Examples
    </a>

    <h1>Invoice</h1>

<?php include_once __DIR__ . '/_common/nav.php' ?>

    <form method="POST" class="card card-body">
        <input type="hidden" name="delete" value="true">
        <div class="form-row align-items-center">

            <?php if ($invoice->status === 1 && $invoice->attemped === 0) { ?>
                <div class="col-12">
                    <div class="alert alert-warning">
                        The invoice <?php echo $invoice->id ?> has been paid, it cannot be cancelled.
                    </div>
                </div>
            <?php } elseif ($invoice->status === 0 && $invoice->attemped === 1) { ?>
                <div class="col">
                    <div class="alert alert-success">
                        The invoice <?php echo $invoice->id ?> has not been paid, and it should be.
                    </div>
                </div>
            <?php } ?>

            <div class="form-group col-md-12">
                <label for="invoiceId">invoiceId</label>
                <input id="invoiceId" type="text" class="form-control" name="invoiceId"
                       placeholder="cus_1v577va23b"
                       value="<?php echo $_GET['invoiceId'] ?? $invoice->invoiceId ?>" required>
                <small class="input-text text-black-50">invoiceId to delete.</small>
            </div>

            <div class="col-12">
                <hr>
            </div>

            <div class="form-group col-md-12 text-right">
                <button class="btn btn-lg btn-danger mb-3">
                    <i class="fas fa-trash"></i> Confirm Invoice Cancel
                </button>
            </div>

        </div>
    </form>

<?php include_once '../_master/footer.php' ?>