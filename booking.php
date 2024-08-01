<?php include('layouts/header.php') ?>
<div class="section big-55-height over-hide z-bigger">

    <div class="parallax parallax-top" style="background-image: url('img/gallery/10.jpg')"></div>
    <div class="dark-over-pages"></div>

    <div class="hero-center-section pages">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-12 parallax-fade-top">
                    <div class="hero-text">My Bookings</div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="section padding-top z-bigger">
    <div class="container">
        <div class="row justify-content-center padding-bottom-smaller">
                <div class="col-lg-3 m-4">
        <form action="" method="get">
    <label class="form-label">Reservation Filtering</label>
    <select name="status" class="wide" style="display: none;" onchange="this.form.submit()">
        <option value="">All</option>
        <option <?php if (isset($_GET['status']) && $_GET['status'] == 'Pending') echo 'selected'; ?> value="Pending">Pending</option>
        <option <?php if (isset($_GET['status']) && $_GET['status'] == 'Approved') echo 'selected'; ?> value="Approved">Approved</option>
        <option <?php if (isset($_GET['status']) && $_GET['status'] == 'Rejected') echo 'selected'; ?> value="Rejected">Rejected</option>
         <option <?php if (isset($_GET['status']) && $_GET['status'] == 'Cancelled') echo 'selected'; ?> value="Cancelled">Cancelled</option>
    </select>
    <div class="nice-select wide" tabindex="0" style="
    border: 1px solid black;
"><span class="current" style="color: black;"><?=isset($_GET['status']) ? $_GET['status'] : 'All'?></span>
        <ul class="list">
            <li data-value="Male" class="option">All</li>
            <li data-value="Pending" class="option <?php if (isset($_GET['status']) && $_GET['status'] == 'Pending') echo 'selected'; ?>">Pending</li>
            <li data-value="Approved" class="option <?php if (isset($_GET['status']) && $_GET['status'] == 'Approved') echo 'selected'; ?>">Approved</li>
            <li data-value="Rejected" class="option  <?php if (isset($_GET['status']) && $_GET['status'] == 'Rejected') echo 'selected'; ?>">Rejected</li>
            <li data-value="Cancelled" class="option <?php if (isset($_GET['status']) && $_GET['status'] == 'Cancelled') echo 'selected'; ?>">Cancelled</li>
        </ul>
    </div>
        </form>
    </div>
            <div class="col-md-12">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th scope="col">Booking ID</th>
                                <th scope="col">Venue Name</th>
                                <th scope="col">Location Name</th>
                                <th scope="col">Booking Date</th>
                                <th scope="col">Start Time</th>
                                <th scope="col">End Time</th>
                                <th scope="col">Total Hours</th>
                                <th scope="col">Total Price</th>
                                <th scope="col">Status</th>
                                <th scope="col">Payment Status</th>
                                <th scope="col">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $status = isset($_GET['status']) ? $_GET['status'] : '';
                            $sql = "SELECT 
                                        a.booking_id, a.venue_id, a.booking_date, a.start_time, a.end_time, 
                                        a.total_price, a.status AS book_status, a.total_hours,
                                        b.venue_name, 
                                        c.location_name, 
                                        p.status AS payment_status
                                    FROM 
                                        bookings a 
                                    INNER JOIN 
                                        venues b ON a.venue_id = b.venue_id 
                                    INNER JOIN 
                                        locations c ON b.loc_id = c.loc_id 
                                    LEFT JOIN 
                                        payments p ON a.booking_id = p.booking_id 
                                    WHERE 
                                        a.userid = '$userid'";
                                    if (!empty($status)) {
                                        $sql .= " AND b.status = '$status'";
                                    }
                
                                    $sql .= " ORDER BY FIELD(a.status, 'Pending', 'Approved','Cancelled', 'Rejected')  ASC, b.created_at desc";

                            $res = $conn->query($sql);

                            if ($res->num_rows > 0) {
                                while ($row = $res->fetch_assoc()) { ?>
                                    <tr>
                                        <td><?php echo $row['booking_id']; ?></td>
                                        <td><?php echo $row['venue_name']; ?></td>
                                        <td><?php echo $row['location_name']; ?></td>
                                        <td><?php echo date('F j, Y', strtotime($row['booking_date'])); ?></td>
                                        <td><?php echo date('g:i A', strtotime($row['start_time'])); ?></td>
                                        <td><?php echo date('g:i A', strtotime($row['end_time'])); ?></td>
                                        <td><?php echo $row['total_hours']; ?></td>
                                        <td><?php echo number_format($row['total_price'], 2); ?></td>
                                        <td><?php echo $row['book_status']; ?></td>
                                        <td><?php echo $row['payment_status']; ?></td>
                                        <td>
                                            <?php if ($row['book_status'] == 'Approved' && $row['payment_status'] != 'Paid') { ?>
                                                <button type="button" class="btn btn-primary" onclick="confirmPayment(<?php echo $row['booking_id']; ?>)">Pay</button>
                                            <?php } ?>
                                            <?php if ($row['book_status'] == 'Pending') { ?>
                                                <button type="button" class="btn btn-danger" onclick="confirmCancellation(<?php echo $row['booking_id']; ?>)">Cancel</button>
                                            <?php } ?>
                                            <!--<?php if ($row['payment_status'] == 'Paid') { ?>-->
                                            <!--    <button type="button" class="btn btn-warning" onclick="confirmRefund(<?php echo $row['booking_id']; ?>)">Refund</button>-->
                                            <!--<?php } ?>-->
                                        </td>
                                    </tr>
                            <?php }
                            } ?>
                        </tbody>
                    </table>

                    <script>
                        function confirmPayment(bookingId) {
                            Swal.fire({
                                title: 'Confirm Payment',
                                text: "Are you sure you want to proceed with the payment Booking#" + bookingId + "?",
                                icon: 'question',
                                showCancelButton: true,
                                confirmButtonColor: '#3085d6',
                                cancelButtonColor: '#d33',
                                confirmButtonText: 'Confirm'
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    window.location.href = 'process_payment.php?booking_id=' + bookingId;
                                }
                            });
                        }

                        function confirmCancellation(bookingId) {
                            Swal.fire({
                                title: 'Confirm Cancellation',
                                text: "Are you sure you want to cancel this booking?",
                                icon: 'warning',
                                showCancelButton: true,
                                confirmButtonColor: '#d33',
                                cancelButtonColor: '#3085d6',
                                confirmButtonText: 'Yes, cancel it!'
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    window.location.href = 'cancel_booking.php?booking_id=' + bookingId;
                                }
                            });
                        }

                        function confirmRefund(bookingId) {
                            Swal.fire({
                                title: 'Confirm Refund',
                                text: "Are you sure you want to request a refund?",
                                icon: 'question',
                                showCancelButton: true,
                                confirmButtonColor: '#3085d6',
                                cancelButtonColor: '#d33',
                                confirmButtonText: 'Yes, refund it!'
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    window.location.href = 'process_refund.php?booking_id=' + bookingId;
                                }
                            });
                        }
                    </script>


                </div>
            </div>
        </div>
    </div>
</div>
<?php include('layouts/footer.php') ?>