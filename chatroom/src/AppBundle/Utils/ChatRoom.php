<?php
namespace AppBundle\Utils;
use Entity\Message;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;


class ChatRoom extends Controller
{
	private $db; //Hold the mysqli connection
	protected $container;



	function __construct($c)
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

		$unix_time = strtotime($time);
		if($unix_time === false)
		{
			throw new \Exception('Invalid time specified');
		}
		$sql_date = date('Y-m-d H:i:s' , $unix_time); //MySQL formatted date string (santitised);


		$em = $this->getDoctrine()->getManager();
				
		$messages = $em->createQuery("SELECT e FROM AppBundle:Message e WHERE e.timestamp > '{$sql_date}'")->getResult();

		return $messages;
	}

	public function postMessage($user_id, $content)
	{
		$em = $this->getDoctrine()->getManager();
		$user = $em->find('\AppBundle\Entity\User', $user_id);
		if(!$user instanceof \AppBundle\Entity\User)
		{
			throw new \Exception('Unknown User ID in postMessage');	
		}

		$message = new \AppBundle\Entity\Message($user, $content);
		
		$em->persist($message);
		$em->flush();
		return true;
	}


}