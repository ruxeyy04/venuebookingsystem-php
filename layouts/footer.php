	<!-- Footer -->

	<div class="section padding-top-bottom-small background-black over-hide footer">
		<div class="container">
			<div class="row">
				<div class="col-md-3 text-center text-md-left">
					<img src="img/logo.png" alt="">
					<p class="color-grey mt-4">Ozamiz City<br>Misamis Occidental, 7200</p>
				</div>
				<div class="col-md-4 text-center text-md-left">
					<h6 class="color-white mb-3">Pages</h6>
					<a href="home.php">Home</a>
					<a href="about.php">About</a>
					<a href="venue.php">Venue</a>
					<a href="contact.php">Contact</a>
				</div>
				<div class="col-md-5 mt-4 mt-md-0 text-center text-md-left logos-footer">
					<h6 class="color-white mb-3">about us</h6>
					<p class="color-grey mb-4">Welcome to Espares, your premier destination for seamless venue booking experiences. Whether you're planning a wedding, corporate event, concert, or private party, Espares offers an extensive range of stunning venues to suit every occasion and budget.</p>

				</div>
			</div>
		</div>
	</div>

	<div class="section py-4 background-dark over-hide footer-bottom">
		<div class="container">
			<div class="row">
				<div class="col-md-6 text-center text-md-left mb-2 mb-md-0">
					<p>2024 © Lance Espares | ITP4</p>
				</div>
				<div class="col-md-6 text-center text-md-right">
					<a href="#" class="social-footer-bottom">Facebook</a>
					<a href="#" class="social-footer-bottom">Twitter</a>
					<a href="#" class="social-footer-bottom">Instagram</a>
				</div>
			</div>
		</div>
	</div>
	<!-- End Footer -->

	<div class="scroll-to-top"></div>

	<?php
	if (isset($_SESSION['alert'])) {
		echo $_SESSION['alert'];
		unset($_SESSION['alert']);
	}
	?>
	<!-- JAVASCRIPT
    ================================================== -->
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.min.js"></script>
	<script src="js/plugins.js"></script>
	<?php 
	if ($current_page == 'index.php' || $current_page == 'about.php') { ?>
	<script src="js/reveal-home.js"></script>
	<?php }
	?>

	<script src="js/custom.js"></script>
	<!-- End Document
================================================== -->
	</body>


	</html>