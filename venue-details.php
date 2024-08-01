<?php include('layouts/header.php') ?>
<div class="section big-55-height over-hide">

    <div class="parallax parallax-top" style="background-image: url('/location/location_info_cover.jpg')"></div>
    <div class="dark-over-pages"></div>

    <div class="hero-center-section pages">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-12 parallax-fade-top">
                    <div class="hero-text">Location Details</div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
if (!isset($_GET['venue_id']) && !isset($_GET['venue_id'])) {
    echo '<meta http-equiv="refresh" content="0;url=venue.php">';
    exit;
}

$venue_id = isset($_GET['venue_id']) ? (int)$_GET['venue_id'] : null;

if ($venue_id !== null) {
    $sql = "SELECT a.*, a.image AS venue_image, b.*
			FROM venues a 
			LEFT JOIN locations b ON a.loc_id = b.loc_id  
            WHERE a.venue_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $venue_id);
} else {
    // Handle invalid input
    echo '<meta http-equiv="refresh" content="0;url=venue.php">';
    exit;
}

$stmt->execute();
$loc_res = $stmt->get_result();

if ($loc_res->num_rows == 0) {
    // Handle no matching records
    echo '<meta http-equiv="refresh" content="0;url=venue.php">';
    exit;
}
if ($venue_id !== null) {
    $loc = $loc_res->fetch_assoc();
}

?>
<div class="section padding-top-bottom z-bigger">
    <div class="section z-bigger">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 mt-4 mt-lg-0">
                    <div class="section">
                        <div id="rooms-sync1">
                            <div class="item">
                                <img src="/venue/<?= $loc['venue_image'] ?>" alt="">
                            </div>
                        </div>
                    </div>
                    <div class="section pt-5">
                        <h6><?= $loc['venue_name'] ?></h6>
                    </div>
                    <div class="section pt-5">
                        <h5>discription</h5>
                        <p class="mt-3"><?= $loc['description'] ?></p>
                    </div>
                    <div class="section pt-5">
                        <h5>Origin Location</h5>
                        <p class="mt-3"><?= $loc['location_name'] ?></p>
                    </div>
                    <div class="section pt-4">
                        <div class="row">
                            <div class="col-12">
                                <h5 class="mb-3">Overview</h5>
                            </div>
                            <div class="col-lg-6">
                                <p><strong class="color-black">Minimum Capacity:</strong> <?= $loc['min_capacity'] ?></p>
                                <p><strong class="color-black">Maximum Capacity:</strong> <?= $loc['max_capacity'] ?></p>
                                <p><strong class="color-black">Price p/ Hour:</strong>₱ <?= number_format($loc['priceperhour'], 2) ?></p>
                                <p><strong class="color-black">Status:</strong> <?= $loc['status'] ?></p>
                            </div>
                        </div>
                    </div>
                    <div class="section pt-4">
                        <h5>Address</h5>
                        <p class="mt-3"><?= $loc['address'] ?></p>
                    </div>
                </div>
                <div class="col-lg-4 order-first order-lg-last">
                    <form action="" method="post">
                        <input type="hidden" name="loc_id" value="<?= $loc['image'] ?>">
                        <div class="section background-dark p-4">
                            <div class="row">
                                <div class="col-12">
                                    <label for="">Start Time</label>
                                    <input type="time" value="<?= isset($_SESSION['start_time']) ? $_SESSION['start_time'] : '' ?>" name="start_time" class="form-control" required="" style="height: 42px;
                                    border: 1px solid #5e5e5e4a;color: white;">
                                </div>
                                <div class="col-12">
                                    <label for="">End Time</label>
                                    <input type="time" value="<?= isset($_SESSION['end_time']) ? $_SESSION['end_time'] : '' ?>" name="end_time" class="form-control" required="" style="height: 42px;
                                    border: 1px solid #5e5e5e4a;color: white;">
                                </div>
                                <div class="col-12">
                                    <label for="">Date</label>
                                    <div class="input-daterange input-group" id="flight-datepicker">
                                        <div class="row">
                                            <div class="col-12">
                                                <div class="form-item">
                                                    <span class="fontawesome-calendar"></span>
                                                    <input class="input-sm" type="text" autocomplete="off" id="start-date-1" name="book_date" placeholder="booking date" data-date-format="DD, MM d" value="<?= isset($_SESSION['book_date']) ? $_SESSION['book_date'] : '' ?>">
                                                    <span class="date-text date-depart"></span>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                </div>

                                <?php
                                if (isset($_SESSION['available_booking'])) { ?>
                                    <div class="col-12">
                                        <label for="">Total Capacity</label>
                                        <input type="number" min="<?= $loc['min_capacity'] ?>" max="<?= $loc['max_capacity'] ?>" name="total_capacity" class="form-control" required style="height: 42px;
                                    border: 1px solid #5e5e5e4a;color: white;">
                                    </div>
                                    <div class="col-12">
                                        <label for="">Hours</label>
                                        <h6 style="color: white;">Total Hours: <?= $_SESSION['total_hours'] ?></h6>
                                    </div>
                                    <div class="col-12">
                                        <label for="">Amount</label>
                                        <h6 style="color: white;">Total Amount: ₱<?= number_format($_SESSION['total_amount'], 2) ?></h6>
                                    </div>
                                    <div class="col-12 pt-4">
                                        <button class="booking-button" type="submit" name="book_now">Book Now</button>
                                    </div>
                                <?php } else { ?>
                                    <div class="col-12 pt-4">
                                        <button class="booking-button" type="submit" name="check_availability">Check Availability</button>
                                    </div>
                                <?php  }
                                ?>

                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<?php

