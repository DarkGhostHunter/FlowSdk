<?php

include_once '../_master/head.php';

$active = 'delete';


/** @var \DarkGhostHunter\FlowSdk\Flow $flow */
$flow = FlowInstance::getFlow();

if (isset($_POST['subscriptionId']) && isset($_POST['delete']) && (bool)$_POST['delete'] === true) {

    $subscription = $flow->subscription()->cancel($_POST['subscriptionId'], (bool)$_POST['at_period_end']);

?>
    <form action="<?php echo currentUrlPath('retrieve.php') ?>" method="get" class="alert alert-success small">
        <input type="hidden" name="subscriptionId" value="<?php echo $subscription->subscriptionId ?>">
        Subscription <code><?php echo $subscription->subscriptionId ?></code> has been deleted.
        <div class="text-right">
            <button type="submit" class="btn btn-sm btn-primary">
                See Subscription (Retrieve) &raquo;
            </button>
        </div>
    </form>
<?php

} elseif (isset($_GET['subscriptionId'])) {
    $subscription = $flow->subscription()->get($_GET['subscriptionId']);
} else {
    $subscription = $flow->subscription()->make([]);
}
?>

<a href="<?php echo currentUrlPath('..') ?>" class="btn btn-link">
    &laquo; Go back to Examples
</a>

<h1>Subscription</h1>

<?php include_once __DIR__ . '/_common/nav.php' ?>

    <form method="POST" class="card card-body">
        <input type="hidden" name="delete" value="true">
        <div class="form-row align-items-center">

            <div class="form-group col-md-6">
                <label for="subscriptionId">subscriptionId</label>
                <input id="subscriptionId" type="text" class="form-control" name="subscriptionId"
                       placeholder="cus_1v577va23b"
                       value="<?php echo $subscription->subscriptionId ?>" required>
                <small class="input-text text-black-50">subscriptionId to delete.</small>
            </div>

            <div class="form-group col-md-6">
                <div class="custom-control custom-checkbox">
                    <input type="checkbox" class="custom-control-input" id="at_period_end" name="at_period_end"
                    <?php echo $subscription->cancel_at_period_end ? 'checked' : '' ?>>
                    <label class="custom-control-label" for="at_period_end">Cancel at Period End</label>
                </div>
            </div>

            <div class="col-12">
                <hr>
            </div>

            <div class="form-group col-md-12 text-right">
                <button class="btn btn-lg btn-danger mb-3">
                    <i class="fas fa-trash"></i> Confirm Subscription Delete
                </button>
            </div>

        </div>
    </form>

<?php include_once '../_master/footer.php' ?>