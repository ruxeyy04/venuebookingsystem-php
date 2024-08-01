<?php
include 'config.php'; 

if (!isset($_SESSION['userid'])) {
    echo '<meta http-equiv="refresh" content="0;url=login.php">';
    exit();
}

if (isset($_GET['booking_id'])) {
    $booking_id = $_GET['booking_id'];
    $userid = $_SESSION['userid'];

    // Check if the booking exists and belongs to the user
    $sql = "SELECT * FROM bookings WHERE booking_id = '$booking_id' AND userid = '$userid'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // Update the payment status
        $sql = "UPDATE payments SET status = 'Paid', payment_date = '$timestamp' WHERE booking_id = '$booking_id'";
        
        if ($conn->query($sql) === TRUE) {
            $_SESSION['alert'] = "<script>
            Swal.fire({
                icon: 'success',
                title: 'Payment Successful',
                text: 'Your payment has been processed successfully.',
            });
            </script>";
            echo '<meta http-equiv="refresh" content="0;url=booking.php">';
        } else {
            $_SESSION['alert'] = "<script>
            Swal.fire({
                icon: 'error',
                title: 'Payment Error',
                text: 'An error occurred while processing your payment. Please try again later.',
            });
            </script>";
            echo '<meta http-equiv="refresh" content="0;url=booking.php">';
        }
    } else {
        $_SESSION['alert'] = "<script>
        Swal.fire({
            icon: 'error',
            title: 'Booking Not Found',
            text: 'The booking you are trying to pay for was not found.',
        });
        </script>";
        echo '<meta http-equiv="refresh" content="0;url=booking.php">';
    }
} else {
    echo '<meta http-equiv="refresh" content="0;url=booking.php">';
}
?>
