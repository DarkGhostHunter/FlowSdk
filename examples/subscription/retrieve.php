<?php

include_once '../_master/head.php';

$active = 'retrieve';

if ($_GET['subscriptionId'] ?? null) {

    /** @var \DarkGhostHunter\FlowSdk\Flow $flow */
    $flow = FlowInstance::getFlow();

    $subscription = $flow->subscription()->get($_GET['subscriptionId']);

}
?>

<a href="<?php echo currentUrlPath('..') ?>" class="btn btn-link">
    &laquo; Go back to Examples
</a>

<h1>Subscription</h1>

<?php include_once __DIR__ . '/_common/nav.php' ?>


<?php if (isset($subscription)) { ?>
    <div class="card text-white bg-success mb-3 w-100">
        <h3 class="card-header">Subscription <?php echo $subscription->subscriptionId ?> retrieved</h3>
        <div class="card-body">
            <?php if (!$subscription->exists()) { ?>
                <div class="alert alert-danger">
                    This subscription has been deleted
                </div>
            <?php } ?>
            <pre><?php print_r($subscription->toArray()) ?></pre>
            <form action="<?php echo currentUrlPath('update.php') ?>" method="get" class="text-right">
                <input type="hidden" name="subscriptionId" value="<?php echo $subscription->subscriptionId ?>">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-edit"></i> Update Subscription
                </button>
            </form>
        </div>
    </div>
<?php } ?>

<form method="GET" action="<?php echo currentUrlPath('retrieve.php')?>" class="card card-body">
    <div class="form-row">

        <div class="form-group col-md-12">
            <label for="subscriptionId">subscriptionId</label>
            <input id="subscriptionId" type="text" class="form-control" placeholder="cus_1v577va23b"
                   value="<?php echo $_GET['subscriptionId'] ?? null ?>"
                   name="subscriptionId" required>
            <small class="input-text text-black-50">subscriptionId to retrieve</small>
        </div>

        <div class="col-12">
            <hr>
        </div>

        <div class="form-group col-md-12 text-right">
            <button class="btn btn-lg btn-primary mb-3">
                <i class="fas fa-check"></i> Retrieve Subscription
            </button>
        </div>
    </div>
</form>

<?php include_once '../_master/footer.php' ?>