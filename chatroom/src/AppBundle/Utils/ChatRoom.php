<?php
namespace AppBundle\Utils;

class ChatRoom
{
	private $db; //Hold the mysqli connection

	function __construct()
	{
		$this->db = new \mysqli('127.0.0.1', 'chatroom', 'h2rTRKvBhVnBJ8jS', 'chatroom');	
		if (mysqli_connect_errno()) 
		{
			throw new Exception('Unable to connect to the database');
		}
	}

	function getMessagesSince($time)
	{
		$unix_time = strtotime($time);
		if($unix_time === false)
		{
			throw new Exception('Invalid time specified');
		}
		$sql_date = date('Y-m-d H:i:s' , $unix_time); //MySQL formatted date string (santitised);
		$sql = "SELECT messages.message_id, messages.content, messages.timestamp, users.email_address FROM messages 
		LEFT JOIN users ON messages.user_id = users.user_id WHERE messages.timestamp > '{$sql_date}'";

		$messages = Array();
		$sql_result = $this->db->query($sql);
		while($row = $sql_result->fetch_assoc())
		{
			$row['content'] = htmlentities($row['content']); //Prevent XSS on content
			$messages[] = $row;
		}

		return $messages;
	}
}