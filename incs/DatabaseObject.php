<?php 

require_once($_SERVER['DOCUMENT_ROOT'] . "/photo_gallery/incs/config.php");
require_once( ROOT_PATH . "incs/MySqlDatabase.php");
//model
class DatabaseObject
{
	public static function findAll()
	{
		$result = static::findByQuery("SELECT * FROM " . static::$table_name . " ORDER BY id ASC", array());
		return !empty($result) ? $result : false;
	}

	public static function getLastId()
	{
		$result = static::findByQuery("SELECT id FROM " . static::$table_name . " ORDER BY id DESC LIMIT 1", array());
		return !empty($result) ? array_shift($result) : false;
	}

	public static function getById($id)
	{
		$result = static::findByQuery("SELECT * FROM " . static::$table_name . " WHERE id = :id LIMIT 1", array('id' => $id));
		return !empty($result) ? array_shift($result) : false;
	}

	public static function findByQuery($query, $bindings)
	{
		global $db;
		$result = $db->query($query, $bindings);
		$object_array = array();
		if($result)
		{
			foreach ($result as $row){
				array_push($object_array, static::instantiate($row));
			}
		}
		return (count($object_array) > 0) ? $object_array : false;
	}

	public function save()
	{
		return isset($this->id) ? $this->update() : $this->create();
	}

	protected function create()
	{
		global $db;
		$attributes = $this->attributes();
		$query = "INSERT INTO " . static::$table_name . " (" . join(", ", array_keys($attributes)) . ") VALUES 
					(:" . join(", :", array_keys($attributes)) . ")";
		
		foreach ($attributes as $key => $value) {
			$bindings[$key] = $this->$key;
			if ($key === "is_admin") 
			{
				$bindings['is_admin'] = $this->make_admin;
			}
		}

		$result = $db->insertQuery($query, $bindings);

		//revert private $make_admin back to 0
		$this->revokeAdmin();

		if($result){
			$this->id = $result;
			return $result;
		}else{
			return false;
		}
	}

	protected function update()
	{
		global $db;
		$attributes = $this->attributes();

		foreach ($attributes as $key => $value) {
			$attribute_pairs[$key] = $key . " = :" . $key;
			$bindings[$key] = $this->$key;
			if ($key === "is_admin") 
			{
				$bindings['is_admin'] = $this->make_admin;
			}
		}

		$query = "UPDATE " . static::$table_name . " SET " . join(", ", $attribute_pairs) . " WHERE id = :id LIMIT 1";

		$result = $db->query( $query, $bindings );
		return $result ? true : false;
	}

	public static function instantiate($record)
	{
		// $class_name = get_called_class();
		// $object = new $class_name;
		$object = new static;
		foreach ($record as $attribute => $value)
		{
			if($object->has_attribute($attribute)){
				$object->$attribute = $value;
			}
		}

		return $object;
	}

	protected function attributes()
	{
		// return get_object_vars($this);   @return ll give errors if there are object attributes or variables that not in the db
		$attributes = array();
		foreach (static::$db_fields as $field) 
		{
			if (property_exists($this, $field))
			{
				$attributes[$field] = $this->$field;
			}
		}

		return $attributes;
	}

	private function has_attribute($attribute)
	{
		$object_vars = $this->attributes(); /*@funtion return array of all class attributes or variables*/
		return array_key_exists($attribute, $object_vars);  /*@funtion returns true if attribute exists in array*/
	}


}