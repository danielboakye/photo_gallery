<?php require_once($_SERVER['DOCUMENT_ROOT'] . "/photo_gallery/incs/config.php"); ?>
<?php session_start(); ?>
<?php include( ROOT_PATH . "incs/header.php"); ?>

<link rel="stylesheet" type="text/css" href="../css/pinterest-style.css" media="all">
<style type="text/css">
	.margin-btm-md, h4 ~ p{
		font-family: cursive;
	}
</style>
<div id="wrapper" class="no-menubar" style="margin-top: 40px;">

     <div id="main">

        <div class="section">
           <section>
              <div class="container">
                 <div class="row">
                    <div class="col-md-8">
                       <h4 class="margin-btm-md">Drop us a line</h4>
                      <form role="form" action="formmail.php" class="contact-form validation-engine ajax-send" method="POST">
                          <div class="row">
                             <div class="col-sm-4 form-group">
                                <label class="sr-only" for="input_name">Name *</label>
                                <input type="text" name="name" class="form-control validate[required]" id="input_name" placeholder="Name *">
                             </div>
                             <div class="col-sm-4 form-group">
                                <label class="sr-only" for="input_email">Email *</label>
                                <input type="email" name="email" class="form-control validate[required,custom[email]]" id="input_email" placeholder="Email *">
                             </div>
                             <div class="col-sm-4 form-group">
                                <label class="sr-only" for="input_subject">Subject</label>
                                <input type="text" name="subject" class="form-control" id="input_subject" placeholder="Subject">
                             </div>
                          </div>
                          <div class="form-group">
                             <label class="sr-only" for="input_message">Message</label>
                             <textarea name="message" class="form-control validate[required]" rows="7" id="input_message" placeholder="Message"></textarea>
                          </div>
                          <div class="form-group">
                             <button type="submit" class="btn btn-default btn-wide">Send</button>
                             <span class="loading-spinner" style="display:none;"></span>
                          </div>
                          <?php if(isset($_SESSION['message']) && $_SESSION['message'] !== "" ) : ?>
                          	<div class="alert alert-success">
                          		<?= htmlentities($_SESSION['message']); ?>
                          	</div>
                      	  <?php endif; ?>
                       </form>


                    </div>
                    <div class="col-md-3 col-md-offset-1">
                       <h4 class="margin-btm-md">Information</h4>
                       <p class="address-block">
                          University of Ghana<br>
                          Legon, Accra<br>
                          GHANA<br>
                       </p>
                       <p class="phone-block">
                          +233 26 119 2456
                       </p>
                       <p class="email-block">
						  <a href="mailto:danielboakye98@yahoo.com">support@ArkInc.com</a>
                       </p>
                    </div>
                 </div>
              </div> <!-- container -->
           </section>
        </div> <!-- section -->


         <div class="section no-padding contact-map">
           <section>
              <div id="map-canvas"></div>
              <script src="https://maps.googleapis.com/maps/api/js?v=3.exp&amp;sensor=false"></script>
              <script type="text/javascript">
                 function initialize_google_map() {
                    var myLatlng = new google.maps.LatLng(5.6415, -0.1904);
                    var mapOptions = {
                       zoom: 12,
                       center: myLatlng,
                       scrollwheel: false
                    }
                    var map = new google.maps.Map(document.getElementById('map-canvas'), mapOptions);

                    var iconBase = 'http://d.azelab.com/mental/Demo/assets/img/';

                    var marker_icon = {
                       url: iconBase + 'map_marker.png',
                       size: new google.maps.Size(44,49),
                       origin: new google.maps.Point(0,0),
                       anchor: new google.maps.Point(22,49)
                    };

                    var marker = new google.maps.Marker({
                       position: myLatlng,
                       map: map,
                       title: 'M',
                       icon: marker_icon
                    });
                 }
                 google.maps.event.addDomListener(window, 'load', initialize_google_map);
              </script>
           </section>
        </div> <!-- section -->

     </div> <!-- main -->

  </div> <!-- wrapper -->

<?php include( ROOT_PATH . "incs/footer.php"); ?>
<?php $_SESSION['message'] = "";  ?>