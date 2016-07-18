<?php
namespace AppBundle\Utils;

class ChatRoom
{
	private $db; //Hold the mysqli connection

	function __construct()
	{
		$this->db = new \mysqli('127.0.0.1', 'chatroom', 'h2rTRKvBhVnBJ8jS', 'chatroom');	
	}

	function getMessages()
	{
		//$sql = "SELECT * FROM messages "
		return 'foo';
	}
}