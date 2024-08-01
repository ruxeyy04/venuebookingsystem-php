<?php
include('./config.php');
$userid = isset($_SESSION['userid']) ? $_SESSION['userid'] : null;

if (isset($_SESSION['userid'])) {
    $userinfo_sql = "SELECT *
FROM userinfo 
WHERE userid = ?";
    $userinfo_stmt = $conn->prepare($userinfo_sql);
    $userinfo_stmt->bind_param("i", $userid);
    $userinfo_stmt->execute();
    $userinfo_res = $userinfo_stmt->get_result();
    $userinfo = $userinfo_res->fetch_assoc();
}

$current_page = basename($_SERVER['PHP_SELF']);
function is_active($page, $current_page)
{
    if ($page === $current_page) {
        echo 'active';
    }
}
if (isset($_SESSION['usertype'])) {
    if ($_SESSION['usertype'] == 'Incharge') {
        echo '<meta http-equiv="refresh" content="0;url=/incharge/index.php">';
        exit();
    } else if ($_SESSION['usertype'] == 'Admin') {
        echo '<meta http-equiv="refresh" content="0;url=/admin/index.php">';
        exit();
    }
}

?>
<!DOCTYPE html>
<!--[if IE 8]><html class="no-js lt-ie9" lang="en"> <![endif]-->
<!--[if gt IE 8]>
<!-->
<html class="no-js" lang="en"><!--<![endif]-->

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Espares - Venue Booking</title>

    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <meta name="theme-color" content="#212121" />
    <meta name="msapplication-navbutton-color" content="#212121" />
    <meta name="apple-mobile-web-app-status-bar-style" content="#212121" />

    <link href="https://fonts.googleapis.com/css?family=Poppins:100,100i,200,200i,300,300i,400,400i,500,500i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css?family=Roboto:100,100i,300,300i,400,400i,500,500i,700,700i,900,900i&amp;subset=cyrillic,cyrillic-ext,greek,greek-ext,latin-ext,vietnamese" rel="stylesheet">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" />
    <link rel="stylesheet" href="css/ionicons.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.10.0/css/bootstrap-datepicker.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.5.7/jquery.fancybox.min.css" />
    <link rel="stylesheet" href="css/owl.carousel.css" />
    <link rel="stylesheet" href="css/owl.transitions.css" />
    <link rel="stylesheet" href="css/style.css" />
    <link rel="stylesheet" href="css/colors/color.css" />

    <link rel="icon" type="image/png" href="favicon.png">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        const Toast = Swal.mixin({
            toast: true,
            position: 'bottom',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
            didOpen: (toast) => {
                toast.addEventListener('mouseenter', Swal.stopTimer);
                toast.addEventListener('mouseleave', Swal.resumeTimer);
            }
        });
    </script>
</head>

<body>

    <div class="loader">
        <div class="loader__figure"></div>
    </div>

    <svg class="hidden">
        <svg id="icon-nav" viewBox="0 0 152 63">
            <title>navarrow</title>
            <path d="M115.737 29L92.77 6.283c-.932-.92-1.21-2.84-.617-4.281.594-1.443 1.837-1.862 2.765-.953l28.429 28.116c.574.57.925 1.557.925 2.619 0 1.06-.351 2.046-.925 2.616l-28.43 28.114c-.336.327-.707.486-1.074.486-.659 0-1.307-.509-1.69-1.437-.593-1.442-.315-3.362.617-4.284L115.299 35H3.442C2.032 35 .89 33.656.89 32c0-1.658 1.143-3 2.552-3H115.737z" />
        </svg>
    </svg>


    <!-- Nav and Logo
	================================================== -->

        <?php include('layouts/navbar.php') ?>

    <!-- Primary Page Layout
	================================================== -->