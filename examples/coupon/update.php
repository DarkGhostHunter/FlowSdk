<?php

include_once '../_master/head.php';

$active = 'update';

/** @var \DarkGhostHunter\FlowSdk\Flow $flow */
$flow = FlowInstance::getFlow();

if (isset($_GET['couponId']) && !isset($_POST['attributes'])) {

    $coupon = $flow->coupon()->get($_GET['couponId']);

} elseif (isset($_POST['couponId']) && isset($_POST['attributes'])) {

    $coupon = $flow->coupon()->update(
        $_POST['couponId'],
        $_POST['attributes']
    );

?>
    <form action="<?php echo currentUrlPath('delete.php') ?>" method="get" class="alert alert-success small">
        <input type="hidden" name="couponId" value="<?php echo $coupon->id ?>">
        Customer <code><?php echo $coupon->id ?></code> has been updated.
        <div class="text-right">
            <button class="btn btn-danger btn-sm">
                <i class="fas fa-trash-alt"></i> Delete coupon?
            </button>
        </div>
    </form>
<?php
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
        <div class="form-row">

            <div class="form-group col-md-6">
                <label for="couponId">couponId</label>
                <input id="couponId" type="text" class="form-control" name="couponId"
                       placeholder="64857"
                       value="<?php echo $_GET['couponId'] ?? null ?>" required>
                <small class="input-text text-black-50">couponId to update.</small>
            </div>

            <div class="col-12">
                <hr>
            </div>

            <div class="form-group col-md-6">
                <label for="name">name</label>
                <input id="name" type="text" class="form-control" name="attributes[name]"
                       value="<?php echo $coupon->name ?>"
                       placeholder="My Super Coupon" required>
                <small class="input-text text-black-50">Name of the Coupon.</small>
            </div>

            <div class="col-12">
                <hr>
            </div>

            <div class="form-group col-md-12 text-right">
                <button class="btn btn-lg btn-primary mb-3">
                    <i class="fas fa-check"></i> Update Coupon
                </button>
            </div>

        </div>
    </form>

<?php include_once '../_master/footer.php' ?>