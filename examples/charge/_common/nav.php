<ul class="nav nav-pills nav-fill border p-1 rounded mb-3">
    <li class="nav-item">
        <a class="nav-link <?php echo $active === 'create' ? 'active disabled' : '' ?>"
           href="<?php echo currentUrlPath() ?>">Create</a>
    </li>
    <li class="nav-item">
        <a class="nav-link <?php echo $active === 'list' ? 'active disabled' : '' ?>"
           href="<?php echo currentUrlPath('list.php' . (
                   isset($_GET['customerId']) ? '?customerId=' .  $_GET['customerId'] : ''
               )
           ) ?>">List</a>
    </li>
    <li class="nav-item">
        <a class="nav-link <?php echo $active === 'reverse' ? 'active disabled' : '' ?>"
           href="<?php echo currentUrlPath('reverse.php') ?>">Reverse</a>
    </li>
</ul>