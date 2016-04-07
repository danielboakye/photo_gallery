<?php 

require_once($_SERVER['DOCUMENT_ROOT'] . "/photo_gallery/incs/config.php");

function strip_zeroes_from_date($marked_string="")
{
	// remove zeroes with *
	$no_zeroes = str_replace("*0","",$marked_string);
	// remove * if no zeroes exists
	return str_replace("*","",$no_zeroes);
}

function redirect_to($new_location = NULL) 
{
	if ( $new_location != NULL)
	{
		header("Location: {$new_location}");
		exit();
	}
}

function __autoload($class_name)
{
	$class_name = strtolower($class_name);
	$path = ROOT_PATH . "incs/{$class_name}.php";
	if(file_exists($path))
	{
		require_once($path);
	}else{
		die("The file {$class_name}.php could not be found");
	}
}

function log_action($action, $message = "")
{
	global $log_file;
	$new = file_exists($log_file) ? true : false;
	if( $handle = fopen($log_file, 'a') )
	{
		// setlocale(LC_ALL, array('en_GB.UTF8', 'en_GB@euro', 'en_GB', 'english'));
		//gmstrftime() sets current locale - strftime() addon
		$time_stamp = gmstrftime("%d-%m-%Y %H:%M:%S", time());
		$content = "{$time_stamp} | {$action}: {$message} \n";
		fwrite($handle, $content);
		fclose($handle);
		if($new) { chmod($log_file, 0755); }
	}else{
		echo "Could not open file @ " . __DIR__;
	}

}

// function diverse_array($vector) 
// {
//     $result = array();
//     foreach($vector as $key1 => $value1)
//         foreach($value1 as $key2 => $value2)
//             $result[$key2][$key1] = $value2;
//     return $result;
// } 
// print_r(diverse_array($_FILES['images']));



// @FUCNTION FOR FILESIZE PERSONAL
//@return size in humman readable format
// function human_filesize($filename, $decimals = 2) {

// 	if( file_exists($filename) )
// 	{
// 		$bytes = filesize($filename);
// 		$sz = 'BKMGTP';
// 		$factor = floor((strlen($bytes) - 1) / 3);
// 		$ans = sprintf("%.{$decimals}f", $bytes / pow(1024, $factor)) . @$sz[$factor];
// 		return (substr($ans, -1) == "K") ? $ans : $ans."b";  
// 	}
// }