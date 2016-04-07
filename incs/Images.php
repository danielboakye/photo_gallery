<?php 

require_once($_SERVER['DOCUMENT_ROOT'] . "/photo_gallery/incs/config.php");
require_once( ROOT_PATH . "incs/MySqlDatabase.php");
require_once( ROOT_PATH . "incs/DatabaseObject.php");
//model
class Images extends DatabaseObject
{
	protected static $table_name = "images";
	protected static $db_fields = array( 'id', 'location', 'caption', 'uniqname', 'is_profile');
	public $id;
	public $location;
	public $caption;
	public $uniqname;
	public $is_profile;	


	public static function file_size($path)
	{
	    if( file_exists($path) )
	    {
	    	$bytes = sprintf('%u', filesize($path));

		    if ($bytes > 0)
		    {
		        $unit = intval(log($bytes, 1024));
		        $units = array('B', 'KB', 'MB', 'GB');

		        if (array_key_exists($unit, $units) === true)
		        {
		            return sprintf('%d %s', $bytes / pow(1024, $unit), $units[$unit]);
		        }
		    }

		    return $bytes;
	    }

	    return "";
	}

	public static function save_images($user, $paths, $filenames, $uniqnames, $profile = 0)
	{
		// $this->id; can use instead of passing in id as argument
		global $db;
		// $object = new static;
		
		for ($i=0; $i < count($paths); $i++) 
		{ 
			$query = "INSERT INTO images (id, location, caption, uniqname, is_profile) VALUES (:id, :location, :caption, :uniqname, :profile)";
			$result = $db->insertQuery(
					$query, array('id' => $user, 'location' => $paths[$i], 'caption' => $filenames[$i], 'uniqname' => $uniqnames[$i],
						'profile' => $profile)
				);
			if($result === false) { return false; }
		}
		return $result;
	}

	public function delete()
	{
		global $db;
		$query = "DELETE FROM " . self::$table_name . " WHERE uniqname = :uid LIMIT 1";
		$result = $db->query( $query, array('uid' => $this->uniqname) );
		return $result ? true : false;

		/*@NOTE
		** After deleting the instance of this Image still exists so you can do something like 
		** echo $this->caption was removed successfully or something eventhough it will not be in the db
		*/ 
	}




} 



