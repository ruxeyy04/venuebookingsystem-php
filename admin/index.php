<?php include('layouts/header.php') ?>
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
  <h1 class="h2">Dashboard</h1>
</div>
<?php
// Total Rooms
$totalRoomsQuery = "SELECT COUNT(*) as total_venue FROM venues";
$totalRoomsResult = $conn->query($totalRoomsQuery);
$total_venue = $totalRoomsResult->fetch_assoc()['total_venue'];

// Clients
$totalClientsQuery = "SELECT COUNT(*) as total_clients FROM userinfo WHERE usertype = 'client'";
$totalClientsResult = $conn->query($totalClientsQuery);
$totalClients = $totalClientsResult->fetch_assoc()['total_clients'];

// Booked Rooms
$bookedRoomsQuery = "SELECT COUNT(*) as booked FROM bookings WHERE status = 'Approved'";
$bookedRoomsResult = $conn->query($bookedRoomsQuery);
$booked = $bookedRoomsResult->fetch_assoc()['booked'];

// Available Rooms
$availableRoomsQuery = "SELECT COUNT(*) as available_venue FROM venues WHERE status = 'Available'";
$availableRoomsResult = $conn->query($availableRoomsQuery);
$available_venue = $availableRoomsResult->fetch_assoc()['available_venue'];


?>

<div class="container">
  <div class="row">
    <div class="col-md-3">
      <div class="card">
        <div class="card-body">
          <h5 class="card-title"><i class="fa fa-bed"></i> Total Venue</h5>
          <p class="card-text"><?php echo $total_venue; ?></p>
        </div>
      </div>
    </div>
    <div class="col-md-3">
      <div class="card">
        <div class="card-body">
          <h5 class="card-title"><i class="fa fa-users"></i> Clients</h5>
          <p class="card-text"><?php echo $totalClients; ?></p>
        </div>
      </div>
    </div>
    <div class="col-md-3">
      <div class="card">
        <div class="card-body">
          <h5 class="card-title"><i class="fa fa-book"></i> Booked Venues</h5>
          <p class="card-text"><?php echo $booked; ?></p>
        </div>
      </div>
    </div>
    <div class="col-md-3">
      <div class="card">
        <div class="card-body">
          <h5 class="card-title"><i class="fa fa-check-circle"></i> Available Venue</h5>
          <p class="card-text"><?php echo $available_venue; ?></p>
        </div>
      </div>
    </div>
  </div>
</div>


<div class="container">
  <div class="row">
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
              <th>Status</th>
            </tr>
          </thead>
          <tbody>
            <?php
            $sql = "SELECT b.booking_id, CONCAT(u.fname, ' ', u.lname) AS client_name, v.venue_name, l.location_name, b.booking_date, b.start_time, b.end_time, TIMEDIFF(b.end_time, b.start_time) AS total_hours, b.total_price, (v.max_capacity - v.min_capacity) AS total_capacity, b.status
      FROM bookings b
      INNER JOIN venues v ON b.venue_id = v.venue_id
      INNER JOIN locations l ON v.loc_id = l.loc_id
      INNER JOIN userinfo u ON b.userid = u.userid
      ORDER BY FIELD(b.status, 'Pending', 'Approved', 'Cancelled', 'Rejected') ASC, b.created_at DESC";

            $result = mysqli_query($conn, $sql);

            ?>
            <?php
            if (mysqli_num_rows($result) > 0) {
              while ($row = mysqli_fetch_assoc($result)) {
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
                </tr>
            <?php
              }
            } else {
              echo '<tr><td colspan="11" class="text-center">No data available</td></tr>';
            }
            ?>


          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>

<?php include('layouts/footer.php') ?>