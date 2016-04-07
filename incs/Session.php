<?php  

/*@session -> to track logins and logouts of users
** Dont store entire db objects. id for a paticular object is the best option so 
** that the unique id could rather be used to query the data back if needed
** cos the session could become stale when the db is updated and the session holds the old data
*/

class Session
{
	private $logged_in = false;
	public $user_id;
	
	public function __construct()
	{
		session_start();  //start session to make Session super global active
		$this->checkLogin();
	}

	public function login($user)
	{
		if($user){
			$this->user_id = $_SESSION['user_id'] = $user->id;
			$this->logged_in = true;
		}
	}

	public function logout()
	{
		unset($_SESSION['user_id']);
		unset($this->user_id);
		$this->logged_in = false;
	}

	private function checkLogin()
	{
		if(isset($_SESSION['user_id'])){
			$this->user_id = htmlspecialchars($_SESSION['user_id']);
			$this->logged_in = true;
		}else{
			unset($this->user_id);
			$this->logged_in = false;
		}
	}

	public function isLoggedIn()
	{
		return $this->logged_in;
	}
}

$session = new Session();