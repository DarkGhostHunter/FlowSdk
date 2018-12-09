<?php

include_once '../_master/head.php';

$active = 'update';

/** @var \DarkGhostHunter\FlowSdk\Flow $flow */
$flow = FlowInstance::getFlow();

if (isset($_GET['subscriptionId']) && !isset($_POST['attributes'])) {

    $subscription = $flow->subscription()->get($_GET['subscriptionId']);

} elseif (isset($_POST['subscriptionId']) && isset($_POST['attributes'])) {

    $subscription = $flow->subscription()->update(
        $_POST['subscriptionId'],
        $_POST['attributes']
    );

?>
    <form action="<?php echo currentUrlPath('delete.php') ?>" method="get" class="alert alert-success small">
        <input type="hidden" name="subscriptionId" value="<?php echo $subscription->subscriptionId ?>">
        Subscription <code><?php echo $subscription->subscriptionId ?></code> has been updated.
        <div class="text-right">
            <button class="btn btn-danger btn-sm">
                <i class="fas fa-trash-alt"></i> Delete subscription?
            </button>
        </div>
    </form>
<?php
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
        <div class="form-row">

            <div class="form-group col-md-6">
                <label for="subscriptionId">subscriptionId</label>
                <input id="subscriptionId" type="text" class="form-control" name="subscriptionId"
                       placeholder="cus_1v577va23b"
                       value="<?php echo $subscription->subscriptionId ?>" required>
                <small class="input-text text-black-50">subscriptionId to update.</small>
            </div>

            <div class="col-12">
                <hr>
            </div>

            <div class="form-group col-md-4">
                <label for="trial_period_days">trial_period_days</label>
                <input id="trial_period_days" type="number" class="form-control" name="attributes[trial_period_days]"
                       value="<?php echo $subscription->trial_period_days ?>"
                       placeholder="3">
                <small class="input-text text-black-50">Days for trial period. Overrides the Plan Id Trial days.</small>
            </div>


            <div class="col-12">
                <hr>
            </div>

            <div class="form-group col-md-12 text-right">
                <button class="btn btn-lg btn-primary mb-3">
                    <i class="fas fa-edit"></i> Update Subscription
                </button>
            </div>

        </div>
    </form>

<?php include_once '../_master/footer.php' ?>