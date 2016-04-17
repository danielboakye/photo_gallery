<?php 

require_once($_SERVER['DOCUMENT_ROOT'] . "/photo_gallery/incs/config.php");
require_once( ROOT_PATH . "incs/_functions.php");

require_once( ROOT_PATH . "incs/Session.php");
require_once( ROOT_PATH . "incs/MySqlDatabase.php");
require_once( ROOT_PATH . "incs/Pagination.php");

require_once( ROOT_PATH . "incs/User.php");
require_once( ROOT_PATH . "incs/Images.php");


$db = new MySqlDatabase();

$page = !empty($_GET['pg']) ? intval($_GET['pg']) : 1;
$page = ( isset($page) && $page === 0 ) ? 1 : $page; 

$per_page = 10;
$total_count = Images::countAll();

$pagination = new Pagination($page, $per_page, $total_count);

if( $pagination->current_page > $pagination->total_pages() )
{
  $page = $pagination->current_page = 1;
}

$display = Images::getPublicPages($per_page, $pagination->offset());

if($pagination->total_pages() < 1)
{
  $display = Images::getPublic();
}


 ?>
<?php include( ROOT_PATH . "incs/header.php"); ?>

<div class="section no-padding" style="margin-top: 50px;" id="section-gallery">
  <ol class="breadcrumb" style="margin-bottom: 0;">
    <li><a href="./"><span class="glyphicon glyphicon-home"></span></a></li>
    <li class="active" style="font-weight: bolder; font-family: cursive; color: #000;">Photo Wall</li>
    <div class="text-center" style="font-weight: bolder; font-family: cursive; color: #000; margin-top: -1.95em;">
      <span>Page <?= $page . " of " . $pagination->total_pages(); ?></span>
    </div>
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

<style type="text/css">
  .pagination > li.disabled{
  cursor: not-allowed;
  filter: alpha(opacity=65);
  -webkit-box-shadow: none;
          box-shadow: none;
  /*opacity: .65;*/
  pointer-events: none;
  background-color: #ff0;
  border-color: #d43f3a;
  }

  .pagination > li.active a{
     z-index: 2;
     /*color: #555;*/
    cursor: not-allowed;
    /*background-color: #00f;*/
    /*border: 1px solid #ddd;*/
    border-bottom-color: transparent;
    background-color: #00f; 
    color: white;
  }
</style>
          <nav class="text-center">
            <ul class="pagination" style="font-family: cursive;">

            <?php if($pagination->total_pages() > 1 ) : ?>

              <?php if($pagination->hasPreviousPage() ) : ?>
                <li>
                  <a href="<?= $_SERVER['PHP_SELF'] . "?pg=" . urlencode($pagination->previous_page()); ?>" aria-label="Previous" >
                    <span aria-hidden="true">Previous</span>
                  </a>
                </li>
              <?php endif; ?>


              <!-- if page num > 1 -->
              <?php if($page > 1) : ?>
                <?php for($i = $page-3; $i < $page; $i++) : ?>
                  <?php if($i > 0) : ?>
                    <li><a href="<?= $_SERVER['PHP_SELF'] . "?pg=" . urlencode($i); ?>"><?= htmlentities($i); ?></a></li>
                  <?php endif; ?>  
                <?php endfor; ?>

                <li class="disabled active">
                  <a href="<?= $_SERVER['PHP_SELF'] . "?pg=" . urlencode($page); ?>" ><span><?= htmlentities($page); ?> </span></a>
                </li>

                <?php for($i = $page+1; $i <= $pagination->total_pages(); $i++) : ?>
                  <li><a href="<?= $_SERVER['PHP_SELF'] . "?pg=" . urlencode($i); ?>"><?= htmlentities($i); ?></a></li>
                  <?php if($i >= $page + 3 ){
                      break;
                    }  ?>
                <?php endfor; ?>
              <?php endif; ?>


              <?php if($pagination->hasNextPage() ) : ?>
                <li>
                  <a href="<?= $_SERVER['PHP_SELF'] . "?pg=" . urlencode($pagination->next_page()); ?>" aria-label="Next">
                    <span aria-hidden="true">Next</span>
                  </a>
                </li>
              <?php endif; ?> 

           <?php endif; ?>
            
          </ul>
        </nav>


      </div>
  </div>
<?php else : ?>
  <h3 class="alert alert-danger" style="height: 270px; margin: 0;"> Error! Resource not found.</h3>
<?php endif; ?>  


 <?php include( ROOT_PATH . "incs/footer.php"); ?>


