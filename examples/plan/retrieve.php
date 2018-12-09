<?php

include_once '../_master/head.php';

$active = 'retrieve';

if ($_GET['planId'] ?? null) {

    /** @var \DarkGhostHunter\FlowSdk\Flow $flow */
    $flow = FlowInstance::getFlow();

    $plan = $flow->plan()->get($_GET['planId']);

}
?>

<a href="<?php echo currentUrlPath('..') ?>" class="btn btn-link">
    &laquo; Go back to Examples
</a>

<h1>Plan</h1>

<?php include_once __DIR__ . '/_common/nav.php' ?>


<?php if (isset($plan)) { ?>
    <div class="card text-white bg-success mb-3 w-100">
        <h3 class="card-header">Plan <?php echo $plan->planId ?> retrieved</h3>
        <div class="card-body">
            <?php if (!$plan->exists()) { ?>
                <div class="alert alert-danger">
                    This Plan has been deleted
                </div>
            <?php } ?>
            <pre><?php print_r($plan->toArray()) ?></pre>
            <form action="update.php" method="GET" class="text-right">
                <input type="hidden" name="planId" value="<?php echo $plan->planId ?>">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-edit"></i> Update Plan
                </button>
            </form>
        </div>
    </div>
<?php } ?>

<form method="GET" action="<?php echo currentUrlPath('retrieve.php')?>" class="card card-body">
    <div class="form-row">
        <div class="form-group col-md-12">
            <label for="planId">planId</label>
            <input id="planId" type="text" class="form-control" placeholder="planId-0fb12747"
                   value="<?php echo $_GET['planId'] ?? null ?>"
                   name="planId" required>
            <small class="input-text text-black-50">planId to retrieve</small>
        </div>

        <div class="col-12">
            <hr>
        </div>

        <div class="form-group col-md-12 text-right">
            <button class="btn btn-lg btn-primary mb-3">
                <i class="fas fa-check"></i> Retrieve Plan
            </button>
        </div>
    </div>
</form>

<?php include_once '../_master/footer.php' ?>