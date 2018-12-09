<?php

include_once '../_master/head.php';


if ($_POST['token'] ?? null) {

    /** @var \DarkGhostHunter\FlowSdk\Flow $flow */
    $flow = FlowInstance::getFlow();

    $payment = $flow->payment()->get($_POST['token']);


    $diff = date_diff(new DateTime($payment->requestDate),new DateTime($payment->paymentData['date']));
}

?>

<h1>Payment Retrieved</h1>

<div class="card mb-3">
    <h2 class="card-header">
        <?php echo $payment->commerceOrder ?>
    </h2>
    <div class="card-body">
        <div class="table">
            <table class="table">
                <tbody>
                    <tr>
                        <th scope="row">Status</th>
                        <td><?php echo $payment->status ?></td>
                    </tr>
                    <tr>
                        <th scope="row">Payment Method</th>
                        <td><?php echo $payment->paymentData['media'] ?></td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="text-right">
            <strong>Payment process time:</strong> <?php echo $diff->format('%H:%I:%S'); ?>
        </div>

        <h3>Variable dump (as array)</h3>
        <pre class="alert alert-info"><code><?php print_r($payment->toArray()); ?></code></pre>

    </div>

</div>

    <div class="text-left">
        <a href="//<?php echo "{$_SERVER['HTTP_HOST']}{$_SERVER['REQUEST_URI']}" ?>/../.." class="btn btn-lg btn-primary">
            &laquo; Go back to Index
        </a>
    </div>


<?php include_once '../_master/footer.php' ?>