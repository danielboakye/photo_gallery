	<script type="text/javascript" src="<?= BASE_URL ?>js/jquery-2.1.4.min.js"></script>		
	<script type="text/javascript" src="<?= BASE_URL ?>js/bootstrap.min.js"></script>
	<script type="text/javascript" src="<?= BASE_URL ?>js/lightbox.min.js"></script>
	<script type="text/javascript" src="<?= BASE_URL ?>js/plugins/canvas-to-blob.min.js"></script>
	<script type="text/javascript" src="<?= BASE_URL ?>js/fileinput.min.js"></script>
	<script type="text/javascript" src="<?= BASE_URL ?>js/master.min.js"></script>
	</body>
</html>



<?php if(isset($db)) { $db->close_connection(); } ?>