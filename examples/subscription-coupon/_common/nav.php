<ul class="nav nav-pills nav-fill border p-1 rounded mb-3">
    <li class="nav-item">
        <a class="nav-link <?php echo $active === 'attach' ? 'active disabled' : '' ?>"
           href="<?php echo currentUrlPath() ?>">Attach</a>
    </li>
    <li class="nav-item">
        <a class="nav-link <?php echo $active === 'detach' ? 'active disabled' : '' ?>"
           href="<?php echo currentUrlPath('detach.php') ?>">Detach</a>
    </li>
</ul>