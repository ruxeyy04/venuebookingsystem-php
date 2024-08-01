<?php
require('../config.php');
// Get the current page filename
$current_page = basename($_SERVER['PHP_SELF']);

$menu_items = array(
    "index.php" => array("Dashboard", "fa fa-home"),
    "booked_list.php" => array("Booking", "fa fa-book"),
    "users.php" => array("Users", "fa fa-users"),
    "venue.php" => array("Venue", "fa fa-building"),
    "location.php" => array("Location", "fa fa-map-marker"),
    "profile.php" => array("Profile", "fa fa-user"),
);
function is_active($page, $current_page)
{
    if ($page === $current_page) {
        echo 'active';
    }
}

$page_title = "Dashboard";

foreach ($menu_items as $href => $label) {
    if ($href === $current_page) {
        $page_title = $label;
        break;
    }
}
$userid = isset($_SESSION['userid']) ? $_SESSION['userid'] : null;

if (isset($_SESSION['userid'])) {
    $userinfo_sql = "SELECT * FROM userinfo WHERE userid = ?";
    $userinfo_stmt = $conn->prepare($userinfo_sql);
    $userinfo_stmt->bind_param("i", $userid);
    $userinfo_stmt->execute();
    $userinfo_res = $userinfo_stmt->get_result();
    $userinfo = $userinfo_res->fetch_assoc();
}
if (isset($_SESSION['usertype'])) {
    if ($_SESSION['usertype'] == 'Incharge') {
        echo '<meta http-equiv="refresh" content="0;url=/incharge/index.php">';
        exit();
    } else if ($_SESSION['usertype'] == 'Client') {
        echo '<meta http-equiv="refresh" content="0;url=/index.php">';
        exit();
    }
}
if (!isset($_SESSION['userid'])) {
    echo '<meta http-equiv="refresh" content="0;url=/login.php">';
    exit();
}
?>
<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <title>Espares | Venue Booking | Admin</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" />
  <style>
    .bd-placeholder-img {
      font-size: 1.125rem;
      text-anchor: middle;
      -webkit-user-select: none;
      -moz-user-select: none;
      -ms-user-select: none;
      user-select: none;
    }

    @media (min-width: 768px) {
      .bd-placeholder-img-lg {
        font-size: 3.5rem;
      }
    }
  </style>
  <link href="assets/dashboard.css" rel="stylesheet">
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
 <form action="<?php echo in_array(basename($_SERVER['PHP_SELF']), array('venue.php', 'booked_list.php', 'users.php', 'location.php')) ? basename($_SERVER['PHP_SELF']) : 'venue.php'; ?>" method="GET">
  <nav class="navbar navbar-dark sticky-top bg-dark flex-md-nowrap p-0 shadow">
    <a class="navbar-brand col-md-3 col-lg-2 mr-0 px-3" href="#">Venue Booking | Admin</a>
    <button class="navbar-toggler position-absolute d-md-none collapsed" type="button" data-toggle="collapse" data-target="#sidebarMenu" aria-controls="sidebarMenu" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
                               
                                <input class="form-control form-control-dark w-100" type="text" placeholder="Search" aria-label="Search" name="search">
                           
    
    <ul class="navbar-nav px-3">
      <li class="nav-item text-nowrap">
        <a class="nav-link" href="?logout">Sign out</a>
      </li>
    </ul>
  </nav>
 </form>
  <div class="container-fluid">
    <div class="row">
        <?php include('layouts/navbar.php') ?>

      <main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-md-4">