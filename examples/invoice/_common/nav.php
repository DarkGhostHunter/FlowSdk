<ul class="nav nav-pills nav-fill border p-1 rounded mb-3">
    <li class="nav-item">
        <a class="nav-link <?php echo $active === 'retrieve' ? 'active disabled' : '' ?>"
           href="<?php echo currentUrlPath('index.php') ?>">Retrieve</a>
    </li>
    <li class="nav-item">
        <a class="nav-link <?php echo $active === 'cancel' ? 'active disabled' : '' ?>"
           href="<?php echo currentUrlPath('cancel.php') ?>">Cancel</a>
    </li>
</ul>