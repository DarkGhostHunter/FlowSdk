<?php

include_once '../_master/head.php';

if (isset($_POST['customerId']) && isset($_POST['url_return'])) {

    /** @var \DarkGhostHunter\FlowSdk\Flow $flow */
    $flow = FlowInstance::getFlow();

    $response = $flow->customer()->registerCard(
        $_POST['customerId'],
        $_POST['url_return']
    );

    header('Location: '. $response->getUrl());

}
?>

<a href="<?php echo currentUrlPath('..') ?>" class="btn btn-link">
    &laquo; Go back to Examples
</a>

<h1>Credit Card</h1>

<form method="POST" class="card card-body">
    <div class="form-row">
        <div class="form-group col-md-6">
            <label for="customerId">customerId</label>
            <input id="customerId" type="text" class="form-control" name="customerId"
                   placeholder="cus_1v577va23b"
                   value="<?php echo $_POST['customerId'] ?? null ?>" required>
            <small class="input-text text-black-50">Customer ID for your application.</small>
        </div>
        <div class="form-group col-md-6">
            <label for="url_return">url_return</label>
            <input id="url_return" type="text" class="form-control" name="url_return"
                   value="<?php echo currentUrlPath('register.php') ?>" required>
            <small class="input-text text-black-50">URL where your Customer will return to see the registration status.</small>
        </div>

        <div class="col-12">
            <hr>
        </div>

        <div class="form-group col-md-12 text-right">
            <button class="btn btn-lg btn-primary mb-3">
                <i class="fas fa-check"></i> Register Credit Card
            </button>
        </div>

    </div>
</form>

<?php include_once '../_master/footer.php' ?>