<?php

$servername = "localhost";
            $username = "u659181579_venuebooking";
            $password = "=3!6WOv8xgZ";
            $database = "u659181579_venuebooking";

$conn = mysqli_connect($servername, $username, $password, $database);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Arrays for random data
$venue_ids = [21, 22, 23, 29, 30, 32];
$booking_statuses = ["Approved", "Cancelled", "Rejected"];
$payment_statuses = ["Paid", "Unpaid"];
$start_date = "2024-06-13";
$users_range = range(50, 500);
$total_capacity_range = range(100, 500);

// Function to generate random time ensuring end_time is after start_time
function generateRandomTimes($startHour = 8, $endHour = 22)
{
    $start_time_hour = rand($startHour, $endHour - 1);
    $end_time_hour = rand($start_time_hour + 1, $endHour);
    
    $start_time = sprintf("%02d:00:00", $start_time_hour);
    $end_time = sprintf("%02d:00:00", $end_time_hour);
    
    return [$start_time, $end_time];
}

// Function to calculate total hours
function calculateTotalHours($start_time, $end_time)
{
    $start = strtotime($start_time);
    $end = strtotime($end_time);
    return ($end - $start) / 3600;
}

// Function to calculate total price
function calculateTotalPrice($venue_id, $total_hours)
{
    global $conn;
    $result = $conn->query("SELECT priceperhour FROM venues WHERE venue_id = $venue_id");
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        return $row['priceperhour'] * $total_hours;
    }
    return 0;
}

// Insertion loop for bookings and payments
for ($i = 0; $i < 495; $i++) {
    $userid = $users_range[array_rand($users_range)];
    $venue_id = $venue_ids[array_rand($venue_ids)];
    $booking_date = date('Y-m-d', strtotime("$start_date + $i days"));
    list($start_time, $end_time) = generateRandomTimes();
    $total_hours = calculateTotalHours($start_time, $end_time);
    $total_price = calculateTotalPrice($venue_id, $total_hours);
    $total_capacity = $total_capacity_range[array_rand($total_capacity_range)];
    $booking_status = $booking_statuses[array_rand($booking_statuses)];
    $created_at = date('Y-m-d H:i:s');

    $sql_booking = "INSERT INTO bookings (userid, venue_id, booking_date, start_time, end_time, total_hours, total_price, total_capacity, status, created_at)
                    VALUES ('$userid', '$venue_id', '$booking_date', '$start_time', '$end_time', '$total_hours', '$total_price', '$total_capacity', '$booking_status', '$created_at')";

    if ($conn->query($sql_booking) === TRUE) {
        $booking_id = $conn->insert_id; // Get the last inserted booking id
        $payment_status = $payment_statuses[array_rand($payment_statuses)];
        $payment_date = $created_at;
        $amount = $total_price;

        $sql_payment = "INSERT INTO payments (booking_id, userid, payment_date, status, amount)
                        VALUES ('$booking_id', '$userid', '$payment_date', '$payment_status', '$amount')";

        if ($conn->query($sql_payment) === TRUE) {
            echo "Booking $i and payment inserted successfully.<br>";
        } else {
            echo "Error: " . $sql_payment . "<br>" . $conn->error;
        }
    } else {
        echo "Error: " . $sql_booking . "<br>" . $conn->error;
    }
}

$conn->close();
?>