if (isset($_POST['check_availability'])) {
    $venue_id = $_GET['venue_id'];
    $start_time = $_POST['start_time'];
    $end_time = $_POST['end_time'];

    // Hours Difference
    $start_timestamp = strtotime($start_time);
    $end_timestamp = strtotime($end_time);
    $time_difference = $end_timestamp - $start_timestamp;
    $hours_difference = $time_difference / 3600;
    // Hours Difference
    $hours_difference;
    $book_date1 = isset($_POST['book_date']) ? $_POST['book_date'] : '';
    $_SESSION['start_time'] = $start_time;
    $_SESSION['end_time'] = $end_time;
    $_SESSION['book_date'] = $book_date1;

    if (!empty($start_time) && !empty($end_time) && !empty($book_date1)) {
        $start_timestamp = strtotime($start_time);
        $end_timestamp = strtotime($end_time);

        if ($end_timestamp < $start_timestamp) {
            $_SESSION['alert'] = "<script>
            Toast.fire({
                icon: 'info',
                title: 'End time cannot be earlier than start time.',
            });
        </script>";
            echo '<meta http-equiv="refresh" content="0;url=venue-details.php?venue_id=' . $venue_id . '">';
            exit();
        }
    }

    $dateObject = DateTime::createFromFormat('m.d.Y', $book_date1);
    $formattedDate = $dateObject->format('Y-m-d');

    $conditions = [];
    $params = [];
    $types = '';

$conditions[] = "status NOT IN ('Cancelled', 'Rejected') AND venue_id = ? AND booking_date = ? AND (
    (start_time < ? AND end_time > ?) OR 
    (start_time < ? AND end_time > ?) OR 
    (start_time >= ? AND start_time < ?) OR 
    (end_time > ? AND end_time <= ?)
)";


    $params[] = $venue_id;
    $params[] = $formattedDate;
    $params[] = $end_time;
    $params[] = $start_time;
    $params[] = $end_time;
    $params[] = $start_time;
    $params[] = $start_time;
    $params[] = $end_time;
    $params[] = $start_time;
    $params[] = $end_time;

    $query = "SELECT * FROM bookings WHERE " . implode(' AND ', $conditions);
    $stmt = $conn->prepare($query);
    $stmt->bind_param('isssssssss', ...$params);
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if there are any conflicting bookings
    if ($result->num_rows > 0) {
        $_SESSION['alert'] = "<script>
        Swal.fire({
            icon: 'info',
            title: 'Not Available',
            text: 'Sorry, the venue is not available for the selected dates and times.',
        })
        </script>";
    } else {
        $_SESSION['alert'] = "<script>
        Swal.fire({
            icon: 'success',
            title: 'Available',
            text: 'The venue is available for booking!',
        })
        </script>";
        $total =  $hours_difference * $loc['priceperhour'];
        $_SESSION['total_hours'] = number_format($hours_difference, 2);
        $_SESSION['total_amount'] = $total;
        $_SESSION['available_booking'] = true;
    }

    // Free the result set
    $result->free_result();

    // Redirect back to venue details page
    echo '<meta http-equiv="refresh" content="0;url=venue-details.php?venue_id=' . $venue_id . '">';
    exit();
}


