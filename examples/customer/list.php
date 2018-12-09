<?php

include_once '../_master/head.php';

$active = 'list';

/** @var \DarkGhostHunter\FlowSdk\Flow $flow */
$flow = FlowInstance::getFlow();

$paged = $flow->customer()->getPage($_GET['page'] ?? 1);

$currentPage = $paged->page;

$customers = $paged->items;
?>

<a href="<?php echo currentUrlPath('..') ?>" class="btn btn-link">
    &laquo; Go back to Examples
</a>

<h1>Customer</h1>

<?php include_once __DIR__ . '/_common/nav.php'; ?>


<div class="list-group mb-3">

<?php foreach ($customers as $customer) { ?>

    <div class="list-group-item">
        <div class="row">
            <div class="col-12 col-md">
                <h4><?php echo $customer->name ?></h4>
                <div class="mb-3" id="accordion">
                    <button class="btn btn-link collapsed" type="button" data-toggle="collapse"
                            data-target="#<?php echo $customer->customerId ?>">
                        Data
                    </button>
                    <div id="<?php echo $customer->customerId ?>" class="collapse" data-parent="#accordion">
                        <div class="card-body pb-1">
                            <pre><?php print_r($customer->toArray()) ?></pre>
                        </div>
                    </div>
                </div>

            </div>
            <div class="col-12 col-md-auto text-right">
                <form action="<?php echo currentUrlPath('retrieve.php?customerId=' . $customer->customerId)?>" method="get" class="d-inline-block">
                    <input type="hidden" name="customerId" value="<?php echo $customer->customerId ?>">
                    <button type="submit" class="btn btn-primary btn-sm">
                        See
                    </button>
                </form>
                <?php if ($customer->exists()) { ?>
                <form action="update.php" method="post" class="d-inline-block">
                    <input type="hidden" name="customerId" value="<?php echo $customer->customerId ?>">
                    <button type="submit" class="btn btn-primary btn-sm">
                        Update
                    </button>
                </form>
                <form action="delete.php" method="post" class="d-inline-block">
                    <input type="hidden" name="customerId" value="<?php echo $customer->customerId ?>">
                    <button type="submit" class="btn btn-danger btn-sm">
                        Delete
                    </button>
                </form>
                <?php } else { ?>
                <button type="button" class="btn btn-secondary btn-sm" disabled>
                    Deleted
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

