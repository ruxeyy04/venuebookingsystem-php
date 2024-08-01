<?php

$menu_items = array(
    "index.php" => "Home",
    "about.php" => "About",
    "location.php" => "Locations",
    "venue.php" => "Venues",
    "contact.php" => "Contact",
);



?>
<nav id="menu-wrap" class="menu-back cbp-af-header ">

    <div class="menu">
        <a href="index-2.html">
            <div class="logo">
                <img src="img/logo.png" alt="">
            </div>
        </a>
        <ul>
            <?php foreach ($menu_items as $href => $label) : ?>

                <li>
                    <a class="<?php is_active($href, $current_page); ?>" href="<?php echo $href; ?>"><?php echo $label; ?></a>
                </li>
            <?php endforeach; ?>
            <?php
            if (isset($_SESSION['userid'])) { ?>
                <li>
                    <a href="#!">Profile</a>
                    <ul>
                        <li><a href="booking.php">My Booking</a></li>
                        <li><a href="profile.php">Profile</a></li>
                        <li><a href="?logout">Logout</a></li>
                    </ul>
                </li>
            <?php } else { ?>

                <li>
                    <a href="login.php"><span>Login</span></a>
                </li>
            <?php } ?>
        </ul>
    </div>
</nav>
<?php 
if ($current_page != 'venue-details.php') {
    unset($_SESSION['start_time']);
    unset($_SESSION['end_time']);
    unset($_SESSION['book_date']);
    unset($_SESSION['available_booking']);
}
?>