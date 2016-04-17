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

	private $temp_path;
	public $upload_dir = "uploads";
	public $errors = array();
	public $ext;

	protected $upload_errors = array(
		    UPLOAD_ERR_OK => 'No Errors',
		    UPLOAD_ERR_INI_SIZE => 'Larger than upload max size',
		    UPLOAD_ERR_FORM_SIZE => 'Larger than form upload max size',
		    UPLOAD_ERR_PARTIAL => 'Partial Upload',
		    UPLOAD_ERR_NO_FILE => 'No file',
		    UPLOAD_ERR_NO_TMP_DIR => 'No temp. directory',
		    UPLOAD_ERR_CANT_WRITE => 'Cant write to disk',
		    UPLOAD_ERR_EXTENSION => "file upload stopped by extension"
		);

	//@ parameter is $_FILE(['image']) as an argument
	public function attach_file($file) {
	    // Perform error checking on the form parameters
	    if(!$file || empty($file) || !is_array($file)) {
	    
	        $this->errors[] = "No file was uploaded.";
	        return false;
	    } elseif($file['error'] != 0) {
	        
	        $this->errors[] = $this->upload_errors[$file['error']];
	        return false;
	    } else {
	        
	        $this->temp_path  = $file['tmp_name'];
	        $this->caption  = basename($file['name']);
	        $this->ext = pathinfo($file['name'], PATHINFO_EXTENSION);
	        
	        return true;

	    }
	}

	// @return update() or  create() images
	public function process($user_id = null, $is_profile = null) {

	    // A new record won't have an id yet.
	    if(isset($this->id) ) {
	       
	        return $this->rename();

	    } else {

	        if(!empty($this->errors)) { return false; }

	        // Make sure the caption is not too long for the DB
	        if(strlen($this->caption) > 255) {
	            $this->errors[] = "The caption can only be 255 characters long.";
	            return false;
	        }

	        if(empty($this->caption) || empty($this->temp_path)) {
	            $this->errors[] = "The file location was not available.";
	            return false;
	        }

	        $this->uniqname = md5(uniqid());
	        $target_path = ROOT_PATH .DS. 'public' .DS. 'admin' .DS. $this->upload_dir ."/". $this->uniqname . "." . $this->ext;

	        if(file_exists($target_path)) {
	            $this->errors[] = "The file {$this->filename} already exists.";
	            return false;
	        }


	        if(move_uploaded_file($this->temp_path, $target_path)) {
	        	$this->id = $user_id;
	        	$this->is_profile = $is_profile;
	        	$this->location = $this->upload_dir ."/". $this->uniqname . "." . $this->ext;
	           
	            // Save a corresponding entry to the database
	            if($this->create()) {
	                
	                unset($this->temp_path);
	                return true;
	                // echo "reaced1 here ";die();
	            }

	        // echo "reaced2 here ";die();
	        } else {
	            
	            $this->errors[] = "The file upload failed, possibly due to incorrect permissions on the upload folder.";
	            return false;
	        }
	    }
	}

	public function getProfileImg($user_id)
	{
		$result = static::findByQuery("SELECT * FROM images WHERE id = :id AND is_profile = 1 LIMIT 1", array('id' => $user_id));
		return !empty($result) ? array_shift($result) : false;
	}

	public function getByUniq($uid)
	{
		$result = static::findByQuery("SELECT * FROM images WHERE uniqname = :id LIMIT 1", array('id' => $uid));
		return !empty($result) ? array_shift($result) : false;
	}

	public function comments()
	{
		return Comment::findCommentsOn($this->uniqname);
	}

	public static function getPublic()
	{
		$result = static::findByQuery("SELECT * FROM " . static::$table_name . " WHERE is_profile = 0", array());
		return !empty($result) ? $result : false;
	}

	public static function getPublicPages($per_page, $offset)
	{
		$query = "SELECT * FROM images WHERE is_profile = 0 LIMIT :per_page OFFSET :num";
		$bindings = array('per_page' => (int)$per_page, 'num' => (int)$offset);

		global $db;
		$result = $db->query_BindInt($query, $bindings);

		$object_array = array();
		if($result)
		{
			foreach ($result as $row){
				array_push($object_array, self::instantiate($row));
			}
		}
		$result = (count($object_array) > 0) ? $object_array : false;
		return !empty($result) ? $result : false;
	}

	public function rename()
	{
		global $db;
		$query = "UPDATE images SET caption = :caption WHERE uniqname = :uniqname LIMIT 1";
		//used sql because query return an associative array and update need a boolean SORTA!!
		$result = $db->sql( $query, array( 'caption' => $this->caption, 'uniqname' => $this->uniqname) );
		return $result ? true : false;
	}

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

	public static function saveAll($user, $paths, $filenames, $uniqnames, $profile = 0)
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
		$query = "DELETE FROM images WHERE uniqname = :uid LIMIT 1";
		$result = $db->query( $query, array('uid' => $this->uniqname) );
		
		return $result ? true : false;

		/*@NOTE
		** After deleting the instance of this Image still exists so you can do something like 
		** echo $this->caption was removed successfully or something eventhough it will not be in the db
		*/ 
	}

	public static function countAll()
	{
		global $db;
		$query = "SELECT COUNT(*) AS total FROM images WHERE is_profile = 0";
		$result = $db->query( $query, array() );
		
		return $result ? (int)$result->fetch()['total'] : 0;
	}


	public function destroy()
	{
		//remove from server
		if ( $this->delete() )
		{
			$path = ROOT_PATH . 'public/admin/' . $this->location;
			return unlink($path) ? true : false; 
		}

		return false;
	}




} 



