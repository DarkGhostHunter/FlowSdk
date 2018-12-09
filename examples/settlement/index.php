<?php

include_once '../_master/head.php';

$active = 'retrieve';

if ($_POST['id'] ?? null) {

    /** @var \DarkGhostHunter\FlowSdk\Flow $flow */
    $flow = FlowInstance::getFlow();

    $settlement = $flow->settlement()->get($_POST['id']);

} elseif ($_POST['date'] ?? null) {

    /** @var \DarkGhostHunter\FlowSdk\Flow $flow */
    $flow = FlowInstance::getFlow();

    $settlement = $flow->settlement()->getByDate($_POST['date']);

}
?>

<a href="<?php echo currentUrlPath('..') ?>" class="btn btn-link">
&laquo; Go back to Examples
</a>

<h1>Settlement</h1>

    <div class="card card-body">
        <div class="form-row">

            <form method="post" class="form-group col-md-6">
                <h4>Settlement by Id</h4>
                <label for="id">id</label>
                <input id="id" type="text" class="form-control" placeholder="1034"
                       name="id" >
                <small class="input-text text-black-50">Settlement ID to retrieve</small>


                <div class="form-group col-md-12 text-right">
                    <button class="btn btn-lg btn-primary mb-3">
                        <i class="fas fa-check"></i> Retrieve By Id
                    </button>
                </div>
            </form>

            <form method="post" class="form-group col-md-6">
                <h4>Settlement by Date</h4>
                <label for="date">date</label>
                <input id="date" type="date" class="form-control" placeholder="1034"
                       name="date" >
                <small class="input-text text-black-50">Date gets converted to <code>YYYY-MM-DD</code> automatically in Chromium. Nice!</small>

                <div class="form-group col-md-12 text-right">
                    <button class="btn btn-lg btn-primary mb-3">
                        <i class="fas fa-check"></i> Retrieve By Date
                    </button>
                </div>
            </form>

            <div class="col-12">
                <hr>
            </div>
        </div>
    </div>

<?php if (isset($settlement)) { ?>
    <div class="card text-white bg-success mb-3 w-100">
        <h3 class="card-header">Settlement <?php echo $settlement->id ?> retrieved</h3>
        <div class="card-body">
            <pre><?php print_r($settlement->toArray()) ?></pre>
        </div>
    </div>
<?php } ?>

<?php include_once '../_master/footer.php' ?>