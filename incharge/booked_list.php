<?php include('layouts/header.php') ?>
<div class="row">
                <div class="col-lg-3 m-4">
                <form action="" method="get">
                <select class="form-control" name="status" onchange="this.form.submit()">
                    <option value="">All</option>
                    <option <?php if (isset($_GET['status']) && $_GET['status'] == 'Pending') echo 'selected'; ?> value="Pending">Pending</option>
                    <option <?php if (isset($_GET['status']) && $_GET['status'] == 'Approved') echo 'selected'; ?> value="Approved">Approved</option>
                    <option <?php if (isset($_GET['status']) && $_GET['status'] == 'Rejected') echo 'selected'; ?> value="Rejected">Rejected</option>
                    <option <?php if (isset($_GET['status']) && $_GET['status'] == 'Cancelled') echo 'selected'; ?> value="Cancelled">Cancelled</option>
                </select>
                </form>
            </div>
    <div class="col-md-12">
        <h2>Recent Bookings</h2>
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Book#</th>
                        <th>Client Name</th>
                        <th>Venue Name</th>
                        <th>Location</th>
                        <th>Booked Date</th>
                        <th>Start Time</th>
                        <th>End Time</th>
                        <th>Total Hours</th>
                        <th>Total Price</th>
                        <th>Total Capacity</th>
                        <th>Booked Status</th>
                        <th>Payment Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                     $status = isset($_GET['status']) ? $_GET['status'] : '';
                    $sql = "SELECT 
                                b.booking_id, 
                                CONCAT(u.fname, ' ', u.lname) AS client_name, 
                                v.venue_name, 
                                l.location_name, 
                                b.booking_date, 
                                b.start_time, 
                                b.end_time, 
                                TIMEDIFF(b.end_time, b.start_time) AS total_hours, 
                                b.total_price, 
                                (v.max_capacity - v.min_capacity) AS total_capacity, 
                                b.status,
                                p.status AS payment_status
                            FROM 
                                bookings b
                            INNER JOIN 
                                venues v ON b.venue_id = v.venue_id
                            INNER JOIN 
                                locations l ON v.loc_id = l.loc_id
                            INNER JOIN 
                                userinfo u ON b.userid = u.userid
                            LEFT JOIN 
                                payments p ON b.booking_id = p.booking_id";
                        if (!empty($status)) {
                            $sql .= " WHERE b.status = '$status'";
                        } 
                        
                        $sql .= " ORDER BY FIELD(b.status, 'Pending', 'Approved','Cancelled', 'Rejected')  ASC, b.created_at desc";
                            

                    $result = mysqli_query($conn, $sql);

                    if (mysqli_num_rows($result) > 0) {
                        while ($row = mysqli_fetch_assoc($result)) {
                            $approvedmodal = 'approvedmodal_' . $row['booking_id'];
                            $rejectmodal = 'rejectmodal_' . $row['booking_id'];
                    ?>
                            <tr>
                                <td><?php echo $row["booking_id"]; ?></td>
                                <td><?php echo $row["client_name"]; ?></td>
                                <td><?php echo $row["venue_name"]; ?></td>
                                <td><?php echo $row["location_name"]; ?></td>
                                <td><?php echo date('F j, Y', strtotime($row['booking_date'])); ?></td>
                                <td><?php echo date('g:i A', strtotime($row['start_time'])); ?></td>
                                <td><?php echo date('g:i A', strtotime($row['end_time'])); ?></td>
                                <td><?php echo $row["total_hours"]; ?></td>
                                <td>₱<?php echo number_format($row["total_price"], 2); ?></td>
                                <td><?php echo $row["total_capacity"]; ?></td>
                                <td><?php echo $row["status"]; ?></td>
                                <td><?php echo $row["payment_status"]; ?></td>
                                <td>
                                    <?php
                                    if ($row["status"] == 'Pending') { ?>
                                        <button class="btn btn-success" data-toggle="modal" data-target="#<?= $approvedmodal ?>">Approve</button>
                                        <button class="btn btn-danger" data-toggle="modal" data-target="#<?= $rejectmodal ?>">Reject</button>
                                    <?php
                                    } ?>
                                </td>
                            </tr>
                            <!-- Modal For Reject -->
                            <div class="modal fade" id="<?= $rejectmodal ?>" tabindex="-1" role="dialog" aria-labelledby="rejectModalLabel" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="rejectModalLabel">Reject Booking</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            Are you sure you want to reject this booking?
                                        </div>
                                        <div class="modal-footer">
                                            <form action="" method="post">
                                                <input type="hidden" name="book_id" value="<?= $row["booking_id"] ?>">
                                                <input type="hidden" name="status" value="Rejected">
                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                                <button type="submit" class="btn btn-primary" name="update_status">Confirm Reject</button>
                                            </form>

                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- Modal For Approve -->
                            <div class="modal fade" id="<?= $approvedmodal ?>" tabindex="-1" role="dialog" aria-labelledby="approveModalLabel" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="approveModalLabel">Approve Booking</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            Are you sure you want to approve this booking?
                                        </div>
                                        <div class="modal-footer">
                                            <form action="" method="post">
                                                <input type="hidden" name="book_id" value="<?= $row["booking_id"] ?>">
                                                <input type="hidden" name="status" value="Approved">
                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                                <button type="submit" class="btn btn-primary" name="update_status">Confirm Approve</button>
                                            </form>

                                        </div>
                                    </div>
                                </div>
                            </div>
                    <?php
                        }
                    } else {
                        echo '<tr><td colspan="12" class="text-center">No data available</td></tr>';
                    }
                    ?>

                </tbody>
            </table>
        </div>
    </div>
</div>

<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_status'])) {
    $book_id = $_POST['book_id'];
    $status = $_POST['status'];

    $update_sql = "UPDATE bookings SET status = ? WHERE booking_id = ?";
    if ($stmt = mysqli_prepare($conn, $update_sql)) {
        mysqli_stmt_bind_param($stmt, 'si', $status, $book_id);
        if (mysqli_stmt_execute($stmt)) {
            $_SESSION['alert'] = "<script>
                                Toast.fire({
                                    icon: 'success',
                                    title: 'Book status updated successfully.',
                                });
                            </script>";
            echo '<meta http-equiv="refresh" content="0;url=booked_list.php">';
            exit();
        } else {
            $_SESSION['alert'] = "<script>
                                Toast.fire({
                                    icon: 'success',
                                    title: 'Error updating book status: " . mysqli_error($conn) . "',
                                });
                            </script>";
            echo '<meta http-equiv="refresh" content="0;url=booked_list.php">';
            exit();
        }
        mysqli_stmt_close($stmt);
    } else {
        $_SESSION['alert'] = "<script>
                            Toast.fire({
                                icon: 'success',
                                title: 'Error preparing statement: " . mysqli_error($conn) . "',
                            });
                        </script>";
        echo '<meta http-equiv="refresh" content="0;url=booked_list.php">';
        exit();
    }
}
?>

<?php include('layouts/footer.php') ?>