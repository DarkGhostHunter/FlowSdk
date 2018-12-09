<?php

include_once '../_master/head.php';

if ($_POST['token'] ?? null) {

    /** @var \DarkGhostHunter\FlowSdk\Flow $flow */
    $flow = FlowInstance::getFlow();

    $card = $flow->customer()->getCard($_POST['token']);

}
?>

<a href="<?php echo currentUrlPath('..') ?>" class="btn btn-link">
    &laquo; Go back to Examples
</a>

<h1>Credit Card</h1>

<div class="card">

    <h2 class="card-header">
        Card Registration Status
    </h2>
    <pre class="card-body"><?php print_r($card->toArray()) ?></pre>

    <?php if ($card->exists()) { ?>
        <form method="POST" action="<?php echo currentUrlPath('unregister.php') ?>" class="card-body">
            <input type="hidden" name="customerId" value="<?php echo $card->customerId ?>">
            <div class="text-right">
                <a href="<?php echo currentUrlPath('../charge?customerId=' . $card->customerId) ?>" class="btn btn-lg btn-primary mb-3">
                    <i class="fas fa-credit-card"></i> Create Charges
                </a>
                <button class="btn btn-lg btn-danger mb-3">
                    <i class="fas fa-trash"></i> Unregister Credit Card
                </button>
            </div>
        </form>
    <?php } else { ?>
        <form method="POST" action="<?php echo currentUrlPath('index.php') ?>" class="card-body">
            <input type="hidden" name="customerId" value="<?php echo $card->customerId ?>">
            <div class="text-right">
                <button class="btn btn-lg btn-danger mb-3">
                    <i class="fas fa-redo"></i> Try Again
                </button>
            </div>
        </form>
    <?php }?>


</div>

<?php include_once '../_master/footer.php' ?>