<?php
include 'config.php'; // Include your database connection

if (!isset($_SESSION['userid'])) {
    echo '<meta http-equiv="refresh" content="0;url=login.php">';
    exit();
}

if (isset($_GET['booking_id'])) {
    $booking_id = $_GET['booking_id'];
    $userid = $_SESSION['userid'];

    // Check if the booking exists and belongs to the user
    $sql = "SELECT * FROM bookings WHERE booking_id = '$booking_id' AND userid = '$userid' AND status = 'Pending'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // Update the booking status to 'Cancelled'
        $sql = "UPDATE bookings SET status = 'Cancelled' WHERE booking_id = '$booking_id'";
        
        if ($conn->query($sql) === TRUE) {
            $_SESSION['alert'] = "<script>
            Swal.fire({
                icon: 'success',
                title: 'Booking Cancelled',
                text: 'Your booking has been cancelled successfully.',
            });
            </script>";
            echo '<meta http-equiv="refresh" content="0;url=booking.php">';
        } else {
            $_SESSION['alert'] = "<script>
            Swal.fire({
                icon: 'error',
                title: 'Cancellation Error',
                text: 'An error occurred while cancelling your booking. Please try again later.',
            });
            </script>";
            echo '<meta http-equiv="refresh" content="0;url=booking.php">';
        }
    } else {
        $_SESSION['alert'] = "<script>
        Swal.fire({
            icon: 'error',
            title: 'Booking Not Found',
            text: 'The booking you are trying to cancel was not found or cannot be cancelled.',
        });
        </script>";
        echo '<meta http-equiv="refresh" content="0;url=booking.php">';
    }
} else {
    echo '<meta http-equiv="refresh" content="0;url=booking.php">';
}
?>
