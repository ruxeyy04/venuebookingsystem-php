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
if (!isset($_GET['loc_id']) && !isset($_GET['loc_id'])) {
    echo '<meta http-equiv="refresh" content="0;url=venue.php">';
    exit;
}

$loc_id = isset($_GET['loc_id']) ? (int)$_GET['loc_id'] : null;

if ($loc_id !== null) {
    $sql = "SELECT a.*, COUNT(b.venue_id) AS total_venue 
			FROM locations a 
			LEFT JOIN venues b ON b.loc_id = a.loc_id  WHERE a.loc_id = ?
			GROUP BY a.loc_id";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $loc_id);
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
if ($loc_id !== null) {
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
                                <img src="/location/<?= $loc['image'] ?>" alt="">
                            </div>
                        </div>
                    </div>
                    <div class="section pt-5">
                        <h6><?= $loc['location_name'] ?></h6>
                    </div>
                    <div class="section pt-5">
                        <h5>discription</h5>
                        <p class="mt-3"><?= $loc['description'] ?></p>
                    </div>
                    <div class="section pt-4">
                        <div class="row">
                            <div class="col-12">
                                <h5 class="mb-3">Available Venue</h5>
                            </div>
                            <div class="col-lg-6">
                                <p><strong class="color-black">Venue:</strong> <?= $loc['total_venue'] ?></p>
                            </div>
                        </div>
                    </div>
                    <div class="section pt-4">
                        <h5>Address</h5>
                        <p class="mt-3"><?= $loc['address'] ?></p>
                    </div>
                </div>
                <div class="col-lg-4 order-first order-lg-last">
                    <form action="venue.php" method="get">
                        <input type="hidden" name="loc_id" value="<?= $loc['image'] ?>">
                        <div class="section background-dark p-4">
                            <div class="row">
                                <div class="col-12">
                                    <label for="">Start Time</label>
                                    <input type="time" value="<?= isset($_GET['start_time']) ? $_GET['start_time'] : '' ?>" name="start_time" class="form-control" required="" style="height: 42px;
                                    border: 1px solid #5e5e5e4a;color: white;">
                                </div>
                                <div class="col-12">
                                    <label for="">End Time</label>
                                    <input type="time" value="<?= isset($_GET['end_time']) ? $_GET['end_time'] : '' ?>" name="end_time" class="form-control" required="" style="height: 42px;
                                    border: 1px solid #5e5e5e4a;color: white;">
                                </div>
                                <div class="col-12">
                                    <label for="">Date</label>
                                    <div class="input-daterange input-group" id="flight-datepicker">
                                        <div class="row">
                                            <div class="col-12">
                                                <div class="form-item">
                                                    <span class="fontawesome-calendar"></span>
                                                    <input class="input-sm" type="text" autocomplete="off" id="start-date-1" name="book_date" placeholder="booking date" data-date-format="DD, MM d" value="<?= isset($_GET['book_date']) ? $_GET['book_date'] : '' ?>">
                                                    <span class="date-text date-depart"></span>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <label for="">Total Persons</label>
                                    <input type="number" value="" name="total" class="form-control" required="" style="color: white;border: 1px solid #5e5e5e4a;height: 42px;" placeholder="Total Persons">
                                </div>
                                <div class="col-12 pt-4">
                                    <button class="booking-button" type="submit">Check availability</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="section padding-bottom over-hide">
    <div class="container">
        <div class="row justify-content-center">
            <?php
            $items_per_page = 6;
            $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
            $offset = ($page - 1) * $items_per_page;

            $search_term = isset($_GET['search']) ? $_GET['search'] : '';
            $loc_id = isset($_GET['loc_id']) ? $_GET['loc_id'] : '';
            $start_time = isset($_GET['start_time']) ? $_GET['start_time'] : '';
            $end_time = isset($_GET['end_time']) ? $_GET['end_time'] : '';
            $book_date1 = isset($_GET['book_date']) ? $_GET['book_date'] : '';
            $total_guests = isset($_GET['total']) ? (int)$_GET['total'] : 0;

            $conditions = [];
            $params = [];
            $types = '';

            if (!empty($search_term)) {
                $like_search_term = "%" . $search_term . "%";
                $conditions[] = "(v.venue_name LIKE ? OR l.location_name LIKE ?)";
                $params[] = $like_search_term;
                $params[] = $like_search_term;
                $types .= 'ss';
            }
            if (!empty($start_time) && !empty($end_time) && !empty($book_date1)) {
                // Convert start_time and end_time to 24-hour format timestamps for comparison
                $start_timestamp = strtotime($start_time);
                $end_timestamp = strtotime($end_time);

                if ($end_timestamp < $start_timestamp) {
                    $_SESSION['alert'] = "<script>
					Toast.fire({
						icon: 'info',
						title: 'End time cannot be earlier than start time.',
					});
				</script>";
                    echo '<meta http-equiv="refresh" content="0;url=venue.php">';
                    exit();
                } else {
                    // Proceed with your logic if times are valid
                }
            }

            if (!empty($start_time) && !empty($end_time) && !empty($book_date1)) {
                // Convert book_date to Y-m-d format
                $dateObject = DateTime::createFromFormat('m.d.Y', $book_date1);
                $formattedDate = $dateObject->format('Y-m-d');

                // Check for overlapping bookings
                $conditions[] = "v.venue_id NOT IN (
					SELECT b.venue_id FROM bookings b 
					WHERE b.booking_date = ? AND (
						(b.start_time < ? AND b.end_time > ?) OR 
						(b.start_time < ? AND b.end_time > ?) OR 
						(b.start_time >= ? AND b.start_time < ?) OR 
						(b.end_time > ? AND b.end_time <= ?)
					)
				)";

                $params[] = $formattedDate;

                $params[] = $end_time;
                $params[] = $start_time;
                $params[] = $end_time;
                $params[] = $start_time;
                $params[] = $start_time;
                $params[] = $end_time;
                $params[] = $start_time;
                $params[] = $end_time;
                $types .= 'sssssssss';
            }

            if ($total_guests > 0) {
                $conditions[] = "v.max_capacity >= ?";
                $params[] = $total_guests;
                $types .= 'i';
            }
            if (!empty($loc_id)) {
                $conditions[] = "l.loc_id = ?";
                $params[] = $loc_id;
                $types .= 's';
            }
            $where = !empty($conditions) ? 'WHERE ' . implode(' AND ', $conditions) : '';

            $total_items_query = "SELECT COUNT(*) as total FROM venues v 
                      INNER JOIN locations l ON v.loc_id = l.loc_id 
                      $where";
            $stmt = $conn->prepare($total_items_query);
            if (!empty($params)) {
                $stmt->bind_param($types, ...$params);
            }
            $stmt->execute();
            $total_items_result = $stmt->get_result();
            $total_items_row = $total_items_result->fetch_assoc();
            $total_items = $total_items_row['total'];
            $stmt->close();

            $total_pages = ceil($total_items / $items_per_page);

            $params[] = $offset;
            $params[] = $items_per_page;
            $types .= 'ii';

            $query = "SELECT v.*, l.*, v.image AS venue_image FROM venues v 
          INNER JOIN locations l ON v.loc_id = l.loc_id 
          $where 
          LIMIT ?, ?";
            $stmt = $conn->prepare($query);
            $stmt->bind_param($types, ...$params);
            $stmt->execute();
            $result = $stmt->get_result();
            ?>

            <?php
            if ($result->num_rows > 0) {
                $counter = 0;
                $row_counter = 1;
                while ($row = $result->fetch_assoc()) {
                    $counter++;
                    $mt_classes = '';
                    $scroll_reveal = '';
                    $delay = 0.2 * $counter;
                    if ($row_counter == 1) {
                        $mt_classes = 'mt-4 mt-md-0';
                    } else {
                        $mt_classes = 'mt-4';
                    }

                    $scroll_reveal = 'data-scroll-reveal="enter bottom move 50px over 0.7s after ' . $delay . 's"';

            ?>
                    <div class="col-md-4 <?= $mt_classes ?>" <?= $scroll_reveal ?>>
                        <div class="room-box background-white">
                            <div class="room-name"><?= $row['location_name'] ?></div>
                            <div style="background-image: url('/venue/<?= $row['venue_image'] ?>'); background-position: center;
                                background-size: cover;
                                height: 234px;
                                width: 100%;">
                            </div>
                            <div class="room-box-in">
                                <h5 class="mt-3"><?= (strlen($row['venue_name']) > 15 ? substr($row['venue_name'], 0, 15) . '...' : $row['venue_name']) ?></h5>
                                <p class="mt-3"><?= (strlen($row['description']) > 50 ? substr($row['description'], 0, 50) . '...' : $row['description']) ?></p>
                                <a class="mt-1 btn btn-primary" href="#!">₱ <?= number_format($row['priceperhour'], 2) ?> p/ hour</a>
                                <div class="room-icons mt-4 pt-4">
                                    <a href="vanue-details.php?venue_id=<?= $row['venue_id'] ?>">full info</a>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php
                    // Reset counter and increment row counter after third column
                    if ($counter == 3) {
                        $counter = 0;
                        $row_counter++;
                    }
                }
            } else { ?>
                <div class="col-md-12">
                    <h3 class="text-center">No Venue Available</h3>
                </div>
            <?php }
            ?>



            <div class="col-lg-12 d-flex justify-content-center mt-4">
                <div class="pagination">
                    <?php if ($page > 1) { ?>
                        <a class="btn btn-primary ml-1 mr-1" href="?<?= http_build_query(array_merge($_GET, ['page' => $page - 1])) ?>"><i class="fa fa-long-arrow-left"></i> Prev</a>
                    <?php } ?>

                    <?php if ($total_pages <= 5) { ?>
                        <?php for ($i = 1; $i <= $total_pages; $i++) { ?>
                            <a class="btn btn-primary ml-1 mr-1 <?= $page == $i ? 'btn-active' : '' ?>" href="?<?= http_build_query(array_merge($_GET, ['page' => $i])) ?>"><?= $i ?></a>
                        <?php } ?>
                    <?php } else { ?>
                        <?php if ($page <= 3) { ?>
                            <?php for ($i = 1; $i <= 3; $i++) { ?>
                                <a class="btn btn-primary ml-1 mr-1 <?= $page == $i ? 'btn-active' : '' ?>" href="?<?= http_build_query(array_merge($_GET, ['page' => $i])) ?>"><?= $i ?></a>
                            <?php } ?>
                            <li><span>...</span></li>
                            <li><a href="?<?= http_build_query(array_merge($_GET, ['page' => $total_pages])) ?>"><?= $total_pages ?></a></li>
                        <?php } elseif ($page >= $total_pages - 2) { ?>
                            <a class="btn btn-primary ml-1 mr-1" href="?<?= http_build_query(array_merge($_GET, ['page' => 1])) ?>">1</a>
                            <a class="btn btn-primary ml-1 mr-1" href="#!">...</a>
                            <?php for ($i = $total_pages - 2; $i <= $total_pages; $i++) { ?>
                                <a class="<?= $page == $i ? 'btn-active' : '' ?> btn btn-primary ml-1 mr-1" href="?<?= http_build_query(array_merge($_GET, ['page' => $i])) ?>"><?= $i ?></a>
                            <?php } ?>
                        <?php } else { ?>
                            <li><a class="btn btn-primary ml-1 mr-1" href="?<?= http_build_query(array_merge($_GET, ['page' => 1])) ?>">1</a></li>
                            <a class="btn btn-primary ml-1 mr-1" href="#!">...</a>
                            <?php for ($i = $page - 1; $i <= $page + 1; $i++) { ?>
                                <a class="<?= $page == $i ? 'btn-active' : '' ?> btn btn-primary ml-1 mr-1" href="?<?= http_build_query(array_merge($_GET, ['page' => $i])) ?>"><?= $i ?></a>
                            <?php } ?>
                            <a class="btn btn-primary ml-1 mr-1" href="#!">...</a>
                            <a class="btn btn-primary ml-1 mr-1" href="?<?= http_build_query(array_merge($_GET, ['page' => $total_pages])) ?>"><?= $total_pages ?></a>
                        <?php } ?>
                    <?php } ?>

                    <?php if ($page < $total_pages) { ?>
                        <a class="btn btn-primary ml-1 mr-1" href="?<?= http_build_query(array_merge($_GET, ['page' => $page + 1])) ?>">Next <i class="fa fa-long-arrow-right"></i></a>
                    <?php } ?>
                </div>
            </div>

        </div>
    </div>
</div>
<?php include('layouts/footer.php') ?>