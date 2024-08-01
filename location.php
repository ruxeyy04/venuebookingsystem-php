<?php include('layouts/header.php') ?>
<div class="section big-55-height over-hide z-bigger">

	<div class="parallax parallax-top" style="background-image: url('/location/location_cover.jpg')"></div>
	<div class="dark-over-pages"></div>

	<div class="hero-center-section pages">
		<div class="container">
			<div class="row justify-content-center">
				<div class="col-12 parallax-fade-top">
					<div class="hero-text">Different Locations</div>
				</div>
			</div>
		</div>
	</div>
</div>

<div class="section padding-top-bottom over-hide background-grey">
	<div class="container mb-5">
		<form action="" method="get">
			<div class="row d-flex justify-content-center">
				<div class="col-md-4">
					<input type="text" name="search" class="form-control" value="<?= isset($_GET['search']) ? $_GET['search'] : '' ?>" placeholder="Search Venue...">
				</div>
				<div class="col-md-2">
					<button class="btn btn-primary" type="submit">Search</button>
				</div>
			</div>
		</form>
	</div>
	<div class="container">
		<div class="row justify-content-center">
			<?php
			$items_per_page = 6;
			$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
			$offset = ($page - 1) * $items_per_page;

			$conditions = [];
			$params = [];
			$types = '';
			$search_term = isset($_GET['search']) ? $_GET['search'] : '';
			if (!empty($search_term)) {
				$like_search_term = "%" . $search_term . "%";
				$conditions[] = "l.location_name LIKE ?";
				$params[] = $like_search_term;
				$types .= 's';
			}

			$where = !empty($conditions) ? 'WHERE ' . implode(' AND ', $conditions) : '';

			$total_items_query = "SELECT COUNT(*) as total FROM locations l 
                      INNER JOIN venues v ON v.loc_id = l.loc_id 
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

			$query = "SELECT l.*, l.image AS location_image, COUNT(v.venue_id) AS total_venue 
			FROM locations l 
			LEFT JOIN venues v ON v.loc_id = l.loc_id 
			$where 
			GROUP BY l.loc_id 
			LIMIT ?, ?";
			$stmt = $conn->prepare($query);
			if (!empty($params)) {
				$stmt->bind_param($types, ...$params);
			}
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
							<div style="background-image: url('/location/<?= $row['location_image'] ?>'); background-position: center;
                                background-size: cover;
                                height: 234px;
                                width: 100%;">
							</div>
							<div class="room-box-in">
								<h5 class="mt-3"><?= (strlen($row['location_name']) > 15 ? substr($row['location_name'], 0, 15) . '...' : $row['location_name']) ?></h5>
								<p class="mt-3"><?= (strlen($row['description']) > 50 ? substr($row['description'], 0, 50) . '...' : $row['description']) ?></p>
								<a class="mt-1 btn btn-primary" href="venue.php?loc_id=<?= $row['loc_id'] ?>">Total Venue: <?= $row['total_venue'] ?></a>
								<div class="room-icons mt-4 pt-4">
									<a href="location-details.php?loc_id=<?= $row['loc_id'] ?>">full Location Info</a>
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