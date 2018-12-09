<?php

include_once '../_master/head.php';

$active = 'delete';

/** @var \DarkGhostHunter\FlowSdk\Flow $flow */
$flow = FlowInstance::getFlow();

if (isset($_POST['couponId']) && isset($_POST['delete']) && (bool)$_POST['delete'] === true) {

    $coupon = $flow->coupon()->delete($_POST['couponId']);

?>
    <form action="<?php echo currentUrlPath('retrieve.php') ?>" method="get" class="alert alert-success small">
        <input type="hidden" name="couponId" value="<?php echo $coupon->id ?>">
        Coupon <code><?php echo $coupon->id ?></code> has been deleted.
        <div class="text-right">
            <button type="submit" class="btn btn-sm btn-primary">
                See Coupon (Retrieve) &raquo;
            </button>
        </div>
    </form>
<?php

} elseif (isset($_GET['couponId'])) {
    $coupon = $flow->coupon()->get($_GET['couponId']);
} else {
    $coupon = $flow->coupon()->make([]);
}
?>

<a href="<?php echo currentUrlPath('..') ?>" class="btn btn-link">
    &laquo; Go back to Examples
</a>

<h1>Coupon</h1>

<?php include_once __DIR__ . '/_common/nav.php' ?>

    <form method="POST" class="card card-body">
        <input type="hidden" name="delete" value="true">
        <div class="form-row">

            <div class="form-group col-md-6">
                <label for="couponId">couponId</label>
                <input id="couponId" type="text" class="form-control" name="couponId"
                       placeholder="6484"
                       value="<?php echo $coupon->id ?>" required>
                <small class="input-text text-black-50">couponId to delete.</small>
            </div>

            <div class="col-12">
                <hr>
            </div>

            <div class="form-group col-md-12 text-right">
                <button class="btn btn-lg btn-danger mb-3">
                    <i class="fas fa-trash"></i> Confirm Coupon Delete
                </button>
            </div>

        </div>
    </form>

<?php include_once '../_master/footer.php' ?>