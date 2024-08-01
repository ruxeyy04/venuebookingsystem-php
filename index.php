<?php include('layouts/header.php') ?>
<div class="section hero-full-height over-hide">

	<div id="poster_background"></div>
	<div id="video-wrap" class="parallax-top">
		<video id="video_background" preload="auto" autoplay loop="loop" muted="muted" poster="img/trans.png">
			<source src="video/video.mp4" type="video/mp4">
		</video>
	</div>
	<div class="dark-over-video"></div>

	<div class="hero-center-section ver-2">
		<div class="container">
			<div class="row justify-content-center">
				<div class="col-12 parallax-fade-top">
					<div class="hero-text-ver-2">Welcome to<br>Espares</div>
				</div>
			</div>
		</div>
	</div>
</div>

<div class="section background-dark z-too-big">
	<div class="container">
		<div class="row">
			<div class="col-md-12">
	
				<form action="venue.php" method="get">
					<div class="row justify-content-center home-translate">
						<div class="col-5 no-mob">
							<div class="row">
								<div class="col-6">
									<input type="time" value="<?= isset($_GET['start_time']) ? $_GET['start_time'] : '' ?>" name="start_time" class="form-control" required="" style="height: 42px;
                                    border: 1px solid #5e5e5e4a;color: white;">
								</div>
								<div class="col-6">
									<input type="time" value="<?= isset($_GET['end_time']) ? $_GET['end_time'] : '' ?>" name="end_time" class="form-control" required="" style="height: 42px;
                                    border: 1px solid #5e5e5e4a;color: white;">
								</div>
							</div>
						</div>
						<div class="col-3 no-mob">
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
						<div class="col-2 no-mob">
							<div class="row">
								<div class="col-12">
									<input type="number" value="<?= isset($_GET['total']) ? $_GET['total'] : '' ?>" name="total" class="form-control" required="" style="height: 42px;
                                    border: 1px solid #5e5e5e4a;color: white;" placeholder="Total Persons">
								</div>
							</div>
						</div>
						<div class="col-6  col-sm-4 col-lg-2">

							<?php
							if (isset($_GET['total']) || isset($_GET['end_time']) || isset($_GET['start_time']) || isset($_GET['book_date'])) { ?>
								<div class="btn-group">
									<button class="booking-button" type="submit">Check</button>
									<button class="booking-button" type="button" onclick="window.location = 'venue.php'">Clear</button>
								</div>
							<?php	} else { ?>
								<button class="booking-button" type="submit">Check</button>
							<?php	}
							?>


						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>

<div class="section padding-top-bottom over-hide">
	<div class="container">
		<div class="row">
			<div class="col-md-6 align-self-center">
				<div class="row justify-content-center">
					<div class="col-10">
						<div class="subtitle text-center mb-4">Espares Venue Booking</div>
						<h2 class="text-center">Experience Unforgettable Moments with Espares!</h2>
						<p class="text-center mt-5">Book your event at Espares and create memories that will last a lifetime! Our stunning venue, exceptional service, and attention to detail will ensure your special occasion is truly extraordinary. Whether it's a wedding, corporate event, or celebration, we provide the perfect setting to make your event spectacular. Reserve your date today and let us help you celebrate in style!</p>
					</div>
				</div>
			</div>
			<div class="col-md-6 mt-4 mt-md-0">
				<div class="img-wrap">
					<img src="img/rooms.png" alt="">
				</div>
			</div>
		</div>
	</div>
</div>

<div class="section background-grey over-hide">
	<div class="container-fluid px-0">
		<div class="row mx-0">
			<div class="col-xl-6 px-0">
				<div class="img-wrap" id="rev-1">
					<img src="img/royalgarden.jpg" alt="">
					<div class="text-element-over">Royal Garden</div>
				</div>
			</div>
			<div class="col-xl-6 px-0 mt-4 mt-xl-0 align-self-center">
				<div class="row justify-content-center">
					<div class="col-10 col-xl-8 text-center">
						<h3 class="text-center">Royal Garden</h3>
						<p class="text-center mt-4">Here at the Royal Garden Hotel, we welcome you with contemporary style and warm hospitality.</p>
						<a class="mt-5 btn btn-primary" href="venue.php">check availability</a>
					</div>
				</div>
			</div>
		</div>
		<div class="row mx-0">
			<div class="col-xl-6 px-0 mt-4 mt-xl-0 pb-5 pb-xl-0 align-self-center">
				<div class="row justify-content-center">
					<div class="col-10 col-xl-8 text-center">
						<h3 class="text-center">Be Palace Hotel</h3>
						<p class="text-center mt-4">An intimate and charming atmosphere. The Be Palace Hotel is the right choice for visitors who are searching for a combination of charm, peace and quiet.</p>
						<a class="mt-5 btn btn-primary" href="venue.php">check availability</a>
					</div>
				</div>
			</div>
			<div class="col-xl-6 px-0 order-first order-xl-last mt-5 mt-xl-0">
				<div class="img-wrap" id="rev-2">
					<img src="img/bepalace.jpg" alt="">
					<div class="text-element-over">Be Palace Hotel</div>
				</div>
			</div>
		</div>
	</div>
