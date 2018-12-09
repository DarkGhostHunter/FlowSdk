<ul class="nav nav-pills nav-fill border p-1 rounded mb-3">
    <li class="nav-item">
        <a class="nav-link <?php echo $active === 'create' ? 'active disabled' : '' ?>"
           href="<?php echo currentUrlPath() ?>">Create</a>
    </li>
    <li class="nav-item">
        <a class="nav-link <?php echo $active === 'retrieve' ? 'active disabled' : '' ?>"
           href="<?php echo currentUrlPath('retrieve.php') ?>">Retrieve</a>
    </li>
    <li class="nav-item">
        <a class="nav-link <?php echo $active === 'update' ? 'active disabled' : '' ?>"
           href="<?php echo currentUrlPath('update.php') ?>">Update</a>
    </li>
    <li class="nav-item">
        <a class="nav-link <?php echo $active === 'list' ? 'active disabled' : '' ?>"
           href="<?php echo currentUrlPath('list.php') ?>">List</a>
    </li>
    <li class="nav-item">
        <a class="nav-link <?php echo $active === 'delete' ? 'active disabled' : '' ?>"
           href="<?php echo currentUrlPath('delete.php') ?>">Delete</a>
    </li>
</ul>