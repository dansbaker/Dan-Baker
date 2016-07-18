<?php
namespace AppBundle\Utils;

class ChatRoom
{
	private $db; //Hold the mysqli connection

	function __construct()
	{
		$this->db = new mysqli('localhost', 'chatroom', 'h2rTRKvBhVnBJ8jS', 'chatroom');	
	}

	function getMessages((string)$session_id)
	{
		
	}
}