</div>


<div class="section padding-top-bottom over-hide background-grey">
	<div class="container">
		<div class="row justify-content-center">
			<div class="col-md-8 align-self-center">
				<div class="subtitle with-line text-center mb-4">Venue</div>
				<h3 class="text-center padding-bottom-small">Our Available Venue Location</h3>
			</div>
			<div class="section clearfix"></div>
			<?php

			$sql = "SELECT a.*, COUNT(b.venue_id) AS total_venue 
			FROM locations a 
			LEFT JOIN venues b ON b.loc_id = a.loc_id 
			GROUP BY a.loc_id 
			ORDER BY RAND() 
			LIMIT 6";
			$res = $conn->query($sql);

			if ($res->num_rows > 0) {
				while ($row = $res->fetch_assoc()) {
			?>
					<div class="col-lg-4 mt-4 mt-md-0" data-scroll-reveal="enter bottom move 50px over 0.7s after 0.2s">
						<div class="room-box background-white">
							<div class="room-name"><?= $row['location_name'] ?></div>
							<div style="background-image: url('/location/<?= $row['image'] ?>'); background-position: center;
                                background-size: cover;
                                height: 234px;
                                width: 100%;">
							</div>
							<div class="room-box-in">
								<h5 class=""><?= (strlen($row['location_name']) > 15 ? substr($row['location_name'], 0, 15) . '...' : $row['location_name']) ?></h5>
								<p class="mt-3"><?= (strlen($row['description']) > 50 ? substr($row['description'], 0, 50) . '...' : $row['description']) ?></p>
								<a class="mt-1 btn btn-primary" href="venue.php?loc_id=<?= $row['loc_id'] ?>">Total Venue: <?= $row['total_venue'] ?></a>
								<div class="room-icons mt-4 pt-4">
									<a href="location-details.php?loc_id=<?= $row['loc_id'] ?>">full info</a>
								</div>
							</div>
						</div>
					</div>
			<?php    }
			}
			?>

		</div>
	</div>
</div>

<div class="section padding-top z-bigger">
	<div class="container">
		<div class="row justify-content-center padding-bottom-smaller">
			<div class="col-md-8">
				<div class="subtitle with-line text-center mb-4">get in touch</div>
				<h3 class="text-center padding-bottom-small">drop us a line</h3>
			</div>
			<div class="section clearfix"></div>
			<div class="col-md-6 col-lg-4">
				<div class="address">
					<div class="address-in text-left">
						<p class="color-black">Address:</p>
					</div>
					<div class="address-in text-right">
						<p>Ozamiz City, Misamis Occidental, 7200</p>
					</div>
				</div>
				<div class="address">
					<div class="address-in text-left">
						<p class="color-black">City:</p>
					</div>
					<div class="address-in text-right">
						<p>Ozamiz City</p>
					</div>
				</div>
				<div class="address">
					<div class="address-in text-left">
						<p class="color-black">Open Hours:</p>
					</div>
					<div class="address-in text-right">
						<p>8:00 am</p>
					</div>
				</div>
			</div>
			<div class="col-md-6 col-lg-4">
				<div class="address">
					<div class="address-in text-left">
						<p class="color-black">Phone:</p>
					</div>
					<div class="address-in text-right">
						<p>+63912345678</p>
					</div>
				</div>
				<div class="address">
					<div class="address-in text-left">
						<p class="color-black">Email:</p>
					</div>
					<div class="address-in text-right">
						<p>espares@gmail.com</p>
					</div>
				</div>
				<div class="address">
					<div class="address-in text-left">
						<p class="color-black">Close Hour:</p>
					</div>
					<div class="address-in text-right">
						<p>5:00 pm</p>
					</div>
				</div>
			</div>
			<div class="section clearfix"></div>
			<div class="col-md-8 text-center mt-5" data-scroll-reveal="enter bottom move 50px over 0.7s after 0.2s">
				<p class="mb-0"><em>available at: 8am - 5pm</em></p>
				<h2 class="text-opacity">+63912345678</h2>
			</div>
		</div>
	</div>
</div>
<?php include('layouts/footer.php') ?>