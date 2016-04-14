<?php 

require_once($_SERVER['DOCUMENT_ROOT'] . "/photo_gallery/incs/config.php");
require_once( ROOT_PATH . "incs/_functions.php");

require_once( ROOT_PATH . "incs/Session.php");
require_once( ROOT_PATH . "incs/MySqlDatabase.php");

require_once( ROOT_PATH . "incs/User.php");
require_once( ROOT_PATH . "incs/Images.php");
require_once( ROOT_PATH . "incs/Comment.php");

// if(!$session->isLoggedIn()){
//     redirect_to("login.php");
// }

if ( empty($_GET['id']) )
{
	$session->message("The photo could not be located");
	redirect_to("../index.php");
}

$db = new MySqlDatabase();
$pic = Images::getByUniq( htmlspecialchars($_GET['id']) );

if ( !$pic )
{
	$session->message("The photo could not be located");
	redirect_to("../index.php");
}

if ( $_POST )
{
	$author = trim( htmlspecialchars( $_POST['author'] ) );
	$body = trim( htmlspecialchars( $_POST['body'] ) );

	$new_comment = Comment::make($pic->uniqname, $author, $body);

	if( $new_comment && $new_comment->save() )
	{
		unset($author); unset($body);
		redirect_to("index.php?id=". urldecode($pic->uniqname));
	}else{
		$message = "Error! Could not save comment";
	}
}

$comments = $pic->comments();

?>

<?php include( ROOT_PATH . "incs/header.php"); ?>

<div class="section no-padding" style="margin-top: 50px; margin-bottom: 1%;">
	<ol class="breadcrumb">
		<li><a href="../index.php"><span class="glyphicon glyphicon-home"></span></a></li>
		<li class="active">Photos</li>
	</ol>
</div>

<style type="text/css">
	img.img-thumbnail{ width: 100%; height: 100%;}
	.alert.page-header{ line-height: 30px; padding: 1em; margin-bottom: 1em;}
</style>


<div class="container">
	<div class="row">
		<div class="col-md-6">
			<img class=" img-thumbnail" src="<?= "../admin/" . $pic->location;  ?>" alt="<?= $pic->caption; ?>" >
		</div>
		<div class="col-md-6">
			<div class="alert alert-info page-header">Post a Comment about the image</div>
			<form method="POST" action="index.php?id=<?= urlencode($pic->uniqname); ?>">
				<div class="form-group">
					<label for="author" class="control-label">Name</label>
					<input type="text" name="author" class="form-control" placeholder="Anonymous">
				</div>
				<div class="form-group">
					<label for="body" class="control-label">Comment</label>
					<textarea class="form-control" rows="9" name="body" required placeholder="I like this image"></textarea>
				</div>

				<div class="form-group">
					<input type="submit" class="btn btn-info btn-group-justified">
				</div>
			</form>
		</div>
	</div>
</div>


<?php if(isset($message)) : ?>
	<div class="alert alert-danger"><?= htmlentities($message); ?></div>
<?php endif; ?>	

	<!-- list out the comments -->
<style type="text/css">
	@media screen and (max-width: 990px){
		.clean_date{ border-top: 1px solid #e1e1e1; margin: 0px 1em; padding-top: 4px;}
	}
</style>

<?php if(!empty($comments)) : ?>
	<div class="container" style="font-weight: bold; font-family: cursive;">
		<?php foreach ($comments as $comment) : ?>
			<div class="well">
				<div class="row page-header" style="color: #00f; text-align: left; border-color: #e1e1e1;">
					<span class="fa fa-user"></span> <?= htmlentities($comment->author); ?>
				</div>
				<div class="row">
					<div class="col-md-10"><div class="" style="padding: 10px;"><?= nl2br(htmlentities($comment->body)); ?></div></div>
					<div class="col-md-2 clean_date"><span><?= htmlentities(clean_date($comment->created)); ?></span></div>
				</div>
			</div>
		<?php endforeach; ?>	
	</div>

<?php endif; ?>	

<?php include( ROOT_PATH . "incs/footer.php"); ?>
