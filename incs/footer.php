
		<footer class="footer-distributed">

			<div class="footer-left">

				<a class="" href="./">
					<div style="width: 400px;">
						<img src="<?= BASE_URL ?>img/monuments.png" class="img-responsive" style="float: left; margin-top: -.4em;">
						<span style="padding-left: .2em; float: left;"><h3>rk Inc!</h3></span>
					</div>
				</a>

				<br style="clear: both;">

				<p class="footer-links">
					<a href="#">Home</a>
					·
					<a href="#">Blog</a>
					·
					<a href="#">About</a>
					·
					<a href="#">Faq</a>
					·
					<a href="#">Contact</a>
				</p>

				<p class="footer-company-name">Ark Inc! &copy; <?= date('Y', time()); ?></p>
			</div>

			<div class="footer-center">

				<div>
					<i class="fa fa-map-marker"></i>
					<p><span>Commonwealth hall</span> Ug, Ghana</p>
				</div>

				<div>
					<i class="fa fa-phone"></i>
					<p>+233 26 119 2456</p>
				</div>

				<div>
					<i class="fa fa-envelope"></i>
					<p><a href="mailto:danielboakye98@yahoo.com">support@ArkInc.com</a></p>
				</div>

			</div>

			<div class="footer-right">

				<p class="footer-company-about">
					<span>About Ark Inc!</span>
					Lorem ipsum dolor sit amet, consectateur adispicing elit. Fusce euismod convallis velit, eu auctor lacus vehicula sit amet.
				</p>

				<div class="footer-icons">

					<a href="https://www.facebook.com/daniel.boakye.165"><i class="fa fa-facebook"></i></a>
					<a href="https://twitter.com/danielboakye13"><i class="fa fa-twitter"></i></a>
					<a href="#"><i class="fa fa-linkedin"></i></a>
					<a href="https://github.com/danielboakye"><i class="fa fa-github"></i></a>
					<a href="#"><i class="fa fa-google-plus"></i></a>

				</div>

			</div>

		</footer>
		
<script type="text/javascript" src="<?= BASE_URL ?>js/jquery-2.1.4.min.js"></script>		
<script type="text/javascript" src="<?= BASE_URL ?>js/bootstrap.min.js"></script>
<script type="text/javascript" src="<?= BASE_URL ?>js/lightbox.min.js"></script>
<script type="text/javascript" src="<?= BASE_URL ?>js/plugins/canvas-to-blob.min.js"></script>
<script type="text/javascript" src="<?= BASE_URL ?>js/fileinput.min.js"></script>
<script type="text/javascript" src="<?= BASE_URL ?>js/master.min.js"></script>
</body>
</html>



<?php if(isset($db)) { $db->close_connection(); } ?>