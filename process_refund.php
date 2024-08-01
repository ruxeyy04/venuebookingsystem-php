<?php
include 'config.php'; // Include your database connection file

if (!isset($_SESSION['userid'])) {
    echo '<meta http-equiv="refresh" content="0;url=login.php">';
    exit();
}

if (isset($_GET['booking_id'])) {
    $booking_id = $_GET['booking_id'];
    $userid = $_SESSION['userid'];

    // Check if the booking exists, belongs to the user, and the payment status is 'Paid'
    $sql = "SELECT * FROM bookings WHERE booking_id = '$booking_id' AND userid = '$userid' AND (status = 'Pending' OR status = 'Rejected')";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $booking = $result->fetch_assoc();

        // Check if the payment is 'Paid'
        $payment_sql = "SELECT * FROM payments WHERE booking_id = '$booking_id' AND status = 'Paid'";
        $payment_result = $conn->query($payment_sql);

        if ($payment_result->num_rows > 0) {
            // Start a transaction
            $conn->begin_transaction();

            try {
                $update_payment_sql = "UPDATE payments SET status = 'Refunded' WHERE booking_id = '$booking_id'";
                if ($conn->query($update_payment_sql) === TRUE) {
                    if ($booking['status'] != 'Rejected') {
                        $update_booking_sql = "UPDATE bookings SET status = 'Cancelled' WHERE booking_id = '$booking_id'";
                        if ($conn->query($update_booking_sql) === TRUE) {
                            $conn->commit();

                            $_SESSION['alert'] = "<script>
                            Swal.fire({
                                icon: 'success',
                                title: 'Refund Processed',
                                text: 'Your refund has been processed successfully and the booking has been cancelled.',
                            })
                            </script>";
                            echo '<meta http-equiv="refresh" content="0;url=booking.php">';
                        } else {
                            throw new Exception("Error updating booking status: " . $conn->error);
                        }
                    } else {
                        // Commit the transaction
                        $conn->commit();

                        $_SESSION['alert'] = "<script>
                        Swal.fire({
                            icon: 'success',
                            title: 'Refund Processed',
                            text: 'Your refund has been processed successfully.',
                        })
                        </script>";
                        echo '<meta http-equiv="refresh" content="0;url=booking.php">';
                    }
                } else {
                    throw new Exception("Error updating payment status: " . $conn->error);
                }
            } catch (Exception $e) {
                $conn->rollback();

                $_SESSION['alert'] = "<script>
                Swal.fire({
                    icon: 'error',
                    title: 'Refund Error',
                    text: 'An error occurred while processing your refund. Please try again later.',
                })
                </script>";
                echo '<meta http-equiv="refresh" content="0;url=booking.php">';
            }
        } else {
            $_SESSION['alert'] = "<script>
            Swal.fire({
                icon: 'error',
                title: 'Refund Not Available',
                text: 'No paid payment found for this booking.',
            })
            </script>";
            echo '<meta http-equiv="refresh" content="0;url=booking.php">';
        }
    } else {
        $_SESSION['alert'] = "<script>
        Swal.fire({
            icon: 'error',
            title: 'Booking Not Found',
            text: 'The booking you are trying to refund was not found or cannot be refunded.',
        })
        </script>";
        echo '<meta http-equiv="refresh" content="0;url=booking.php">';
    }
} else {
    echo '<meta http-equiv="refresh" content="0;url=booking.php">';
}
?>
