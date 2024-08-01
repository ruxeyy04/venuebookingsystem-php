<?php
session_start();
date_default_timezone_set('Asia/Manila');

$dateTime = new DateTime();

$timestamp = $dateTime->format('Y-m-d H:i:s');

$servername = "localhost";
            $username = "root";
            $password = "";
            $database = "venuebooking";

$conn = mysqli_connect($servername, $username, $password, $database);
$delivery_fee = 20.00;
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

if (isset($_GET['logout'])) {
    session_destroy();
    session_unset();
    session_start();
    $_SESSION['alert'] = "<script>
    Swal.fire({
        icon: 'success',
        title: 'Logout Successful',
        allowOutsideClick: false
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = '/login.php';
        }
    });
</script>";
}