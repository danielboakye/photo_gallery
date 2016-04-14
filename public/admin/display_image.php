<?php 

require_once($_SERVER['DOCUMENT_ROOT'] . "/photo_gallery/incs/config.php");
require_once( ROOT_PATH . "incs/_functions.php");

require_once( ROOT_PATH . "incs/Session.php");
require_once( ROOT_PATH . "incs/MySqlDatabase.php");

require_once( ROOT_PATH . "incs/User.php");
require_once( ROOT_PATH . "incs/Images.php");
require_once( ROOT_PATH . "incs/Comment.php");

if(!$session->isLoggedIn()){
    redirect_to("login.php");
}

if ( empty($_GET['id']) )
{
	$session->message("The photo could not be located");
	redirect_to("manage_images.php");
}

$db = new MySqlDatabase();
$pic = Images::getByUniq( htmlspecialchars($_GET['id']) );

if ( !$pic )
{
	$session->message("The photo could not be located");
	redirect_to("manage_images.php");
}

if ( $_POST )
{
	$author = trim( htmlspecialchars( $_POST['author'] ) );
	$body = trim( htmlspecialchars( $_POST['body'] ) );

	$new_comment = Comment::make($pic->uniqname, $author, $body);

	if( $new_comment && $new_comment->save() )
	{
		unset($author); unset($body);
		redirect_to("display_image.php?id=". urldecode($pic->uniqname));
	}else{
		$message = "Error! Could not save comment";
	}
}

$comments = $pic->comments();

?>

<?php include( ROOT_PATH . "incs/header.php"); ?>

<div class="section no-padding" style="margin-top: 50px;">
	<ol class="breadcrumb">
		<li><a href="./"><span class="glyphicon glyphicon-home"></span></a></li>
		<li><a href="manage_images.php">All Images</a></li>
		<li class="active">Photos</li>
	</ol>
</div>

<div class="well" style="margin-bottom: 1%;">
	<h2 class="page-header">Admin - Display Image</h2>
</div>

<style type="text/css">
	img.img-thumbnail{ width: 100%; height: 100%;}
	.alert.page-header{ line-height: 30px; padding: 1em; margin-bottom: 1em;}
</style>

<div id="loginModal" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title" style="font-family: Georgia, serif;">Remove Comment </h4>
            </div>
            <div class="modal-body">
                <div class="panel-heading list-group list-group-item-danger">
                    <h3 class="list-group-item" style="font-family: comic;">Are you sure you want to delete this comment?</h3>
                </div>           
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-warning" data-dismiss="modal">Dismiss</button>
                <a id="acceptDel"><button type="button" class="btn btn-success">Accept</button></a>
            </div>
        </div>
    </div>
</div>

<?php if( isset($_SESSION['message']) && trim($_SESSION['message']) != "" ) : ?>
	<div class="container alert alert-info"><?= htmlentities($_SESSION['message']); ?></div>
<?php endif; ?>	

<div class="container">
	<div class="row">
		<div class="col-md-6">
			<img class=" img-thumbnail" src="<?= $pic->location;  ?>" alt="<?= $pic->caption; ?>" >
		</div>
		<div class="col-md-6">
			<div class="alert alert-info page-header">Post a Comment about the image</div>
			<form method="POST" action="display_image.php?id=<?= urlencode($pic->uniqname); ?>">
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
				<div class="row page-header" style="color: #00f; text-align: left;  border-color: #e1e1e1; ">
					<span class="fa fa-user"></span> <?= htmlentities($comment->author); ?>
					<a style="cursor: pointer; float: right; font-size: 25px; margin-top: -15px;" data-toggle="modal" data-target="#loginModal" class="delUser">
						<span class="fa fa-trash" uid="<?= urlencode($comment->id); ?>"></span>
					</a>
				</div>
				<div class="row">
					<div class="col-md-10"><div class="" style="padding: 10px;"><?= nl2br(htmlentities($comment->body)); ?></div></div>
					<div class="col-md-2 clean_date"><span><?= htmlentities(clean_date($comment->created)); ?></span></div>
				</div>
			</div>
		<?php endforeach; ?>	
	</div>

<?php endif; ?>	

<?php include( ROOT_PATH . "incs/admin-footer.php"); ?>
<script>
	$(document).ready(function(){
		var uid;
		$('.delUser').click(function() {
			uid = $(this).find('span').attr('uid');

		});

		$('#acceptDel').click(function () {
			window.location.href = 'delete_comment.php?id='+uid;
		});
	});
</script>
<?php $_SESSION['message'] = ""; ?>