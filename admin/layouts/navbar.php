<nav id="sidebarMenu" class="col-md-3 col-lg-2 d-md-block bg-light sidebar collapse">
    <div class="sidebar-sticky pt-3">
        <ul class="nav flex-column">
            <?php foreach ($menu_items as $href => $data) : ?>
                <li class="nav-item">
                    <a class="nav-link <?php is_active($href, $current_page); ?>" href="<?php echo $href; ?>">
                        <i class="<?php echo $data[1]; ?>"></i>
                        <?php echo $data[0]; ?> 
                    </a>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>
</nav>