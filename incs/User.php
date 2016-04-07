<?php 

require_once($_SERVER['DOCUMENT_ROOT'] . "/photo_gallery/incs/config.php");
require_once( ROOT_PATH . "incs/MySqlDatabase.php");
require_once( ROOT_PATH . "incs/DatabaseObject.php");
//model
class User extends DatabaseObject
{
	protected static $table_name = "users";
	protected static $db_fields = array( 'id', 'username', 'password', 'first_name', 'last_name', 'is_admin');
	public $id;
	public $username;
	public $password;
	public $first_name;
	public $last_name;
	public $is_admin;
	private $make_admin = 0;

	public function fullName()
	{
		if(isset($this->first_name) && isset($this->last_name))
		{
			return "{$this->first_name} {$this->last_name}";
		}
		return "";
	}

	private static function getUserByName($username, $is_admin)
	{
		$query = "SELECT * FROM " . self::$table_name . " WHERE username = :name AND is_admin = :admin LIMIT 1";
		$result = self::findByQuery($query, array('name' => $username, 'admin' => $is_admin));
		return !empty($result) ? array_shift($result) : false;
	}

	public static function getByKeyword($username)
	{
		$username = "%" . $username . "%";
		$query = "SELECT * FROM users WHERE username LIKE :name OR first_name LIKE :name OR last_name LIKE :name";
		$result = self::findByQuery($query, array('name' => $username));
		return !empty($result) ? $result : false;
	}

	public function __set($property, $value)
	{
		if (property_exists($this, $property))
		{
		    $this->$property = $value;
		}
		return $this;
	}

	public function __get($property)
	{
		return $this->$property;	
	}

	public function displayAdminStatus()
	{
		return $this->__get("make_admin");
	}

	public function upgradeToAdmin()
	{
		$this->__set("make_admin", 1);
	}

	public function revokeAdmin()
	{
		$this->__set("make_admin", 0);
		// return true;
	}

	public function delete()
	{
		global $db;
		$query = "DELETE FROM " . self::$table_name . " WHERE id = :id LIMIT 1";
		$result = $db->query( $query, array('id' => $this->id) );
		return $result ? true : false;
	}

	public static function authenticateAdmin($username="", $password="") 
	{
		$admin = self::getUserByName($username, 1);
		if($admin){

			if ( password_verify($password, $admin->password) ) {

				return $admin;
			} else {
				return false;
			} 
		}else {
			return false;
		}
	}


}