<?php

include_once '../_master/head.php';

$active = 'list';

/** @var \DarkGhostHunter\FlowSdk\Flow $flow */
$flow = FlowInstance::getFlow();

$paged = $flow->customer()->getChargesPage($_GET['customerId'], $_GET['page'] ?? 1);

$currentPage = $paged->page;

$charges = $paged->items;
?>

<a href="<?php echo currentUrlPath('..') ?>" class="btn btn-link">
    &laquo; Go back to Examples
</a>

<h1>Customer</h1>

<?php include_once __DIR__ . '/_common/nav.php'; ?>

<div class="list-group mb-3">

    <form method="get" class="card mb-3">
        <div class="card-body">
            <div class="form-row">
                <div class="form-group col-md">
                    <label for="customerId">customerId</label>
                    <input id="customerId" type="text" name="customerId" class="form-control"
                           value="<?php echo $_GET['customerId'] ?? '' ?>"
                           placeholder="cus_julcghzhbp">
                    <small class="text-black-50">The customerId to retrieve its charges.</small>
                </div>
                <div class="form-group col-md-auto text-right">
                    <label>&nbsp;</label><br>
                    <button type="submit" class="btn btn-primary">
                        Get List of Charges
                    </button>
                </div>
            </div>
        </div>
    </form>

<?php foreach ($charges as $key => $charge) { ?>

    <div class="list-group-item">
        <div class="row">
            <div class="col-12 col-md">
                <h4><?php echo $charge->subject ?> | $ <?php echo $charge->amount ?></h4>
                <div class="mb-3" id="accordion">
                    <button class="btn btn-link collapsed" type="button" data-toggle="collapse"
                            data-target="#<?php echo 'item-' . $key ?>">
                        Data
                    </button>
                    <div id="<?php echo 'item-' . $key ?>" class="collapse" data-parent="#accordion">
                        <div class="card-body pb-1">
                            <pre><?php print_r($charge->toArray()) ?></pre>
                        </div>
                    </div>
                </div>

            </div>
            <div class="col-12 col-md-auto text-right">
                <?php if ($charge->exists()) { ?>
                <form action="<?php echo currentUrlPath('reverse.php') ?>" method="post" class="d-inline-block">
                    <input type="hidden" name="transactionType" value="commerceOrder">
                    <input type="hidden" name="transactionId" value="<?php echo $charge->commerceOrder ?>">
                    <button type="submit" class="btn btn-danger btn-sm">
                        Reverse
                    </button>
                </form>
                <?php } else { ?>
                <button type="button" class="btn btn-secondary btn-sm" disabled>
                    Reversed
                </button>
                <?php } ?>
            </div>

        </div>

    </div>

<?php } ?>
</div>

<nav>
    <ul class="pagination pagination-lg justify-content-center">
        <?php if ($currentPage > 1) { ?>
            <li class="page-item">
                <a class="page-link" href="<?php echo currentUrlPath('list.php?page=' . ($currentPage - 1)) ?>">
                    &laquo; Previous
                </a>
            </li>
            <li class="page-item">
                <a class="page-link" href="<?php echo currentUrlPath('list.php?page=' . ($currentPage - 1)) ?>">
                    <?php echo $currentPage - 1 ?>
                </a>
            </li>
        <?php } ?>
        <li class="page-item active">
            <div class="page-link disabled">
                <?php echo $currentPage ?>
            </div>
        </li>

        <?php if ($paged->hasMore) { ?>
            <li class="page-item">
                <a class="page-link" href="<?php echo currentUrlPath('list.php?page=' . ($currentPage + 1)) ?>">
                    <?php echo $currentPage + 1 ?>
                </a>
            </li>
            <li class="page-item">
                <a class="page-link" href="<?php echo currentUrlPath('list.php?page=' . ($currentPage + 1)) ?>">
                    Next &raquo;
                </a>
            </li>
        <?php } ?>
    </ul>
</nav>

