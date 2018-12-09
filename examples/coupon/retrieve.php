<?php

include_once '../_master/head.php';

$active = 'retrieve';

if ($_GET['couponId'] ?? null) {

    /** @var \DarkGhostHunter\FlowSdk\Flow $flow */
    $flow = FlowInstance::getFlow();

    $coupon = $flow->coupon()->get($_GET['couponId']);

}
?>

<a href="<?php echo currentUrlPath('..') ?>" class="btn btn-link">
    &laquo; Go back to Examples
</a>

<h1>Coupon</h1>

<?php include_once __DIR__ . '/_common/nav.php' ?>


<?php if (isset($coupon)) { ?>
    <div class="card text-white bg-success mb-3 w-100">
        <h3 class="card-header">Coupon <?php echo $coupon->id ?> retrieved</h3>
        <div class="card-body">
            <?php if (!$coupon->exists()) { ?>
                <div class="alert alert-danger">
                    This Coupon has been deleted
                </div>
            <?php } ?>
            <pre><?php print_r($coupon->toArray()) ?></pre>
            <form action="update.php" method="GET" class="text-right">
                <input type="hidden" name="couponId" value="<?php echo $coupon->id ?>">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-edit"></i> Update Coupon
                </button>
            </form>
        </div>
    </div>
<?php } ?>

<form method="GET" action="<?php echo currentUrlPath('retrieve.php')?>" class="card card-body">
    <div class="form-row">
        <div class="form-group col-md-12">
            <label for="couponId">couponId</label>
            <input id="couponId" type="text" class="form-control" placeholder="couponId-0fb12747"
                   value="<?php echo $_GET['couponId'] ?? null ?>"
                   name="couponId" required>
            <small class="input-text text-black-50">couponId to retrieve</small>
        </div>

        <div class="col-12">
            <hr>
        </div>

        <div class="form-group col-md-12 text-right">
            <button class="btn btn-lg btn-primary mb-3">
                <i class="fas fa-check"></i> Retrieve Coupon
            </button>
        </div>
    </div>
</form>

<?php include_once '../_master/footer.php' ?>