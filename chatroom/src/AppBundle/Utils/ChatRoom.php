<?php
namespace AppBundle\Utils;
use Entity\Message;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;


class ChatRoom extends Controller
{
	private $db; //Hold the mysqli connection
	protected $container;



	function __construct(\appDevDebugProjectContainer $c)
	{
		$this->container = $c;
		$this->db = new \mysqli('127.0.0.1', 'chatroom', 'ApfAG3WcEDf0oPY9', 'chatroom');	
		if (mysqli_connect_errno()) 
		{
			throw new \Exception('Unable to connect to the database');
		}
	}

	//Request user_id by email_address and password. Return false if no matching user was found, otherwise return numerical user_id
	public function authenticateUser($email_address, $password)
	{
		$password = md5($password); //Hash password
		$email_address = $this->db->real_escape_string($email_address); //Sanitise Email Address
		$sql = "SELECT user_id FROM users WHERE email_address = '{$email_address}' AND password_hash = '{$password}';";
		if(!$sql_result = $this->db->query($sql))
		{
			throw new \Exception('Unable to perform authentication query');
		}
		if($sql_result->num_rows == 0) return false; //matching user not found
		$user = $sql_result->fetch_assoc();
		return $user['user_id'];
	}

	//Get messages since the given timestamp. Time must be specified in strtotime compatible format.
	public function getMessagesSince($time) 
	{
		$messages = $this->get('app.messages')->getMessagesSince($time);
		return $messages;
	}

	public function postMessage($user_id, $content)
	{
		$user = $this->get('app.users')->findById($user_id);
		$this->get('app.messages')->saveMessage($user, $content);	
		return true;
	}


}