<?php 
require_once($_SERVER['DOCUMENT_ROOT'] . "/photo_gallery/incs/config.php");
require_once( ROOT_PATH . "incs/_functions.php");

require_once( ROOT_PATH . "incs/Session.php");
require_once( ROOT_PATH . "incs/MySqlDatabase.php");

require_once( ROOT_PATH . "incs/User.php");
require_once( ROOT_PATH . "incs/Images.php");

// <!-- MAX_FILE_SIZE = 2097152 -->
if(!$session->isLoggedIn()){
    redirect_to("login.php");
}

if (empty($_FILES['images'])) {
    echo json_encode(['error'=>'No files found for upload.']); 
    // or you can throw an exception 
    return; // terminate
}

$upload_errors = array(
    UPLOAD_ERR_OK => 'No Errors',
    UPLOAD_ERR_INI_SIZE => 'Larger than upload max size',
    UPLOAD_ERR_FORM_SIZE => 'Larger than form upload max size',
    UPLOAD_ERR_PARTIAL => 'Partial Upload',
    UPLOAD_ERR_NO_FILE => 'No file',
    UPLOAD_ERR_NO_TMP_DIR => 'No temp. directory',
    UPLOAD_ERR_CANT_WRITE => 'Cant write to disk',
    UPLOAD_ERR_EXTENSION => "file upload stopped by extension"
);

$errors = $_FILES['images']['error'];
foreach ($errors as $val) {
    $output[] = $upload_errors[$val];
}


// get the files posted
$images = $_FILES['images'];

// get admin username stored for log file
$db = new MySqlDatabase();
$username = User::getById($session->user_id)->username;

// a flag to see if everything is ok
$success = null;

// file paths to store
$paths = [];

// get file names
$filenames = $images['name'];

// loop and process files
for($i=0; $i < count($filenames); $i++){
    $ext = explode('.', basename($filenames[$i]));
    $uniqname = md5(uniqid());
    $uniqnames[] = $uniqname;
    $target = "uploads" . DS. $uniqname . "." . array_pop($ext);
    if(move_uploaded_file($images['tmp_name'][$i], $target)) {
        $success = true;
        $paths[] = $target;
    } else {
        $success = false;
        break;
    }
}

// check and process based on successful status 
if ($success) {

    //insert into database
    $saved = Images::save_images($session->user_id ,$paths, $filenames, $uniqnames);
   
    $output = ['uploaded' => 'All files uploaded.'];
    if($saved === false)
    { 
        $output = ['error'=>'Error while saving images. Contact the system administrator'];
        log_action('Upload', "{$username}, ID - {$session->user_id} had a failed upload."); 
    }else{

        //log upload
        $counter = ( count($filenames) > 1 ) ? count($filenames) . " new images." : "a new image"; 
        log_action('Upload', "{$username}, ID - {$session->user_id} added " . $counter);
    }

} elseif ($success === false) {
    $output = ['error'=>'Error while uploading images. Contact the system administrator'];
    // delete any uploaded files
    foreach ($paths as $file) {
        unlink($file);
    }
} else {
    $output = ['error'=>'No files were processed.'];
}

// return a json encoded response for plugin to process successfully
echo json_encode($output);