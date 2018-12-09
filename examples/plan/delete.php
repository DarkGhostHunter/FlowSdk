<?php

include_once '../_master/head.php';

$active = 'delete';

/** @var \DarkGhostHunter\FlowSdk\Flow $flow */
$flow = FlowInstance::getFlow();

if (isset($_POST['planId']) && isset($_POST['delete']) && (bool)$_POST['delete'] === true) {

    $plan = $flow->plan()->delete($_POST['planId']);

?>
    <form action="<?php echo currentUrlPath('retrieve.php') ?>" method="get" class="alert alert-success small">
        <input type="hidden" name="planId" value="<?php echo $plan->planId ?>">
        Plan <code><?php echo $plan->planId ?></code> has been deleted.
        <div class="text-right">
            <button type="submit" class="btn btn-sm btn-primary">
                See Plan (Retrieve) &raquo;
            </button>
        </div>
    </form>
<?php

} elseif (isset($_POST['planId'])) {
    $plan = $flow->plan()->get($_POST['planId']);
} else {
    $plan = $flow->plan()->make([]);
}
?>

<a href="<?php echo currentUrlPath('..') ?>" class="btn btn-link">
    &laquo; Go back to Examples
</a>

<h1>Plan</h1>

<?php include_once __DIR__ . '/_common/nav.php' ?>

    <form method="POST" class="card card-body">
        <input type="hidden" name="delete" value="true">
        <div class="form-row">

            <div class="form-group col-md-6">
                <label for="planId">planId</label>
                <input id="planId" type="text" class="form-control" name="planId"
                       placeholder="cus_1v577va23b"
                       value="<?php echo $plan->planId ?>" required>
                <small class="input-text text-black-50">planId to delete.</small>
            </div>

            <div class="col-12">
                <hr>
            </div>

            <div class="form-group col-md-12 text-right">
                <button class="btn btn-lg btn-danger mb-3">
                    <i class="fas fa-trash"></i> Confirm Plan Delete
                </button>
            </div>

        </div>
    </form>

<?php include_once '../_master/footer.php' ?>