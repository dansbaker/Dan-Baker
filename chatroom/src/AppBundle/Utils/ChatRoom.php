<?php
namespace AppBundle\Utils;
use Entity\Message;
use Symfony\Component\DependancyInjection\Container;


class ChatRoom 
{
	private $db; //Hold the mysqli connection
	private $container;



	function __construct($c)
	{
		$this->container = $c;
		$this->db = new \mysqli('127.0.0.1', 'chatroom', 'h2rTRKvBhVnBJ8jS', 'chatroom');	
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

		$sql = "SELECT messages.message_id, messages.content, messages.timestamp, users.email_address FROM messages 
		LEFT JOIN users ON messages.user_id = users.user_id WHERE messages.timestamp > '{$sql_date}'";
		
		$messages = Array();
		if(!$sql_result = $this->db->query($sql))
		{
			throw new \Exception('Unable to query database for messages');
		} 

		while($row = $sql_result->fetch_assoc())
		{
			$row['content'] = htmlentities($row['content']); //Prevent XSS on content
			$messages[] = $row;
		}

		return $messages;
	}

	public function postMessage($user_id, $content)
	{

		$message = new \AppBundle\Entity\Message($user_id, $content);
		$em = $this->getDoctrine()->getManager();
		$em->persist($product);
		$em->flush();
		return true;
		/*$message_content = $this->db->real_escape_string($message_content); //sanitise message content
		$sql = "INSERT INTO messages (message_id, user_id, content, timestamp) VALUES (NULL, {$user_id}, '{$message_content}', NOW());";
		if(!$sql_result = $this->db->query($sql))
		{
			throw new \Exception('Unable to insert message into database');	
		} 
		return true;*/
	}


}