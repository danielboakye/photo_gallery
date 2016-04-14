<?php 

require_once($_SERVER['DOCUMENT_ROOT'] . "/photo_gallery/incs/config.php");
require_once( ROOT_PATH . "incs/MySqlDatabase.php");
require_once( ROOT_PATH . "incs/DatabaseObject.php");

class Comment extends DatabaseObject
{
	protected static $table_name = "comments";
	protected static $db_fields = array( 'id', 'image_uniqid', 'created', 'author', 'body' );
	public $id;
	public $image_uniqid;
	public $created;
	public $author;
	public $body;

	public static function make($image_uniqid, $author, $body)
	{
		if( !empty($image_uniqid) && !empty($body) )
		{
			if( empty($author) )
			{
				$author = "Anonymous";
			}
			$comment = new Comment();
			$comment->image_uniqid = strval($image_uniqid);
			$comment->created = gmstrftime("%d-%m-%Y %H:%M:%S", time());
			$comment->author = $author;
			$comment->body = $body;

			return $comment;
		}
		
		return false;
	}

	public static function findCommentsOn($image_uniqid)
	{
		$query = "SELECT * FROM " . static::$table_name . " WHERE image_uniqid = :image_uniqid ORDER BY id ASC";
		$result = static::findByQuery( $query, array('image_uniqid' => $image_uniqid));
		return !empty($result) ? $result : false;
	}

	public function delete()
	{
		global $db;
		$query = "DELETE FROM " . self::$table_name . " WHERE id = :id LIMIT 1";
		$result = $db->query( $query, array('id' => $this->id) );
		return $result ? true : false;
	}

}