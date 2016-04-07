<?php namespace lyndaCms\DB;

function get($table_name, $con, $limit = array(':lim' => 10) ) {
	//if the binding is after the where clause then we can execute with the bindings at once
	//but if there is no where clause like insert or the binding is before the where clause then
	//use bindParam to do the binding before sole execute
	try {
		$rows = $con->prepare("SELECT * FROM $table_name WHERE id < :lim ORDER BY id ASC");
		// $rows->execute($limit);
		$rows = $rows->fetchAll();
		return (empty($rows) ? false :  $rows);
	} catch (Exception $e) {
		return false;
	}	
}

function query($query, $bindings, $con){
	$stmt = $con->prepare($query);
	$stmt->execute($bindings);

	return ($stmt->rowCount() > 0) ? $stmt : false;
	// $stmt-fetchAll()
}

function insertQuery($query, $bindings, $con){
	
	try {
		$myQuery = $con->prepare($query)
	;		$myQuery->execute($bindings);

	} catch (PDOException $e) {
		echo "Error" . $e->getMessage();
	}
}

function insertAdmin($query, $bindings, $con){
	
	try {
		$myQuery = $con->prepare($query);
		$myQuery->bindParam(':username', $bindings['username']);
		$myQuery->bindParam(':hashed_password', $bindings['hashed_password']);
		
		$myQuery->execute();

	} catch (PDOException $e) {
		echo "Error" . $e->getMessage();
	}
}

function get_subject_by_id($id,$con,$public=true){
	if($public){
		$queryStmt = "SELECT * FROM subjects WHERE id = :id AND visible = 1 LIMIT 1";
		//default == display for public only visible = 1 elements
	}else{
		$queryStmt = "SELECT * FROM subjects WHERE id = :id LIMIT 1";
		// admin == display all no matter its visibility to manage it
	}

	$query = query($queryStmt,array(':id' => $id),$con);	

	return ( !empty($query) ) ? $query->fetchAll()[0] : null;
}

function get_page_by_id($id,$con,$public=true){
	if($public){
		$queryStmt = "SELECT * FROM pages WHERE id = :id AND visible = 1 LIMIT 1";
	}else{
		$queryStmt = "SELECT * FROM pages WHERE id = :id LIMIT 1";
	}	
		
	$query = query($queryStmt,array(':id' => $id),$con);
	
	return ( !empty($query) ) ? $query->fetchAll()[0] : null;
}

function get_admin_by_id($id,$con){

	$query = query(
		"SELECT * FROM admins WHERE id = :id LIMIT 1",
		array(':id' => $id),
		$con
	);
	
	return ( !empty($query) ) ? $query->fetchAll()[0] : null;
}

// ======================================================================================================================
function generate_salt($length) {
	// Not 100% unique, not 100% random, but good enough for a salt
	// MD5 returns 32 characters
	$unique_random_string = md5(uniqid(mt_rand(), true)); //unique random string with true meaning longer and much more secure value

	// Valid characters for a salt are [a-zA-Z0-9./]
	$base64_string = base64_encode($unique_random_string);

	// But not '+' which is valid in base64 encoding
	$modified_base64_string = str_replace('+', '.', $base64_string);

	// Truncate string to the correct length
	$salt = substr($modified_base64_string, 0, $length);

	return $salt;
}
// ================================================
// The password_encrypt function has an inbuilt php 5.6 and above equivalent called password_hash($password, PASSWORD_BCRYPT, ['cost' => 10] )
// FOR  Blowfish algorithm and password_hash($password, PASSWORD_DEFAULT)
// IT NEEDS NO SALT INPUT ... IT GENERATES THE SALT ON ITS OWN ==> BETTER TO  LET IT BE S0
// =================================================
function password_encrypt($password){
	$hash_format = "$2y$10$"; //tells php to use blowfish with the cost of 10
	$salt_length = 22;			// Blowfish salts should be 22-characters or more
	$salt = generate_salt($salt_length);
	$format_and_salt = $hash_format . $salt;
	$hash = crypt($password, $format_and_salt);
	return $hash;
}
// ==============================================================================================================================
function password_check($password, $existing_hash) {
// ================================================
// The password_check function has an inbuilt php 5.6 and above equivalent 
// called password_verify($new_password as plain text,Old_hashed_password in db);
// =================================================
	// existing hash contains format and salt at start
	$hash = crypt($password, $existing_hash);
	if ($hash === $existing_hash) {
		return true;
	} else {
		return false;
	}
}

// ==============================================================================================================================

function get_admin_by_username($username,$con){

	$query = query(
		"SELECT * FROM admins WHERE username = :username LIMIT 1",
		array(':username' => $username),
		$con
	);
	
	return ( !empty($query) ) ? $query->fetchAll()[0] : null;
}

function attempt_login($username, $password, $con) {

	$admin = get_admin_by_username($username,$con);

	if($admin){
		// found admin, now check password
		if ( password_verify($password, $admin["hashed_password"]) ) {
			// password matches
			return $admin;
		} else {
			// password does not match
			return false;
		} 
	}else {
		// admin not found
		return false;
	}

}

function logged_in() {
	return isset($_SESSION['admin_id']);
}

function confirm_logged_in() {
	if (!logged_in()) {
		\HTML::redirect_to("login.php");
	}
}