if (isset($_POST['book_now'])) {
    // Check if user is logged in
    if (!isset($_SESSION['userid'])) {
        echo '<meta http-equiv="refresh" content="0;url=login.php">';
        exit();
    }

    $venue_id = $_GET['venue_id'];
    $start_time = $_POST['start_time'];
    $end_time = $_POST['end_time'];
    $total_capacity = $_POST['total_capacity'];
    if (!empty($start_time) && !empty($end_time) && !empty($book_date1)) {
        $start_timestamp = strtotime($start_time);
        $end_timestamp = strtotime($end_time);

        if ($end_timestamp < $start_timestamp) {
            $_SESSION['alert'] = "<script>
            Toast.fire({
                icon: 'info',
                title: 'End time cannot be earlier than start time.',
            });
        </script>";
            echo '<meta http-equiv="refresh" content="0;url=venue-details.php?venue_id=' . $venue_id . '">';
            exit();
        }
    }
    // Hours Difference
    $start_timestamp = strtotime($start_time);
    $end_timestamp = strtotime($end_time);
    $time_difference = $end_timestamp - $start_timestamp;
    $hours_difference = $time_difference / 3600;

    $book_date1 = isset($_POST['book_date']) ? $_POST['book_date'] : '';


    // Calculate number of days
    $dateObject = DateTime::createFromFormat('m.d.Y', $book_date1);
    $formattedDate = $dateObject->format('Y-m-d');
    $total_hours = $hours_difference;

    // Fetch venue price per hour from database
    $query = "SELECT priceperhour FROM venues WHERE venue_id = '$venue_id'";
    $result = $conn->query($query);
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $price_per_hour = $row['priceperhour'];
    } else {
        // Handle case where venue is not found
        $_SESSION['alert'] = "<script>
        Swal.fire({
            icon: 'error',
            title: 'Booking Error',
            text: 'Venue not found. Please try again later.',
        })
        </script>";
        echo '<meta http-equiv="refresh" content="0;url=venue-details.php?venue_id=' . $venue_id . '">';
        exit();
    }

    $totalprice =  $total_hours * $price_per_hour;
    // Get userid from session
    $userid = $_SESSION['userid'];

    // Start a transaction
    $conn->begin_transaction();

    try {
        // Insert booking record into bookings table
        $query = "INSERT INTO bookings (userid, venue_id, booking_date, start_time, end_time, total_hours, total_price, total_capacity, created_at) 
                  VALUES ('$userid', '$venue_id', '$formattedDate', '$start_time', '$end_time', $total_hours, '$totalprice', '$total_capacity', '$timestamp')";

        if ($conn->query($query) === TRUE) {
            // Get the last inserted booking_id
            $booking_id = $conn->insert_id;
            echo $booking_id;
            // Insert payment record into payments table
            $payment_status = 'Unpaid';
            $query = "INSERT INTO payments (booking_id, userid, status, amount) 
                      VALUES ('$booking_id', $userid, '$payment_status', '$totalprice')";
            echo $query;
            if ($conn->query($query) === TRUE) {
                // Commit the transaction
                $conn->commit();
                unset($_SESSION['start_time']);
                unset($_SESSION['end_time']);
                unset($_SESSION['book_date']);
                unset($_SESSION['available_booking']);
                // Booking and payment successful
                $_SESSION['alert'] = "<script>
                            Swal.fire({
                                icon: 'success',
                                title: 'Booking Successful',
                                text: 'Your booking has been added. Waiting for approval to pay',
                                allowOutsideClick: false,
                                allowEscapeKey: false,
                                allowEnterKey: false,
                                showConfirmButton: true,
                                confirmButtonText: 'OK'
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    window.location.href = 'booking.php';
                                }
                            });
                        </script>
                        ";
                echo '<meta http-equiv="refresh" content="0;url=venue-details.php?venue_id=' . $venue_id . '">';
                exit();
            } else {
                throw new Exception('Error inserting payment record.');
            }
        } else {
            throw new Exception('Error inserting booking record.');
        }
    } catch (Exception $e) {
        // Rollback the transaction
        $conn->rollback();

        // Error occurred while booking
        $_SESSION['alert'] = "<script>
        Swal.fire({
            icon: 'error',
            title: 'Booking Error',
            text: 'An error occurred while processing your booking. Please try again later.',
        })
        </script>";
        echo '<meta http-equiv="refresh" content="0;url=venue-details.php?venue_id=' . $venue_id . '">';
        exit();
    }
}

?>

<?php include('layouts/footer.php') ?>