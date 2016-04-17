<?php 

require_once($_SERVER['DOCUMENT_ROOT'] . "/photo_gallery/incs/config.php");

class MySqlDatabase
{   
	private $connection;

	function __construct() 
	{
		$this->open_connection();
	}

	public function open_connection()
	{	
		try {
			$this->connection = new \PDO('mysql:host=' . DB_HOST .'; dbname=' . DB_NAME, DB_USER, DB_PASS);
			$this->connection->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
			//default fetch mode is associative array
			$this->connection->setAttribute(\PDO::ATTR_DEFAULT_FETCH_MODE, \PDO::FETCH_ASSOC);

		} catch (PDOException $e) {
			die("Could not connect to the database: " . $e->getmessage());
		}
	}

	public function close_connection()
	{
		if(isset($this->connection))
		{
			$this->connection = null;
		}
	}

	public function query($query, $bindings)
	{
		$stmt = $this->connection->prepare($query);
		$stmt->execute($bindings);
		$result = ($stmt->rowCount() > 0) ? $stmt : false;
		$this->confirm_result($result);
		return $result;


		//run returned variable->fetchAll() on all queries that are not instantiated 
		// like the count(*)
	}

	public function query_BindInt($query, $bindings)
	{
		$stmt = $this->connection->prepare($query);
		foreach ($bindings as $key => &$val) 
		{
  		    $stmt->bindParam($key, intval($val), PDO::PARAM_INT);
		}
		$stmt->execute();
		$result = ($stmt->rowCount() > 0) ? $stmt : false;
		return $result;
	}

	public function sql($query, $bindings)
	{
		$stmt = $this->connection->prepare($query);
		return $stmt->execute($bindings);
	}

	// @function in case you are using bindParam u can just loop through them
		// bindParam binds values to variables so can only be of format variable = :value
		// foreach ($bindings as $key => &$val) {
  		//     $sth->bindParam($key, $val);
		// }

	public function insertQuery($query, $bindings)
	{
		$stmt = $this->connection->prepare($query);

		foreach ($bindings as $key => &$val) 
		{
  		    $stmt->bindParam($key, htmlspecialchars($val));
		}

		return ( $stmt->execute() && ($stmt->rowCount()>0) ) ? $this->connection->lastInsertId() : false;
	}

	// public function insertImage($query, $bindings)
	// {
	// 	$stmt = $this->connection->prepare($query);

	// 	foreach ($bindings as $key => &$val) 
	// 	{
 // 			  $stmt->bindParam($key, htmlspecialchars($val));
	// 	}

	// 	return ( $stmt->execute() && ($stmt->rowCount()>0) ) ? true : false;
	// }

	private function confirm_result($result)
	{
		if ( !$result )
		{
			return false;
		}
	}

	public function createQuery($data)  
	{
		$vals = ""; $vars = ""; $x = 1;
		foreach ($data as $key => $value) {
			if($x < count($data)){
				$vars .=  $key . ", "; 
				$vals .= ":" . $key . ", ";
				$x++;
			}else{
				$vars .=  $key; 
				$vals .= ":" . $key;
			}	
		}

		$createdQuery = $vars . "|" . $vals;
		return explode("|",$createdQuery);

		//Make all name variabeles of input field the same as column names in the database
		//Make all inputs fields required if their values are not null in the database
		//search the post array and pop out hidden fields from the arrray b4 using this function
	}


	// end database class
}


