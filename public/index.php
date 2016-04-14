<?php 

require_once($_SERVER['DOCUMENT_ROOT'] . "/photo_gallery/incs/config.php");
require_once( ROOT_PATH . "incs/_functions.php");

require_once( ROOT_PATH . "incs/Session.php");
require_once( ROOT_PATH . "incs/MySqlDatabase.php");

require_once( ROOT_PATH . "incs/User.php");
require_once( ROOT_PATH . "incs/Images.php");

// if(!$session->isLoggedIn()){
//     redirect_to("login.php");
// }

?>

<?php 
	
	$db = new MySqlDatabase();
	$display = Images::getPublic();

 ?>
<?php include( ROOT_PATH . "incs/header.php"); ?>

<div class="section no-padding" style="margin-top: 50px;">
	<ol class="breadcrumb" style="margin-bottom: 0;">
		<li><a href="./"><span class="glyphicon glyphicon-home"></span></a></li>
		<li class="active" style="font-weight: bolder; font-family: cursive; color: #000;">Photo Wall</li>
	</ol>
</div>

<link rel="stylesheet" type="text/css" href="css/pinterest-style.css" media="all">

<?php if( isset($display) && !empty($display) ) : ?>
    <div class="container-fluid" style="padding-right: 0; padding-left: 0;">
      <div class="section no-padding st-invert">
          <section>
              <ul id="" class="gallery gl-cols-5 gl-pinterest">
                  <?php foreach ($display as $img) : ?>
                  <li class="gl-item">
                      <a href="<?= BASE_URL . "admin/" . $img->location; ?>" data-lightbox="example-set" data-title="<?= $img->caption; ?>">
                          <figure title="click to expand">
                              <img src="<?= BASE_URL . "admin/" . $img->location; ?>" alt="<?= $img->caption; ?>" >
                              <figcaption>
                                  <div class="middle">
                                      <div class="middle-inner">
                                          <p class="gl-item-title" style="box-sizing: border-box; font-size: 17px; text-transform: lowercase; word-wrap: break-word; word-break: break-all;">
                                            <?= $img->caption ."<br>". Images::file_size( ROOT_PATH ."public/admin/" . $img->location); ?>
                                          </p>
                                          
                                          <div class="mb-social">
                                              <a href="<?= BASE_URL . "admin/" . $img->location; ?>" data-lightbox="example-set" title="Preview"><i class="fa fa-image"></i></a>
                                              <a href="https://twitter.com/danielboakye13" title="twitter"><i class="fa fa-twitter"></i></a>
                                              <a href="https://www.facebook.com/daniel.boakye.165" title="Facebook"><i class="fa fa-facebook"></i></a>
                                              <a href="https://www.instagram.com/daniel_kojo/" title="Instagram"><i class="fa fa-instagram"></i></a>
                                              <a href="<?= BASE_URL . "image/?id=" . $img->uniqname; ?>" title="Comments"><i class="fa fa-comment"></i></a>
                                          </div>
                                          
                                      </div>
                                  </div>
                              </figcaption>
                          </figure>
                      </a>
                  </li>
                  <?php endforeach; ?>
              </ul>
          </section>
      </div>
  </div>
<?php else : ?>
  <h3 class="alert alert-danger" style="height: 270px; margin: 0;"> Error! Resource not found.</h3>
<?php endif; ?>  


 <?php include( ROOT_PATH . "incs/footer.php"); ?>